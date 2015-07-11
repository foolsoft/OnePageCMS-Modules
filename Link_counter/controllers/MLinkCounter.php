<?php
class MLinkCounter extends cmsController
{
  protected $_tableName = 'link_counter';

  public function actionOpen($param)
  {
    $this->Redirect(fsHtml::Url(URL_ROOT.'404'));
    if (!$param->Exists('id', true)) {
      return;
    }
    $link = $this->_table->GetField('link', $param->id);
    if ($link == '') {
      return;
    }
    $this->Redirect($link);
    $this->_table->Plus($param->id);    
  }
  
  public function Counter($param)
  {
    if (!$param->Exists('id', true)) {
      return '';
    }
    return $this->_table->GetCount($param->id);
  }
}