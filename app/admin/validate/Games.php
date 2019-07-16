<?php
/*
 * @Descripttion: 游戏验证器
 * @Author: FengQiMan
 * @Date: 2019-07-15 10:28:25
 */

namespace app\admin\validate;

class Games extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'cname'  => 'require|chsDash|unique:game_list',
        'ename'  => 'require|chsDash|unique:game_list',
        'hot'   => 'number',
        'status'   => 'require|in:0,1'
    ];

    // 验证提示
    protected $message  =   [
        
        'cname.require'    => '中文名称不能为空',
        'cname.chsDash'    => '中文名称格式不正确',
        'cname.unique'     => '中文名称已存在',
        'ename.require'    => '英文名称不能为空',
        'ename.chsDash'    => '英文名称格式不正确',
        'ename.unique'     => '英文名称已存在',
        'hot.number'      => '热度必须是数字',
        'status.require'     => '状态不能为空',
        'status.in'     => '状态格式不正确'
    ];

    // 应用场景
    protected $scene = [
        
        'add'  =>  ['cname', 'ename', 'hot', 'status'],
        // 'edit' =>  ['name', 'sort', 'url'],
    ];
    
}
