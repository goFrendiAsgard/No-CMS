DROP TABLE IF EXISTS `cms_twn_town_commodity`; 
DROP TABLE IF EXISTS `cms_twn_town`; 
DROP TABLE IF EXISTS `cms_twn_job`; 
DROP TABLE IF EXISTS `cms_twn_hobby`; 
DROP TABLE IF EXISTS `cms_twn_country`; 
DROP TABLE IF EXISTS `cms_twn_commodity`; 
DROP TABLE IF EXISTS `cms_twn_citizen_hobby`; 
DROP TABLE IF EXISTS `cms_twn_citizen`; 

#
# TABLE STRUCTURE FOR: cms_twn_citizen
#

CREATE TABLE `cms_twn_citizen` (
  `citizen_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `town_id` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `job_id` int(10) DEFAULT NULL,
  `IQ` int(10) DEFAULT NULL,
  `Capita` float DEFAULT NULL,
  PRIMARY KEY (`citizen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_twn_citizen_hobby
#

CREATE TABLE `cms_twn_citizen_hobby` (
  `citizen_hobby_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `citizen_id` int(10) DEFAULT NULL,
  `hobby_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`citizen_hobby_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_twn_commodity
#

CREATE TABLE `cms_twn_commodity` (
  `commodity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`commodity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (1, 'Coal', 'mines');
INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (2, 'Crude', 'mines');
INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (3, 'tomato', 'vegetables,fruit');
INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (4, 'apple', 'fruit');
INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (5, 'beef', 'livestock');


#
# TABLE STRUCTURE FOR: cms_twn_country
#

CREATE TABLE `cms_twn_country` (
  `country_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_country (`country_id`, `name`) VALUES (1, 'Indonesia');
INSERT INTO cms_twn_country (`country_id`, `name`) VALUES (2, 'Japan');
INSERT INTO cms_twn_country (`country_id`, `name`) VALUES (3, 'USA');
INSERT INTO cms_twn_country (`country_id`, `name`) VALUES (4, 'Greece');


#
# TABLE STRUCTURE FOR: cms_twn_hobby
#

CREATE TABLE `cms_twn_hobby` (
  `hobby_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`hobby_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_hobby (`hobby_id`, `name`) VALUES (1, 'Fishing');
INSERT INTO cms_twn_hobby (`hobby_id`, `name`) VALUES (2, 'Reading');
INSERT INTO cms_twn_hobby (`hobby_id`, `name`) VALUES (3, 'Writing');
INSERT INTO cms_twn_hobby (`hobby_id`, `name`) VALUES (4, 'Trolling');


#
# TABLE STRUCTURE FOR: cms_twn_job
#

CREATE TABLE `cms_twn_job` (
  `job_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_job (`job_id`, `name`) VALUES (1, 'Scientist');
INSERT INTO cms_twn_job (`job_id`, `name`) VALUES (2, 'Programmer');
INSERT INTO cms_twn_job (`job_id`, `name`) VALUES (3, 'Doctor');
INSERT INTO cms_twn_job (`job_id`, `name`) VALUES (4, 'Teacher');


#
# TABLE STRUCTURE FOR: cms_twn_town
#

CREATE TABLE `cms_twn_town` (
  `town_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`town_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_twn_town_commodity
#

CREATE TABLE `cms_twn_town_commodity` (
  `town_commodity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `town_id` int(10) DEFAULT NULL,
  `commodity_id` int(10) DEFAULT NULL,
  `priority` int(10) DEFAULT NULL,
  PRIMARY KEY (`town_commodity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

