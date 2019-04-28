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
    

    
}
