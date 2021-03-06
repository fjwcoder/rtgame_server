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

namespace app\admin\model;

/**
 * 菜单模型
 */
class Menu extends AdminBase
{
    
    /**
     * 隐藏状态获取器
     */
    public function getIsHideTextAttr()
    {
        
        $is_hide = [DATA_DISABLE => '否', DATA_NORMAL => '是'];
        
        return $is_hide[$this->data['is_hide']];
    }
}
