<?php
class fsPayAdminWebmoney extends AdminPayController
{
  protected $_controllerSettings = array(
    'webmoney_use' => '0',
    'webmoney_test' => '2',
    'webmoney_wallet' => '',
    'webmoney_secret' => '' 
  ); 

  public function Config($Param)
  {
    $this->Tag('settings', $this->settings);
    return $this->CreateView(array(), $this->_Template('ConfigWebmoney', 'AdminFsPay'));
  }
}
?>