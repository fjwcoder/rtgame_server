<?php
/*
 * @Descripttion: 机器人订单逻辑
 * @Author: FengQiMan
 * @Date: 2019-07-22 08:55:45
 */

namespace app\api\logic;

use app\api\error\Common as CommonError;

class Robot extends ApiBase
{ 
  

    public function getROrderDetail($param = []){
        $paginate = false;
       
        $decoded_user_token = $param['decoded_user_token'];

        $user_id = $decoded_user_token->user_id;

        // $waiter = $this->modelWaiter->getInfo(['user_id'=>$user_id]);
        // if (empty($waiter)){
        //     return [API_CODE_NAME => 40202, API_MSG_NAME => '该用户不是代练人员']; 
        // }

        // $w_where['order_id'] = $param['order_id'];
        // $w_where['id'] = $param['oid'];
        // $w_field = 'waiter_id,user_id';
        // $order_u_w = $this->modelRobotOrder->getInfo($w_where,$w_field);

        // if(isset($param['step']) && $param['step'] > 0 ){
        //     $where['a.step'] = $param['step'];
        // }
        
        $where['a.order_id'] = $param['order_id'];
        $where['a.id'] = $param['oid'];
        $this->modelRobotOrder->alias('a');//设置当前数据表的别名
        $field = 'a.id, a.order_id, 
            a.area_name, a.game_info, a.special_info, a.pay_money, a.user_mobile,
            a.game_account, 
            a.waiter_id, a.pay_time,a.finish_time,a.create_time,a.step,a.status,a.order_type

            ,v.cname as game_name, 
            j.name as plantform_name, 

            k.nickname as waiter_name,
            k.headimgurl
            ';
        // $order='a.order_id desc';
       
        $this->modelRobotOrder->join = [
            [SYS_DB_PREFIX."game_list v", "a.game_id = v.id", "left"],
            [SYS_DB_PREFIX."game_plantform j", " a.plantform_id = j.id", "left"],
            [SYS_DB_PREFIX."waiter k", " a.waiter_id = k.id", "left"],
        ];


        $robot_order = $this->modelRobotOrder->getInfo($where, $field); // 这样查出来是对象，要转成数组
        // dd($robot_order);
        if(empty($robot_order)){
          return CommonError::$getError; //  查询失败;
        }

        $order = $robot_order->getData(); // 模型的查询结果为对象，需要用这个方法转成数组

        $where = [];
        $where['d.order_id'] = $param['order_id'];
        $where['d.oid'] = $param['oid'];
        
        $field = 'g.name as server_name,d.begin_info,d.end_info,d.server_price,d.server_img, d.id as s_id,d.server_type,d.server_con';
        
        $this->modelRobotOrderDetail->alias('d');//设置当前数据表的别名
        
        $this->modelRobotOrderDetail->join = [
            [SYS_DB_PREFIX."game_server g", "d.server_id = g.id", "left"],
        ];
        
        $detail =  $this->modelRobotOrderDetail->getList($where, $field, '', $paginate);  // 这样查出来是对象，要转成数组

        $order['detail'] = $detail;
       
        return $order;
    }
 

}