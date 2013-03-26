CREATE TABLE `{{ complete_table_name:citizen }}` (
  `citizen_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10),
  `name` varchar(50),
  `birthdate` date,
  `job_id` int(10),
  PRIMARY KEY (`citizen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:job }}` (
  `job_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20),
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:hobby }}` (
  `hobby_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20),
  PRIMARY KEY (`hobby_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:country }}` (
  `country_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20),
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:commodity }}` (
  `commodity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20),
  PRIMARY KEY (`commodity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:city_tourism }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10),
  `tourism_id` int(10),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:city_commodity }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10),
  `commodity_id` int(10),
  `priority` int(10),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:city }}` (
  `city_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(10),
  `name` varchar(20),
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:citizen_hobby }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `citizen_id` int(10),
  `hobby_id` int(10),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:tourism }}` (
  `tourism_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20),
  PRIMARY KEY (`tourism_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;