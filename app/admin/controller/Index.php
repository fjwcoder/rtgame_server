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

namespace app\admin\controller;

/**
 * 首页控制器
 */
class Index extends AdminBase
{
    
    /**
     * 首页方法
     */
    public function index()
    {

        // 获取首页数据
        $index_data = $this->logicAdminBase->getIndexData();
        
        $this->assign('info', $index_data);
        
        return $this->fetch('index');
    }
}
