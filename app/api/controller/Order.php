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
        $where['a.status'] = isset($this->param['status'])?$this->param['status']:1;
        if(isset($this->param['step']) && $this->param['step'] != 0 && $this->param['step'] != ''){ // 订单进度： 1 生成，待支付；2 支付，待分配；3 代练中；4 完成 5，默认0，即获取全部进度的订单
             
            $where['a.step'] = $this->param['step'];

        }
           



        return $this->apiReturn($this->logicOrder->getOrderList($where));

    }

      /**
     * create by fjw in 19.3.14
     * 订单记录
     * @param user_id: 用户id
     */
    public function getOrderDetail(){
       

        // $decoded_user_token = $this->param['decoded_user_token'];
        // $where = ['a.user_id'=>$decoded_user_token->user_id];
       
             
        //     // $where['a.oid'] = $this->param['oid'];

        
        //    $where['a.order_id'] = $this->param['order_id'];
        //    $where['a.oid'] = $this->param['oid'];


        return $this->apiReturn($this->logicOrder->getOrderDetail($this->param));
        // die;
    }
 /**
     * create by fjw in 19.3.14
     * 待抢订单记录
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

}