<?php
class fsPayAdminHandpay extends AdminPayController
{
  public static $name = 'Оплата при получении';

  protected $_controllerSettings = array(
    'handpay_use' => '1'
  ); 

  public function Config($Param)
  {
    $this->Tag('settings', $this->settings);
    return $this->CreateView(array(), $this->_Template('ConfigHandpay', 'AdminFsPay'));
  }
  
}
?>