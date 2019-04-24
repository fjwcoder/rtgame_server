<?php

namespace app\common\service\wechat\driver\wxgzh;

class Wechat{

    public $wechat_config = array(
        // appid
        'appid' =>'',
        'appsecret' =>'',
        'original_id'=>'', // 
        'token'=>''
    );

    public function __construct($param = []){
        $this->wechat_config['appid'] = $param['appid'];
        $this->wechat_config['appsecret'] = $param['appsecret'];
        $this->wechat_config['original_id'] = $param['original_id'];
        $this->wechat_config['token'] = $param['token'];
    }

    public function index(){
        if(!isset($_GET['echostr'])){
			$this -> responseMsg();
		}else{
			$this -> valid();//验证key
		}
    }
    /**
     * ########################################################################
     *  信息验证模块 create by fjw in 18.5.30
     * ########################################################################
     */
    public function valid()
    {
        $echoStr = $_GET['echostr'];
        if($this->checkSignature()){//调用验证签名checkSignature函数
        	echo $echoStr;
        	exit;
        }
    }

    private function checkSignature()
	{
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
		$tmpArr = array($this->wechat_config['token'], $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
    }


    /**
     * ########################################################################
     *  响应公众号事件/信息 create by fjw in 18.5.30
     * ########################################################################
     */
    public function responseMsg()
	{
        $postStr = file_get_contents('php://input');
        // file_put_contents('responsemsg.txt', $postStr);
		if (!empty($postStr))
		{
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$RX_TYPE = trim($postObj -> MsgType);
			switch($RX_TYPE)
			{
                case 'event':
                    $resultStr = $this -> handleEvent($postObj);
				break;
				case 'text':
					$resultStr = $this -> handleText($postObj);
				break;
				default:
					$resultStr = 'Unknow msg type: '.$RX_TYPE;
				break;
			}
			echo $resultStr;
		}else{
			echo "no user's post data";
		}
    }
    

/**
     * ########################################################################
     *  响应公众号 “事件消息” 方法 create by fjw in 18.5.30
     * ########################################################################
     */
    public function handleEvent($object){
        $openid = strval($object->FromUserName);
        $registerObj = new Regist();
        $access_token = $this->access_token();
        $content = "";
        switch ($object->Event){
            case "subscribe": // ok by fjw in 18.6.2
                $url = wechatApi('USER_BASEINFO', ['ACCESS_TOKEN'=>$access_token, 'OPENID'=>$openid]);
                $response = httpsGet($url); 
                $user_info = json_decode($response, true);

                if(empty($object->EventKey) && empty($user_info['qr_scene_str'])){ //不带场景值,直接注册 或者 重新关注
                    $pid = 0;
                }else{ //带场景值
                    $pid = $user_info['qr_scene_str']; 
                }
                unset($user_info['subscribe_scene'], $user_info['qr_scene'], $user_info['qr_scene_str']);
                $regist = $registerObj->subscribe($user_info, $pid); 
                if($regist['status']){ // 发送模板消息
                    if(isset($regist['type'])){
                        switch($regist['type']){
                            case 'new':
                                $data = ['openid'=>$openid, 
                                    'first'=>$regist['first'],
                                    'keyword1'=>$user_info['nickname'], 
                                    'keyword2'=>$regist['name'],
                                    'keyword3'=>$regist['name'],
                                    'keyword4'=>date('Y-m-d H:i:s', time()),
                                    'remark'=>$regist['remark']];
                                $this->sendTemplate('registTemplate', $data);
                                return true;
                            break;
                            case 'old':
                                $content .= $regist['first'].$regist['remark'];
                            break;
                            default:
                                $content .= 'data error';
                            break;
                        }
                    }else{
                        $content .= $regist['first'];
                    }
                    
                }else{ // 发送文本消息
                    $content .= $regist['first'];
                }
            break;
            case 'unsubscribe': // 2018.9.13 增加 取消关注 by fjw
                Db::name('users') -> where(['openid'=>$openid]) -> update(['subscribe'=>2]);
            break;
            case "CLICK":
                switch($object->EventKey){
                    case "spread_qrcode": // 我的推广， 完成 in 18.6.26
                        # 图文模式 , 不可删除, 与图片模式只能留一个
                        $model = new Users();
                        $user = $model->getUserInfo($openid, true, 1); // true 是否通过openid查询，1是否只查询status=1的
                        if(!empty($user)){
                            $view_url = $this->server_name.'/index/spread/spread?uid='.$user['id'];
                            $content_array = [
                                [
                                    'Title'=>'推广', 
                                    'Description'=>$user['nickname'].'的推广二维码', 
                                    'PicUrl'=>$this->server_name.'/static/picture/mobile/user_spread.jpg', 
                                    'Url'=>$view_url
                                ]
                            ];
                            $result = $this->transmitNews($object, $content_array);
                            return $result;
                        }else{
                            $content .= '未关注公众号或者用户已锁定';
                        }

                        # 图片模式
                        // $media = $this->getTempMaterial($openid);
						
                        // if($media['status']){
                        //     $result = $this->transmitImage($object, $media['media_id']);
                        //     $deal = false;
                        // }else{
                        //     $content .= $media['content'];
                        // }
                        
                    break;
                    case 'dailyCheckIn': // 每日签到, 完成 in 18.6.26
                        $model = new Users();
                        $user = $model->getUserInfo($openid, true, 1); // true 是否通过openid查询，1是否只查询status=1的
                        $this->setLoginSession(true, $user); // 设置登录session
                        if(!empty($user)){
                            $userObj = new User();
                            $res = $userObj->dailyCheckIn($user['id'], $user['nickname'], false);
                            $res = json_decode($res, true);
                            $this->setLoginSession(false); // 清理登录session
                            $content .= $res['content'];
                        }else{
                            $content .= '未关注公众号或者用户已锁定';
                        }
                    break;
                    default: 
                        $content .= " unknown ";
                    break;
                }
            break;
            case "VIEW":
                $content .= "跳转链接 ".$object->EventKey;
            break;
            case "SCAN": 
                $content .= "扫描场景 ";//.$object->EventKey;
            break;
            case "LOCATION":
                $content .= "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
            break;
            case "scancode_waitmsg":
                $content .= 'scancode_waitmsg';
            break;
            case "scancode_push": // 收货可以用这个，或者直接微信扫码
                $content .= "扫码推事件";
            break;
            case "pic_sysphoto":
                $content .= "系统拍照";
            break;
            case "pic_weixin":
                $content .= "相册发图：数量 ".$object->SendPicsInfo->Count;
            break;
            case "pic_photo_or_album":
                $content .= "拍照或者相册：数量 ".$object->SendPicsInfo->Count;
            break;
            case "location_select":
                $content .= "发送位置：标签 ".$object->SendLocationInfo->Label;
            break;
            default:
                $content .= "receive a new event: ".$object->Event;
            break;
        }

        $result = $this->transmitText($object, $content);
        return $result;
    }

}

