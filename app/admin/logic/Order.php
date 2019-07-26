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

    /**
     * @Author: FengQiMan
     * @Descripttion: 查看订单详情
     * @Date: 2019-07-22 10:22:22
     */
    public function getOrderDetail($param = []){
        $oid = $param['o_id'];
        $order_id = $param['order_id'];
        $where = [
            'a.id' => $oid,
            'a.order_id' => $order_id,
        ];
        $this->modelOrder->alias('a');
        
        $this->modelOrder->join = [
            [SYS_DB_PREFIX."game_list v", "a.game_id = v.id", "left"],
            [SYS_DB_PREFIX."game_plantform j", " a.plantform_id = j.id", "left"],
            [SYS_DB_PREFIX."order_detail d", "a.id=d.oid", "left"],
        ];
        
        $field = 'a.id, a.order_id, a.user_id,v.cname as game_name,j.name as plantform_name,a.area_name,a.user_mobile,a.game_account,a.game_password,a.game_user,a.pay_money,a.game_info,a.special_info,a.step,a.waiter_id,a.create_time,a.pay_time,a.finish_time,a.status,a.order_type,d.server_id,d.begin_info,d.end_info,d.server_price,d.server_img,d.server_type,d.server_con
            ';

        return $this->modelOrder->getInfo($where, $field, '', false);
    }


}
