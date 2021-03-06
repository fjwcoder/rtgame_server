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

namespace app\api\controller;

use app\common\controller\ControllerBase;

use app\api\error\Common as CommonError;
/**
 * PROJECT_创建订单
 * 
 * 
 */
class Order extends ApiBase
{

    /**
     * 创建订单
     */
    public function creatOrders(){
        
        return $this->apiReturn($this->logicOrder->creatOrders($this->param));
    }

      /**
     * create by fjw in 19.3.14
     * 订单记录
     * @param user_id: 用户id
     */
    public function getOrderList(){
       
        // dump($this->param);die;
        $decoded_user_token = $this->param['decoded_user_token'];
        $where = ['a.user_id'=>$decoded_user_token->user_id];

        // $where['a.status'] = isset($this->param['status'])?$this->param['status']:['<', 9];
        if(isset($this->param['status']) && $this->param['status'] != 0){
            $where['a.status'] = $this->param['status'];
        }
        if(isset($this->param['step']) && $this->param['step'] != 0 && $this->param['step'] != ''){ // 订单进度： 1 生成，待支付；2 支付，待分配；3 代练中；4 完成 5，默认0，即获取全部进度的订单
             
            $where['a.step'] = $this->param['step'];

        }
           



        return $this->apiReturn($this->logicOrder->getOrderList($where));

    }

      /**
     * create by fjw in 19.3.14
     * 订单详情
     * @param user_id: 用户id
     */
    public function getOrderDetail(){
       

        // $decoded_user_token = $this->param['decoded_user_token'];
        // $where = ['a.user_id'=>$decoded_user_token->user_id];
       
             
        //     // $where['a.oid'] = $this->param['oid'];

        
        //    $where['a.order_id'] = $this->param['order_id'];
        //    $where['a.oid'] = $this->param['oid'];
        // dd($this->param['true_type']);
        // 19.7.22 fengqiman 添加 true_type 字段 1：
        if(isset($this->param['true_type']) && $this->param['true_type'] == '2'){
            return $this->apiReturn($this->logicRobot->getROrderDetail($this->param));

        // 19.2.25 fengqiman 修改查询订单中不传 true_type 字段问题，前期小程序查看订单详情不传 true_type 字段
        // }elseif(isset($this->param['true_type']) && $this->param['true_type'] == '1'){
        //     return $this->apiReturn($this->logicOrder->getOrderDetail($this->param));
        }else{
            return $this->apiReturn($this->logicOrder->getOrderDetail($this->param));
            // return $this->apiReturn(CommonError::$getError); // 没有传 true_type 查询失败;
        }
        
        // die;
    }
 /**
     * create by fjw in 19.3.14
     * 抢单列表：未分配列表
     * @param user_id: 用户id
     */
    public function getHeldOrderList(){
       
      
        return $this->apiReturn($this->logicOrder->getHeldOrderList($this->param));

    }
/**
     * 抢单
     */
    public function assignWaiter(){
    
        return $this->apiReturn($this->logicOrder->assignWaiter($this->param));
    }

    /**
     * 修改订单进度
     */
    public function changeOrderStep(){
        return $this->apiReturn($this->logicOrder->changeOrderStep($this->param));
    }

    /**
     * 取消订单
     */
    public function changeOrderStatus(){
        return $this->apiReturn($this->logicOrder->changeOrderStatus($this->param));
    }

    /**
     * 保存完成截图
     */
    public function saveFinishImg(){
        return $this->apiReturn($this->logicOrder->saveFinishImg($this->param));
    }

    /**
     * 小程序首页订单列表 只显示 已付钱-没有代练的 优先显示待接单的订单
     * FengQiMan 2019-07-16
     * 已支付-未接单 step=2
     */
    public function getIndexOrder()
    {
        return $this->apiReturn($this->logicOrder->getIndexOrder());
    }


   
}