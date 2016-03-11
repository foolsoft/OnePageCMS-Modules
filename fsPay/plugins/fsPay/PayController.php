<?php
class PayController extends cmsController
{
  protected $_tableName = 'pay_comepay';
  protected $_autoLoadSettings = false;
 
  protected function _NotifySend($title, $text)
  {
      if ($this->settings->notify_email != '') {
        return fsFunctions::Mail($this->settings->notify_email, $title, $text, CMSSettings::GetInstance('robot_email'));  
      }
      return true;
  }
 
  protected function _CheckPayment($paymentId)
  {
    $this->Redirect(fsHtml::Url(URL_ROOT.'404'));
    if ($paymentId == '') {
      return -1;
    }
    $this->_table->GetField(array('id', 'sum', 'system'), $paymentId);
    if ($this->_table->result->id == '' ||
        $this->_table->result->system != substr(get_class($this), 5)) {
      return -1;
    }
    $this->Redirect('');
    return $this->_table->result->sum;
  }
  
  public function Finnaly()
  {
    if ($this->Html() == '') {
        $this->Html($this->CreateView(array(), $this->_Template($_REQUEST['method'], 'FsPay')));
    }
  }
  
  public function __construct()
  {
    parent::__construct();
    $this->settings = $this->_LoadSettings('PayController');
  }
}

class AdminPayController extends AdminPanel
{
  protected $_tableName = 'pay_comepay';
  protected $_autoLoadSettings = false;
  protected $_controllerSettings = array();

  public function Init($Param)
  {
    $this->Tag('title', 'On-Line '.T('XMLfspay_pay'));
    parent::Init($Param);
  }

  public function Finnaly()
  {
    if ($this->Html() == '') {
        $this->Html($this->CreateView(array(), $this->_Template($_REQUEST['method'], 'AdminFsPay')));
    }
  }

  public function UnInstall()
  {
      fsFunctions::DeleteDirectory(PATH_ROOT.'controllers/fsPay/');
      fsFunctions::DeleteDirectory(PATH_PLUGINS.'fsPay/');
      fsFunctions::DeleteDirectory($this->_TemplatePath('FsPay'));
      fsFunctions::DeleteDirectory($this->_TemplatePath('AdminFsPay'));
      fsFunctions::DeleteFile(PATH_ROOT.'models/pay_comepay.php');
      fsFunctions::DeleteFile(PATH_LANG.'xml/ru_FsPay.xml');
      fsFunctions::DeleteFile(PATH_LANG.'en-to-ru/LangFsPay.php');
      fsFunctions::DeleteFile(PATH_LANG.'ru-to-en/LangFsPay.php');
      $this->_table->Drop()->Execute();
      $this->_table->Execute('DROP TABLE `'.fsConfig::GetInstance('db_prefix').'pay_type_operations`');
  }

  public function __construct()
  {
    parent::__construct();
    $this->settings = $this->_LoadSettings('PayController');
    if (count($this->_controllerSettings) > 0) {
      $controllerSettings = new fsDBTable('controller_settings');
      $controllerSettings->controller = $this->settings->controller;
      foreach ($this->_controllerSettings as $cs => $value) {
        if (!$this->settings->Exists($cs)) {
          $controllerSettings->name = $cs;
          $controllerSettings->value = $value;
          $controllerSettings->Insert()->Execute();  
        }
      }
    }
    
  }
  
  public function actionAjaxLoadSystemConfig($Param)
  {
    if (!$Param->Exists('system')) {
        return '';
    }
    $this->Html($this->_LoadSystemConfig($Param->system));  
    return $this->Html();
  }
  
  private function _LoadSystemConfig($system)
  {
    $class = 'fsPay'.$system;
    $class = new $class();
    return $class->Config(null);
  }
  
  public function actionDoConfig($Param)
  {
    $Param->dynamic_create = $Param->Exists('dynamic_create') ? 1 : 0;
    parent::actionDoConfig($Param);
  }
  
  public function actionConfig($param)
  {
    $this->Tag('settings', $this->settings);
  }

  public function actionSetSort($Param)
  {
    fsSession::Set('pay_sort', $Param->sort);
    fsSession::Set('pay_sort_desc', $Param->sort_desc);
    $this->Redirect($this->_My());
  }

  public function actionIndex($param)
  {
    $page = $param->Exists('page', true) ? $param->page - 1 : 0;
    $arrSerach = $param->Exists('search') ? $param->search : array(); 
    if (!isset($arrSerach['count'])) {
        $arrSerach['count'] = 20;
    }
    if (!isset($arrSerach['start'])) {
        $arrSerach['start'] =  $page * $arrSerach['count'];
    }
    $this->Tag('search', new fsStruct($arrSerach));
    $btnsPlugins = array();
    $arr =  fsFunctions::DirectoryInfo(PATH_ROOT.'controllers/fsPay/Plugins/', true, false, array('Admin'), array('php'));
    foreach ($arr['NAMES'] as $plugin) {
      $class = substr($plugin, 0, strlen($plugin) - 4);
      if (!class_exists($class)) {
        continue;
      }
      $btnsPlugins[] = array('url' => fsHtml::Url(URL_ROOT.$class.'/Index'),
                             'title' => isset($class::$Name) && !empty($class::$Name)
                                        ? T($class::$Name)
                                        : T($class));
    }
    $this->Tag('plugins', $btnsPlugins);
    unset($btnsPlugins);
    if(!fsSession::Exists('pay_sort')) {
        fsSession::Create('pay_sort', 'id');
    }
    if(!fsSession::Exists('pay_sort_desc')) {
        fsSession::Create('pay_sort_desc', '1');
    }
    
    $this->Tag('users', $PayFunctions::GetContactInfo());
    $this->Tag('pays', $this->_table->LoadPays(fsSession::GetInstance('pay_sort'),
                                               fsSession::GetInstance('pay_sort_desc') == 1,
                                               $this->Tag('search')));
  }

  public function actionActivate($Param)
  {
    $this->_Referer();
    if (!$Param->Exists('key', true)) {
      $this->Message(T('XMLcms_bme_activate'));
      return;
    }
    $this->_table->Update(array('status', 'message', 'date_payed'), 
                          array('2', T('Ручная проводка'), date("Y-m-d H:m:s")))
                          ->Where("`id` = '".$Param->key."'")
                          ->Execute();
  }

}
?>