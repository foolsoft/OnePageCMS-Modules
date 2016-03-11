<?php
$MNAME = 'PayController'; //Имя класса модуля
$SETTINGS = array(
  // ассоциативный массив настроек модуля, например  "setting1" => "value1"
  'notify_email' => '',
  'main_template' => 'index',
  'dynamic_create' => 0,
  'secret' => '!Secret string#',
  'inform_template' => 'Новый платеж #{id}',
  'ok_template' => 'Поступила оплата счета #{id}' 
  );

//Следующие две переменные необходимы если нужен пункт в панели администратора (файл "controllers/Admin".$MNAME.".php" - в данном случае обязателен)
$MADMIN_START = 'Index'; //Главный метод в панели администратора
$MTEXT = 'Online оплата'; //Текст ссылки в панели администратора

$MENU = array (  
  //"Пункт в меню" => "Ссылка пункта" в виде ИмяМодуля/Метод 
  'Создание платежа' => 'Pay/Create'
);
?>
