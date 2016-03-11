<?php
class fsPayQiwi extends PayController
{
  public function actionGood($param)
  {
    $this->Redirect(URL_ROOT);
    if (!$param->Exists('order', true)) {
        return;
    }
    $this->Redirect(URL_ROOT.'Pay/Good/id/'.$param->order.'/');  
  }
  
  public function actionCheck($param) 
  {
    $this->Redirect(URL_ROOT);
    fsFunctions::IncludeFile(PATH_ROOT.'controllers/fsPay/Systems/QiwiSoap/classes.php'); 
    $s = new SoapServer(PATH_ROOT.'controllers/fsPay/Systems/QiwiSoap/IShopClientWS.wsdl', array('classmap' => array('tns:updateBill' => 'SoapQiwiParam', 'tns:updateBillResponse' => 'SoapQiwiResponse')));
    $s->setClass('fsPayQiwi');
    $s->handle();
  }
  
  public function updateBill($param)
  {
    if (!class_exists('SoapQiwiResponse')) {
      return $this->Redirect(URL_ROOT);
    }
    $temp = new SoapQiwiResponse();
  	if ($this->settings->qiwi_id == '' ||
        $this->settings->qiwi_secret == '' || 
        $param->login != $this->settings->qiwi_id ||
  	    $param->password != strtoupper(md5($param->txn.strtoupper(md5($this->settings->qiwi_secret)))) 
       ) {
      $temp->updateBillResult = 666;
    } else {
    	$this->_table->current = $param->txn;
      if ($this->_table->id == '' || $this->_table->system != 'Qiwi' || $this->_table->status == '2') {
        $temp->updateBillResult = 13;
      } else {
        if ($param->status == 60) {
      	  $this->_NotifySend("FsPay - #".$param->txn, $this->_view->HtmlCompile($this->settings->ok_template, array('id' => $param->txn)));
          $this->_table->message = 'OK';
          $this->_table->date_payed = date("Y-m-d H:m:i");
          $this->_table->status = '2';
        } else if ($param->status > 100) {
      		$this->_table->message = T('XMLfspay_canceled');
      	} else if ($param->status >= 50 && $param->status < 60) {
      		$this->_table->status = '1';
          $this->_table->message = T('XMLfspay_in_process');
      	} else {
      		$this->_table->message = T('XMLfspay_unknown_status');
      	}
      	$this->_table->save();
      	$temp->updateBillResult = 0;
      }
    }
    return $temp; 
  }
  
  public function actionAction($Param)
  {
    $sum = $this->_CheckPayment($Param->payment);
    if ($sum < 0) {
        return $this->_Referer();
    }
    $html = "<html><head><title>".T('XMLcms_text_loading')."...</title></head><body>";
    $html .= $this->CreateView(array(), $this->_Template('Loader', 'FsPay'));
    $html .= "<form style='position:absolute;visibility:hidden;' id='pay' name='pay' method='POST' action='https://w.qiwi.ru/setInetBill_utf.do'>
    	                          <input type='hidden' name='summ' value='".$sum."' />
                              	<input type='hidden' name='com' value='".T('XMLfspay_pay_for')." №:".$Param->payment." (".URL_ROOT.")' />
                              	<input type='hidden' name='txn_id' value='".$Param->payment."' />
                                <input type='hidden' name='to' id='to' value='' />
                                <input type='hidden' name='check_agt' value='".$this->settings->qiwi_agt."' />
                              	<input type='hidden' name='from' value='".$this->settings->qiwi_id."' />
                              	<input type='hidden' name='lifetime' value='".$this->settings->qiwi_lifetime."' />
                                <input type='submit'>
                              </form>";
   $html .= "<script type='text/javascript'>";
   $html .= "var tel = '';
            while (!/^\d{10}$/.test(tel)) {
              tel = prompt('".T('XMLfspay_enter_phone')."');
            }
            document.getElementById('to').value = tel;";
   $html .= "document.forms[0].submit();</script></body></html>";
   $this->Html($html);
  }
  
}