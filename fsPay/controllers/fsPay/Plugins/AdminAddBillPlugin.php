<?php
class AdminAddBillPlugin extends AdminPayController
{
    public static $Name = 'XMLfsaddbill_create'; 
  
    private function _GetTemplate($name) 
    {
        return $this->_Template($name, 'AdminFsPay');
    }
  
    public function actionIndex($param) 
    {
        $db = new fsDBTableExtension('pay_type_operations');
        $db->Select()->Order(array('name'))->Execute('', false);
        $operations = array();
        while($db->Next()) {
            $operations[$db->result->id] = $db->result->name;
        }
        $this->Tag('operations', $operations);
        
        $first = '';
        $this->Tag('systems', PayFunctions::GetPaymentsSystems('', $first, $this->settings, false, false));
        $this->Tag('users', $PayFunctions::GetContactInfo());
        
        $html = $this->CreateView(array(), $this->_GetTemplate('AdminAddBillPluginCreateBill'));
        $this->Html($html);
    }
    
    public function actionAdd($param) 
    {
        $param->sum = (float) $param->sum;
        if($param->sum < 0) {
            $this->_Referer();
            return $this->Message(T('XMLfsaddbill_needcheck'));
        }
        $payId = $this->_table->Add($param->system, $param->id_operation, $param->sum, $param->contact, 'this', $param->comment);
        if($payId > 0) {
            $user_info = new user_info();
            $email = $user_info->GetValueBySpecialType($param->contact, fsSpecialFields::Email);
            if($email != null && $email != '') {
                $message = fsFunctions::StringFormat(T('XMLfsaddbill_newbill'), array($payId, $param->sum, URL_ROOT));
                fsFunctions::Mail($email, T('XMLfsaddbill_newbilltitle'), $message);
            }
            $hash = payFunctions::Sign($payId, $this->settings->secret, $param->PHPSESSID);
            $this->_table->Update(array('hash'), array($hash))
                    ->Where("`id` = '".$payId."'")
                    ->Execute();

            $this->Redirect(fsHtml::Url(URL_ROOT.'AdminPayController/Index'));
            $this->Message(T('XMLcms_added'));
        }
        
    }
                              
}
