<?php
class fsPayAdminYandexKassa extends AdminPayController
{
  protected $_controllerSettings = array(
    'yandexkassa_use' => '0',
    'yandexkassa_shopId' => '0',
    'yandexkassa_scId' => '',
    'yandexkassa_password' => '',
    'yandexkassa_demo' => '1',
  );

  public function Config($param)
  {
    $this->Tag('settings', $this->settings);
    return $this->CreateView(array(), $this->_Template('ConfigYandexKassa', 'AdminFsPay'));
  }

}