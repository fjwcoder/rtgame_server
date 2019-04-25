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

namespace app\common\model;


/**
 * 
 */
class User extends ModelBase
{
    /**
     * 
     */
    public function getUserInfo($where, $field = ''){
        $this->modelWxUser->alias('w');
        $this->modelWxUser->join = [
            [SYS_DB_PREFIX . 'user u', 'u.id = w.user_id'],
        ];
        $select_field = '
            w.wx_id, w.user_id, w.mobile, w.app_openid, w.nickname, w.sex, w.headimgurl,
            w.app_subscribe_time, w.unionid, 
            w.wx_openid, w.city, w.province, w.country, w.wx_subscribe_time,
            w.wx_subscribe_scene, w.wx_qr_scene, w.wx_qr_scene_str,

            u.username, u.password, u.money, u.status
        ';

        $field = empty($field)?$select_field:$field;
        return $this->modelWxUser->getInfo($where, $field);
    }



    /**
     * 密码修改器
     */
    public function setPasswordAttr($value)
    {
        
        return data_md5_key($value);
    }

}
