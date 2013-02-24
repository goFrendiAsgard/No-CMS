DROP TABLE IF EXISTS `blog_photo`; 
DROP TABLE IF EXISTS `blog_comment`; 
DROP TABLE IF EXISTS `blog_category_article`; 
DROP TABLE IF EXISTS `blog_article`; 
DROP TABLE IF EXISTS `blog_category`; 

#
# TABLE STRUCTURE FOR: blog_category
#

CREATE TABLE `blog_category` (
  `category_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO blog_category (`category_id`, `category_name`, `description`) VALUES (1, 'science', NULL);
INSERT INTO blog_category (`category_id`, `category_name`, `description`) VALUES (2, 'fun', NULL);


#
# TABLE STRUCTURE FOR: blog_article
#

CREATE TABLE `blog_article` (
  `article_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_title` varchar(100) NOT NULL,
  `article_url` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `author_user_id` int(20) unsigned NOT NULL,
  `content` text,
  `allow_comment` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`),
  UNIQUE KEY `article_title` (`article_title`),
  UNIQUE KEY `article_url` (`article_url`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO blog_article (`article_id`, `article_title`, `article_url`, `date`, `author_user_id`, `content`, `allow_comment`) VALUES (1, 'article 1', 'article-1_1', '2013-02-12 07:20:58', 1, '<p>\n	artikel satu</p>\n<div style=\"page-break-after: always;\">\n	<span style=\"display: none;\">&nbsp;</span></div>\n<p>\n	ini akan muncul setelah read more di klik</p>\n', 0);
INSERT INTO blog_article (`article_id`, `article_title`, `article_url`, `date`, `author_user_id`, `content`, `allow_comment`) VALUES (2, 'article 2', 'article-2', '2013-02-07 15:44:10', 1, '<p>\n	artikel 2</p>\n<div style=\"page-break-after: always;\">\n	<span style=\"display: none;\">&nbsp;</span></div>\n<p>\n	lain-lain</p>\n', 1);


#
# TABLE STRUCTURE FOR: blog_category_article
#

CREATE TABLE `blog_category_article` (
  `category_id` int(20) unsigned NOT NULL,
  `article_id` int(20) unsigned NOT NULL,
  PRIMARY KEY (`category_id`,`article_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `blog_category_article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `blog_category` (`category_id`),
  CONSTRAINT `blog_category_article_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `blog_article` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO blog_category_article (`category_id`, `article_id`) VALUES (2, 1);


#
# TABLE STRUCTURE FOR: blog_comment
#

CREATE TABLE `blog_comment` (
  `comment_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `author_user_id` int(20) unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: blog_photo
#

CREATE TABLE `blog_photo` (
  `photo_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(20) unsigned NOT NULL,
  `url` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO blog_photo (`photo_id`, `article_id`, `url`) VALUES (3, 1, '9bd30-scandalbox.jpg');
INSERT INTO blog_photo (`photo_id`, `article_id`, `url`) VALUES (4, 1, 'b1f20-si_5097488_vjjnjbm6s1_lr.jpg');
INSERT INTO blog_photo (`photo_id`, `article_id`, `url`) VALUES (5, 1, '8e66a-scandal_thumb.jpg');


