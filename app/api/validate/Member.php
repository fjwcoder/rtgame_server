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

namespace app\api\validate;

/**
 * 会员验证器
 */
class Member extends ApiBase
{
    
    // 验证规则
    protected $rule = [
        'username'  => 'require',
        'password'  => 'require',
    ];

    // 验证提示
    protected $message = [
        'username.require'    => '用户名不能为空',
        'password.require'    => '密码不能为空',
    ];

    // 应用场景
    protected $scene = [
        
        'login'  =>  ['username','password'],
    ];
}
