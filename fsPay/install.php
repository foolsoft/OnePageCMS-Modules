<?php


$db->Query("

CREATE TABLE IF NOT EXISTS `".fsConfig::GetInstance('db_prefix')."pay_comepay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_payed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` varchar(25) NOT NULL,
  `system` varchar(50) NOT NULL,
  `id_operation` int(11) NOT NULL COMMENT '".fsConfig::GetInstance('db_prefix')."pay_type_operations:name:id#',
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `afterpay` enum('0','1') NOT NULL DEFAULT '0',
  `sum` float NOT NULL,
  `contact` varchar(200) NOT NULL,
  `comment` text NOT NULL,
  `message` varchar(255) NOT NULL DEFAULT '-',
  `creator` varchar(50) NOT NULL DEFAULT 'this',
  `language` varchar(5) NOT NULL,
  `hash` varchar(125) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=".fsConfig::GetInstance('db_codepage').";

");


$db->Query("

CREATE TABLE IF NOT EXISTS `".fsConfig::GetInstance('db_prefix')."pay_type_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(125) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=".fsConfig::GetInstance('db_codepage').";


");



$db->Query("
  INSERT INTO `".fsConfig::GetInstance('db_prefix')."pay_type_operations` (`id`, `name`) VALUES
    (1, 'Оплата товаров'),
    (2, 'Оплата услуг');
");
?>