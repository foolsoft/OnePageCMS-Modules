<?php
$db->query("
     CREATE TABLE IF NOT EXISTS `".fsConfig::GetInstance('db_prefix')."link_counter` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `count` int(11) NOT NULL DEFAULT '0',
        `link` varchar(500) NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=".fsConfig::GetInstance('db_codepage').";
    ");