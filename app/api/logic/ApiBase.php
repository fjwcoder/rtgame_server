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

namespace app\api\logic;

use app\common\logic\LogicBase;
use app\api\error\CodeBase;

/**
 * Api基础逻辑
 */
class ApiBase extends LogicBase
{

    /**
     * API返回数据
     */
    public function apiReturn($code_data = [], $return_data = [], $return_type = 'json')
    {
        
        if (is_array($code_data) && array_key_exists(API_CODE_NAME, $code_data)) {
            
            !empty($return_data) && $code_data['data'] = $return_data;

            $result = $code_data;
            
        } else {
            
            $result = CodeBase::$success;
            
            $result['data'] = $code_data;
        }
        
        $return_result = $this->checkDataSign($result);
        
        $return_result['exe_time'] = debug('api_begin', 'api_end');
        
        return $return_type == 'json' ? json($return_result) : $return_result;
    }

    /**
     * 检查是否需要响应数据签名
     */
    public function checkDataSign($data)
    {
        
        $info = $this->modelApi->getInfo(['api_url' => URL]);
        
        $info['is_response_sign'] && !empty($data['data']) && $data['data']['data_sign'] = create_sign_filter($data['data']);
        
        return $data;
    }
    
    /**
     * API错误终止程序
     */
    public function apiError($code_data = [])
    {
        
        return throw_response_exception($code_data);
    }

    /**
     * API提交附加参数检查
     */
    public function checkParam($param = [])
    {
        $info = $this->modelApi->getInfo(['api_url' => URL]);

        empty($info) && $this->apiError(CodeBase::$apiUrlError);
        
        // 把access_token 放到header里
        // (empty($param['access_token']) || $param['access_token'] != get_access_token()) && $this->apiError(CodeBase::$accessTokenError);
        
        /**
         * EDIT BY FJW IN 19.3.12
         * 同时验证header和body里的access_token
         * 
         * 
         */

        $access_token = isset($_SERVER['HTTP_ACCESS_TOKEN'])?$_SERVER['HTTP_ACCESS_TOKEN']:'';
        if(empty($access_token)){
            $access_token = isset($param['access_token'])?$param['access_token']:'';
        }

        (empty($access_token) || $access_token != get_access_token()) && $this->apiError(CodeBase::$accessTokenError);
        
        if ($info['is_user_token'] && empty($param['user_token'])) {
            
            $this->apiError(CodeBase::$userTokenNull);
            
        } elseif ($info['is_user_token']) {
        
            $decoded_user_token = decoded_user_token($param['user_token']);
            
            is_string($decoded_user_token) && $this->apiError(CodeBase::$userTokenError);

            $param['decoded_user_token'] = $decoded_user_token; // add by fjw in 19.3.12
        }

        $info['is_request_sign']    && (empty($param['data_sign'])      || create_sign_filter($param) != $param['data_sign']) && $this->apiError(CodeBase::$dataSignError);
        
        return isset($decoded_user_token['data'])?$decoded_user_token['data']:[];
    }
}
