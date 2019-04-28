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
 * waiter 模块
 * 
 */
class Order extends ApiBase
{ /**
     * 创建订单
     */
    public function creatOrders($param = []){

        $decoded_user_token = $param['decoded_user_token'];
        $user_id = $decoded_user_token->user_id;
        $dataArray = json_decode($param['dataArray'], true);
// dump($dataArray); die; 
        /**
         * 做假数据，测试生成订单
         */
        // $param = [
        //     'game_id' => 1,
        //     'plantform_id'=>2,
        //     'area_name'=>'area_name',
        //     'user_mobile'=>'18767676767',
        //     'game_account'=>'game_account',
        //     'game_password'=>'game_password',
        //     'game_user'=>'game_user', // 游戏角色名称
        //     'game_info' =>  'game_info',
        //     'special_info' =>  'special_info',
        //     'dataArray'=> [
        //         [
        //             'server_id'=>'server_id', 'begin_info'=>'begin_info',
        //             'end_info'=>'end_info', 'server_price'=>10,
        //         ]
        //     ]
        // ];


        $success = false; // 方法是否执行成功
        $pay_money = 0; // 订单总金额
        $order_id = 'GP'.setOrderID(); // 游戏代练订单号

        foreach($dataArray as $k=>$v){
            $order_detail[] = [
                'order_id'=>$order_id,
                'user_id'=>$user_id,
                'server_id'=>$v['server_id'],
                'begin_info'=>$v['begin_info'],
                'end_info'=>$v['end_info'],
                'server_price'=>$v['server_price'],
                'server_img'=>'',
            ];
            $pay_money +=  $v['server_price'];  
        }

        
        $order = [
            
            'order_id'=>$order_id,
            'user_id' =>intval($user_id),
            'game_id' => intval($param['game_id']),
            'plantform_id'=>intval($param['plantform_id']),
            'area_name'=>$param['area_name'],
            'user_mobile'=>$param['user_mobile'],
            'game_account'=>$param['game_account'],
            'game_password'=>$param['game_password'],
            'game_user'=>$param['game_user'], // 游戏角色名称
            'pay_money' => $pay_money,
            'game_info' =>  isset($param['game_info'])?$param['game_info']:'',
            'special_info' =>  isset($param['special_info'])?$param['special_info']:'',
            'step' =>  1,
            'waiter_id' =>  0,
            'create_time' => time(),
            'pay_time' =>  '',
            'finish_time' => '',
            'status' =>  1,
            
        ];

        // 执行插入
        Db::startTrans();
        try{
            $order_res = $this->modelOrder->setInfo($order);
            $oid =  $this->modelOrder->getLastInsID();
            foreach($order_detail as $k=>$v){
                $order_detail[$k]['oid'] = $oid;
            }
       
            $detail_res = Db::name('order_detail') -> insertAll($order_detail);
            if($order_res && $detail_res){
                
                Db::commit();
                $success = true;
            }
        }catch(\Exception $e){
            // dump($e); 
            Db::rollback();
        }
        
       return $success;

    }

     /**
     * create by fjw in 19.3.14
     * 订单记录
     */
     //  order_detail id oid order_id user_id server_id server_price server_img
     //  order        id order_id  user_id game_id plantform_id area_name user_mobile game_account game_password game_user pay_money game_info 
     // special_info step waiter_id create_time pay_time finish_time status



    public function getOrderList($where=[], $field = '', $order='a.order_id desc',$paginate = false){
        $field = 'a.id, a.order_id, v.cname as game_name, j.name as plantform_name, 
                a.area_name, a.pay_money, a.step, a.status, a.create_time';
        $this->modelOrder->alias('a');//设置当前数据表的别名

        $this->modelOrder->join = [

            [SYS_DB_PREFIX."game_list v", "a.game_id = v.id", "left"],
            [SYS_DB_PREFIX."game_plantform j", " a.plantform_id = j.id", "left"],
            
        ];

        return $this->modelOrder->getList($where, $field, $order, $paginate);

    }


    
}