<?php 
class MSlides extends cmsController
{
  protected $_tableName = 'sliders';
  private $_pathImages, $_urlImages; 

  public function Init($request)
  {
    $this->_pathImages = PATH_IMG.'slides/';
    $this->_urlImages = URL_IMG.'slides/';
    parent::Init($request);
  }
  
  public function Slider($param)
  {
    $result = '';
    if(!$param->Exists('id', true)) {
      return $result;
    }
    $this->_table->Select()->Where('`id` = "'.$param->id.'"')->Execute();
    if($param->id != $this->_table->result->id) {
      return $result;
    }
    
    $this->Tag('id', $param->id);
    $this->Tag('width', $this->_table->result->width);
    $this->Tag('width_unit', $this->_table->result->width_unit);
    $this->Tag('height', $this->_table->result->height);
    $this->Tag('path', $this->_urlImages.$param->id.'/');
    $this->Tag('navigation', $this->_table->result->navigation);
    $this->Tag('interval', $this->_table->result->interval);
    $this->Tag('animation', $this->_table->result->animation);
       
    $tableSliedes = new fsDBTable('slides');
    $slides = $tableSliedes->Select()->Where('`id_slider` = "'.$param->id.'"')
              ->Order(array('order'))->ExecuteToArray();

    $this->Tag('count', count($slides));
    
    $result = $this->CreateView(array('slides' => $slides), 
      PATH_TPL.CMSSettings::GetInstance('template').'/MSlides/'.$this->_table->result->template);     
    
    $this->_AddMyScriptsAndStyles(true, true, URL_JS, URL_THEME_CSS);
    
    return $result;
  }
}