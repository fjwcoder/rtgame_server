<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     |                     |
// +---------------------------------------------------------------------+
// | Repository |                    |
// +---------------------------------------------------------------------+

/**
 * api状态码说明
 * 
 * 1xx: Informational	用于协议握手阶段的临时应答
 * 2xx: Success	客户端的请求被成功地接收
 * 3xx: Redirection	客户端必须有一些附加的动作才能完成它们的请求
 * 4xx: Client Error	此类错误应该由客户端负责
 * 5xx: Server Error	服务器对此类错误负责
 * 
 */
namespace app\api\error;

class CodeBase
{


    public static $success              = [API_CODE_NAME => 200,         API_MSG_NAME => '操作成功'];
    
    // 接口访问状态码
    public static $accessTokenError     = [API_CODE_NAME => 10001,   API_MSG_NAME => '接口访问权限错误'];
    
    public static $userTokenNull        = [API_CODE_NAME => 10002,   API_MSG_NAME => '用户Toekn不能为空'];
    
    public static $apiUrlError          = [API_CODE_NAME => 10003,   API_MSG_NAME => '接口路径错误'];
    
    public static $dataSignError        = [API_CODE_NAME => 10004,   API_MSG_NAME => '数据签名错误'];
    
    public static $userTokenError       = [API_CODE_NAME => 10005,   API_MSG_NAME => '用户Toekn解析错误'];
    
}
