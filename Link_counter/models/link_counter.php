<?php
class link_counter extends fsDBTableExtension
{
  public function __destruct()
  {
    parent::__destruct();
  }
  
  function GetClick($id)
  {
    $this->Select(array('count'))->Where('`id` = "'.$id.'"')->Execute();
    return $this->_result->count;
  }
  
  function Plus($id, $count = '+1')
  {
    return $this->Execute(fsFunctions::StringFormat(
            'UPDATE `{0}` SET `count` = `count` {1} WHERE `id` = "{2}"',
            array(
                $this->_struct->name,
                $count,
                $id
            )
    ));
  }
}