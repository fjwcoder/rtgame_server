<?php
/*
 * @Descripttion: 机器人数据管理
 * @Author: FengQiMan
 * @Date: 2019-07-15 15:09:33
 */
namespace app\admin\controller;

class Robot extends AdminBase
{
    
    // 机器人数据列表 FengQiMan 2019-07-15
    public function robotList()
    {
        // 获取代练人员
        $waiter = $this->logicWaiter->getWaiterList(['status'=>1]);
        // dump($waiter); die;
        $this->assign('waiter', $waiter);
        $this->assign('waiter_json', json_encode($waiter, JSON_UNESCAPED_UNICODE));
        return $this->fetch('robot_list');
    }

    // 获取机器人数据  FengQiMan 2019-07-15
    public function getRobotData()
    {
        $step = input('step', 0, 'intval');
        $where = [];
        if($step !== 0){
            $where['step'] = $step;
        }
        return $this->logicRobot->getRobotData($where, true, 'id desc', 15);
    }

    // 生成机器人数据 FengQiMan 2019-07-15
    public function addRobotData()
    {
        IS_POST && $res = $this->logicRobot->addRobotData($this->param);
        return $res;
    }




}

