DROP TABLE IF EXISTS `cms_nordrassil_column_option`; 
DROP TABLE IF EXISTS `cms_nordrassil_column`; 
DROP TABLE IF EXISTS `cms_nordrassil_table_option`; 
DROP TABLE IF EXISTS `cms_nordrassil_table`; 
DROP TABLE IF EXISTS `cms_nordrassil_project_option`; 
DROP TABLE IF EXISTS `cms_nordrassil_project`; 
DROP TABLE IF EXISTS `cms_nordrassil_template_option`; 
DROP TABLE IF EXISTS `cms_nordrassil_template`; 

#
# TABLE STRUCTURE FOR: cms_nordrassil_template
#

CREATE TABLE `cms_nordrassil_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `generator_path` varchar(100) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO cms_nordrassil_template (`template_id`, `name`, `generator_path`) VALUES (1, 'No-CMS default Module', 'nordrassil/default_generator/generator/index');


#
# TABLE STRUCTURE FOR: cms_nordrassil_template_option
#

CREATE TABLE `cms_nordrassil_template_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `option_type` varchar(50) NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO cms_nordrassil_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (1, 1, 'dont_make_form', 'make form for this table', 'table');
INSERT INTO cms_nordrassil_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (2, 1, 'dont_create_table', 'don\'t create/drop table on installation', 'table');
INSERT INTO cms_nordrassil_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (3, 1, 'make_frontpage', 'Make front page for this table', 'table');
INSERT INTO cms_nordrassil_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (4, 1, 'import_data', 'Also create insert statement (e.g: for configuration table)', 'table');
INSERT INTO cms_nordrassil_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (5, 1, 'hide', 'shown', 'column');


#
# TABLE STRUCTURE FOR: cms_nordrassil_project
#

CREATE TABLE `cms_nordrassil_project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `db_server` varchar(50) NOT NULL,
  `db_port` varchar(50) NOT NULL,
  `db_schema` varchar(50) NOT NULL,
  `db_user` varchar(50) NOT NULL,
  `db_password` varchar(50) NOT NULL,
  `db_table_prefix` varchar(50) NOT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: cms_nordrassil_project_option
#

CREATE TABLE `cms_nordrassil_project_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: cms_nordrassil_table
#

CREATE TABLE `cms_nordrassil_table` (
  `table_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: cms_nordrassil_table_option
#

CREATE TABLE `cms_nordrassil_table_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: cms_nordrassil_column
#

CREATE TABLE `cms_nordrassil_column` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `data_type` varchar(50) NOT NULL,
  `data_size` int(11) DEFAULT NULL,
  `role` varchar(50) NOT NULL,
  `lookup_table_id` int(11) NOT NULL,
  `lookup_column_id` int(11) DEFAULT NULL,
  `relation_table_id` int(11) DEFAULT NULL,
  `relation_table_column_id` int(11) DEFAULT NULL,
  `relation_selection_column_id` int(11) DEFAULT NULL,
  `relation_priority_column_id` int(11) DEFAULT NULL,
  `selection_table_id` int(11) DEFAULT NULL,
  `selection_column_id` int(11) DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `value_selection_mode` varchar(50) DEFAULT NULL,
  `value_selection_item` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`column_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: cms_nordrassil_column_option
#

CREATE TABLE `cms_nordrassil_column_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

