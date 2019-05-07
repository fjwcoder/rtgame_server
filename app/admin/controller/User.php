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
class User extends AdminBase
{
    
    /**
     *  首页
     */
    public function index()
    {


        return $this->fetch('index');
    }



    /**
     *  前台用户列表
     */
    public function list()
    {   
        // $user = $this->logic
        // $waiter = $this->logicWaiter->getWaiterList(['status'=>1]);
        // $this->assign('waiter', $waiter);
        // $this->assign('waiter_json', json_encode($waiter, JSON_UNESCAPED_UNICODE));

        return $this->fetch('list');
    }

    // 
    public function getUserList(){
        $status = input('status', 0, 'intval');
        $where = [];
        if($status !== 0){
            $where['a.status'] = $status;
        }

        $list = $this->logicUser->getUserList($where, true, 'a.id desc', 15);  //JSON_UNESCAPED_UNICODE
        
        return $list;
    }

    // // 分配代练人员
    public function changeStatus(){
        $user_id = input('user_id', 0, 'intval');
        
        $status = input('status', 2, 'intval');
        $data = [];
        if($this->logicUser->changeStatus($user_id, $status)){
            $data['code'] = 200;
            $data['msg'] = '操作成功';
        }else{
            $data['code'] = 400;
            $data['msg'] = '操作失败';
        }
        
        return $data;
    }



}
