<?php
include 'classes.php';
$s = new SoapServer('IShopClientWS.wsdl', array('classmap' => array('tns:updateBill' => 'SoapQiwiParam', 'tns:updateBillResponse' => 'SoapQiwiResponse')));
$s->setClass('QiwiSoap');
$s->handle();
?>