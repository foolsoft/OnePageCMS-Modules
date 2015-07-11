<?php 
class AdminMSlides extends AdminPanel
{
  protected $_tableName = 'sliders';
  private $_pathImages, $_urlImages; 

  public function Init($request)
  {
    $this->Tag('title', T('XMLslides'));
    $this->_pathImages = PATH_IMG.'slides/';
    $this->_urlImages = URL_IMG.'slides/';
    parent::Init($request);
  }

  private function _CheckParam($param)
  {
    if($param->name == '' || $param->template == ''
      || !$param->Exists('interval', true) 
      || !$param->Exists('width', true)
      || !$param->Exists('height', true)) {
      $this->Message(T('XMLcms_text_need_all_data'));
      $this->_Referer();
      return false;
    }
    $param->interval = $param->interval * 1000; 
    return true;
  }
  
  public function actionAjaxDeleteSlide($param) 
  {
    if(!$param->Exists('key', true)) {
      $this->Html("error:".T('XMLcms_text_need_all_data'));
      return;
    }
    $tableSliedes = new fsDBTable('slides');
    $tableSliedes->current = $param->key;
    if($param->key != $tableSliedes->id) {
      $this->Html("error:".T('XMLcms_text_page_not_found'));
      return;
    }
    fsFunctions::DeleteFile($this->_pathImages.$tableSliedes->id_slider.'/'.$tableSliedes->image);
    $tableSliedes->Delete()->Where('`id` = "'.$param->key.'"')->Execute();
    $this->Html('ok');
  }
  
  public function actionAjaxAddSlide($param)
  {
    if(!$param->Exists('key', true)) {
      $this->Html("error:".T('XMLcms_text_need_all_data'));
      return;
    }
    $this->_table->current = $param->key;
    if($param->key != $this->_table->id) {
      $this->Html("error:".T('XMLcms_text_page_not_found'));
      return;
    }
    fsFunctions::CreateDirectory($this->_pathImages);
    fsFunctions::CreateDirectory($this->_pathImages.$param->key);
    $error = fsFunctions::CheckUploadFiles('slide',
                                           array('image/gif', 'image/jpg', 'image/jpeg', 'image/png'),
                                           false,
                                           true,
                                           false);
    if($error) {
      $this->Html("error:".T('XMLcms_text_bad_file_format'));
      return;
    }
    $newFile = '';
    if (fsFunctions::UploadFiles('slide', $this->_pathImages.$param->key.'/', $newFile)) {
      $tableSliedes = new fsDBTable('slides');
      $tableSliedes->id_slider = $param->key;
      $tableSliedes->image = $newFile;
      $tableSliedes->order = 0;
      $tableSliedes->html = '';
      $tableSliedes->href = '';
      $tableSliedes->Insert()->Execute();
      if($tableSliedes->insertedId > 0) {
        $this->Tag('src', $this->_urlImages.$param->key.'/'.$newFile);
        $this->Tag('order', 0);
        $this->Tag('html', '');
        $this->Tag('width', $this->_table->width);
        $this->Tag('width_unit', $this->_table->width_unit);
		$this->Tag('height', $this->_table->height);
        $this->Tag('href', '');
        $this->Tag('alt', '');
        $this->Tag('id', $tableSliedes->insertedId);
      } else {
        $this->Html("error:".T('XMLcms_text_file_upload_error'));
        return;
      }
    }
  }
  
  public function actionDoAdd($param)
  {
    if(!$this->_CheckParam($param)) {
      return;
    }
    $id = parent::actionDoAdd($param);  
    if($id > 0) {
      $this->Redirect($this->_My('Edit?key='.$id));
    }
  }

  private function _CreateTagTemplates()
  {
    $templates = fsFunctions::DirectoryInfo(PATH_TPL.CMSSettings::GetInstance('template').'/MSlides', true, false, 'Slider', array('php'));
    $this->Tag('templates', $templates['NAMES']);
    $this->Tag('animations', array('none', 'slide', 'scroll', 'fade'));
  }

  public function actionAdd($param)
  {
    $this->_CreateTagTemplates();
  }

