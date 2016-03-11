<?php
class fsPayAdminPaypal extends AdminPayController
{
  protected $_controllerSettings = array(
    'payapl_use' => '0',
    'paypal_email' => '',
    'paypal_demo' => '1',
  );

  public function Config($param)
  {
    $this->Tag('settings', $this->settings);
    return $this->CreateView(array(), $this->_Template('ConfigPaypal', 'AdminFsPay'));
  }

}