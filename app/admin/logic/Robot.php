<?php
/*
 * @Descripttion: 机器人数据逻辑
 * @Author: FengQiMan
 * @Date: 2019-07-15 15:14:27
 */
namespace app\admin\logic;
use think\Db;

class Robot extends AdminBase
{
    
    // 获取所有机器人数据 FengQiMan 2019-07-15
    public function getRobotData($where, $field = '', $order = 'id', $paginate=false)
    {
        $this->modelRobotOrder->alias('a');//设置当前数据表的别名

        $this->modelRobotOrder->join = [

            [SYS_DB_PREFIX."game_list v", "a.game_id = v.id", "left"],
            [SYS_DB_PREFIX."game_plantform j", " a.plantform_id = j.id", "left"],
            [SYS_DB_PREFIX."robot_order_detail d", "a.id=d.oid", "left"],
            
        ];       

        $field = 'a.id, a.order_id, v.cname as game_name, j.name as plantform_name, 
            a.area_name, a.pay_money, a.step, a.status, a.create_time, a.order_type,
            
            d.begin_info, d.end_info
            
            ';
        return $this->modelRobotOrder->getList($where, $field, $order, $paginate);
    }

    // 机器人添加数据  FengQiMan 2019-07-15 (未完成)
    public function addRobotData($data = [])
    {
        // dd($data);
        $url = url('robotList');
        if($data['num'] == '' || !ctype_digit($data['num'])){
            return ['code'=>400,'msg'=>'请输入正确的正整数'];
        }
        $num = (int)$data['num'];// 添加的数量
        $games_ids = Db::name('game_list')->where(['status'=>1])->field('id')->select();
        
        $games_ids = array_column($games_ids,'id','id');
        // dd($games_ids);
        // $games_ids = implode(',',$games_ids);
        // dd($games_ids);
        if($data['type'] == 1){
            // return ['code'=>200,'msg'=>'代练'];
            
            for ($i=0; $i < $num; $i++) { 
                $order_id = 'GP'.setOrderID(); // 游戏代练订单号
                $res[$i] = [
                    'order_id'      =>  $order_id,

                    // 虚假数据
                    'user_id'       =>  $i + time(),
                    'game_id'       =>  array_rand($games_ids),
                    'plantform_id'  =>  $i + time(),
                    'area_name'     =>  $i + time(),
                    'user_mobile'   =>  $i + time(),
                    'game_account'  =>  $i + time(),
                    'game_password' =>  $i + time(),
                    'game_user'     =>  $i + time(), // 游戏角色名称
                    'pay_money'     =>  $i + time(),
                    'game_info'     =>  $i + time(),
                    'special_info'  =>  $i + time(),

                    'step'          =>  1,
                    'waiter_id'     =>  0,
                    'create_time'   =>  time(),
                    'pay_time'      =>  '',
                    'finish_time'   =>  '',
                    'status'        =>  1,
                    'order_type'    =>  $data['type'],
                ];
            }
            Db::startTrans();
            try{
                $add = Db::name('robot_order')->insertAll($res);
                if($add){ Db::commit(); }
            }catch(\Exception $e){
                // dump($e); 
                Db::rollback();
            }
            return ['code'=>200,'msg'=>'创建成功'];
        }else{
            return ['code'=>200,'msg'=>'陪玩'];
            for ($i=0; $i < $data['num']; $i++) { 
                
            }

        }
    }

    




}
