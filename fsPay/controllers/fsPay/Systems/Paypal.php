<?php
class fsPayPaypal extends PayController
{
  public function actionSuccess($param)
  {
    $this->Redirect(URL_ROOT);
    if (!$param->Exists('invoiceId', true)) {
        return;
    }
    $this->Redirect(fsHtml::Url(URL_ROOT.'Pay/Good?id='.$param->invoiceId));
  }

  public function actionCheck($param)
  {
    /*$ch = curl_init('https://www.howsmyssl.com/a/check');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    $data = curl_exec($ch);
    curl_close($ch);*/

    /*$json = json_decode($data);
    echo $json->tls_version.'<br />';
    //$curl_info = curl_version();
    //echo $curl_info['ssl_version'];
    printf("0x%x\n", OPENSSL_VERSION_NUMBER);*/

    define("LOG_FILE", __DIR__."/Paypal.log");
    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
      $keyval = explode ('=', $keyval);
      if (count($keyval) == 2)
         $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
    $req = 'cmd=_notify-validate';
    if(function_exists('get_magic_quotes_gpc')) {
    	$get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
    	$value = $get_magic_quotes_exists && get_magic_quotes_gpc() == 1
          ? urlencode(stripslashes($value))
          : urlencode($value);
        $req .= "&$key=$value";
    }
    $paypal_url = $this->settings->paypal_demo ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
    $ch = curl_init($paypal_url);
    if ($ch == false) {
    	die('no curl init');
    }
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    $res = curl_exec($ch);
    if (curl_errno($ch) != 0) {
        $error = curl_error($ch);
    	//error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . $error . PHP_EOL, 3, LOG_FILE);
    	curl_close($ch);
    	die($error);
    } else {
		if(DEBUG == true) {
			//error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
			//error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
		}
		curl_close($ch);
    }
    $tokens = explode("\r\n\r\n", trim($res));
    $res = trim(end($tokens));
    if (strcmp($res, "VERIFIED") == 0) {
        if($param->receiver_email == '' || $param->receiver_email != $this->settings->paypal_email
            || !$param->Exists('item_number', true) || $param->mc_currency != 'USD') {
            //error_log('Error 1'.PHP_EOL, 3, LOG_FILE);
            die('error');
        }
        $this->_table->current = $param->item_number;
        if($this->_table->id != $param->item_number || $this->_table->status != 0
            || (float)$param->mc_gross != (float)$this->_table->sum) {
            //error_log('Error 2'.PHP_EOL, 3, LOG_FILE);
            die('error');
        }
        if(strtolower($param->payment_status) == 'completed') {
            $this->_table->status = 2;
            $this->_table->message = $param->txn_id;
            $this->_table->date_payed = date("Y-m-d H:m:i");
            $this->_table->Save();
            //error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
            die('ok');
        } else {
            //error_log(date('[Y-m-d H:i e] '). "Status: ".$param->payment_status.PHP_EOL, 3, LOG_FILE);
        }
    } else if (strcmp ($res, "INVALID") == 0) {
    	//error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
    }
    die('error');
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
        $additionalInfo .= "<input name='email' type='hidden' value='".$user_info[$userFieldForRestore]['value']."'>";
    }
    $url = $this->settings->paypal_demo == 1 ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
    $html = "<html><head><title>".T('XMLcms_text_loading')."...</title></head><body>";
    $html .= $this->CreateView(array(), $this->_Template('Loader', 'FsPay'));
    $html .= "<form accept-charset='utf-8' style='visibility:hidden;position:absolute;' method='post' id='pay' action='".$url."'>
        <input type='hidden' name='cmd' value='_xclick'>
        <input type='hidden' name='business' value='".$this->settings->paypal_email."''>
        <input type='hidden' name='charset' value='utf-8'>
        <input type='hidden' name='return' value='".$this->_My('Success?invoiceId='.$param->payment)."'>
        <input type='hidden' name='currency_code' value='USD'>
        <input type='hidden' name='notify_url' value='".$this->_My('Check')."'>
        <input type='hidden' name='amount' value='".$sum."'>
        <input type='hidden' name='item_number' value='".$param->payment."'>
        <input type='hidden' name='item_name' value='".T($this->_table->result->mysqlRow['link_id_operation'])."'>
        ".$additionalInfo."</form>
        <script type='text/javascript'>document.forms[0].submit();</script></body></html>";
    $this->Html($html);
  }
}