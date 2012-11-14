CREATE TABLE `blog_category` (
  `category_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/
CREATE TABLE `blog_article_lang` (
  `article_lang_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(20) unsigned NOT NULL,
  `lang` varchar(100) NOT NULL,
  `content` text,
  PRIMARY KEY (`article_lang_id`),
  UNIQUE KEY `article_lang` (`article_lang_id`, `article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/
CREATE TABLE `blog_category_article` (
  `category_id` int(20) unsigned NOT NULL,
  `article_id` int(20) unsigned NOT NULL,
  PRIMARY KEY (`category_id`,`article_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `blog_category_article_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `blog_article` (`article_id`),
  CONSTRAINT `blog_category_article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `blog_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*split*/
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
/*split*/
CREATE TABLE `blog_photo` (
  `photo_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(20) unsigned NOT NULL,
  `url` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
