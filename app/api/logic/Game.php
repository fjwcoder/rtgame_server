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
class Game extends ApiBase
{
    // 获取游戏列表
    public function getGameServer($gid = 0){
        $where = ['status'=>1];
        if($gid != 0){
            $where['gid'] = $gid;
        }
        $data = [
            'game'=>$this->getGameList($where, 'id, cname'),
            'plantform'=>$this->getGamePlantform($where, 'id, gid, name, area_num'),
            'area'=>$this->getGameArea($where, 'id, gid, pid, name')
        ];

        return $data;
    }

    public function getGameList($where, $field){
        
        $list = $this->modelGameList->getList($where, $field, 'id', false);

        return $list;
    }

    public function getGamePlantform($where, $field){

        $list = $this->modelGamePlantform->getList($where, $field, 'id', false);

        return $list;
    }

    public function getGameArea($where, $field){

        $list = $this->modelGameArea->getList($where, $field, 'id', false);

        return $list;
    }

    // 获取游戏服务列表
    public function getGServerList($gid = 0){
        $where = ['status'=>1];
        if($gid != 0){
            $where['gid'] = $gid;
        }
        $list = $this->modelGameServer->getList($where, true, 'server_sort', false);

        return $list;
    }


    
}
