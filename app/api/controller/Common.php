<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | fjwcoder<fjwcoder@gmail.com>                           |
// +---------------------------------------------------------------------+
// |  |                      |
// +---------------------------------------------------------------------+

namespace app\api\controller;

/**
 * PROJECT_妈咪v2  公共基础接口控制器
 * 1. 用户登录
 * 2. 用户注册
 * 3. 用户修改密码
 * 4. 短信验证码
 */
class Common extends ApiBase
{

    /**
     * 短信验证码
     */
    public function sendSms(){

        return $this->apiReturn($this->logicCommon->sendSms($this->param));

    }

    /**
     * 微信小程序用户注册
     */
    public function wxappLogin(){

        return $this->apiReturn($this->logicCommon->wxappLogin($this->param));

    }


    
}
