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
use getdate;
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
                'begin_info'=>isset($v['begin_info'])?$v['begin_info']:'',
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
        
        if($success){
            return ['status'=>$success, 'msg'=>'订单生成成功', 'oid'=>$oid,'order_id'=>$order_id];
        }else{
            return ['status'=>$success, 'msg'=>'订单生成失败'];
        }


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
    public function getOrderDetail($param = []){
        $paginate = false;
       
       $decoded_user_token = $param['decoded_user_token'];
    //    $where = ['a.user_id'=>$decoded_user_token->user_id];
        if(isset($param['step']) && $param['step'] > 0 ){
            $where['a.step'] = $param['step'];
        }
       $where['a.order_id'] = $param['order_id'];
       $where['a.id'] = $param['oid'];
       $this->modelOrder->alias('a');//设置当前数据表的别名
 
        $field = 'a.id, a.order_id, 
            a.area_name, a.game_info, a.special_info, a.pay_money, a.user_mobile,
            a.game_account, a.waiter_id, a.pay_time,a.finish_time,a.create_time,a.step,a.status

            ,v.cname as game_name, 
            j.name as plantform_name, 

            k.nickname as waiter_name,
            k.headimgurl
            ';
        // $order='a.order_id desc';
       
        $this->modelOrder->join = [

            [SYS_DB_PREFIX."game_list v", "a.game_id = v.id", "left"],
            [SYS_DB_PREFIX."game_plantform j", " a.plantform_id = j.id", "left"],
            [SYS_DB_PREFIX."waiter k", " a.waiter_id = k.id", "left"],
            
        ];


        $order =  $this->modelOrder->getInfo($where, $field); // 这样查出来是对象，要转成数组
        
        if(empty($order)){
          return CommonError::$getError; //  查询失败;
        }

        $order = $order->getData(); // 模型的查询结果为对象，需要用这个方法转成数组


      
        $where = [];
        $where['d.order_id'] = $param['order_id'];
        $where['d.oid'] = $param['oid'];
        $field = 'g.name as server_name,d.begin_info,d.end_info,d.server_price,d.server_img, d.id as s_id';
        // $order = 'd.oid desc';
        $this->modelOrderDetail->alias('d');//设置当前数据表的别名
        
        $this->modelOrderDetail->join = [

            [SYS_DB_PREFIX."game_server g", "d.server_id = g.id", "left"],
        
        ];
        
        $detail =  $this->modelOrderDetail->getList($where, $field, 'd.oid desc', $paginate);  // 这样查出来是对象，要转成数组
        // $details = [];
        // foreach($detail as $v){
        //     $details[] = $v->getData();
        // }
    //     dump($order); 
    //   dump($details); die;
        $order['detail'] = $detail;
       
        return $order;
    }
/**
     * create by fjw in 19.3.14
     * 待抢订单记录
     */
     //  order_detail id oid order_id user_id server_id server_price server_img
     //  order        id order_id  user_id game_id plantform_id area_name user_mobile game_account game_password game_user pay_money game_info 
     // special_info step waiter_id create_time pay_time finish_time status


    /**
     * 抢单列表: 未分配的列表
     */
     public function getHeldOrderList($param = []){
        // dump($param);die;
       
        $decoded_user_token = $param['decoded_user_token'];
        $user_id =  $decoded_user_token->user_id;

  
        $waiter = $this->modelWaiter->getInfo(['user_id'=>$user_id, 'status'=>1]);

        if (empty($waiter)){
            return [API_CODE_NAME => 40202, API_MSG_NAME => '该用户不是代练人员']; 

        }

        $where = ['a.step'=>2, 'a.status'=>1, 'a.waiter_id'=>0];
        if (isset($param['gid']) && $param['gid'] != 0){
            $where['a.game_id'] = $param['gid'];
        }
        
        $order='a.pay_money desc';
        $this->modelOrder->alias('a');//设置当前数据表的别名
  
         $field = 'a.id,a.order_id ,v.cname as game_name, j.name as plantform_name, 
            a.area_name,a.game_info ,a.special_info, a.waiter_id,
            a.pay_money,a.user_mobile,a.game_account
            ,a.step,a.status, a.create_time
        ';
         // 
        $this->modelOrder->join = [
            [SYS_DB_PREFIX."game_list v", "a.game_id = v.id", "left"],
            [SYS_DB_PREFIX."game_plantform j", " a.plantform_id = j.id", "left"],
            // [SYS_DB_PREFIX."waiter k", " a.waiter_id = k.id", "left"],
            
        ];

        return $this->modelOrder->getList($where, $field, $order, 15);

    } 

    /**
     * 抢单
     */
    public function assignWaiter($param = []){
        // dump($this->param);die;
        $oid = $param['oid'];
        $order_id = $param['order_id'];
        $decoded_user_token = $param['decoded_user_token'];
        $user_id =  $decoded_user_token->user_id;
        $waiter = $this->modelWaiter->getInfo(['user_id'=>$user_id,'status'=>1]); // add by fjw：查询条件要加上状态status=1
        // add by fjw: 增加验证：如果该用户不存在，需要返回给前台信息
        if(empty($waiter)){
            return CommonError::$refuseError; //  没有权限接受订单;
          }
        $waiter_id = $waiter['id'];
        return $this->modelOrder->assignWaiter($oid, $order_id, $waiter_id);
    }
    
     /**
     * 修改订单状态
     * @param order_id
     * @param id
     * @param step
     * @param next
     */
    public function changeOrderStep($param = []){
        $where = ['order_id'=>$param['order_id'], 'id'=>$param['id'], 'step'=>$param['step']];
        $order = $this->modelOrder->getInfo($where);
        if($order){
            $change = $this->modelOrder->updateInfo($where, ['step'=>$param['next']]);
        }else{
            return [API_CODE_NAME => 40203, API_MSG_NAME => '订单不存在'];
        }
    
     
    }

/**
     * 修改订单状态
     * @param order_id
     * @param id
     * @param status
     */
    public function changeOrderStatus($param = []){
        $where = ['order_id'=>$param['order_id'], 'id'=>$param['id'], 'status'=>$param['status']];
        $order = $this->modelOrder->getInfo($where);
        if($order){
            $status = ($param['status'] == 1)?2:1;
            $change = $this->modelOrder->updateInfo($where, ['status'=>$status]);
        }else{
            return [API_CODE_NAME => 40203, API_MSG_NAME => '订单不存在'];
        }
    
     
    }

/**
     * 修改订单状态
     * @param order_id
     * @param id
     * @param s_id
     * @param server_img
     */
    public function saveFinishImg($param = []){
        $where = ['order_id'=>$param['order_id'], 'id'=>$param['id'], 'step'=>3];
        $order = $this->modelOrder->getInfo($where);
        if($order){
            $change = $this->modelOrderDetail->updateInfo(['oid'=>$param['id'], 'order_id'=>$param['order_id'], 'id'=>$param['s_id']], ['server_img'=>$param['server_img']]);
        }else{
            return [API_CODE_NAME => 40203, API_MSG_NAME => '订单不存在'];
        }
    
     
    }



}