<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | fjwcoder<fjwcoder@gmail.com>                           |
// +---------------------------------------------------------------------+
// | Repository |                   |
// +---------------------------------------------------------------------+

namespace app\api\logic;

use think\Db;
use app\api\error\Common as CommonError;

/**
 * 附加功能
 */
class Attach extends ApiBase
{

    /**
     * create by fjw in 19.3.13
     * 关于我们
     */
    public function aboutUs(){

        // 需要优化成缓存
        $abo_text = Db::name('about') -> where(['abo_id'=>1]) -> column('abo_text');

        return $abo_text;

    }

    /**
     * create by fjw in 19.3.13
     * 意见反馈
     */
    public function ideaFeedback($data = []){
        $f_text = htmlspecialchars($data['f_text']);
        if(empty($f_text)){

            return CommonError::$feedbackContentEmpty; // 反馈内容不可为空

        }

        $decoded_user_token = $data['decoded_user_token'];

        $f_info = [
            'f_text' => $f_text,
            'f_time' => date('Y-m-d H:i:s'),
            'u_id' => $decoded_user_token->user_id
        ];

        if(!Db::name('feedback') -> insert($f_info)){

            return CommonError::$feedbackError; //  反馈失败

        }

    }
}
