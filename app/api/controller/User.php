<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | fjwcoder<fjwcoder@gmail.com>                           |
// +---------------------------------------------------------------------+
// | Repository |                   |
// +---------------------------------------------------------------------+

namespace app\api\controller;

use app\common\controller\ControllerBase;

/**
 * PROJECT_妈咪v2 用户模块
 * 
 * 
 * wxapp注册后，绑定手机号码
 */
class User extends ApiBase
{
    /**
     * create by fjw in 19.2.25
     * 获取 wxapp index页面的数据
     */
    public function wxappGetUserInfo(){

//         $decoded_user_token = $this->param['decoded_user_token'];
//         // dump($decoded_user_token); die;
//         $where = ['u.id'=>$decoded_user_token->user_id, 'u.status'=>1];
    
// // dump($this->logicUser->getUserDetail($where)); die;
//         return $this->apiReturn($this->logicUser->getUserDetail($where));
    }

    /**
     * create by fjw in 19.3.18
     * 获取用户详情
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     */
    public function getUserDetail(){

        // 获取用户详情，通过参数拼凑查询条件
        $where = ['u.status'=>1];

        $decoded_user_token = $this->param['decoded_user_token'];
// dump($decoded_user_token); die;
        // if(isset($decoded_user_token->user_id) && $decoded_user_token->user_id != 0){
            $where['u.id'] = $decoded_user_token->user_id;
            // $where['w.user_id'] = $decoded_user_token->user_id;
        // } 

        // if(isset($decoded_user_token->unionid)){
        //     // $where['u.unionid'] = $decoded_user_token->unionid;
        //     $where['u.unionid'] = $decoded_user_token->unionid;
        // }

        // if(isset($decoded_user_token->mobile)){
        //     $where['u.mobile'] = $decoded_user_token->mobile;
        //     $where['w.mobile'] = $decoded_user_token->mobile;
        // }

        // isset($decoded_user_token->app_openid) && $where['w.app_openid'] = $decoded_user_token->app_openid;
        // isset($decoded_user_token->wx_openid) && $where['w.wx_openid'] = $decoded_user_token->wx_openid;

        // dump($where); die;
        return $this->apiReturn($this->logicUser->getUserDetail($where));

    }

    /**
     * create by fjw in 19.3.18
     * 完善用户信息
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     */
    public function editUserDetail(){

        return $this->apiReturn($this->logicUser->editUserDetail($this->param));

    }

    /**
     * create by fjw in 19.3.18
     * wxapp注册后绑定手机号码
     * @param mobile: 
     * @param yzm:
     */
    public function wxappBindMobile(){
 

        return $this->apiReturn($this->logicUser->wxappBindMobile($this->param));

    }




}
