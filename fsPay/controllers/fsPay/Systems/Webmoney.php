<?php
class fsPayWebmoney extends PayController {
  
  public function actionGood($param)
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
           die(T('XMLfspay_unknown_payment'));
        }
        if((float)$this->_table->result->sum != (float)$param->LMI_PAYMENT_AMOUNT) {
           die(T('XMLfspay_unknown_sum'));
        }
        if ($param->LMI_PAYEE_PURSE == $this->settings->webmoney_wallet) {
            die('YES');
        } else {
            die(T('XMLfspay_unknown_wallet'));
        }
    } else {
      $common_string = $param->LMI_PAYEE_PURSE.$param->LMI_PAYMENT_AMOUNT.$param->LMI_PAYMENT_NO.$param->LMI_MODE.$param->LMI_SYS_INVS_NO.$param->LMI_SYS_TRANS_NO.$param->LMI_SYS_TRANS_DATE.$this->settings->webmoney_secret.$param->LMI_PAYER_PURSE.$param->LMI_PAYER_WM;
      $hash = strtoupper(hash('sha256', $common_string));
      $this->_table->current = $param->LMI_PAYMENT_NO;
      if ($this->_table->status != '0') {
        $this->_table->message = T('XMLfspay_already_payed');
        $this->_table->status = '1';
        $this->_table->save();
        exit;
      }
      if ((float)$this->_table->result->sum != (float)$param->LMI_PAYMENT_AMOUNT) {
        $this->_table->message = T('XMLfspay_unknown_sum').' ('.$param->LMI_PAYMENT_AMOUNT.')';
        $this->_table->status = '1';
        $this->_table->save();
        exit;
      }
      if($hash != $param->LMI_HASH) { 
        $this->_table->message = T('XMLfspay_unknown_hash');
        $this->_table->status = '1';
        $this->_table->save();
        exit;
      }
      $this->_NotifySend("FsPay - #".$param->LMI_PAYMENT_NO,
                         $this->_view->HtmlCompile($this->settings->ok_template, array('id' => $param->LMI_PAYMENT_NO)));
      $this->_table->message = 'OK';
      $this->_table->date_payed = date("Y-m-d H:m:i");
      $this->_table->status = '2';
      $this->_table->save();
    }
    exit;
  }
 
  public function actionAction($param)
  {
    $sum = $this->_CheckPayment($param->payment);
    if ($sum < 0) {
        return $this->_Referer();
    }
    $html = "<html><head><title>".T('XMLcms_text_loading')."...</title></head><body>";
    $html .= $this->CreateView(array(), $this->_Template('Loader', 'FsPay'));
    $html .= "<form accept-charset='cp1251' style='position:absolute;visibility:hidden;' id='pay' name='pay' method='POST' action='https://merchant.webmoney.ru/lmi/payment.asp'>
            <input type='hidden' name='LMI_PAYMENT_AMOUNT' value='".$sum."' />
            <input type='hidden' name='LMI_PAYMENT_DESC' value='".T('XMLfspay_pay_for')." №:".$param->payment." (".URL_ROOT.")' />
            <input type='hidden' name='LMI_PAYMENT_NO' value='".$param->payment."' />
            <input type='hidden' name='LMI_PAYEE_PURSE' value='".$this->settings->webmoney_wallet."' />
            <input type='hidden' name='LMI_SIM_MODE' value='".$this->settings->webmoney_test."' />
            <input type='submit'>
        </form>";
   $html .= "<script type='text/javascript'>document.forms[0].submit();</script></body></html>";
   $this->Html($html); 
  }
}