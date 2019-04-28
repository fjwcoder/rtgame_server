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

/**
 * 支付模块
 */
class Payment extends ApiBase
{

    /**
     * create by fjw in 19.3.14
     * 代练订单支付
     */
    public function orderPay($param = []){

        $decoded_user_token = $param['decoded_user_token'];

        $this->modelOrder->alias('o');

        $this->modelOrder->join = [
            [SYS_DB_PREFIX . 'wx_user w', 'o.user_id = w.user_id'],
        ];

        // 筛选查询字段
        $field = 'o.*, w.app_openid as openid';

        $res = $this->modelOrder->getInfo([

            'o.order_id'=>$param['order_id'], 'o.id'=>intval($param['oid']), 'o.step'=>0, 'o.status'=>1

        ], $field);
        
        if($res['step'] != 1 || $res['status'] != 1){
            return ['status'=>false, 'msg'=>'该订单不可支付'];
        }

        $order = [

            "body"=>'保险支付',
            "out_trade_no" => $res['order_id'], //$order['order_id'],
            "total_fee" => $res['pay_money'],
            'openid'=>$res['openid']
        ];

        return $this->servicePay->driverWxpay->pay($order, 'Wxapp'); 


    }


    
}
