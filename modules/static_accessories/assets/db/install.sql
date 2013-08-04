CREATE TABLE `{{ complete_table_name:slide }}` (
  `slide_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image_url` varchar(100),
  `content` text,
  PRIMARY KEY (`slide_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:tab_content }}` (
  `tab_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `caption` varchar(50),
  `content` text,
  PRIMARY KEY (`tab_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:visitor_counter }}` (
  `counter_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(20),
  `time` datetime,
  `agent` varchar(100),
  PRIMARY KEY (`counter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;