<?php
class fsPayPaymaster extends PayController
{
  public function actionSuccess($param)
  {
    $this->Redirect(URL_ROOT);
    if (!$param->Exists('LMI_PAYMENT_NO', true)) {
        return;
    }
    $this->Redirect(fsHtml::Url(URL_ROOT.'Pay/Good?id='.$param->LMI_PAYMENT_NO));
  }

  public function actionCheck($param)
  {
    if ($param->LMI_PREREQUEST == '1') {
        $this->_table->Select()->Where("`id` = '".$param->LMI_PAYMENT_NO."' AND `status` = '0'")->Execute();
        if($this->_table->result->id == '') { 
           return $this->Html(T('XMLfspay_unknown_payment')); 
        }
        if($this->_table->result->sum != $param->LMI_PAYMENT_AMOUNT) { 
          return $this->Html(T('XMLfspay_unknown_sum'));
        }
        if ('RUB' == $param->LMI_CURRENCY) {
            $this->Html('YES');
        } else {
            $this->Html(T('Неверная валюта оплаты'));
        }
    } else {
      $this->_table->current = $param->LMI_PAYMENT_NO;
      if ($this->_table->status != '0') {
        $this->_table->message = T('XMLfspay_already_payed');
        $this->_table->status = '1';
        $this->_table->save();
        return;            
      }
      if ((float)$this->_table->result->sum != (float)$param->LMI_PAYMENT_AMOUNT) {
        $this->_table->message = T('XMLfspay_unknown_sum').' ('.$param->LMI_PAYMENT_AMOUNT.')';
        $this->_table->status = '1';
        $this->_table->save();
        return;
      }
      
      $commonString = 
            $this->settings->paymaster_id.';'.
            $param->LMI_PAYMENT_NO.';'.
            $param->LMI_SYS_PAYMENT_ID.';'.
            $param->LMI_SYS_PAYMENT_DATE.';'.
            $param->LMI_PAYMENT_AMOUNT.';'.
            $param->LMI_CURRENCY.';'.
            $param->LMI_PAID_AMOUNT.';'.
            $param->LMI_PAID_CURRENCY.';'.
            $param->LMI_PAYMENT_SYSTEM.';'.
            $param->LMI_SIM_MODE.';'.
            $this->settings->paymaster_secret;
      $hash = base64_encode(md5($commonString, true));
      
      if($hash != $param->LMI_HASH) { 
        $this->_table->message = T('XMLfspay_unknown_hash');
        $this->_table->status = '1';
        $this->_table->save();
        return; 
      }
      
      $this->_NotifySend("FsPay - #".$param->LMI_PAYMENT_NO, $this->_view->HtmlCompile($this->settings->ok_template, array('id' => $param->LMI_PAYMENT_NO)));
      $this->_table->message = 'OK';
      $this->_table->date_payed = date("Y-m-d H:m:i");
      $this->_table->status = '2';
      $this->_table->save();
    }
  }

  public function actionAction($param)
  {
    $sum = $this->_CheckPayment($param->payment);
    if ($sum < 0) {
        return $this->_Referer();
    }
    $CRC = $this->settings->robokassa_login.':'.$sum.':'.$param->payment.':'.$this->settings->robokassa_public;
    $CRC = strtoupper(md5($CRC));
    $html = "<html><head><title>".T('XMLcms_text_loading')."...</title></head><body>";
    $html .= $this->CreateView(array(), $this->_Template('Loader', 'FsPay'));
    $html .= "<form style='visibility:hidden;position:absolute;' id='pay' name='pay' method='POST' action='https://paymaster.ru/Payment/Init'>
                <input type='hidden' name='LMI_MERCHANT_ID' value='".$this->settings->paymaster_id."' />
                <input type='hidden' name='LMI_PAYMENT_AMOUNT' value='".$sum."' />
                <input type='hidden' name='LMI_PAYMENT_NO' value='".$param->payment."' /> 
                <input type='hidden' name='LMI_PAYMENT_DESC' value='".T('XMLfspay_pay_for')." №".$param->payment." (".URL_ROOT.")' />
                <input type='hidden' name='LMI_CURRENCY' value='RUB' />
                <input type='submit'>
              </form>";
    $html .= "<script type='text/javascript'>document.forms[0].submit();</script></body></html>";
    $this->Html($html);
  }
}