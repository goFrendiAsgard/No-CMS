CREATE TABLE `nds_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `generator_path` varchar(100) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*split*/
CREATE TABLE `nds_template_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `option_type` varchar(50) NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*split*/
CREATE TABLE `nds_project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `db_server` varchar(50) NOT NULL,
  `db_port` varchar(50) NOT NULL,
  `db_schema` varchar(50) NOT NULL,
  `db_user` varchar(50) NOT NULL,
  `db_password` varchar(50) NOT NULL,  
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*split*/
CREATE TABLE `nds_project_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*split*/
CREATE TABLE `nds_table` (
  `table_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*split*/
CREATE TABLE `nds_table_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*split*/
CREATE TABLE `nds_column` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `data_type` varchar(50) NOT NULL,
  `data_size` int(11) NULL,
  `role` varchar(50) NOT NULL,
  `lookup_table_id` int(11) NOT NULL,
  `lookup_column_id` int(11) NULL,
  `relation_table_id` int(11) NULL,
  `relation_table_column_id` int(11) NULL,
  `relation_selection_column_id` int(11) NULL,
  `relation_priority_column_id` int(11) NULL,
  `selection_table_id` int(11) NULL,
  `selection_column_id` int(11) NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`column_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*split*/
CREATE TABLE `nds_column_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*split*/
CREATE TABLE `nds_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
