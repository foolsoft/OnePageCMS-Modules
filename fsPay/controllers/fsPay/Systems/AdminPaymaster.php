<?php
class fsPayAdminPaymaster extends AdminPayController
{

  protected $_controllerSettings = Array(
    'paymaster_use' => '0',
    'paymaster_id' => '0', 
    'paymaster_secret' => '',
  ); 

  public function Config($param)
  {
    $this->Tag('settings', $this->settings);
    return $this->CreateView(array(), $this->_Template('ConfigPaymaster', 'AdminFsPay'));
  }
  
}