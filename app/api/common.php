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

use \Firebase\JWT\JWT;
use think\cache\driver\Redis; // 引入redis


/**
 * JWT验签方法
 */
function tokenSign($member)
{
    
    $key = API_KEY . JWT_KEY;
    
    $jwt_data = $member;//['user_id' => $member['user_id'], 'mobile' => $member['mobile'], 'create_time' => $member['create_time']];

    $token = [
        "iss"   => "MAMI JWT",         // 签发者
        "iat"   => TIME_NOW,              // 签发时间
        "exp"   => TIME_NOW + TIME_NOW,   // 过期时间
        "aud"   => 'MAMI',             // 接收方
        "sub"   => 'MAMI',             // 面向的用户
        "data"  => $jwt_data
    ];
    
    $jwt = JWT::encode($token, $key);
    
    $jwt_data['user_token'] = $jwt;
    
    return $jwt_data;
}


// 解密user_token
function decoded_user_token($token = '')
{
    
    try {
        
        $decoded = JWT::decode($token, API_KEY . JWT_KEY, array('HS256'));

        return (array) $decoded;
        
    } catch (Exception $ex) {
        
        return $ex->getMessage();
    }
}

// 获取解密信息中的data
function get_member_by_token($token = '')
{
    
    $result = decoded_user_token($token);

    return $result['data'];
}

// 数据验签时数据字段过滤
function sign_field_filter($data = [])
{
    
    $data_sign_filter_field_array = config('data_sign_filter_field');
    
    foreach ($data_sign_filter_field_array as $v)
    {
        
        if (array_key_exists($v, $data)) {
            
            unset($data[$v]);
        }
    }
    
    return $data;
}

// 过滤后的数据生成数据签名
function create_sign_filter($data = [], $key = '')
{
    
    $filter_data = sign_field_filter($data);
    
    return empty($key) ? data_md5_key($filter_data, API_KEY) : data_md5_key($filter_data, $key);
}



/**
 * create by fjw in 19.3.12
 * app返回user_token时 （注册和登录时）
 * 格式化用户的基本信息 
 */
function formatReturnUserInfo($user){
    return [
        'user_id'=>isset($user['id'])?$user['id']:0, 
        'mobile'=>isset($user['mobile'])?$user['mobile']:'',
        'datum'=>isset($user['datum'])?$user['datum']:0,
        'rongyun_token'=>isset($user['rongyun_token'])?$user['rongyun_token']:'',
        'unionid'=>'',
        'create_time'=>isset($user['create_time'])?$user['create_time']:time(),
    ];

}

/**
 * create by fjw in 19.3.18
 * 微信小程序返回用户数据
 */
function wxappReturnUserInfo($user){
    return [
        'user_id'=>isset($user['user_id'])?$user['user_id']:0,
        'mobile'=>isset($user['mobile'])?$user['mobile']:'',
        'app_openid'=>$user['app_openid'],
        'unionid'=>'',
        'create_time'=>isset($user['app_subscribe_time'])?$user['app_subscribe_time']:time()
    ];
}






/**
 * create by fjw in 19.3.14
 * 设置缓存
 */
function apiRedisSet($key, $value, $expire = 7200){

    $redis = new Redis();

    return $redis->set($key, $value, $expire);
}

 /**
 * create by fjw in 19.3.14
 * 读取缓存
 */
function apiRedisGet($key, $default = false){

    $redis = new Redis();

    return $redis->get($key, $default);
}

 /**
 * create by fjw in 19.3.14
 * 删除缓存
 */
function apiRedisRemove($key){

    $redis = new Redis();

    return $redis->rm($key);
}