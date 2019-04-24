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
 * PROJECT_妈咪v2  支付模块
 * 
 * 
 * 
 */
class Payment extends ApiBase
{
    /**
     * create by fjw in 19.3.25
     * 保险业务订单支付
     */
    public function insurancePay(){

        return $this->apiReturn($this->logicPayment->insurancePay($this->param));

    }
    

    



}
