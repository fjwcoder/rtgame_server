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

namespace app\admin\logic;

/**
 * 
 */
class Waiter extends AdminBase
{

    public function getWaiterList($where, $field = '', $order = 'id'){

        return $this->modelWaiter->getList($where, true, $order, false);
    }


}
