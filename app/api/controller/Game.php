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

namespace app\api\controller;

use app\common\controller\ControllerBase;

/**
 * PROJECT_代练平台 打手信息
 * 
 * 
 */
class Game extends ApiBase
{

    /**
     * 获取游戏平台区服列表
     */
    public function getGameServer(){
        $gid = isset($this->param['gid'])?$this->param['gid']:0;
        return $this->apiReturn($this->logicGame->getGameServer($gid));
    }

    /**
     * 获取游戏服务列表
     */
    public function getGServerList(){

        $gid = isset($this->param['gid'])?$this->param['gid']:0;

        return $this->apiReturn($this->logicGame->getGServerList($gid));
    }

    /**
     * 获取游戏列表
     */
    public function getGameList(){
        $gid = isset($this->param['gid'])?$this->param['gid']:0;
        $where_game = ['status'=>1];
        if(isset($this->param['gid']) && $this->param['gid'] != 0 && $this->param['gid'] != ''){
            $where_game['id'] = $gid;
        }

        return $this->apiReturn($this->logicGame->getGameList($where_game, true));
    }


}
