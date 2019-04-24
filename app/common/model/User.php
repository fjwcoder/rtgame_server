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
    
    // public function userRegist($param){
    //     $data = [
    //         'app_openid' => $param['openid'],
    //         'nickname' => $param['nickName'],
    //         'sex' => $param['gender'],
    //         'headimgurl' => $param['avatarUrl'],
    //         'country' => $param['country'],
    //         'province' => $param['province'],
    //         'city' => $param['city'],
    //         'app_subscribe_time' => time(),
    //         'unionid' =>$param['unionid']
    //     ];

    //     return $this->modelWxUser->setInfo($data);
    // }


    /**
     * wxapp关注后，绑定手机号
     */
    public function wxappBindMobile($param = []){


        
    }

    /**
     * 获取用户信息
     * 包括  user表  和  wx_user表
     */
    public function getUserDetail(){

    }

    /**
     * 完善用户信息
     */
    public function editUserDetail($param = []){

    }


    /**
     * 密码修改器
     */
    public function setPasswordAttr($value)
    {
        
        return data_md5_key($value);
    }

}
