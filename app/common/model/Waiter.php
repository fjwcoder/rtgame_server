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

namespace app\common\model;


/**
 * 
 */
class Waiter extends ModelBase
{
   
    public function getWaiterInfo($user_id, $all=false){

        $waiter = $this->modelWaiter->getInfo(['user_id'=>$user_id]);
        if($all){
            return $waiter;
        }else{
            if(empty($waiter)){
                $res = ['waiter_id'=>0];
            }else{
                $res = ['waiter_id'=>$waiter['id'], 'status'=>$waiter['status']];
            }
            
            return $res;
        }
        
    }


   
}
