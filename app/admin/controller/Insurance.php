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

namespace app\admin\controller;

/**
 * 保险后台控制器
 */
class Insurance extends AdminBase
{
    
    /**
     * 列表
     */
    public function insuranceList()
    {

        $where = $this->logicInsurance->getWhere($this->param);
        
        $this->assign('list', $this->logicInsurance->getInsuranceList($where, 'a.*,c.name as category_name', 'a.insurance_id desc'));
        
        return $this->fetch('insurance_list');
    }
    
    /**
     * 保险添加
     */
    public function insuranceAdd()
    {
        
        
        $this->insuranceCommon();
        
        return $this->fetch('insurance_edit');
    }
    
    /**
     * 保险编辑
     */
    public function insuranceEdit()
    {
        
        $this->insuranceCommon();

        $info = $this->logicInsurance->getInsuranceInfo(['a.insurance_id' => $this->param['insurance_id']], 'a.*,c.name as category_name');
        
        // !empty($info) && $info['img_ids_array'] = str2arr($info['img_ids']);
        
        $this->assign('info', $info);
        
        return $this->fetch('insurance_edit');
    }
    
    /**
     * 保险添加与编辑通用方法
     */
    public function insuranceCommon()
    {
        
        IS_POST && $this->jump($this->logicInsurance->insuranceEdit($this->param));
        
        $this->assign('insurance_category_list', $this->logicInsurance->getInsuranceCategoryList([], 'id,name', '', false));
    }
    
    /**
     * 保险分类添加
     */
    public function insuranceCategoryAdd()
    {
        
        IS_POST && $this->jump($this->logicInsurance->insuranceCategoryEdit($this->param));
        
        return $this->fetch('insurance_category_edit');
    }
    
    /**
     * 保险分类编辑
     */
    public function insuranceCategoryEdit()
    {
        
        IS_POST && $this->jump($this->logicInsurance->insuranceCategoryEdit($this->param));
        
        $info = $this->logicInsurance->getInsuranceCategoryInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('insurance_category_edit');
    }
    
    /**
     * 保险分类列表
     */
    public function insuranceCategoryList()
    {
        
        $this->assign('list', $this->logicInsurance->getInsuranceCategoryList());
       
        return $this->fetch('insurance_category_list');
    }
    
    /**
     * 保险分类删除
     */
    public function insuranceCategoryDel($id = 0)
    {
        
        $this->jump($this->logicInsurance->insuranceCategoryDel(['id' => $id]));
    }
    
    /**
     * 数据状态设置
     */
    public function setStatus()
    {
        
        $this->jump($this->logicAdminBase->setStatus('Insurance', $this->param));
    }
}
