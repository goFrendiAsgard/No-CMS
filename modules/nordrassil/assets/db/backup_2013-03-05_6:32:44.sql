DROP TABLE IF EXISTS `nds_column_option`; 
DROP TABLE IF EXISTS `nds_column`; 
DROP TABLE IF EXISTS `nds_table_option`; 
DROP TABLE IF EXISTS `nds_table`; 
DROP TABLE IF EXISTS `nds_project_option`; 
DROP TABLE IF EXISTS `nds_project`; 
DROP TABLE IF EXISTS `nds_template_option`; 
DROP TABLE IF EXISTS `nds_template`; 

#
# TABLE STRUCTURE FOR: nds_template
#

CREATE TABLE `nds_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `generator_path` varchar(100) NOT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO nds_template (`template_id`, `name`, `generator_path`) VALUES (1, 'No-CMS default Module', 'nordrassil/default_generator/generator/index');


#
# TABLE STRUCTURE FOR: nds_template_option
#

CREATE TABLE `nds_template_option` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `option_type` enum('project','table','column') NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO nds_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (1, 1, 'dont_make_form', 'make form for this table', 'table');
INSERT INTO nds_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (2, 1, 'dont_create_table', 'don\'t create/drop table on installation', 'table');
INSERT INTO nds_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (3, 1, 'make_frontpage', 'Make front page for this table', 'table');
INSERT INTO nds_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (4, 1, 'import_data', 'Also create insert statement (e.g: for configuration table)', 'table');
INSERT INTO nds_template_option (`option_id`, `template_id`, `name`, `description`, `option_type`) VALUES (5, 1, 'hide', 'shown', 'column');


#
# TABLE STRUCTURE FOR: nds_project
#

CREATE TABLE `nds_project` (
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO nds_project (`project_id`, `template_id`, `name`, `db_server`, `db_port`, `db_schema`, `db_user`, `db_password`, `db_table_prefix`) VALUES (2, 1, 'new nordrassil', 'localhost', '3306', 'no_cms', 'root', 'toor', 'nds');
INSERT INTO nds_project (`project_id`, `template_id`, `name`, `db_server`, `db_port`, `db_schema`, `db_user`, `db_password`, `db_table_prefix`) VALUES (3, 1, 'new blog', 'localhost', '3306', 'no_cms', 'root', 'toor', 'blog');


#
# TABLE STRUCTURE FOR: nds_project_option
#

CREATE TABLE `nds_project_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# TABLE STRUCTURE FOR: nds_table
#

