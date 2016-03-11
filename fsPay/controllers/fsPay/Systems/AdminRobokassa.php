<?php
class fsPayAdminRobokassa extends AdminPayController
{

  protected $_controllerSettings = Array(
    'robokassa_use' => '0',
    'robokassa_test' => '1',
    'robokassa_login' => '',
    'robokassa_private' => '', 
    'robokassa_public' => ''
  ); 

  public function Config($Param)
  {
    $this->Tag('settings', $this->settings);
    return $this->CreateView(array(), $this->_Template('ConfigRobokassa', 'AdminFsPay'));
  }
  
}