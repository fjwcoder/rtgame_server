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
class Waiter extends AdminBase
{

    public function getWaiterList($where, $field = true, $order = 'id', $paginate = false){

        return $this->modelWaiter->getList($where, $field, $order, $paginate);
    }

    public function passWaiter($waiter_id, $openid){
        // 1. 查询是否存在已经通过的
        $check_where = [ 'openid'=>$openid, 'status'=>1];
        $check = $this->modelWaiter->getInfo($check_where);
        // dump($check); die;
        if($check){
            return ['code'=>400, 'msg'=>'该用户已经成为代练'];
        }else{
            $up_where = ['id'=>$waiter_id, 'openid'=>$openid, 'status'=>3];
            $up_data = ['status'=>1];
            $res = $this->modelWaiter->updateInfo($up_where, $up_data);
            return $res?['code'=>200, 'msg'=>'操作成功']:['code'=>400, 'msg'=>'操作失败'];
        }
    }

    public function rejectWaiter($waiter_id, $openid, $reasion){

        $up_where = ['id'=>$waiter_id, 'openid'=>$openid, 'status'=>3];
        $up_data = ['status'=>2, 'remark'=>$reasion];
        $res = $this->modelWaiter->updateInfo($up_where, $up_data);
        return $res?['code'=>200, 'msg'=>'操作成功']:['code'=>400, 'msg'=>'操作失败'];
        
    }

    /**
     * @Author: FengQiMan
     * @Descripttion: 查看代练信息
     * @Date: 2019-07-22 11:18:56
     */
    public function getWaiterInfo($param = []){
        $id = $param['id'];
        $openid = $param['openid'];
        $where = [
            'id'=>$id,
            // 'openid' => $openid,
        ];
        return $this->modelWaiter->getInfo($where);
    }




}
