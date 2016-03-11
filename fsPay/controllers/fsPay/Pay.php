<?php
class Pay extends PayController 
{
    public function actionFail($param) 
    {
        $this->Tag('title', T('XMLfspay_payment_error'));
    }

    public function actionMyBills($param) 
    {
        if(!AUTH) {
            return $this->Redirect(fsHtml::Url(URL_ROOT.'user/auth'));
        }
        $this->Tag('title', T('XMLfspay_mybills'));
        $pay_comepay = new pay_comepay();
        $this->Tag('bills', $pay_comepay->LoadUserPays(fsSession::GetArrInstance('AUTH', 'id')));
    }
    
    public function actionCreate($param)
    {
        $this->Tag('title', T('XMLfspay_create'));
    }

    private function _GetError($error) 
    {
        switch ($error) {
            case 1:
                return T('XMLfspay_err_param_count');
            case 2:
                return T('XMLfspay_err_invalid_param');
            case 3:
                return T('XMLfspay_unknown_operation');
            case 4:
                return T('XMLfspay_err_invalid_pay_type');
            case 5:
                return T('XMLfspay_err_no_contact_data');
            default:
                return T('XMLfspay_err_unknown');
        }
    }

    private function _CheckCreated(&$Param)
    {
        if (!$Param->Exists('fs_pay_input_sum')) {
            return 1;
        }
        $sum = $Param->fs_pay_input_sum;
        $sum = str_replace(',', '.', $sum);
        if (!$Param->Exists('fs_pay_input_comment')) {
            return 1;
        }
        if (!$Param->Exists('fs_pay_input_operation_type')) {
            return 1;
        }
        if (!$Param->Exists('fs_pay_input_pay_type')) {
            return 1;
        }
        if (!$Param->Exists('fs_pay_input_contact')) {
            return 1;
        }
        if (!is_numeric($sum) || !is_numeric($Param->fs_pay_input_operation_type)) {
            return 2;
        }
        if ($Param->fs_pay_input_contact == '') {
            return 5;
        }
        $comment = strip_tags($Param->fs_pay_input_comment);
        $sum = round($sum, 2);
        $otype = new fsDBTable('pay_type_operations');
        $otype->Select(array('name'))->Where("`id` = '" . $Param->fs_pay_input_operation_type . "'")->Execute();
        $otype = $otype->result->name;
        if (empty($otype)) {
            return 3;
        }
        $payType = strtolower($Param->fs_pay_input_pay_type) . '_use';
        if (!$this->settings->Exists($payType) || $this->settings->$payType != '1') {
            return 4;
        }
        $Param->fs_pay_input_comment = $comment;
        $Param->fs_pay_input_sum = $sum;
        $Param->fs_pay_input_operation_type_name = $otype;
        return 0;
    }

    public function actionGood($param) 
    {
        $this->Tag('ok', false);
        $this->Tag('title', T('XMLcms_error_access'));
        if ($param->Exists('id', true)) {
            $this->_table->current = $param->id;
            if ($this->_table->id != '' && $this->_table->status == '2') {
                $arr = array('pay_id' => $param->id);
                if ($this->_table->afterpay == 0 && $this->_table->creator != 'this' 
                    && class_exists($this->_table->creator)) {
                  $class = $this->_table->creator;
                  if (method_exists($class, 'AfterPay')) {
                      $class::AfterPay($this->_table->id, $arr);
                      $this->_table->afterpay = 1;
                      $this->_table->Save();
                  }
                }
                foreach ($arr as $k => $v) {
                    $this->Tag($k, $v);
                }
                $this->Tag('title', T('XMLfspay_pay_ok'));
                $this->Tag('ok', true);
            }
        }
    }

