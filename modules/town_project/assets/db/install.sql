CREATE TABLE `{{ complete_table_name:citizen }}` (
  `citizen_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `town_id` int(10),
  `name` varchar(255),
  `birthdate` date,
  `job_id` int(10),
  `IQ` int(10),
  `Capita` float,
  PRIMARY KEY (`citizen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:citizen_hobby }}` (
  `citizen_hobby_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `citizen_id` int(10),
  `hobby_id` int(10),
  PRIMARY KEY (`citizen_hobby_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:commodity }}` (
  `commodity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50),
  `type` varchar(255),
  PRIMARY KEY (`commodity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:country }}` (
  `country_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20),
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:hobby }}` (
  `hobby_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50),
  PRIMARY KEY (`hobby_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:job }}` (
  `job_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50),
  PRIMARY KEY (`job_id`)
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
  `town_commodity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `town_id` int(10),
  `commodity_id` int(10),
  `priority` int(10),
  PRIMARY KEY (`town_commodity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/
INSERT INTO `{{ complete_table_name:commodity }}` (`commodity_id`, `name`, `type`) VALUES
('1', 'Coal', 'mines'),
('2', 'Crude', 'mines'),
('3', 'tomato', 'vegetables,fruit'),
('4', 'apple', 'fruit'),
('5', 'beef', 'livestock');

/*split*/

INSERT INTO `{{ complete_table_name:country }}` (`country_id`, `name`) VALUES
('1', 'Indonesia'),
('2', 'Japan'),
('3', 'USA'),
('4', 'Greece');

/*split*/

INSERT INTO `{{ complete_table_name:hobby }}` (`hobby_id`, `name`) VALUES
('1', 'Fishing'),
('2', 'Reading'),
('3', 'Writing'),
('4', 'Trolling');

/*split*/

INSERT INTO `{{ complete_table_name:job }}` (`job_id`, `name`) VALUES
('1', 'Scientist'),
('2', 'Programmer'),
('3', 'Doctor'),
('4', 'Teacher');