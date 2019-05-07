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
   
}
