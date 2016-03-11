<?php
class AdminPayOperationType extends AdminPanel
{
  protected $_tableName = "pay_type_operations";

  public function Init($Param)
  {
    $this->Tag('title', T('XMLfspay_operation_type'));
    parent::Init($Param);
  }

  public function Finnaly()
  {
    if ($this->Html() == '') {
        $this->Html($this->CreateView(array(), $this->_Template($_REQUEST['method'], 'AdminFsPay/AdminPayOperationType')));
    }
  }

  public function actionAdd($Param)
  {
  }

  public function actionIndex($Param)
  {
    $this->Tag('operations', $this->_table->GetAll(true, false, array('name')));
  }
  
  public function actionEdit($Param)
  {
    $this->_table->current = $Param->key;
    if ($Param->key != $this->_table->id) {
        $this->_Referer();
        return;
    }
    $this->Tag('operation', $this->_table->result);
  }


}