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

namespace app\common\service;

/**
 * 支付服务
 */
class Pay extends ServiceBase implements BaseInterface
{
    
    // const NOTIFY_URL    = 'http://mamiv2.server/api.php/Paynotify/wxappInsurancePayNotify';
    const NOTIFY_URL    = 'https://wxapp.dxyxshop.com/api.php/Paynotify/wxappInsurancePayNotify';
    const CALLBACK_URL  = 'http://xxx/payment/notify';
    
    /**
     * 服务基本信息
     */
    public function serviceInfo()
    {
        
        return ['service_name' => '支付服务', 'service_class' => 'Pay', 'service_describe' => '系统支付服务，用于整合多个支付平台', 'author' => 'fjwcoder', 'version' => '1.0'];
    }
    
}
