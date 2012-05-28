CREATE TABLE `related_entries` (
  `entry_id` int(11) unsigned NOT NULL,
  `entry_model` varchar(128) NOT NULL DEFAULT '',
  `related_id` int(11) unsigned NOT NULL,
  `related_model` varchar(128) NOT NULL DEFAULT '',
  UNIQUE KEY `entry_model` (`entry_model`,`entry_id`,`related_id`,`related_model`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;