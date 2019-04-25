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

namespace app\api\logic;

use app\api\error\CodeBase;
use app\api\error\Common as CommonError;
// use \Firebase\JWT\JWT;

/**
 * 接口基础逻辑
 */
class Common extends ApiBase
{


    /**
     * 发送短信验证码
     * @param $data['type']: 
     *  0 常规验证码，不进行前置检测
     *  1 手机号存在时发送
     *  2 手机号不存在时发送
     */
    public function sendSms($data = []){
        $type = isset($data['type'])?$data['type']:0;
        
        switch($type){
            case 1: // 强制账号存在时发送
                $account = $this->modelUser->getInfo(['mobile'=>$data['mobile']], 'mobile');
                if(empty($account)){ 
                    return CommonError::$emptyThisUser; // 该账号不存在
                }
            break;
            case 2: // 强制账号不存在时发送
                $account = $this->modelUser->getInfo(['mobile'=>$data['mobile']], 'mobile');
                if($account){ 
                    return CommonError::$existThisUser; // 该手机号已存在
                }
            break;
            default: 

            break;
        }
        $parameter['sign_name'] = '妈咪爱天使';
        $parameter['template_code'] = 'SMS_109680029';
        $parameter['phone_number'] = $data['mobile'];
        $code = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
        $parameter['template_param'] = [
            'code' => $code,
            'product' => '信息验证码',
        ];

        $result = $this->serviceSms->driverAlidy->sendSms($parameter);
        if($result){ // 发送成功
            // 把code存入缓存
            if(!apiRedisSet('SMS_CODE_'.$data['mobile'], $code, 300)){
                return CommonError::$smsCodeSendError;
            }

        }else{
            return CommonError::$smsCodeSendError;
        }
        

    }

    /**
     * create by fjw in 19.3.12
     * APP前台登录逻辑接口
     * mobile
     * password
     */
    public function userLogin($data = []){

        // 1. 参数不可为空
        if(empty($data['mobile']) || empty($data['password'])){

            return CommonError::$usernameOrPasswordEmpty;

        }
        // 2. 手机号码格式错误
        if(!isMobileNumber($data['mobile'])){
                    
            return CommonError::$mobileFormatError;

        }


        $user = $this->logicUser->getUserInfo(['mobile'=>$data['mobile']]);

        if(empty($user)){

            return CommonError::$emptyThisUser;

        }

        if (data_md5_key($data['password']) !== $user['password']) {
            
            return CommonError::$passwordError;
        }


        return tokenSign(formatReturnUserInfo($user));
    }

    /**
     * create by fjw in 19.3.12
     * APP前台用户注册方法  
     * mobile
     * password
     * yzm
     */
    public function userRegist($data = [])
    {
        // 1. 参数不可为空
        // if(empty($data['mobile']) || empty($data['yzm'])){
        if(empty($data['mobile']) ){
            return CommonError::$paramEmpty;

        }
        // 2. 手机号码格式错误
        if(!isMobileNumber($data['mobile'])){
            
            return CommonError::$mobileFormatError;

        }

        // 3. 验证码的问题
        $code = apiRedisGet('SMS_CODE_'.$data['mobile']);
        if(apiRedisGet('SMS_CODE_'.$data['mobile']) != $data['yzm']){
            return CommonError::$smsCodeError;
        }

        
        // 4. 判断用户是否存在
        $user = $this->logicUser->getUserInfo(['mobile'=>$data['mobile']]);
        if(!empty($user)){
            return CommonError::$existThisUser;
        }
        
        $data['password']  = data_md5_key($data['password']);

        $register_result = $this->logicUser->setInfo($data);
        if (!$register_result) {
            return CommonError::$registerFail;
        }

        $user = $this->logicUser->getUserInfo(['mobile'=>$data['mobile']]);

        // 将返回的信息格式化成mami项目需要的格式
        return tokenSign(formatReturnUserInfo($user));

    }


