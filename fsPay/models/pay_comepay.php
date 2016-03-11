<?php
class pay_comepay extends fsDBTableExtension
{
  public function __destruct()
  {
    parent::__destruct();
  }
  
  public function GetNotAfterPay($contact)
  {
    return $this->Select()->Where('`status` = "2" AND afterpay = "0" AND contact = "'.$contact.'"')->ExecuteToArray();
  }

  public function ChangeSystem($idBill, $idUser, $system)
  {
    $this->Update(array('system'), array($system))->Where(array(
        array('id' => (int)$idBill),
        array('contanct' => (int)$idUser),
    ))->Execute();
  }

  public function BillExists($idUser, $idTrack, $idVersion)
  {
    $this->Select()->Where(array(
        array('contact' => (int)$idUser),
        array('comment' => '%'.$idTrack.'-'.$idVersion, 'key' => 'LIKE'),
    ))->Execute();
    return is_numeric($this->result->id) && $this->result->id > 0 ? $this->result->id : 0;
  }

  public function SetPayed($id)
  {
    $this->Update(array('message', 'date_payed', 'status'), array('OK', date("Y-m-d H:m:i"), 2))
        ->Where('id = "'.((int)$id).'"')->Execute();
  }

  public function LoadPays($order, $desc, $search)
  {
    $this->Select('*', true)->Order(array($order), array($desc));
    $where = array();
    if ($search->Exists('id', true)) {
        $where[] = array('id' => $search->id);            
    }
    if ($search->contact != '') {
        $where[] = array('contact' => $search->contact);            
    }
    if ($search->count > 0 && count($where) == 0) {
        $this->Limit($search->count, $search->start);
    }
    if (count($where) > 0) {
        $this->Where($where);
    }
    return $this->ExecuteToArray();
  }
  
  public function LoadUserPays($userId)
  {
    return $this->Select('*', true)
        ->Order(array('date_created'), array(true))
        ->Where('`contact` = "'.$userId.'"')->ExecuteToArray();
  }
  
  public function Add($system, $operation, $sum, $contact, $creator = 'this', $comment = '', $language = null)
  {
     $this->ip = fsFunctions::GetIp();
     $this->system = $system;
     $this->id_operation = $operation;
     $this->sum = $sum;
     $this->contact = $contact; 
     $this->language = $language === null ? fsSession::GetInstance('Language') : $language; 
     $this->creator = $creator;
     $this->comment = $comment;
     $this->Insert()->Execute();
     return $this->insertedId;
  }

} 