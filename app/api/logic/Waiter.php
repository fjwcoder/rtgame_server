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

/**
 * waiter 模块
 * 
 */
class Waiter extends ApiBase
{


    /**
     * 申请成为代练人员
     */
    public function applyWaiter($param = []){

        $decoded_user_token = $param['decoded_user_token'];
        // dump($decoded_user_token);
        $user_id = $decoded_user_token->user_id;

        // 1. 查询是否已经是代练人员
        $waiter = $this->modelWaiter->getInfo(['user_id'=>$user_id]);
        if($waiter){
            return CommonError::$existThisUser;
        }

        $user = $this->modelWxUser->getInfo(['user_id'=>$user_id]);
        // dump($user);

        $waiter = [
            
            'user_id' => $user['user_id'],
            'openid' => $user['app_openid'],
            'nickname' => $user['nickname'],
            'realname' => $param['realname'],
            'age' => intval($param['age']),
            'sex' => intval($user['sex']),
            'mobile' => $user['mobile'],
            'id_card' => $param['id_card'],
            'headimgurl' =>$user['headimgurl'],
            'game_id_list' =>$param['game_id_list'],
            'status' => 3
            
        ];

    
        return ($this->modelWaiter->setInfo($waiter)) ? true: false;





        

    }
    
 /**
     * 获取接到的代练单
     */
    public function waiterAssignOrder($param = []){
dump($param);
$paginate = 15;
        $decoded_user_token = $param['decoded_user_token'];
        // dump($decoded_user_token);
        $user_id = $decoded_user_token->user_id; 
        $waiter = $this->modelWaiter->getInfo(['user_id'=>$user_id]);
        $waiter_id = $waiter['id'];
        $where = [];
       
        // $where['step'] = $param['step'];
        // $where['waiter_id'] = $waiter['id'];
        $where['step'] = 1;
        $where['waiter_id'] = 0;
     
        $this->modelOrder->alias('a');//设置当前数据表的别名
  
         $field = 'a.id,a.order_id ,v.cname as game_name, j.name as plantform_name, 
            a.area_name,a.game_info ,a.special_info, 
            a.pay_money,a.user_mobile,a.game_account
            ,a.step,a.status, a.create_time
        ';
         // 
        $this->modelOrder->join = [
            [SYS_DB_PREFIX."game_list v", "a.game_id = v.id", "left"],
            [SYS_DB_PREFIX."game_plantform j", " a.plantform_id = j.id", "left"],
            // [SYS_DB_PREFIX."waiter k", " a.waiter_id = k.id", "left"],
            
        ];

        return $this->modelOrder->getList($where, $field, 'a.create_time desc', $paginate);
        // return $order;
     
    }
    
}
