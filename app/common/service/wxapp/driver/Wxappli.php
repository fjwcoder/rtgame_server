<?php
// +---------------------------------------------------------------------+
// | FJWCODER   | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | fjwcoder <fjwcoder@gmail.com>                          |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\common\service\wxapp\driver;

use app\common\service\wxapp\Driver;
use app\common\service\Wxapp;

/**
 * 微信公众号服务驱动
 */
class Wxappli extends Wxapp implements Driver
{
    
    /**
     * 驱动基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '微信小程序驱动', 'driver_class' => 'Wxappli', 'driver_describe' => '微信小程序驱动', 'author' => 'fjwcoder', 'version' => '1.0'];
    }
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return [ 'appid' => '小程序APPID', 'appsecret' => '小程序APPSECRET'];
    }

    /**
     * 微信小程序
     */
    public function wxapp($param){
        
    }
    
    /**
     * 获取配置信息
     */
    public function config()
    {
        
        return $this->driverConfig('Wxappli');
    }
    
    /**
     * create by fjw in 19.3.18
     * 获取session_key
     */
    public function sessionKey($code){

        /**
         * code 换取 session_key
         * ​这是一个 HTTPS 接口，开发者服务器使用登录凭证 code 获取 session_key 和 openid。
         * 其中 session_key 是对用户数据进行加密签名的密钥。为了自身应用安全，session_key 不应该在网络上传输。
         */
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $config = $this->config();
        $result = json_decode(httpsPost($url, [
            'appid' => $config['appid'],
            'secret' => $config['appsecret'],
            'grant_type' => 'authorization_code',
            'js_code' => $code
        ]), true);

// dump($result); die;

        if (isset($result['errcode'])) {
            // $this->error = $result['errmsg'];
            return false;
        }
        return $result;

    }


   
}
