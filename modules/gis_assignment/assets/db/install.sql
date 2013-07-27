CREATE TABLE `{{ complete_table_name:final }}` (
  `final_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(255),
  `description` text,
  `student_1_nrp` varchar(20),
  `student_1_name` varchar(50),
  `student_2_nrp` varchar(20),
  `student_2_name` varchar(50),
  `student_3_nrp` varchar(20),
  `student_3_name` varchar(50),
  `status` enum('approved','in progress (data)','in progress (coding)','report','finish'),
  PRIMARY KEY (`final_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:orphanage }}` (
  `orphanage_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uploader_nrp` varchar(12),
  `orphanage_name` varchar(50),
  `longitude` double,
  `latitude` double,
  `address` varchar(50),
  `phone` varchar(20),
  `religion_id` int(10),
  `website` varchar(50),
  `min_age` int(10),
  `max_age` int(10),
  `history` text,
  `organization` varchar(50),
  `facility` varchar(50),
  `public_transportation` varchar(50),
  `other_description` text,
  `photo_1` varchar(100),
  `photo_2` varchar(100),
  `photo_3` varchar(100),
  `gender` enum('male','female','both'),
  PRIMARY KEY (`orphanage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:religion }}` (
  `religion_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20),
  PRIMARY KEY (`religion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;