CREATE TABLE `{{ complete_table_name:column }}` (
  `column_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10),
  `name` varchar(50),
  `caption` varchar(50),
  `data_type` enum('int','varchar','char','real','text','date','tinyint','smallint','mediumint','integer','bigint','float','double','decimal','numeric','datetime','timestamp','time','year','tinyblob','tinytext','blob','mediumblob','mediumtext','longblob','longtext'),
  `data_size` int(10),
  `role` enum('primary','lookup','detail many to many','detail one to many'),
  `lookup_table_id` int(10),
  `lookup_column_id` int(10),
  `relation_table_id` int(10),
  `relation_table_column_id` int(10),
  `relation_selection_column_id` int(10),
  `relation_priority_column_id` int(10),
  `selection_table_id` int(10),
  `selection_column_id` int(10),
  `priority` int(10),
  `value_selection_mode` enum('set','enum'),
  `value_selection_item` varchar(255),
  PRIMARY KEY (`column_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:column_option }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `option_id` int(10),
  `column_id` int(10),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:project }}` (
  `project_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int(10),
  `name` varchar(50),
  `db_server` varchar(50),
  `db_port` varchar(50),
  `db_schema` varchar(50),
  `db_user` varchar(50),
  `db_password` varchar(50),
  `db_table_prefix` varchar(50),
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:project_option }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10),
  `option_id` int(10),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:table }}` (
  `table_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10),
  `name` varchar(50),
  `caption` varchar(50),
  `priority` int(10),
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:table_option }}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `option_id` int(10),
  `table_id` int(10),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:template }}` (
  `template_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50),
  `generator_path` varchar(100),
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*split*/

CREATE TABLE `{{ complete_table_name:template_option }}` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int(10),
  `name` varchar(50),
  `description` text,
  `option_type` enum('project','table','column'),
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;