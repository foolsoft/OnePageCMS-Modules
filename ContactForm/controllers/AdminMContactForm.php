<?php
class AdminMContactForm extends AdminPanel
{
  protected $_tableName = 'contact_forms';
  
  public function UnInstall()
  {
    fsFunctions::DeleteFile(PATH_LANG.'ru-to-en/LangMContactForm.php');
    fsFunctions::DeleteFile(PATH_LANG.'en-to-ru/LangMContactForm.php');
    fsFunctions::DeleteFile(PATH_LANG.'xml/en_MContactForm.xml');
    fsFunctions::DeleteFile(PATH_LANG.'xml/ru_MContactForm.xml');
    fsFunctions::DeleteFile(PATH_JS.'jqForm.js');
    fsFunctions::DeleteFile(PATH_JS.'MContactForm.js');
    fsFunctions::DeleteFile(PATH_THEME_CSS.'MContactForm.css');
    fsFunctions::DeleteFile(PATH_ROOT.'models/'.$this->_tableName.'.php');
    fsFunctions::DeleteDirectory(PATH_TPL.'AdminMContactForm');
    fsFunctions::DeleteDirectory(PATH_TPL.CMSSettings::GetInstance('template_admin').'/AdminMContactForm');
    $this->_table->Drop()->Execute();
  }
  
  private function _CheckData(&$Param)
  {
    if ($Param->name == '' || $Param->title == '') {
      $this->_Referer();
      $this->Message(T('XMLcms_text_need_all_data'));
      return false;
    }
    $Param->name = fsFunctions::Chpu($Param->name);
    $Param->ajax = $Param->Exists('ajax') ? 1 : 0;
    $Param->mail_user = $Param->Exists('mail_user') ? 1 : 0;
    return true;
  }
  
  public function Init($request)
  {
    $this->Tag('title', T('XMLcontact_form'));
    parent::Init($request);
  }
  
  public function actionIndex($Param)
  {
    $this->Tag('forms', $this->_table->GetAll(true, false, array('title')));
  }
  
  public function actionConfig($Param)
  {
    $this->Tag('settings', $this->settings);
  }
  
  public function actionAdd($Param)
  {
  } 
  
  public function actionEdit($Param)
  {
    $this->_table->current = $Param->key;
    if ($Param->key != $this->_table->name) {
      $this->_Referer();
      return;
    }
    $this->Tag('form', $this->_table->result);
  }
  
  public function actionDoAdd($Param)
  {
    if (!$this->_CheckData($Param)) {
      return;
    }
    if (!$this->_CheckUnique($Param->name)) {
      return;
    }
    parent::actionDoAdd($Param);
  } 
  
  public function actionDoEdit($Param)
  {
    if (!$this->_CheckData($Param)) {
      return;
    }
    if (!$this->_CheckUnique($Param->name, 'name', $Param->key, 'name')) {
      return;
    }
    parent::actionDoEdit($Param);
  } 
}