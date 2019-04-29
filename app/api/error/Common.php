<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     |     create by fjw in 19.3.12                   |
// +---------------------------------------------------------------------+
// | Repository |                      |
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

class Common
{

    public static $returnFalse              = [API_CODE_NAME => 400, API_MSG_NAME => '失败']; 

    public static $paramEmpty               = [API_CODE_NAME => 40001, API_MSG_NAME => '参数不可为空'];
    public static $paramError               = [API_CODE_NAME => 40002, API_MSG_NAME => '参数错误'];
    public static $paramFormat              = [API_CODE_NAME => 40003, API_MSG_NAME => '参数格式错误'];
    public static $paramTypeError           = [API_CODE_NAME => 40004, API_MSG_NAME => '类型参数不正确']; 

    // 用户注册
    public static $usernameOrPasswordEmpty  = [API_CODE_NAME => 40010, API_MSG_NAME => '账号或密码不能为空'];
    public static $passwordError            = [API_CODE_NAME => 40011, API_MSG_NAME => '密码错误'];
    public static $mobileFormatError        = [API_CODE_NAME => 40012, API_MSG_NAME => '手机号格式不正确'];
    public static $smsCodeSendError         = [API_CODE_NAME => 40013, API_MSG_NAME => '验证码发送失败']; 
    public static $smsCodeError             = [API_CODE_NAME => 40014, API_MSG_NAME => '验证码错误'];  
    public static $loginFail                = [API_CODE_NAME => 40015, API_MSG_NAME => '登录失败'];  
    public static $bindMobileFail           = [API_CODE_NAME => 40016, API_MSG_NAME => '绑定手机号失败']; 
    public static $editUserDetailFail       = [API_CODE_NAME => 40017, API_MSG_NAME => '信息修改失败']; 
    public static $userNoAccess             = [API_CODE_NAME => 40018, API_MSG_NAME => '用户无此权限'];

    public static $existThisUser              = [API_CODE_NAME => 40020, API_MSG_NAME => '用户已存在'];            
    public static $emptyThisUser            = [API_CODE_NAME => 40021, API_MSG_NAME => '用户不存在'];  
    public static $registerFail             = [API_CODE_NAME => 40030, API_MSG_NAME => '注册失败'];

    // 修改密码
    public static $oldOrNewPassword         = [API_CODE_NAME => 40101, API_MSG_NAME => '旧密码或新密码不能为空'];
    public static $changePasswordFail       = [API_CODE_NAME => 40102, API_MSG_NAME => '密码修改失败'];

    // 附加功能 402xx
    public static $feedbackContentEmpty         = [API_CODE_NAME => 40201, API_MSG_NAME => '反馈内容不可为空'];
    public static $feedbackError                = [API_CODE_NAME => 40202, API_MSG_NAME => '反馈失败'];

    public static $getError                = [API_CODE_NAME => 40202, API_MSG_NAME => '获取失败'];
    
    
    
    
    
    
          
      
    
     
           
    
}