  public function actionDoEdit($param)
  {
    if(!$this->_CheckParam($param)) {
      return;
    }                         
    if(parent::actionDoEdit($param) == 0) {
      $htmls = $param->html;
      $orders = $param->order;
      $alts = $param->alt;
      $hrefs = $param->href;
      $tableSliedes = new fsDBTable('slides');
      if(is_array($alts)) {
        foreach($alts as $id => $value) {
          $tableSliedes->current = $id;
          if($tableSliedes->id != $id) {
            continue;
          }    
          $tableSliedes->html = $htmls[$id];
          $tableSliedes->order = $orders[$id];
          $tableSliedes->href = $hrefs[$id];
          $tableSliedes->alt = $alts[$id];
          $tableSliedes->Save();
        }
      }
    }
  }

  public function actionEdit($param)
  {
    $this->_table->current = $param->key;
    if ($this->_table->result->id != $param->key) {
      $this->_Referer();
      return;
    }
    $this->_table->result->interval /= 1000;
    $this->Tag('slider', $this->_table->result);
    $this->_CreateTagTemplates();
    
    $tableSliedes = new fsDBTable('slides');
    $tableSliedes->Select()->Order(array('order'))->Where('`id_slider` = "'.$param->key.'"')->Execute('', false);
    $htmlSlides = '';
    while($tableSliedes->Next()) {
      $this->Tag('src', $this->_urlImages.$param->key.'/'.$tableSliedes->result->image);
      $this->Tag('order', $tableSliedes->result->order);
      $this->Tag('html', $tableSliedes->result->html);
      $this->Tag('width', $this->_table->width);
      $this->Tag('width_unit', $this->_table->width_unit);
      $this->Tag('height', $this->_table->height);
      $this->Tag('href', $tableSliedes->result->href);
      $this->Tag('alt', $tableSliedes->result->alt);
      $this->Tag('id', $tableSliedes->result->id);
      $htmlSlides .= $this->CreateView(array(), PATH_TPL.CMSSettings::GetInstance('template_admin').'/AdminMSlides/AjaxAddSlide.php');  
    }
    $this->Tag('slides', $htmlSlides);
  }

  public function actionIndex($param)
  {
    $this->Tag('sliders', $this->_table->GetAll(true, false, array('name')));
  }
  
  public function actionDelete($param)
  {
    if(parent::actionDelete($param) == 0) {
        $tableSliedes = new fsDBTable('slides');
        $tableSliedes->Delete()->Where('`id_slider` = "'.$param->key.'"')->Execute();
        fsFunctions::DeleteDirectory($this->_pathImages.$param->key);
    }
  }
  
  public function UnInstall()
  {
      fsFunctions::DeleteDirectory(PATH_IMG.'slides/');
      fsFunctions::DeleteFile(PATH_IMG.'sliderLeft.png');
      fsFunctions::DeleteFile(PATH_IMG.'sliderRight.png');
      fsFunctions::DeleteFile(PATH_JS.'MSlides.js');
      fsFunctions::DeleteFile(PATH_THEME_JS.'MSlides.js');
      fsFunctions::DeleteFile(PATH_ATHEME_JS.'AdminMSlides.js');
      fsFunctions::DeleteFile(PATH_CSS.'MSlides.css');
      fsFunctions::DeleteFile(PATH_THEME_CSS.'MSlides.css');
      fsFunctions::DeleteFile(PATH_ATHEME_CSS.'AdminMSlides.css');
      fsFunctions::DeleteDirectory(PATH_TPL.CMSSettings::GetInstance('template_admin').'/AdminMSlides');
      fsFunctions::DeleteDirectory(PATH_TPL.CMSSettings::GetInstance('template').'/MSlides');
      fsFunctions::DeleteFile(PATH_LANG.'xml/ru_slider.xml');
      fsFunctions::DeleteFile(PATH_LANG.'xml/en_slider.xml');
      fsFunctions::DeleteFile(PATH_LANG.'ru-to-en/Slider.php');
      fsFunctions::DeleteFile(PATH_LANG.'en-to-ru/Slider.php');
      $this->_table->Drop()->Execute();
      $tableSliedes = new fsDBTable('slides');
      $tableSliedes->Drop()->Execute();
  }
}