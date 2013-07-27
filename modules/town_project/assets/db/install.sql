CREATE TABLE `{{ complete_table_name:citizen }}` (
  `citizen_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `town_id` int(10),
  `name` varchar(255),
  PRIMARY KEY (`citizen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:commodity }}` (
  `commodity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50),
  PRIMARY KEY (`commodity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:country }}` (
  `country_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20),
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:town }}` (
  `town_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(10),
  `name` varchar(50),
  PRIMARY KEY (`town_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:town_commodity }}` (
  `town_id` int(10),
  `commodity_id` int(10),
  `priority` int(10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;