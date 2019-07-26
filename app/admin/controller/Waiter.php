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
 * 
 */
class Waiter extends AdminBase
{
    
    /**
     * 获取代练人员名单
     */
    public function index()
    {


        
        return $this->fetch('index');
    }

    public function list()
    {



        
        return $this->fetch('list');
    }

    public function getWaiterList(){
        $status = input('status', 0, 'intval');
        $where = [];
        if($status !== 0){
            $where['status'] = $status;
        }

        $list = $this->logicWaiter->getWaiterList($where, true, 'id desc', 15);  //JSON_UNESCAPED_UNICODE
        
        return $list;
    }

    public function passWaiter(){
        $waiter_id = input('waiter_id', 0, 'intval');
        $openid = input('openid');
        return $this->logicWaiter->passWaiter($waiter_id, $openid);
        
    }

    public function rejectWaiter(){
        $waiter_id = input('waiter_id', 0, 'intval');
        $openid = input('openid');
        $reasion = input('reasion');
        return $this->logicWaiter->rejectWaiter($waiter_id, $openid, $reasion);
    }

    /**
     * @Author: FengQiMan
     * @Descripttion: 查看代练信息
     * @Date: 2019-07-22 11:18:16
     */
    public function getWaiterInfo(){
        // dd($this->logicWaiter->getWaiterInfo($this->param));
        $this->assign('data',$this->logicWaiter->getWaiterInfo($this->param));
        return $this->fetch('waiter_info');
    }







}
