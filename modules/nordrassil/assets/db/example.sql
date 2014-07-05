INSERT INTO {{ complete_table_name:project }} (`project_id`, `template_id`, `name`, `db_server`, `db_port`, `db_schema`, `db_user`, `db_password`, `db_table_prefix`) VALUES 
(1, 1, 'example', 'localhost', '3306', 'town', 'root', 'toor', 'twn');
/*split*/
INSERT INTO {{ complete_table_name:table }} (`table_id`, `project_id`, `name`, `caption`, `priority`) VALUES 
(1, 1, 'twn_citizen', 'Citizen', 6),
(2, 1, 'twn_citizen_hobby', 'Citizen Hobby', 9),
(3, 1, 'twn_city', 'City', 5),
(4, 1, 'twn_city_commodity', 'City Commodity', 7),
(5, 1, 'twn_city_tourism', 'City Tourism', 8),
(6, 1, 'twn_commodity', 'Commodity', 3),
(7, 1, 'twn_country', 'Country', 2),
(8, 1, 'twn_hobby', 'Hobby', 1),
(9, 1, 'twn_job', 'Job', 0),
(10, 1, 'twn_tourism', 'Tourism', 4);
/*split*/
INSERT INTO {{ complete_table_name:table_option }} (`id`, `option_id`, `table_id`) VALUES 
(1, 1, 2),
(2, 1, 4),
(3, 1, 5),
(4, 1, 1),
(5, 3, 3);
/*split*/
INSERT INTO {{ complete_table_name:column }} (`column_id`, `table_id`, `name`, `caption`, `data_type`, `data_size`, `role`, `lookup_table_id`, `lookup_column_id`, `relation_table_id`, `relation_table_column_id`, `relation_selection_column_id`, `relation_priority_column_id`, `selection_table_id`, `selection_column_id`, `priority`, `value_selection_mode`, `value_selection_item`) VALUES 
(1, 1, 'citizen_id', 'Citizen Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(2, 1, 'city_id', 'City', 'int', 10, 'lookup', 3, 11, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(3, 1, 'name', 'Name', 'varchar', 50, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL),
(4, 1, 'birthdate', 'Birthdate', 'date', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL),
(5, 1, 'job_id', 'Job', 'int', 10, 'lookup', 9, 25, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL),
(6, 2, 'id', 'Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(7, 2, 'citizen_id', 'Citizen Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(8, 2, 'hobby_id', 'Hobby Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL),
(9, 3, 'city_id', 'City Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(10, 3, 'country_id', 'Country', 'int', 10, 'lookup', 7, 21, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(11, 3, 'name', 'Name', 'varchar', 20, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL),
(12, 4, 'id', 'Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(13, 4, 'city_id', 'City Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(14, 4, 'commodity_id', 'Commodity Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL),
(15, 5, 'id', 'Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(16, 5, 'city_id', 'City Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(17, 5, 'tourism_id', 'Tourism Id', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL),
(18, 6, 'commodity_id', 'Commodity Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(19, 6, 'name', 'Name', 'varchar', 20, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(20, 7, 'country_id', 'Country Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(21, 7, 'name', 'Name', 'varchar', 20, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(22, 8, 'hobby_id', 'Hobby Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(23, 8, 'name', 'Name', 'varchar', 20, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(24, 9, 'job_id', 'Job Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(25, 9, 'name', 'Name', 'varchar', 20, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(26, 10, 'tourism_id', 'Tourism Id', 'int', 10, 'primary', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(27, 10, 'name', 'Name', 'varchar', 20, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(28, 1, 'hobby', 'Hobby', '', NULL, 'detail many to many', 0, NULL, 2, 7, 8, NULL, 8, 23, 5, NULL, NULL),
(29, 3, 'tourism', 'Tourism', '', NULL, 'detail many to many', 0, NULL, 5, 16, 17, NULL, 10, 27, 3, NULL, NULL),
(30, 4, 'priority', 'Priority', 'int', 10, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL),
(31, 3, 'commodity', 'Commodity', '', NULL, 'detail many to many', 0, NULL, 4, 13, 14, 30, 6, 19, 4, NULL, NULL),
(32, 3, 'citizen', 'Citizen', '', NULL, 'detail one to many', 0, NULL, 1, 2, NULL, NULL, NULL, NULL, 5, NULL, NULL);
/*split*/
INSERT INTO {{ complete_table_name:column_option }} (`id`, `column_id`, `option_id`) VALUES 
(1, 3, 5),
(2, 3, 6),
(3, 11, 5),
(4, 11, 6),
(5, 19, 5),
(6, 19, 6),
(7, 21, 5),
(8, 21, 6),
(9, 23, 5),
(10, 23, 6),
(11, 25, 5),
(12, 25, 6),
(13, 27, 5),
(14, 27, 6);