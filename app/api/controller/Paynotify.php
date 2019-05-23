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

namespace app\api\controller;

use app\common\controller\ControllerBase;

/**
 * 支付回调
 */
class Paynotify extends ControllerBase
{
/**
     * 
     */
    // public function wxappInsurancePayNotify(){

    //     return $this->logicPaynotify->wxappInsurancePayNotify();

    // }

    /**
     * 代练订单支付回调
     */
    public function qijianPayNotify(){
        // echo 'hello'; die;
        return $this->logicPaynotify->qijianPayNotify();

    }

}
