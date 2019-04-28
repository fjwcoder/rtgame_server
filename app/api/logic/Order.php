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
use think\Db;
/**
 * waiter 模块
 * 
 */
class Order extends ApiBase
{ /**
     * 创建订单
     */
    public function creatOrders($param = []){
        $decoded_user_token = $param['decoded_user_token'];
        // dump($decoded_user_token);
        $user_id = $decoded_user_token->user_id;
        $server =  $param['dataArray'];
        $pay_money=0;
        foreach($server as $k=>$v){
           
           $pay_money +=  $v['server_price'];  
    
        }
        // dump($user);
        $order_id = setOrderID();
        $order = [
            
            'order_id'=>$order_id,
            'use_id' =>$user_id,
            'game_id' => $param['game_id'],
            'plantform_id'=>$param['plantform_id'],
            'area_name'=>$param['area_name'],
            'user_mobile'=>$param['user_mobile'],
            'game_account'=>$param['game_account'],
            'game_password'=>$param['game_password'],
            'game_user'=>$param['game_user'],
            'pay_money' => $pay_money,
            'game_info' =>  $param['game_info'],
            'special_info' =>  $param['special_info'],
            'step' =>  1,
            'waiter_id' =>  0,
            'create_time' => date('Y-m-d H:i:s', time()),
            'pay_time' =>  0,
            'finish_time' =>0,
            'status' =>  1,


            
        ];
       
        $server =  $param['dataArray'];
            foreach($server as $k=>$v){
                // echo $k."=>".$v."<br />"; 
                $order_detail[] = [
                    // 'oid'=>$oid,
                    'order_id'=>$order_id,
                    'user_id'=>$user_id,
    
                    'server_id'=>$v['server_id'],
                    'begin_info'=>$v['begin_info'],
                    'end_info'=>$v['begin_info'],
                    'server_price'=>$v['server_price'],
                    'server_img'=>'0',
                  

    
                ];
              
        
            }
   
            $server =  $param['dataArray'];
            foreach($server as $k=>$v){
                // echo $k."=>".$v."<br />"; 
                $order_detail[] = [
                    // 'oid'=>$oid,
                    'order_id'=>$order_id,
                    'user_id'=>$user_id,
    
                    'server_id'=>$v['server_id'],
                    'begin_info'=>$v['begin_info'],
                    'end_info'=>$v['begin_info'],
                    'server_price'=>$v['server_price'],
                    'server_img'=>'0',
                  

    
                ];
              
        
            }

        // 执行插入
        Db::startTrans();
        try{
           
            // order insert 
            // insert
            $this->modelOrder->setInfo($order);
          //  return ($this->modelOrder->setInfo($order)) ? true: false;
           // 获取 
            $oid =  $this->modelOrder->getLastInsID();
           // insert

            // 拼凑order_detail 的 oid
  
            foreach($order_detail as $k=>$v){
                // echo $k."=>".$v."<br />"; 
               
                $order_detail[$k]['oid'] = $oid;
        
            }
       
            

            Db::name('order_detail') -> insertAll($order_detail);

            Db::commit();
            // commit
        }catch(\Exception $e){
            // dump($e); 
            Db::rollback();
        }
        
        
      

       





        

    }
    
}