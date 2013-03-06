DROP TABLE IF EXISTS `cms_blg_photo`; 
DROP TABLE IF EXISTS `cms_blg_comment`; 
DROP TABLE IF EXISTS `cms_blg_category_article`; 
DROP TABLE IF EXISTS `cms_blg_category`; 
DROP TABLE IF EXISTS `cms_blg_article`; 

#
# TABLE STRUCTURE FOR: cms_blg_article
#

CREATE TABLE `cms_blg_article` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_title` varchar(100) DEFAULT NULL,
  `article_url` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `author_user_id` int(10) DEFAULT NULL,
  `content` text,
  `allow_comment` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO cms_blg_article (`article_id`, `article_title`, `article_url`, `date`, `author_user_id`, `content`, `allow_comment`) VALUES (2, 'coba', 'coba', '2013-03-05 14:11:56', 1, '<p>\n	coba</p>\n', 1);


#
# TABLE STRUCTURE FOR: cms_blg_category
#

CREATE TABLE `cms_blg_category` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO cms_blg_category (`category_id`, `category_name`, `description`) VALUES (1, 'fun', NULL);
INSERT INTO cms_blg_category (`category_id`, `category_name`, `description`) VALUES (2, 'science', NULL);


#
# TABLE STRUCTURE FOR: cms_blg_category_article
#

CREATE TABLE `cms_blg_category_article` (
  `category_id` int(10) DEFAULT NULL,
  `article_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO cms_blg_category_article (`category_id`, `article_id`) VALUES (1, 2);


#
# TABLE STRUCTURE FOR: cms_blg_comment
#

CREATE TABLE `cms_blg_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `author_user_id` int(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO cms_blg_comment (`comment_id`, `article_id`, `date`, `author_user_id`, `name`, `email`, `website`, `content`) VALUES (1, 2, NULL, 1, 'coba', 'coba@coba.com', 'coba.com', 'coba coba');
INSERT INTO cms_blg_comment (`comment_id`, `article_id`, `date`, `author_user_id`, `name`, `email`, `website`, `content`) VALUES (2, 2, NULL, 1, 'lagi', 'comment._lagi@com', '_lagi.com', 'coba lagi');


#
# TABLE STRUCTURE FOR: cms_blg_photo
#

CREATE TABLE `cms_blg_photo` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

