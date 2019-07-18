<?php
/*
 * @Descripttion: 游戏管理逻辑
 * @Author: FengQiMan
 * @Date: 2019-07-15 09:44:42
 */
namespace app\admin\logic;

class Games extends AdminBase
{
    
    // 获取所有游戏 FengQiMan 2019-07-15
    public function getGameList($where, $field = '', $order = 'id', $paginate=false)
    {
        return $this->modelGameList->getList($where, $field, $order, $paginate);
    }

    // 添加or修改游戏信息逻辑 FengQiMan 2019-07-15
    public function addGames($data = [])
    {
        $validate_result = $this->validateGames->scene('add')->check($data);
        
        if(!$validate_result){
            return [RESULT_ERROR, $this->validateGames->getError()];
        }
        
        $url = url('gameList');
        
        $result = $this->modelGameList->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, 'Games' . $handle_text . '，name：' . $data['cname']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelGameList->getError()];
    }

    // 查询单条游戏信息 FengQiMan 2019-07-15
    public function getGamesInfo($id = 0)
    {
        $where = ['id' => $id];
        return $this->modelGameList->getInfo($where);
    }




}
