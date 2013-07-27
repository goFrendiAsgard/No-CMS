CREATE TABLE `{{ complete_table_name:slideshow }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(100),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:tab }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tittle` varchar(50),
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;