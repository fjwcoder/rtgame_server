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

namespace app\admin\logic;

/**
 * 
 */
class User extends AdminBase
{

    public function getUserList($where, $field = true, $order = 'id', $paginate=false){

        
        $this->modelUser->alias('a');//设置当前数据表的别名

        $this->modelUser->join = [

            [SYS_DB_PREFIX."wx_user w", "a.id=w.user_id", "left"],
            
        ];
        $field = '
                a.id, a.status, a.money,

                w.user_id, w.mobile, w.app_openid as openid, w.nickname, w.sex, w.headimgurl, 
                w.city, w.province, w.country
            ';

        return $this->modelUser->getList($where, $field, $order, $paginate);
    }

    public function changeStatus($user_id, $status){
        $where = ['id'=>$user_id, 'status'=>$status];
        $data['status'] = ($status == 2)?1:$status;
        return $this->modelUser->updateInfo($where, $data);
    }


}
