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

namespace addon;

/**
 * 插件接口
 */
interface AddonInterface
{
    
    /**
     * 插件安装
     */
    public function addonInstall();
    
    /**
     * 插件卸载
     */
    public function addonUninstall();
    
    /**
     * 插件信息
     */
    public function addonInfo();
    
    /**
     * 插件配置信息
     */
    public function addonConfig($param);
}