    public function actionConfirmed($param) {
        $checking = $this->_CheckCreated($param);
        if ($checking != 0) {
            $this->Message($this->_GetError($checking));
            return $this->Redirect(URL_ROOT);
        }
        $this->_table->ip = fsFunctions::GetIp();
        $this->_table->system = $param->fs_pay_input_pay_type;
        $this->_table->id_operation = $param->fs_pay_input_operation_type;
        $this->_table->comment = $param->fs_pay_input_comment;
        $this->_table->sum = $param->fs_pay_input_sum;
        $this->_table->contact = $param->fs_pay_input_contact;
        if ((float) $param->fs_pay_input_sum == 0) {
            $this->_table->status = 2;
            $this->_table->message = 'Free';
            $this->_table->date_payed = date("Y-m-d H:i:s");
        }
        $this->_table->Insert()->Execute();
        $this->_table->Update(array('hash'), array(payFunctions::Sign($this->_table->insertedId, $this->settings->secret, $param->PHPSESSID)
                ))
                ->Where("`id` = '" . $this->_table->insertedId . "'")
                ->Execute();
        $info = $param->ToArray();
        $info['id'] = $this->_table->insertedId;
        $message = $this->_view->HtmlCompile($this->settings->inform_template, $info);
        $this->_NotifySend('FsPay - #' . $this->_table->insertedId, $message);
        if ((float) $param->fs_pay_input_sum == 0) {
            $this->Redirect($this->_My('Good?id=' . $this->_table->insertedId));
        } else {
            $method = 'fsPay' . $param->fs_pay_input_pay_type . '/Action?payment=' . $this->_table->insertedId;
            $this->Redirect(fsHtml::Url(URL_ROOT . $method));
        }
    }

    public function actionConfirm($param)
    {
        $checking = $this->_CheckCreated($param);
        if ($checking != 0) {
            $this->Message($this->_GetError($checking));
            $this->_Referer();
            return;
        }
        $this->Tag('title', T('XMLfspay_pay_confirm'));
        $this->Tag('input_contact', "<input name='fs_pay_input_contact' id='fs_pay_input_contact' type='hidden' value='" . $param->fs_pay_input_contact . "' />" . $param->fs_pay_input_contact);
        $this->Tag('input_sum', "<input name='fs_pay_input_sum' id='fs_pay_input_sum' type='hidden' value='" . $param->fs_pay_input_sum . "' />" . $param->fs_pay_input_sum);
        $this->Tag('input_comment', "<input name='fs_pay_input_comment' id='fs_pay_input_comment' type='hidden' value='" . $param->fs_pay_input_comment . "'>" . ($param->fs_pay_input_comment == '' ? '-' : $param->fs_pay_input_comment));
        $this->Tag('input_operation_type', "<input name='fs_pay_input_operation_type' id='fs_pay_input_operation_type' type='hidden' value='" . $param->fs_pay_input_operation_type . "' />" . $param->fs_pay_input_operation_type_name);
        $this->Tag('input_pay_type', '<input id="fs_pay_input_pay_type" name="fs_pay_input_pay_type" type="hidden" value="' . $param->fs_pay_input_pay_type . '"> ' . payFunctions::GetName($param->fs_pay_input_pay_type));
        $html = $this->CreateView(array(), $this->_Template('Confirm', 'FsPay'));
        $html = "<form action='" . $this->_My('Confirmed') . "' method='POST'>" . $html . "</form>";
        $this->Html($html);
    }

    public function Form($param) 
    {
        if ($this->settings->dynamic_create == 0) {
            return $_REQUEST['method'] == 'Create' ? '<script type="text/javascript">window.location = "' . fsHtml::Url(URL_ROOT . '404') . '";</script>' : '';
        }
        $this->Tag('input_contact', "<input name='fs_pay_input_contact' class='fs_pay_input_contact' id='fs_pay_input_contact' type='text' value='' />");
        $this->Tag('input_sum', "<input name='fs_pay_input_sum' onkeyup=\"if(!/^\d+([\.,]\d*)?$/.test(this.value))this.value='0';\" class='fs_pay_input_sum' id='fs_pay_input_sum' type='text' value='0' />");
        $this->Tag('input_comment', "<textarea name='fs_pay_input_comment' class='fs_pay_input_comment' id='fs_pay_input_comment'></textarea>");
        $otype = new fsDBTableExtension('pay_type_operations');
        $otype->GetAll(false);
        $input_operation_type = '<select class="fs_pay_input_operation_type" name="fs_pay_input_operation_type" id="fs_pay_input_operation_type">';
        while ($otype->Next()) {
            $input_operation_type .= '<option value="' . ($otype->result->id) . '">' . $otype->result->name . '</option>';
        }
        $input_operation_type .= '</select>';
        $this->Tag('input_operation_type', $input_operation_type);
        $input_pay_type = '<select id="fs_pay_input_pay_type" name="fs_pay_input_pay_type" class="fs_pay_input_pay_type">';
        $first = '';
        $input_pay_type .= payFunctions::GetPaymentsSystems('', $first, $this->settings, false);
        $input_pay_type .= '</select>';
        $this->Tag('input_pay_type', $input_pay_type);
        $html = $this->CreateView(array(), $this->_Template('Form', 'FsPay'));
        $html = "<form action='" . $this->_My('Confirm') . "' method='POST'>" . $html . "</form>";
        return $html;
    }

}
