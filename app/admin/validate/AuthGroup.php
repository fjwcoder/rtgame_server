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

namespace app\admin\validate;

/**
 * 权限组验证器
 */
class AuthGroup extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'name' => 'require',
    ];

    // 验证提示
    protected $message  =   [
        
        'name.require' => '权限组名称不能为空',
    ];

    // 应用场景
    protected $scene = [
        
        'add'  => ['name'],
        'edit' => ['name'],
    ];
    
}