CREATE TABLE `nds_table` (
  `table_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (10, 2, 'nds_column', 'Column', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (11, 2, 'nds_column_option', 'Column Option', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (12, 2, 'nds_project', 'Project', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (13, 2, 'nds_project_option', 'Project Option', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (14, 2, 'nds_table', 'Table', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (15, 2, 'nds_table_option', 'Table Option', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (16, 2, 'nds_template', 'Template', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (17, 2, 'nds_template_option', 'Template Option', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (18, 3, 'blog_article', 'Article', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (19, 3, 'blog_category', 'Category', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (20, 3, 'blog_category_article', 'Category Article', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (21, 3, 'blog_comment', 'Comment', 0);
INSERT INTO nds_table (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES (22, 3, 'blog_photo', 'Photo', 0);


#
# TABLE STRUCTURE FOR: nds_table_option
#

CREATE TABLE `nds_table_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO nds_table_option (`id`, `option_id`, `table_id`) VALUES (1, 1, 11);
INSERT INTO nds_table_option (`id`, `option_id`, `table_id`) VALUES (2, 1, 13);
INSERT INTO nds_table_option (`id`, `option_id`, `table_id`) VALUES (3, 1, 15);
INSERT INTO nds_table_option (`id`, `option_id`, `table_id`) VALUES (4, 1, 17);
INSERT INTO nds_table_option (`id`, `option_id`, `table_id`) VALUES (5, 1, 20);
INSERT INTO nds_table_option (`id`, `option_id`, `table_id`) VALUES (6, 3, 18);


#
# TABLE STRUCTURE FOR: nds_column
#

CREATE TABLE `nds_column` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `caption` varchar(50) NOT NULL,
  `data_type` enum('int','varchar','char','real','text','date','tinyint','smallint','mediumint','integer','bigint','float','double','decimal','numeric','datetime','timestamp','time','year','tinyblob','tinytext','blob','mediumblob','mediumtext','longblob','longtext') NOT NULL,
  `data_size` int(11) DEFAULT NULL,
  `role` enum('primary','lookup','detail many to many','detail one to many') NOT NULL,
  `lookup_table_id` int(11) NOT NULL,
  `lookup_column_id` int(11) DEFAULT NULL,
  `relation_table_id` int(11) DEFAULT NULL,
  `relation_table_column_id` int(11) DEFAULT NULL,
  `relation_selection_column_id` int(11) DEFAULT NULL,
  `relation_priority_column_id` int(11) DEFAULT NULL,
  `selection_table_id` int(11) DEFAULT NULL,
  `selection_column_id` int(11) DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `value_selection_mode` enum('set','enum') DEFAULT NULL,
  `value_selection_item` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`column_id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (52, 10, 'column_id', 'Column Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (53, 10, 'table_id', 'Table', 'int', 10, 'lookup', 14, 87, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (54, 10, 'name', 'Name', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (55, 10, 'caption', 'Caption', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (56, 10, 'data_type', 'Data Type', 'varchar', 255, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'enum', '\'int\',\'varchar\',\'char\',\'real\',\'text\',\'date\',\'tinyint\',\'smallint\',\'mediumint\',\'integer\',\'bigint\',\'float\',\'double\',\'decimal\',\'numeric\',\'datetime\',\'timestamp\',\'time\',\'year\',\'tinyblob\',\'tinytext\',\'blob\',\'mediumblob\',\'mediumtext\',\'longblob\',\'longtext\'');
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (57, 10, 'data_size', 'Data Size', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (58, 10, 'role', 'Role', 'varchar', 255, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'enum', '\'primary\',\'lookup\',\'detail many to many\',\'detail one to many\'');
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (59, 10, 'lookup_table_id', 'Lookup Table', 'int', 10, 'lookup', 14, 87, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (60, 10, 'lookup_column_id', 'Lookup Column', 'int', 10, 'lookup', 10, 54, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (61, 10, 'relation_table_id', 'Relation Table', 'int', 10, 'lookup', 14, 87, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (62, 10, 'relation_table_column_id', 'Relation Column (To This Table)', 'int', 10, 'lookup', 10, 54, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (63, 10, 'relation_selection_column_id', 'Relation Column (To Selection Table)', 'int', 10, 'lookup', 10, 54, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (64, 10, 'relation_priority_column_id', 'Relation Priority Column', 'int', 10, 'lookup', 10, 54, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (65, 10, 'selection_table_id', 'Selection Table', 'int', 10, 'lookup', 14, 87, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (66, 10, 'selection_column_id', 'Selection Column', 'int', 10, 'lookup', 10, 54, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (67, 10, 'priority', 'Priority', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (68, 10, 'value_selection_mode', 'Value Selection Mode', 'varchar', 255, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'enum', '\'set\',\'enum\'');
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (69, 10, 'value_selection_item', 'Value Selection Item', 'varchar', 255, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (70, 11, 'id', 'Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (71, 11, 'option_id', 'Option Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (72, 11, 'column_id', 'Column Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (73, 12, 'project_id', 'Project Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (74, 12, 'template_id', 'Template Id', 'int', 10, 'lookup', 16, 94, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (75, 12, 'name', 'Name', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (76, 12, 'db_server', 'DB Server', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (77, 12, 'db_port', 'DB Port', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (78, 12, 'db_schema', 'DB Schema', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (79, 12, 'db_user', 'DB User', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (80, 12, 'db_password', 'DB Password', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (81, 12, 'db_table_prefix', 'DB Table Prefix', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (82, 13, 'id', 'Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (83, 13, 'project_id', 'Project Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (84, 13, 'option_id', 'Option Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (85, 14, 'table_id', 'Table Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (86, 14, 'project_id', 'Project', 'int', 10, 'lookup', 12, 75, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (87, 14, 'name', 'Name', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (88, 14, 'caption', 'Caption', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (89, 14, 'priority', 'Priority', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (90, 15, 'id', 'Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (91, 15, 'option_id', 'Option Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (92, 15, 'table_id', 'Table Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (93, 16, 'template_id', 'Template Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (94, 16, 'name', 'Name', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (95, 16, 'generator_path', 'Generator Path', 'varchar', 100, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (96, 17, 'option_id', 'Option Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (97, 17, 'template_id', 'Template Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (98, 17, 'name', 'Name', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (99, 17, 'description', 'Description', 'text', 65535, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (100, 17, 'option_type', 'Option Type', 'varchar', 255, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'enum', '\'project\',\'table\',\'column\'');
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (101, 16, 'options', 'Options', '', NULL, 'detail one to many', 0, NULL, 17, 97, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (102, 14, 'options', 'Options', '', NULL, 'detail many to many', 0, NULL, 15, 92, 91, NULL, 17, 98, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (103, 12, 'options', 'Options', '', NULL, 'detail many to many', 0, NULL, 13, 83, 84, NULL, 17, 98, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (104, 10, 'options', 'Options', '', NULL, 'detail many to many', 0, NULL, 11, 72, 71, NULL, 17, 98, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (105, 18, 'article_id', 'Article Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (106, 18, 'article_title', 'Article Title', 'varchar', 100, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (107, 18, 'article_url', 'Article Url', 'varchar', 100, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (108, 18, 'date', 'Date', 'datetime', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (109, 18, 'author_user_id', 'Author User Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (110, 18, 'content', 'Content', 'text', 65535, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (111, 18, 'allow_comment', 'Allow Comment', 'tinyint', 3, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (112, 19, 'category_id', 'Category Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (113, 19, 'category_name', 'Category Name', 'varchar', 100, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (114, 19, 'description', 'Description', 'text', 65535, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (115, 20, 'category_id', 'Category Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (116, 20, 'article_id', 'Article Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (117, 21, 'comment_id', 'Comment Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (118, 21, 'article_id', 'Article Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (119, 21, 'date', 'Date', 'date', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (120, 21, 'author_user_id', 'Author User Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (121, 21, 'name', 'Name', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (122, 21, 'email', 'Email', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (123, 21, 'website', 'Website', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (124, 21, 'content', 'Content', 'text', 65535, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (125, 22, 'photo_id', 'Photo Id', 'int', 10, 'primary', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (126, 22, 'article_id', 'Article Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (127, 22, 'url', 'Url', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (128, 19, 'articles', 'Articles', '', NULL, 'detail many to many', 0, NULL, 20, 115, 116, NULL, 18, 106, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (129, 18, 'categories', 'Categories', '', NULL, 'detail many to many', 0, NULL, 20, 116, 115, NULL, 19, 113, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (130, 18, 'photos', 'Photos', '', NULL, 'detail one to many', 0, NULL, 22, 126, NULL, NULL, NULL, NULL, 0, NULL, NULL);
INSERT INTO nds_column (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES (131, 18, 'comments', 'Comments', '', NULL, 'detail one to many', 0, NULL, 21, 118, NULL, NULL, NULL, NULL, 0, NULL, NULL);


#
# TABLE STRUCTURE FOR: nds_column_option
#

CREATE TABLE `nds_column_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_id` int(11) NOT NULL,
  `column_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

