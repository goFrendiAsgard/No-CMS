CREATE TABLE `{{ complete_table_name:componentes }}` (
  `num_int` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50),
  `description` text,
  PRIMARY KEY (`num_int`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:productos }}` (
  `num_int` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50),
  PRIMARY KEY (`num_int`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:rel_com_pro }}` (
  `id_rel` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_com` int(10),
  `id_pro` int(10),
  `quantity` int(10),
  PRIMARY KEY (`id_rel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;