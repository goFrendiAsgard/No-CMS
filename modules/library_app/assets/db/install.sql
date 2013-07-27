CREATE TABLE `{{ complete_table_name:book }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isbn` varchar(20),
  `title` varchar(100),
  `author` varchar(50),
  `publisher` varchar(50),
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:book_category }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_category` int(10),
  `id_book` int(10),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:borrowing }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_member` int(10),
  `borrow_date` date,
  `return_date` date,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:borrowing_detail }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_borrowing` int(10),
  `id_physical_book` int(10),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:category }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:member }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50),
  `address` varchar(100),
  `phone` varchar(30),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:physical_book }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `book_id` int(10),
  `code` varchar(50),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;