    /**
     * 微信小程序用户授权、登录
     */
    public function wxappLogin($param = []){

        // 1. 获取session_key 和 openid
        $wxapp_session = $this->serviceWxapp->driverWxappli->sessionKey($param['code']);
// dump($wxapp_session); die;
        if($wxapp_session == false){ // 获取用户信息失败

            return CommonError::$loginFail;

        }

        $user_info = isset($param['user_info'])?json_decode(htmlspecialchars_decode($param['user_info']), true):[];

        // 2. 用户注册
        if(isset($wxapp_session['unionid'])){
            $unionid = $wxapp_session['unionid'];
        }else{
            $unionid = isset($user_info['unionid'])?$user_info['unionid']:'';
        }
            // 2.1 查询用户是否存在
        $user = $this->modelWxUser->getInfo([
            'app_openid' => $wxapp_session['openid'], 
            'unionid' => $unionid]);
// dump($user); die;
        if(empty($user)){ // 不存在，注册
            $user = [
                'app_openid' => $wxapp_session['openid'],
                'nickname' => $user_info['nickName'],
                'sex' => $user_info['gender'],
                'headimgurl' => $user_info['avatarUrl'],
                'country' => $user_info['country'],
                'province' => $user_info['province'],
                'city' => $user_info['city'],
                // 'app_subscribe_time' => time(),
                'unionid' =>$unionid
            ];
            
            
            if(!$this->modelWxUser->setInfo($user)){

                return CommonError::$loginFail;

            }
            
        }

        // 3. 生成user_token (3rd_token)
        $format = wxappReturnUserInfo($user);
        
        return tokenSign($format);

    }


/** ===================== end 前台 by fjw ===================================== */

    
    


    /**
     * 前台用户修改密码
     */
    // public function userChangePassword($data)
    // {
        
    //     $user = get_member_by_token($data['user_token']);
        
    //     $user_info = $this->logicUser->getUserInfo(['id' => $user->member_id]);
        
    //     if (empty($data['old_password']) || empty($data['new_password'])) {
            
    //         return CommonError::$oldOrNewPassword;
    //     }
        
    //     if (data_md5_key($data['old_password']) !== $user_info['password']) {
            
    //         return CommonError::$passwordError;
    //     }

    //     $user_info['password'] = $data['new_password'];
        
    //     $result = $this->logicUser->setInfo($user_info);
        
    //     return $result ? CodeBase::$success : CommonError::$changePasswordFail;
    // }


    // /**
    //  * 后台用户登录接口逻辑
    //  */
    public function memberLogin($data = [])
    {
      
        // $validate_result = $this->validateMember->scene('login')->check($data);
        if(!isset($data['username']) || !isset($data['password'])){

            return CommonError::$usernameOrPasswordEmpty;
        }

        $member = $this->logicMember->getMemberInfo(['username' => $data['username'], 'status'=>1]);
// dump($member); die;

        if (empty($member)){

            return CommonError::$emptyThisUser;

        }
        
        if (data_md5_key($data['password']) !== $member['password']) {
            
            return CommonError::$passwordError;
        }
        $formatmember = [
            'id'=>$member['id'],
            'nickname'=>$member['nickname'],
            'username'=>$member['username'],
        ];
        return tokenSign($formatmember);
    }
    
    
    // /**
    //  * 后台用户注册方法
    //  */
    // public function register($data)
    // {
        
    //     $data['nickname']  = $data['username'];
    //     $data['password']  = data_md5_key($data['password']);

    //     return $this->logicMember->setInfo($data);
    // }
    // /**
    //  * 修改密码
    //  */
    // public function changePassword($data)
    // {
        
    //     $member = get_member_by_token($data['user_token']);
        
    //     $member_info = $this->logicMember->getMemberInfo(['id' => $member->member_id]);
        
    //     if (empty($data['old_password']) || empty($data['new_password'])) {
            
    //         return CommonError::$oldOrNewPassword;
    //     }
        
    //     if (data_md5_key($data['old_password']) !== $member_info['password']) {
            
    //         return CommonError::$passwordError;
    //     }

    //     $member_info['password'] = $data['new_password'];
        
    //     $result = $this->logicMember->setInfo($member_info);
        
    //     return $result ? CodeBase::$success : CommonError::$changePasswordFail;
    // }
    
    // /**
    //  * 友情链接
    //  */
    // public function getBlogrollList()
    // {
        
    //     return $this->modelBlogroll->getList([DATA_STATUS_NAME => DATA_NORMAL], true, 'sort desc,id asc', false);
    // }
}
