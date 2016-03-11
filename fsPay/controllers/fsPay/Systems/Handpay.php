<?php
class fsPayHandpay extends PayController
{
  public function actionAction($Param)
  {
    $this->Redirect(URL_ROOT);
    if ($this->_CheckPayment($Param->payment) < 0) {
        return;
    }
    $this->Tag('title', T('XMLfspay_order_ok'));
    $this->Tag('payment', $Param->payment);
    $this->Html($this->CreateView(array(), $this->_Template('HandpayCreated', 'FsPay')));
  }
}