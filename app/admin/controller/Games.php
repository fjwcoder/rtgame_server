<?php
/*
 * @Descripttion: 游戏管理
 * @Author: FengQiMan
 * @Date: 2019-07-15 09:44:42
 */

namespace app\admin\controller;

class Games extends AdminBase
{
    
    // 游戏管理列表 FengQiMan 2019-07-15
    public function gameList()
    {
        return $this->fetch('game_list');
    }


    // 获取游戏 FengQiMan 2019-07-15
    public function getGameList()
    {
        $where = [];
        $list = $this->logicGames->getGameList($where, true, 'id desc', 15);
        return $list;
    }

    // 新增游戏信息 FengQiMan 2019-07-15
    public function addGames()
    {
        // 接收数据
        IS_POST && $this->jump($this->logicGames->addGames($this->param));

        return $this->fetch('games_add');
    }

    // 修改游戏信息 FengQiMan 2019-07-15
    public function editGames($id = 0)
    {

        // 接收数据
        IS_POST && $this->jump($this->logicGames->addGames($this->param));

        $this->assign('game_info', $this->logicGames->getGamesInfo($id));
        return $this->fetch('games_edit');
    }



}

