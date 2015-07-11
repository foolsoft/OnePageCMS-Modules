<?php
class AdminMLinkCounter extends AdminPanel
{
  protected $_tableName = 'link_counter'; 

  public function UnInstall()
  {
      $this->_table->Drop()->Execute(); 
      fsFunctions:: DeleteDirectory(PATH_TPL.CMSSettings::GetInstance('template_admin').'/AdminMLinkCounter');
      fsFunctions::DeleteFile(PATH_ROOT.'models/link_counter.php');
      fsFunctions::DeleteFile(PATH_LANG.'xml/ru_MLinkCounter.xml');
      fsFunctions::DeleteFile(PATH_LANG.'xml/en_MLinkCounter.xml');
      fsFunctions::DeleteFile(PATH_LANG.'en-to-ru/MLinkCounter.php');
      fsFunctions::DeleteFile(PATH_LANG.'ru-to-en/MLinkCounter.php');
  }
  
  public function actionEdit($param)
  {
    $this->_table->current = $param->key;
    if ($param->key != $this->_table->id) {
      $this->_Referer();
      return;
    }
    $this->Tag('counter', $this->_table->result);
  }
  
  public function actionAdd($param)
  {
  }

  public function actionIndex($param)
  {
      $this->Tag('counters', $this->_table->GetAll());
  }
}