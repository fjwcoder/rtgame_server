<?php
// +---------------------------------------------------------------------+
// | FJWCODER   | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | fjwcoder fjwcoder@gmail.com                    |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\common\service;

/**
 * 微信公众号
 */
class Wechat extends ServiceBase implements BaseInterface
{
    // const NOTIFY_URL    = 'http://yq.adv.com/index.php/payment/wxPayNotify';
    // const CALLBACK_URL  = 'http://xxx/payment/notify';
    
    /**
     * 服务基本信息
     */
    public function serviceInfo()
    {
        
        return [
            'service_name' => '微信服务', 
            'service_class' => 'Wechat', 
            'service_describe' => '微信公众号', 
            'author' => 'fjwcoder', 
            'version' => '1.0'
        ];

        
    }
    
}
