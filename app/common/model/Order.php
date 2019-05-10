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

namespace app\common\model;


/**
 * 
 */
class Order extends ModelBase
{

    public function assignWaiter($oid, $order_id, $waiter_id, $change=true){

      
        $where = ['id'=>$oid, 'order_id'=>$order_id, 'status'=>1];
        if($change){
            $where['step'] = 2;
        }
        $data = ['step'=>3, 'waiter_id'=>$waiter_id]; 
       
        return $this->modelOrder->updateInfo($where, $data);
    }

    /**
     * 获取订单状态统计
     */
    public function getOrderByStep($user_id){
        $order = $this->modelOrder->getList(['user_id'=>$user_id, 'status'=>1]);
        $order_count = [
            'step_0'=>0, 'step_1'=>0, 'step_2'=>0, 'step_3'=>0, 
            'step_4'=>0, 'step_5'=>0, 'step_6'=>0, 'step_7'=>0

        ];

        foreach($order as $k=>$v){
            $order_count['step_'.$v['step']] += 1;
        }

        return $order_count;
    }
   
}
