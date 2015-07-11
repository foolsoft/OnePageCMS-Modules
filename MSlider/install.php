<?php
//Not requared file
//Some install code. Database query using $db - fsDBConnection object;
//Example:
/*
$db->Query("
     CREATE TABLE IF NOT EXISTS `".fsConfig::Get('db_prefix')."new_table` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=".fsConfig::Get('db_codepage').";
    ");
*/
$db->Query("
CREATE TABLE IF NOT EXISTS `".fsConfig::GetInstance('db_prefix')."sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `template` varchar(50) NOT NULL,
  `width` int(11) NOT NULL DEFAULT '100',
  `height` int(11) NOT NULL DEFAULT '25',
  `width_unit` varchar(5) NOT NULL DEFAULT 'px',
  `navigation` enum('0','1') NOT NULL DEFAULT '1',
  `interval` int(11) NOT NULL DEFAULT '5000',
  `animation` varchar(25) NOT NULL DEFAULT 'slide',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=".fsConfig::GetInstance('db_codepage').";
");
$db->Query("
CREATE TABLE IF NOT EXISTS `".fsConfig::GetInstance('db_prefix')."slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_slider` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `html` text NOT NULL,
  `href` text NOT NULL,
  `alt` varchar(125) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=".fsConfig::GetInstance('db_codepage').";
");