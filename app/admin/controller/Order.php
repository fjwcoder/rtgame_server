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
        // dump($waiter); die;
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

        $list = $this->logicOrder->getOrderList($where, true, 'id desc', 15);  //JSON_UNESCAPED_UNICODE
        
        return $list;
    }

    // 分配代练人员
    public function assignWaiter(){
        $oid = input('oid', 0, 'intval');
        $order_id = input('order_id', '');
        $waiter_id = input('waiter_id', 0, 'intval');
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

    /**
     * @Author: FengQiMan
     * @Descripttion: 查看订单详情
     * @Date: 2019-07-22 10:19:13
     */
    public function getOrderDetail(){
        // d($this->param);
        // dd($this->logicOrder->getOrderDetail($this->param));
        $this->assign('data',$this->logicOrder->getOrderDetail($this->param));
        return $this->fetch('order_detail');
    }



}
