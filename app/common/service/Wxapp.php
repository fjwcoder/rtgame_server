<?php
// +---------------------------------------------------------------------+
// | FJWCODER   | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     |  fjwcoder@gmail.com                    |
// +---------------------------------------------------------------------+
// | Repository |                      |
// +---------------------------------------------------------------------+

namespace app\common\service;

/**
 * 微信公众号
 */
class Wxapp extends ServiceBase implements BaseInterface
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
            'service_class' => 'Wxapp', 
            'service_describe' => '微信小程序', 
            'author' => 'fjwcoder', 
            'version' => '1.0'
        ];

        
    }
    
}
