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
 * PROJECT_创建订单
 * 
 * 
 */
class Order extends ApiBase
{

    /**
     * 创建订单
     */
    public function creatOrders(){
        
        return $this->apiReturn($this->logicOrder->creatOrders($this->param));
    }



}