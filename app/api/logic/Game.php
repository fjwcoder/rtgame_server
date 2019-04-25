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
    public function getGameServer(){
        $data = [
            'game'=>$this->getGameList(['status'=>1], 'id, cname'),
            'plantform'=>$this->getGamePlantform(['status'=>1], 'id, gid, name'),
            'area'=>$this->getGameArea(['status'=>1], 'id, gid, pid, name')
        ];

        return $data;
    }

    public function getGameList($where, $field){
        
        $list = $this->modelGameList->getList($where, $field, 'id', false);
// dump($list); die;
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



    
}
