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

namespace app\admin\controller;

/**
 * 首页控制器
 */
class Order extends AdminBase
{
    
    /**
     *  首页
     */
    public function index()
    {


        return $this->fetch('index');
    }



    /**
     *  订单列表
     */
    public function list()
    {   
        // 获取代练人员
        $waiter = $this->logicWaiter->getWaiterList(['status'=>1]);
        $this->assign('waiter', $waiter);
        $this->assign('waiter_json', json_encode($waiter, JSON_UNESCAPED_UNICODE));

        return $this->fetch('list');
    }

    // 
    public function getOrderList(){
        $step = input('step', 0, 'intval');
        $where = [];
        if($step !== 0){
            $where['step'] = $step;
        }

        $list = $this->logicOrder->getOrderList($where, true, 'id', 15);  //JSON_UNESCAPED_UNICODE
        

        // $list['code'] = 200;
        // $list['msg'] = '';
        // $data['code'] = 200;
        // $data['msg'] = '';
        // $data['data'] = $list;

        return $list;
    }

    // 分配代练人员
    public function assignWaiter(){
        $oid = input('oid', 20, 'intval');
        $order_id = input('order_id', 'GPI429191776051307');
        $waiter_id = input('waiter_id', 2, 'intval');
        $data = [];
        if($this->logicOrder->assignWaiter($oid, $order_id, $waiter_id)){
            $data['code'] = 200;
            $data['msg'] = '分配成功';
        }else{
            $data['code'] = 400;
            $data['msg'] = '分配失败';
        }
        
        return $data;
    }



}
