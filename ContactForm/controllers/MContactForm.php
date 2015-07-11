<?php
class MContactForm extends cmsController
{
  protected $_tableName = 'contact_forms';
  private $_fileAttr = 'file';
  
  public function Init($request) 
  {
    $name = '';
    if ($request->Exists('form')) {
        $form = $request->form;
        $name = $form['name'];
    } else {
        $name = $request->name;
    }
    $this->_table->current = $name;
    parent::Init($request);   
  }
  
  private function _SendMail($Param)
  {
    if ($this->_table->mail == '') {
      $this->Message(T('XMLcontact_form_not_active'));
      return false;
    }
    
    $ips = fsFunctions::Explode("\n", $this->settings->block_ip, '');
    $tags = array('ip' => fsFunctions::GetIp(), 'date' => date('Y-m-d'), 'time' => date('H:i:s'));
    
    if(in_array($tags['ip'], $ips)) {
      $this->Message(T('XMLcms_locked'));
      return false;  
    }
    
    if ($Param->Exists('required')) {
      foreach ($Param->required as $name => $value) {
        if ($value == '') {
          $this->Message(T('XMLcms_text_need_all_data'));
          return false;
        }
        $tags[$name] = $value;  
      }
    }
    if ($Param->Exists('other')) {
      foreach ($Param->other as $name => $value) {
        $tags[$name] = $value;  
      }
    }
    
    $file = array('path' => array(), 'name' => array());
    $countFile = count($_FILES[$this->_fileAttr]);
    for ($i = 0; $i < $countFile; ++$i) {
      if ($_FILES[$this->_fileAttr][$i]['error'] == 0) {
        $file['path'][] = $_FILES[$this->_fileAttr][$i]['tmp_name'];
        $file['name'][] = $_FILES[$this->_fileAttr][$i]['name'];
      }
    }
    $result = fsFunctions::Mail($this->_table->mail,
                                 $this->_table->title,
                                 $this->_view->HtmlCompile($this->_table->message, $tags),
                                 CMSSettings::GetInstance('robot_email'),
                                 $file,
                                 $this->settings->contentType,
                                 $this->settings->codePage);
    for ($i = 0; $i < $countFile; ++$i) {
      fsFunctions::DeleteFile($_FILES[$this->_fileAttr][$i]['tmp_name']);
    }  
    if ($result) {
      if ($this->_table->mail_user == 1 && isset($tags['email'])) {
        fsFunctions::Mail($tags['email'],
                          $this->_table->title_user,
                          $this->_view->HtmlCompile($this->_table->message_to_user, $tags),
                          CMSSettings::GetInstance('robot_email'),
                          $file,
                          $this->settings->contentType,
                          $this->settings->codePage);
      }
      $this->Message(T('XMLcontact_form_send_ok'));
    } else {
      $this->Message(T('XMLcontact_form_send_err'));
    }
    return $result;
  }
  
  public function actionSend($Param)
  {
    if ($this->_table->name == '') {
      $this->_Referer();
      return;
    } 
    $this->_SendMail($Param);
    if ($Param->Exists('ajax')) {
      $this->Html($this->Message());
      $this->Message('');
    } else {
      $this->_Referer();
    }   
  }
  
  public function Form($Param)
  {
    if ($this->_table->name == '') {
      return '';
    }
    $class = 'mContactForm';
    $action = $this->_My('Send').'?name='.$Param->name;
    if ($this->_table->ajax == 1) {
      $this->Tag('message', '<div id="form-'.$Param->name.'-ajax-result"></div>');
      $class .= 'Ajax';
      $action .= '&ajax=true';
      $jqFormFile = '<script src="'.URL_JS.'jqForm.js" type="text/javascript"></script>';
      if (strpos($_REQUEST['includeHead'], $jqFormFile) === false) {
        $_REQUEST['includeHead'] .= $jqFormFile;
      }
    }
    $this->_AddMyScriptsAndStyles(true, true, URL_JS, URL_THEME_CSS);
    return '<form class="'.$class.' form-'.$Param->name.'"
              id="form-'.$Param->name.'" 
              enctype="multipart/form-data"
              action="'.$action.'"
              method="post">'.
                 '<input type="hidden" name="form[name]" value="'.$Param->name.'" />'.
                 $this->_view->HtmlCompile(
                  $this->_table->tpl,
                  array('message' => $this->Tag('message'))
                 ).
           '</form>';
    
  } 
}