<?php
class fsPayYandexKassa extends PayController
{
  public static $name = 'Яндекс Касса';

  public function actionSuccess($param)
  {
    $this->Redirect(URL_ROOT);
    if (!$param->Exists('orderNumber', true)) {
        return;
    }
    $this->Redirect(fsHtml::Url(URL_ROOT.'Pay/Good?id='.$param->orderNumber));
  }

  public function actionCheck($param)
  {
    header("Content-Type: text/xml");
    $this->_table->current = $param->orderNumber;
    $hash = md5($param->action.';'.$param->orderSumAmount.';'.$param->orderSumCurrencyPaycash.';'.
            $param->orderSumBankPaycash.';'.$this->settings->yandexkassa_shopId.';'.$param->invoiceId.';'.
            $param->customerNumber.';'.$this->settings->yandexkassa_password);
    $code = (float)$this->_table->sum != (float)$param->orderSumAmount ? 100
        : (strtolower($hash) != strtolower($param->md5) ? 1 : 0);
    //fsFileWorker::UpdateFile(__DIR__.'/log.txt', $code.': '.strtolower($hash) .' = '. strtolower($param->md5) );
    //fsFileWorker::UpdateFile(__DIR__.'/log2.txt', $param->action.';'.$param->orderSumAmount.';'.$param->orderSumCurrencyPaycash.';'.
    //        $param->orderSumBankPaycash.';'.$this->settings->yandexkassa_shopId.';'.$param->invoiceId.';'.
    //        $param->customerNumber.';'.$this->settings->yandexkassa_password);
    if($param->action == 'paymentAviso') {
        if($code == 0) {
            $this->_table->message = 'OK';
            $this->_table->status = 2;
            $this->_table->date_payed = date("Y-m-d H:m:i");
            $this->_table->Save();
        }
        $response .= '<paymentAvisoResponse performedDatetime="'.$param->requestDatetime.'" '.
            ' code="'.$code.'" invoiceId="'.$param->invoiceId.'" shopId="'.$this->settings->yandexkassa_shopId.'"/>';
    } else {
        if($code == 0) {
            $this->_table->message = 'Invoice: '.$param->invoiceId;
            $this->_table->status = 1;
            $this->_table->Save();
        }
        $response .= '<checkOrderResponse performedDatetime="'.$param->requestDatetime.'" '.
		  'code="'.$code.'" invoiceId="'.$param->invoiceId.'" shopId="'.$this->settings->yandexkassa_shopId.'" />';
    }
    die($response);
  }

  public function actionAction($param)
  {
    $sum = $this->_CheckPayment($param->payment);
    if ($sum < 0) {
        return $this->_Referer();
    }
    $this->_table->current = $param->payment;
    $userFieldForRestore = fsSpecialFields::Email;
    $user_fields = new user_fields();
    $user_fields->GetSpecialField($userFieldForRestore);
    $userFieldForRestoreName = $user_fields->result->name;
    unset($user_fields);
    $additionalInfo = '';
    if($userFieldForRestore != '' && $user_info[$userFieldForRestore]['value'] != '') {
        $user_info = new user_info();
        $user_info = $user_info->GetInfo($this->_table->contact);
        $additionalInfo .= "<input name='cps_email' type='hidden' value='".$user_info[$userFieldForRestore]['value']."'>";
    }
    if($param->service != '') {
        $additionalInfo .= "<input name='paymentType' type='hidden' value='".$param->service."'>";
    }
    $kassaURL = $this->settings->yandexkassa_demo == 1 ? 'https://demomoney.yandex.ru/eshop.xml' : 'https://money.yandex.ru/eshop.xml';
    $html = "<html><head><title>".T('XMLcms_text_loading')."...</title></head><body>";
    $html .= $this->CreateView(array(), $this->_Template('Loader', 'FsPay'));
    $html .= "<form style='visibility:hidden;position:absolute;' method='post' id='pay' action='".$kassaURL."'>
		<input name='scId' type='hidden' value='".$this->settings->yandexkassa_scId."'>
		<input name='shopId' type='hidden' value='".$this->settings->yandexkassa_shopId."'>
		<input name='customerNumber' type='hidden' value='".$this->_table->contact."'>
		<input name='orderNumber' type='hidden' value='".$param->payment."'>
		<input name='Sum' type='hidden' value='".$sum."'>
        ".$additionalInfo."</form>
        <script type='text/javascript'>document.forms[0].submit();</script></body></html>";
    $this->Html($html);
  }
}