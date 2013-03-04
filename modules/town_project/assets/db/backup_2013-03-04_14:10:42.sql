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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_citizen (`citizen_id`, `town_id`, `name`, `birthdate`, `job_id`, `IQ`, `Capita`) VALUES (2, 2, 'Umar Bakri', '2013-03-18', 5, 130, '200');
INSERT INTO cms_twn_citizen (`citizen_id`, `town_id`, `name`, `birthdate`, `job_id`, `IQ`, `Capita`) VALUES (4, 2, 'Bo', '2013-03-16', 8, 150, '150');


#
# TABLE STRUCTURE FOR: cms_twn_citizen_hobby
#

CREATE TABLE `cms_twn_citizen_hobby` (
  `citizen_hobby_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `citizen_id` int(10) DEFAULT NULL,
  `hobby_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`citizen_hobby_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_citizen_hobby (`citizen_hobby_id`, `citizen_id`, `hobby_id`) VALUES (1, 3, 5);
INSERT INTO cms_twn_citizen_hobby (`citizen_hobby_id`, `citizen_id`, `hobby_id`) VALUES (2, 3, 7);
INSERT INTO cms_twn_citizen_hobby (`citizen_hobby_id`, `citizen_id`, `hobby_id`) VALUES (3, 2, 5);
INSERT INTO cms_twn_citizen_hobby (`citizen_hobby_id`, `citizen_id`, `hobby_id`) VALUES (4, 2, 7);
INSERT INTO cms_twn_citizen_hobby (`citizen_hobby_id`, `citizen_id`, `hobby_id`) VALUES (5, 4, 6);


#
# TABLE STRUCTURE FOR: cms_twn_commodity
#

CREATE TABLE `cms_twn_commodity` (
  `commodity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`commodity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (6, 'crude', 'mines');
INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (7, 'beef', 'livestock');
INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (8, 'fish', 'maritime');
INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (9, 'tomato', 'vegetables,fruit');
INSERT INTO cms_twn_commodity (`commodity_id`, `name`, `type`) VALUES (10, 'robot', 'industrial');


#
# TABLE STRUCTURE FOR: cms_twn_country
#

CREATE TABLE `cms_twn_country` (
  `country_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_country (`country_id`, `name`) VALUES (5, 'Indonesia');
INSERT INTO cms_twn_country (`country_id`, `name`) VALUES (6, 'Japan');
INSERT INTO cms_twn_country (`country_id`, `name`) VALUES (7, 'USA');


#
# TABLE STRUCTURE FOR: cms_twn_hobby
#

CREATE TABLE `cms_twn_hobby` (
  `hobby_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`hobby_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_hobby (`hobby_id`, `name`) VALUES (5, 'Fishing');
INSERT INTO cms_twn_hobby (`hobby_id`, `name`) VALUES (6, 'Reading');
INSERT INTO cms_twn_hobby (`hobby_id`, `name`) VALUES (7, 'Swimming');
INSERT INTO cms_twn_hobby (`hobby_id`, `name`) VALUES (8, 'Singing');
INSERT INTO cms_twn_hobby (`hobby_id`, `name`) VALUES (9, 'Flying');


#
# TABLE STRUCTURE FOR: cms_twn_job
#

CREATE TABLE `cms_twn_job` (
  `job_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_job (`job_id`, `name`) VALUES (5, 'Scientist');
INSERT INTO cms_twn_job (`job_id`, `name`) VALUES (6, 'Programmer');
INSERT INTO cms_twn_job (`job_id`, `name`) VALUES (7, 'Doctor');
INSERT INTO cms_twn_job (`job_id`, `name`) VALUES (8, 'Teacher');


#
# TABLE STRUCTURE FOR: cms_twn_town
#

CREATE TABLE `cms_twn_town` (
  `town_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`town_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_town (`town_id`, `country_id`, `name`) VALUES (2, 5, 'Malang');


#
# TABLE STRUCTURE FOR: cms_twn_town_commodity
#

CREATE TABLE `cms_twn_town_commodity` (
  `town_commodity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `town_id` int(10) DEFAULT NULL,
  `commodity_id` int(10) DEFAULT NULL,
  `priority` int(10) DEFAULT NULL,
  PRIMARY KEY (`town_commodity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO cms_twn_town_commodity (`town_commodity_id`, `town_id`, `commodity_id`, `priority`) VALUES (4, 2, 7, 0);
INSERT INTO cms_twn_town_commodity (`town_commodity_id`, `town_id`, `commodity_id`, `priority`) VALUES (5, 2, 8, 1);
INSERT INTO cms_twn_town_commodity (`town_commodity_id`, `town_id`, `commodity_id`, `priority`) VALUES (6, 2, 9, 2);


