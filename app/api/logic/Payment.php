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
     * 疫苗预约记录
     */
    public function insurancePay($param = []){
// dump($param);
        $decoded_user_token = $param['decoded_user_token'];
        $this->modelInsuranceOrder->alias('o');

        $this->modelInsuranceOrder->join = [
            [SYS_DB_PREFIX . 'wx_user w', 'o.user_id = w.user_id'],
        ];

        // 筛选查询字段
        // $field = 'o.id, o.order_id, o.user_id, o.baby_id, o.insurance_id,
        //     o.pay_money, o.step, o.status, w.app_openid as openid';
        $field = 'o.*, w.app_openid as openid';

        $res = $this->modelInsuranceOrder->getInfo([
            'o.order_id'=>$param['order_id'], 'o.step'=>0, 'o.status'=>1
        ], $field);
        

        $order = [
            "body"=>'保险支付',
            "out_trade_no" => $res['order_id'], //$order['order_id'],
            "total_fee" => $res['pay_money'],
            'openid'=>$res['openid']
            ];

        return $this->servicePay->driverWxpay->pay($order, 'Wxapp'); 
        // return $this->modelVaccineAppointment->getList($where, $field, $order, $paginate);

    }


    
}
