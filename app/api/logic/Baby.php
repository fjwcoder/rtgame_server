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

use app\api\error\Common as CommonError;

/**
 * baby 模块
 * 
 * 预定义保单信息列表
 * 预定义保单信息添加
 * 预定义保单信息详情
 * 预定义保单信息修改
 * 
 * 
 * 
 * 添加宝宝信息
 * 更新宝宝信息
 * 修改宝宝信息
 * 获取宝宝详情
 */
class Baby extends ApiBase
{
    /**
     * create by fjw in 19.3.26
     * 预定义保单信息列表
     */
    public function getDefInsuranceInfoList($param = []){

        $decoded_user_token = $param['decoded_user_token'];

        $where['user_id'] = $decoded_user_token->user_id;

        if(isset($param['baby_id']) && $param['baby_id'] > 0){
            $where['baby_id'] = $param['baby_id'];
        }

        return $this->modelDefineInsuranceInfo->getList($where, true, 'id', false);
    }

    /**
     * create by fjw in 19.3.26
     * 预定义保单信息添加
     */
    public function addDefInsuranceInfo($param = []){

        $decoded_user_token = $param['decoded_user_token'];

        $data = [
            'user_id'=>$decoded_user_token->user_id,
            'user_name'=>$param['user_name'],
            'user_id_card'=>$param['user_id_card'],
            'user_id_card_begintime'=>$param['user_id_card_begintime'],
            'user_id_card_endtime'=>$param['user_id_card_endtime'],
            'user_sex'=>intval($param['user_sex']),
            'user_age'=>intval($param['user_age']),
            'user_country'=>$param['user_country'],
            'user_address'=>$param['user_address'],
            'user_mobile'=>$param['user_mobile'],
            'relationship_to_baby'=>$param['relationship_to_baby'],
            
            'baby_id'=>$param['baby_id'],
            'baby_name'=>$param['baby_name'],
            'baby_id_card'=>$param['baby_id_card'],
            'baby_id_card_begintime'=>$param['baby_id_card_begintime'],
            'baby_id_card_endtime'=>$param['baby_id_card_endtime'],
            'baby_sex'=>intval($param['baby_sex']),
            'baby_age'=>intval($param['baby_age']),
            'baby_country'=>$param['baby_country'],
            'baby_address'=>$param['baby_address'],
            'relationship_to_user'=>$param['relationship_to_user'],
        ];
        // dump($data); die;
        return $this->modelDefineInsuranceInfo->setInfo($data);

    }

    /**
     * create by fjw in 19.3.26
     * 预定义保单信息详情
     */
    public function getDefInsuranceInfo($param = []){

        $decoded_user_token = $param['decoded_user_token'];
        $where['id'] = $param['def_id'];
        $where['user_id'] = $decoded_user_token->user_id;
        if(isset($param['baby_id'])){
            $where['baby_id'] = $param['baby_id'];
        }
        

        return $this->modelDefineInsuranceInfo->getInfo($where, true);
    }

    /**
     * create by fjw in 19.3.26
     * 预定义保单信息修改
     */
    public function editDefInsuranceInfo($param = []){

        $decoded_user_token = $param['decoded_user_token'];

        $where = ['id'=>$param['def_id'], 'user_id'=>$decoded_user_token->user_id,];
        if(isset($param['baby_id'])){
            $where['baby_id'] = $param['baby_id'];
        }

        $data = [
            // 'user_id'=>$decoded_user_token->user_id,
            'user_name'=>$param['user_name'],
            'user_id_card'=>$param['user_id_card'],
            'user_id_card_begintime'=>$param['user_id_card_begintime'],
            'user_id_card_endtime'=>$param['user_id_card_endtime'],
            'user_sex'=>$param['user_sex'],
            'user_age'=>$param['user_age'],
            'user_country'=>$param['user_country'],
            'user_address'=>$param['user_address'],
            'user_mobile'=>$param['user_mobile'],
            'relationship_to_baby'=>$param['relationship_to_baby'],
            
            // 'baby_id'=>$param['baby_id'],
            'baby_name'=>$param['baby_name'],
            'baby_id_card'=>$param['baby_id_card'],
            'baby_id_card_begintime'=>$param['baby_id_card_begintime'],
            'baby_id_card_endtime'=>$param['baby_id_card_endtime'],
            'baby_sex'=>$param['baby_sex'],
            'baby_age'=>$param['baby_age'],
            'baby_country'=>$param['baby_country'],
            'baby_address'=>$param['baby_address'],
            'relationship_to_user'=>$param['relationship_to_user'],
        ];

        return $this->modelDefineInsuranceInfo->updateInfo($where, $data);

    }




/**  =================以下为宝宝信息========================    */

    /**
     * create by fjw in 19.3.18
     * 添加baby信息
     */
    public function addBabyInfo($param = []){

        $decoded_user_token = $param['decoded_user_token'];

        $data = [
            'user_id'=>$decoded_user_token->user_id, 
            'baby_name'=>$param['baby_name'], 
            'baby_sex'=>$param['baby_sex'], 
            'father_name'=>$param['father_name'], 
            'mother_name'=>$param['mother_name'], 
            'exigence_name'=>$param['exigence_name'], 
            'exigence_mobile'=>$param['exigence_mobile'], 
            'baby_birthday'=>$param['baby_birthday'], 
            'baby_jiezhong'=>$param['baby_jiezhong']
        ];

        return $this->modelBaby->setInfo($data);

        
    }

    /**
     * create by fjw in 19.3.18
     * 更新Baby信息
     * 
     */
    public function editBabyInfo($param = []){

        $decoded_user_token = $param['decoded_user_token'];
        $where = ['user_id'=>$decoded_user_token->user_id, 'baby_id'=>$param['baby_id'], 'status'=>1];
        $data = [
            // 'user_id'=>$decoded_user_token->user_id, 
            'baby_name'=>$param['baby_name'], 
            'baby_sex'=>$param['baby_sex'], 
            'father_name'=>$param['father_name'], 
            'mother_name'=>$param['mother_name'], 
            'exigence_name'=>$param['exigence_name'], 
            'exigence_mobile'=>$param['exigence_mobile'], 
            'baby_birthday'=>$param['baby_birthday'], 
            'baby_jiezhong'=>$param['baby_jiezhong']
        ];

        return $this->modelBaby->updateInfo($where, $data);


    }


    /**
     * create by fjw in 19.3.18
     * 获取baby列表
     */
    public function getBabyList($data = []){

        $decoded_user_token = $data['decoded_user_token'];

        $where = ['user_id'=>$decoded_user_token->user_id, 'status'=>1];

        return $this->modelBaby->getList($where, true, 'baby_id', false);
    }

    /**
     * create by fjw in 19.3.18
     * 获取baby详情
     */
    public function getBabyInfo($data = []){

        $decoded_user_token = $data['decoded_user_token'];

        $where = ['user_id'=>$decoded_user_token->user_id, 'baby_id'=>$data['baby_id'], 'status'=>1];

        return $this->modelBaby->getInfo($where);
    }


    
}
