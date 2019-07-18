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

namespace app\admin\logic;

/**
 * 
 */
class Order extends AdminBase
{

    public function getOrderList($where, $field = '', $order = 'id', $paginate=false){

        
        $this->modelOrder->alias('a');//设置当前数据表的别名

        $this->modelOrder->join = [

            [SYS_DB_PREFIX."game_list v", "a.game_id = v.id", "left"],
            [SYS_DB_PREFIX."game_plantform j", " a.plantform_id = j.id", "left"],
            [SYS_DB_PREFIX."order_detail d", "a.id=d.oid", "left"],
            
        ];       

        // FengQiMan 2019-07-15 添加订单表 order_type 字段 默认为1 1：代练 2：陪玩
        $field = 'a.id, a.order_id, v.cname as game_name, j.name as plantform_name, 
            a.area_name, a.pay_money, a.step, a.status, a.create_time, a.order_type,
            
            d.begin_info, d.end_info
            
            ';
        // dump($paginate); die;
        return $this->modelOrder->getList($where, $field, $order, $paginate);
    }

    public function assignWaiter($oid, $order_id, $waiter_id){

        return $this->modelOrder->assignWaiter($oid, $order_id, $waiter_id, false);
    }
}
