<?php
class PayFunctions 
{
    public static function Sign($comepayId, $secret, $session) 
    {
        return md5($comepayId . $secret . ' ' . $session . fsFunctions::GetIp());
    }

    public static function GetName($method) 
    {
        $class = 'fsPayAdmin' . $method;
        return class_exists($class) && isset($class::$name) && !empty($class::$name) ? T($class::$name) : $method;
    }

	public static function GetContactInfo()
    {
        $users = new users();
        $user_info = new user_info();
        $users->Select()->Where('`id` > "0"')->Execute('', false);
        $usersData = array();
        while($users->Next()) {
            //$info = $user_info->GetInfo($users->result->id);
            $usersData[$users->result->id] = $users->result->login;
        }
        return $usersData;
    }
	
    public static function GetPaymentsSystems($valuePrefix = '', &$selected = '', $settings = false, $showNoActive = false, $asSelectOptions = true) 
    {
        $systems = fsFunctions::DirectoryInfo(PATH_ROOT . 'controllers/fsPay/Systems/', true, false, array('Admin'), array('php'));
        $html = ''; $data = array();
        foreach ($systems['NAMES'] as $system) {
            $system = substr($system, 5, strlen($system) - 9);
            $systemName = payFunctions::GetName($system);
            if ($selected == '') {
                $selected = 'Admin' . $system;
            }
            $payType = strtolower($system) . '_use';
            if ($settings !== false && $settings->$payType != '1' && $showNoActive === false) {
                continue;
            }
            if($asSelectOptions) {
                $html .= '<option value="' . $valuePrefix . $system . '" ' .
                    ($selected == $system ? 'selected' : '') . '>' .
                    $systemName .
                    '</option>';
            } else {
                $data[$valuePrefix.$system] = $systemName;
            }
        }
        return $asSelectOptions ? $html : $data;
    }

}
