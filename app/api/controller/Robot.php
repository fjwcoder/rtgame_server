<?php
/*
 * @Descripttion: 机器人订单控制器
 * @Author: FengQiMan
 * @Date: 2019-07-22 08:54:08
 */

namespace app\api\controller;

use app\common\controller\ControllerBase;

class Robot extends ApiBase
{

    /**
     * @Author: FengQiMan
     * @Descripttion: 查看机器人订单详情
     * @param {type} 
     * @Date: 2019-07-22 08:55:01
     */
    public function getROrderDetail(){

        return $this->apiReturn($this->logicRobot->getROrderDetail($this->param));

    }


   
}