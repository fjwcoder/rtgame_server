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
     * create by fjw in 19.5.6
     * 获取个人中心首页的信息
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     * @param 
     */
    public function getUserIndex(){

        return $this->apiReturn($this->logicUser->getUserIndex($this->param));

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

        $where['u.id'] = $decoded_user_token->user_id;

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
