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

namespace app\common\service\wechat\driver;

use app\common\service\wechat\Driver;
use app\common\service\Wechat;

/**
 * 微信公众号服务驱动
 */
class Wxgzh extends Wechat implements Driver
{
    
    /**
     * 驱动基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '微信公众号驱动', 'driver_class' => 'Wxgzh', 'driver_describe' => '微信公众号驱动', 'author' => 'fjwcoder', 'version' => '1.0'];
    }
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return ['wxid'=>'验证ID', 'appid' => '公众号APPID', 'appsecret' => '公众号APPSECRET', 
            'original_id' =>'公众号原始ID', 'token' => '验证token'];
    }

    /**
     * 获取配置信息
     */
    public function config()
    {
        
        return $this->driverConfig('Wxgzh');

    }
    
    /**
     * 微信公众号
     */
    public function wechat($param){
        $wechat_config = $this->config();
        
        if(!isset($param['wxid']) || $param['wxid'] !== $wechat_config['wxid'] ){
            return '验证错误';
        }
        
        $wechatObj = new wxgzh\Wechat($wechat_config);

        return $wechatObj->index();
        
        
    }
    
    
    

   
}
