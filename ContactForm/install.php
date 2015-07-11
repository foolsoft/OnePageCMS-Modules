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
CREATE TABLE IF NOT EXISTS `".fsConfig::GetInstance('db_prefix')."contact_forms` (
  `name` varchar(50) NOT NULL,
  `title` varchar(125) NOT NULL,
  `title_user` varchar(125) NOT NULL,
  `mail` varchar(75) NOT NULL,
  `mail_user` enum('0','1') NOT NULL DEFAULT '0',
  `tpl` text NOT NULL,
  `message` text NOT NULL,
  `message_to_user` text NOT NULL,
  `ajax` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=".fsConfig::GetInstance('db_codepage').";
");