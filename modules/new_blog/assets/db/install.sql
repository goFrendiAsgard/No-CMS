CREATE TABLE `{{ complete_table_name:article }}` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_title` varchar(100),
  `article_url` varchar(100),
  `date` datetime,
  `author_user_id` int(10),
  `content` text,
  `allow_comment` tinyint(3),
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:category }}` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100),
  `description` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:category_article }}` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:comment }}` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10),
  `date` date,
  `author_user_id` int(10),
  `name` varchar(50),
  `email` varchar(50),
  `website` varchar(50),
  `content` text,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:photo }}` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10),
  `url` varchar(50),
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;