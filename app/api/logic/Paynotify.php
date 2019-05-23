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

namespace app\api\logic;

use app\api\error\Common as CommonError;
use think\Db;
/**
 * 支付回调模块
 */
class Paynotify extends ApiBase
{

    /**
     * create by fjw in 19.3.31
     * 支付回调
     */
    public function qijianPayNotify(){

        $success = false;
        // 1. 获取支付回调的详细信息
        $result = $this->servicePay->driverWxpay->notify(); 
        // dump($result); die;
        // $result['out_trade_no'] = 'GPI523827610380940';
        $order_where = ['order_id'=>$result['out_trade_no'], 'status'=>1, 'step'=>1];
        $order = $this->modelOrder->getInfo($order_where, true);
        // dump($order); die;
        if($order){
            Db::startTrans();
            try{
                $up_order_info = ['step'=>2, 'pay_time'=>time()];
                if($this->modelOrder->updateInfo($order_where, $up_order_info)){
                    $success = true;
                    Db::commit();
                }
            }catch(\Exception $e){
                // dump($e); 
                Db::rollback();
            }

        }
        
        if($success){
            return 'success';
        }else{
            return 'fail';
        }

    }

    
}
