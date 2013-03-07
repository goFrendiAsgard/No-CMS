DROP TABLE IF EXISTS `cms_blog_photo`; 
DROP TABLE IF EXISTS `cms_blog_comment`; 
DROP TABLE IF EXISTS `cms_blog_category_article`; 
DROP TABLE IF EXISTS `cms_blog_category`; 
DROP TABLE IF EXISTS `cms_blog_article`; 

#
# TABLE STRUCTURE FOR: cms_blog_article
#

CREATE TABLE `cms_blog_article` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_title` varchar(100) DEFAULT NULL,
  `article_url` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `author_user_id` int(10) DEFAULT NULL,
  `content` text,
  `allow_comment` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_blog_category
#

CREATE TABLE `cms_blog_category` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO cms_blog_category (`category_id`, `category_name`, `description`) VALUES (1, 'science', 'all about science');
INSERT INTO cms_blog_category (`category_id`, `category_name`, `description`) VALUES (2, 'fun', 'funny things');


#
# TABLE STRUCTURE FOR: cms_blog_category_article
#

CREATE TABLE `cms_blog_category_article` (
  `category_id` int(10) DEFAULT NULL,
  `article_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_blog_comment
#

CREATE TABLE `cms_blog_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `author_user_id` int(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: cms_blog_photo
#

CREATE TABLE `cms_blog_photo` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

