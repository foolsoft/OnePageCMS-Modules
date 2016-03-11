<?php
class fsPayRobokassa extends PayController
{
  public function actionSuccess($param)
  {
    $this->_table->Select()->Where("`id` = '".$param->InvId."' AND `status` = '1'")->Execute();
    $this->Redirect(fsHtml::Url(URL_ROOT.'Pay/Fail'));
    if ($this->_table->result->id == '') {
      return;
    }
    if ((float)$this->_table->result->sum != $param->OutSum) {
      $this->_table->Update(array('message'), array(T('XMLfspay_unknown_sum')))->Where("`id` = '".$param->InvId."' AND `status` = '1'")->Execute(); 
      return;
    }
    $CRC = strtoupper(md5($param->OutSum.':'.$param->InvId.':'.$this->settings->robokassa_private));
    if ($CRC != strtoupper($param->SignatureValue)) {
      $this->_table->Update(array('message'), array(T('XMLfspay_unknown_hash')))->Where("`id` = '".$param->InvId."' AND `status` = '1'")->Execute(); 
      return;
    }
    $this->_table->Update(array('status', 'message', 'date_payed'),
                          array('2', 'OK', date("Y-m-d H:m:i")))
                  ->Where("`id` = '".$param->InvId."' AND `status` = '1'")->Execute(); 
    $this->_NotifySend("FsPay - #".$param-InvId,
                        $this->_view->HtmlCompile($this->settings->ok_template, array('id' => $param->InvId)));
    $this->Redirect(fsHtml::Url(URL_ROOT."Pay/Good?id=".$param->InvId));
  }

  public function actionCheck($param)
  {
    $this->_table->Select()->Where("`id` = '".$param->InvId."' AND `status` = '0'")->Execute();
    if ($this->_table->result->id == '') {
      return $this->Html(T('XMLfspay_unknown_payment')."\n"); 
    }
    if ((float)$this->_table->result->sum != $param->OutSum) {
      return $this->Html(T('XMLfspay_unknown_sum')."\n"); 
    }
    $CRC = strtoupper(md5($param->OutSum.':'.$param->InvId.':'.$this->settings->robokassa_private));
    if ($CRC != strtoupper($param->SignatureValue)) {
      return $this->Html(T('XMLfspay_unknown_hash')."\n");  
    }
    $this->_table->Update(array('status'), array('1'))->Where("`id` = '".$param->InvId."' AND `status` = '0'")->Execute(); 
    $this->Html("OK".$this->_table->result->id."\n");
  }

  public function actionAction($param)
  {
    $sum = $this->_CheckPayment($param->payment);
    if ($sum < 0) {
        return $this->_Referer();
    }
    $POST_URL = ($this->settings->robokassa_test == '1' ? "http://test.robokassa.ru/Index.aspx" : "https://merchant.roboxchange.com/Index.aspx");
    $CRC = $this->settings->robokassa_login.':'.$sum.':'.$param->payment.':'.$this->settings->robokassa_public;
    $CRC = strtoupper(md5($CRC));
    $html = "<html><head><title>".T('XMLcms_text_loading')."...</title></head><body>";
    $html .= $this->CreateView(array(), $this->_Template('Loader', 'FsPay'));
    $html .= "<form style='visibility:hidden;position:absolute;' id='pay' name='pay' method='POST' action='".$POST_URL."'>
                            	  <input type='hidden' name='mrchLogin' value='".$this->settings->robokassa_login."' />
                                <input type='hidden' name='OutSum' value='".$sum."' />
                                <input type='hidden' name='invId' value='".$param->payment."' /> 
                                <input type='hidden' name='Desc' value='".T('XMLfspay_pay_for')." №".$param->payment." (".URL_ROOT.")' />
                                <input type='hidden' name='SignatureValue' value='".$CRC."' />
                                <input type='submit'>
                              </form>";
    $html .= "<script type='text/javascript'>document.forms[0].submit();</script></body></html>";
    $this->Html($html);
  }
}