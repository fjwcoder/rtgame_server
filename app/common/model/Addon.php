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
 * 插件模型
 */
class Addon extends ModelBase
{
    
    /**
     * 获取插件模型层实例
     */
    public function __get($name)
    {
        
        return addon_ioc($this, $name, LAYER_MODEL_NAME);
    }
}
