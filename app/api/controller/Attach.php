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

namespace app\api\controller;

use app\common\controller\ControllerBase;

/**
 * PROJECT_妈咪v2 附加功能
 * 1. 关于我们
 * 2. 意见反馈
 * 
 */
class Attach extends ApiBase
{


    // 关于我们
    public function aboutUs(){

        return $this->apiReturn($this->logicAttach->aboutUs());

    }

    // 意见反馈
    public function ideaFeedback(){

        return $this->apiReturn($this->logicAttach->ideaFeedback($this->param));

    }


}
