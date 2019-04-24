<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | fjwcoder<fjwcoder@gmail.com>                           |
// +---------------------------------------------------------------------+
// | Repository |   |
// +---------------------------------------------------------------------+

namespace app\common\logic;

use app\api\error\CodeBase;
use app\api\error\Common as CommonError;
use think\Db;
/**
 * 前台用户逻辑
 */
class User extends LogicBase
{
    
    /**
     * create by fjw in 18.12.12
     * 获取会员信息
     */
    public function getUserInfo($where = [], $field = true)
    {
        
        return $this->modelUser->getInfo($where, $field);
    }
    /**
     * create by fjw in 19.3.25
     * 获取会员Index页面信息
     */
    // public function getUserIndex($param = [])
    // {       
    //     // 定义查询条件
    //     $decoded_user_token = $this->param['decoded_user_token'];
    //     dump($decoded_user_token); die;
    //     $where = [''];

    //     $this->modelUser-
        
        
    // }

    /**
     * create by fjw in 19.3.19
     * 获取会员详细信息
     */
    public function getUserDetail($where = [], $field = true){

        $this->modelUser->alias('u');

        $this->modelUser->join = [
            [SYS_DB_PREFIX . 'wx_user w', 'u.id = w.user_id'],
        ];

        // 筛选查询字段
        $field = 'u.us_name, u.us_qq, u.mobile, u.id_card, 
                  u.address_detail, u.id_card_begintime, 
                  u.id_card_endtime, u.us_age, u.status,
                  u.us_sheng, u.us_shi, u.us_qu, u.us_zhen,
                  w.country, w.sex
            ';

        return $this->modelUser->getInfo($where, $field);
    }

    /**
     * create by fjw in 19.3.19 
     * 完善用户资料
     */
    public function editUserDetail($param = []){

        $decoded_user_token = $param['decoded_user_token'];
        // 1. 拼接数据
        $data_user = [
            'us_name'=>$param['us_name'], 
            'mobile'=>$param['mobile'], 
            'id_card'=>$param['id_card'], 
            'address_detail'=>$param['address_detail'], 
            'id_card_begintime'=>$param['id_card_begintime'], 
            'id_card_endtime'=>$param['id_card_endtime'], 
            'us_age'=>intval($param['us_age']),
            'us_sheng'=>$param['us_sheng'], 
            'us_shi'=>$param['us_shi'], 
            'us_qu'=>$param['us_qu']
            // 'us_zhen'=>$param['us_zhen']
        ];
        $data_wx_user = ['country'=>$param['country'],'sex'=>intval($param['sex'])];
        $success = true;
        
        Db::startTrans();
        try{

            if($this->modelUser->updateInfo(['id'=>$decoded_user_token->user_id, 'status'=>1], $data_user)) {
                $this->modelWxUser->updateInfo(['user_id'=>$decoded_user_token->user_id, 'app_openid'=>$decoded_user_token->app_openid], $data_wx_user);
            
                Db::commit();

            }else{
                $success = false;
                $result_code = CommonError::$editUserDetailFail; // 用户详细信息修改失败
            }
        }catch(\Exception $e){
            dump($e);
            Db::rollback();
            $success = false;
            $result_code = CommonError::$bindMobileFail; // 绑定手机号失败
        }
        
        if(!$success){
            return $result_code;
        }
    }

    /**
     * create by fjw in 19.3.19 
     * 微信小程序绑定手机号
     */
    public function wxappBindMobile($param = []){

        // 1. 参数不可为空
        // if(empty($data['mobile']) || empty($data['yzm'])){
        if(empty($param['mobile']) ){

            return CommonError::$paramEmpty;
    
        }
        // 2. 手机号码格式错误
        if(!isMobileNumber($param['mobile'])){
            
            return CommonError::$mobileFormatError;

        }

        

        // 3. 验证码的问题
        // $code = apiRedisGet('SMS_CODE_'.$param['mobile']);
        // if(apiRedisGet('SMS_CODE_'.$param['mobile']) != $param['yzm']){
        //     return CommonError::$smsCodeError;
        // }

        $decoded_user_token = $param['decoded_user_token'];
        // 4.1 查询该手机号和该app_openid下是否存在用户
        $wx_user = $this->modelWxUser->getInfo(['app_openid'=>$decoded_user_token->app_openid, 'mobile'=>$param['mobile']]);
        if($wx_user){
            return CommonError::$existThisUser;
        }

        // 4.2 查询user表中是否有该手机号的用户
        $user = $this->modelUser->getInfo(['mobile'=>$param['mobile'], 'status'=>1]);
        // $result = [];
        $success = true;
        $result_code = CodeBase::$success;
        Db::startTrans();
        try{
            if(empty($user)){ // 不存在，先创建用户；先插入user表，再返回存入wx_user表
                // 拼凑数据
                $user_data = [
                    'mobile'=>$param['mobile'], 'unionid'=>$wx_user['unionid'], 
                    'us_headpic'=>$wx_user['headimgurl'], 
                    'status'=>1
                ];
                if($this->modelUser->setInfo($user_data)){ // 插入user表
                    $user_id = $this->modelUser->getLastInsID();
                    // 插入成功以后，返回存入wx_user表
                    $up_res = $this->modelWxUser->updateInfo(
                        ['app_openid'=>$decoded_user_token->app_openid], 
                        ['user_id'=>$user_id, 'mobile'=>$param['mobile']]
                    );
                    if($up_res){
                        Db::commit();
                    }else{
                        $success = false;
                        $result_code = CommonError::$bindMobileFail; // 绑定手机号失败
                    }
                }else{
                    $success = false;
                    $result_code = CommonError::$bindMobileFail; // 绑定手机号失败
                }
                
            }else{
                $up_res = $this->modelWxUser->updateInfo(
                    ['app_openid'=>$decoded_user_token->app_openid], 
                    ['user_id'=>$user['id'], 'mobile'=>$param['mobile']]
                );
                if($up_res){
                    Db::commit();
                }else{
                    $success = false;
                    $result_code = CommonError::$bindMobileFail; // 绑定手机号失败
                }


            }
             
        }catch(\Exception $e){
            dump($e);
            Db::rollback();
            $success = false;
            $result_code = CommonError::$bindMobileFail; // 绑定手机号失败
        }
        if($success){
            $bind_user = $this->modelWxUser->getInfo(['app_openid'=>$decoded_user_token->app_openid], 'user_id, mobile, app_openid, unionid');
            return tokenSign(wxappReturnUserInfo($bind_user));
        }else{
            return $result_code;
        }
        

    }


    





    


}
