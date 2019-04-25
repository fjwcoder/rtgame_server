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
 * PROJECT_代练平台 打手信息
 * 
 * 
 */
class Waiter extends ApiBase
{

    /**
     * 申请成为代练人员
     */
    public function applyWaiter(){
        
        return $this->apiReturn($this->logicWaiter->applyWaiter($this->param));
    }



}
