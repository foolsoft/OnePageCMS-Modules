<?php
class fsPayAdminQiwi extends AdminPayController
{
  protected $_controllerSettings = array(
    'qiwi_use' => '0',
    'qiwi_id' => '',
    'qiwi_lifetime' => '24',
    'qiwi_agt' => 'true',
    'qiwi_secret' => ''
  ); 

  public function Config($Param)
  {
    $this->Tag('settings', $this->settings);
    return $this->CreateView(array(), $this->_Template('ConfigQiwi', 'AdminFsPay'));
  }
}
?>