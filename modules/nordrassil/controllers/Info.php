<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for new_nordrassil
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // New Nordrassil
            array(
                'navigation_name'   => 'index',
                'url'               => 'nordrassil',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Nordrassil (Module Generator)',
                'parent_name'       => 'main_management',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),

        );

    protected $BACKEND_NAVIGATIONS = array(
            // Manage Template
            array(
                'entity_name'       => 'template',
                'url'               => 'manage_template',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Template',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Project
            array(
                'entity_name'       => 'project',
                'url'               => 'manage_project',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Project',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Table
            array(
                'entity_name'       => 'table',
                'url'               => 'manage_table',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Table',
                'parent_name'       => 'manage_project',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => 1,
                'static_content'    => NULL,
            ),
            // Manage Column
            array(
                'entity_name'       => 'column',
                'url'               => 'manage_column',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Column',
                'parent_name'       => 'manage_table',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => 1,
                'static_content'    => NULL,
            ),

        );

    //////////////////////////////////////////////////////////////////////////////
    // CONFIGURATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $CONFIGS = array();

    //////////////////////////////////////////////////////////////////////////////
    // PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    protected $PRIVILEGES = array();

    //////////////////////////////////////////////////////////////////////////////
    // GROUPS
    //////////////////////////////////////////////////////////////////////////////
    protected $GROUPS = array(
            array('group_name' => 'Nordrassil Manager', 'description' => 'Nordrassil Manager'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            'Nordrassil Manager' => array('template', 'project', 'table', 'column')
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            'Nordrassil Manager' => array(
                'template' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'project' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'table' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'column' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            )
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        // template
        'template' => array(
            'key'    => 'template_id',
            'fields' => array(
                'template_id'          => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'name'                 => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'generator_path'       => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'project'              => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'template_option'      => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // template_option
        'template_option' => array(
            'key'    => 'option_id',
            'fields' => array(
                'option_id'            => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'template_id'          => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'name'                 => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'description'          => array("type" => 'text',       "null" => TRUE),
                'option_type'          => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
            ),
        ),
        // project
        'project' => array(
            'key'    => 'project_id',
            'fields' => array(
                'project_id'           => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'template_id'          => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'name'                 => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'db_server'            => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'db_port'              => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'db_schema'            => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'db_user'              => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'db_password'          => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'db_table_prefix'      => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'table'                => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'project_option'       => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // table
        'table' => array(
            'key'    => 'table_id',
            'fields' => array(
                'table_id'             => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'project_id'           => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'name'                 => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'caption'              => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'priority'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'data'                 => array("type" => 'longtext',   "null" => TRUE),
                'column'               => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'table_option'         => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // column
        'column' => array(
            'key'    => 'column_id',
            'fields' => array(
                'column_id'            => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'table_id'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'name'                 => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'caption'              => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'data_type'            => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'data_size'            => array("type" => 'bigint',     "constraint" => 19,  "null" => TRUE),
                'role'                 => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'lookup_table_id'      => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'lookup_column_id'     => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'relation_table_id'    => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'relation_table_column_id' => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'relation_selection_column_id' => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'relation_priority_column_id' => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'selection_table_id'   => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'selection_column_id'  => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'priority'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'value_selection_mode' => array("type" => 'varchar',    "constraint" => 50,  "null" => TRUE),
                'value_selection_item' => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'column_option'        => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
            ),
        ),
        // column_option
        'column_option' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'option_id'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'column_id'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            ),
        ),
        // project_option
        'project_option' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'project_id'           => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'option_id'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            ),
        ),
        // table_option
        'table_option' => array(
            'key'    => 'id',
            'fields' => array(
                'id'                   => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'option_id'            => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'table_id'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
            ),
        ),
    );
    protected $DATA = array(
        'table' => array(
            array('table_id' => 1, 'project_id' => 1, 'name' => 'twn_citizen', 'caption' => 'Citizen', 'priority' => 6, 'data'=>''),
            array('table_id' => 2, 'project_id' => 1, 'name' => 'twn_citizen_hobby', 'caption' => 'Citizen Hobby', 'priority' => 9, 'data'=>''),
            array('table_id' => 3, 'project_id' => 1, 'name' => 'twn_city', 'caption' => 'City', 'priority' => 5, 'data'=>''),
            array('table_id' => 4, 'project_id' => 1, 'name' => 'twn_city_commodity', 'caption' => 'City Commodity', 'priority' => 7, 'data'=>''),
            array('table_id' => 5, 'project_id' => 1, 'name' => 'twn_city_tourism', 'caption' => 'City Tourism', 'priority' => 8, 'data'=>''),
            array('table_id' => 6, 'project_id' => 1, 'name' => 'twn_commodity', 'caption' => 'Commodity', 'priority' => 3, 'data'=>'[{"commodity_id" : 1, "name" : "vegetables"}, {"commodity_id" : 2, "name" : "fruits"}, {"commodity_id" : 3, "name" : "diary"}]'),
            array('table_id' => 7, 'project_id' => 1, 'name' => 'twn_country', 'caption' => 'Country', 'priority' => 2, 'data'=>'[{"country_id" : 1, "name" : "USA"}, {"country_id" : 2, "name" : "Indonesia"}]'),
            array('table_id' => 8, 'project_id' => 1, 'name' => 'twn_hobby', 'caption' => 'Hobby', 'priority' => 1, 'data'=>'[{"hobby_id" : 1, "name" : "Reading"}, {"hobby_id" : 2, "name" : "Gardenning"}]'),
            array('table_id' => 9, 'project_id' => 1, 'name' => 'twn_job', 'caption' => 'Job', 'priority' => 0, 'data'=>'[{"job_id" : 1, "name" : "Teacher"}, {"job_id" : 2, "name" : "Programmer"}]'),
            array('table_id' => 10, 'project_id' => 1, 'name' => 'twn_tourism', 'caption' => 'Tourism', 'priority' => 4, 'data'=>'[{"tourism_id" : 1, "name" : "Amusement Park"}, {"tourism_id" : 2, "name" : "Beach"}]'),
        ),
        'table_option' => array(
            array('id' => '1', 'option_id' => '1', 'table_id' => '2'),
            array('id' => '2', 'option_id' => '1', 'table_id' => '4'),
            array('id' => '3', 'option_id' => '1', 'table_id' => '5'),
            array('id' => '4', 'option_id' => '1', 'table_id' => '1'),
            array('id' => '5', 'option_id' => '3', 'table_id' => '3'),
        ),
        'column_option' => array(
            array('id' => '1', 'option_id' => '5', 'column_id' => '3'),
            array('id' => '2', 'option_id' => '6', 'column_id' => '3'),
            array('id' => '3', 'option_id' => '5', 'column_id' => '11'),
            array('id' => '4', 'option_id' => '6', 'column_id' => '11'),
            array('id' => '5', 'option_id' => '5', 'column_id' => '19'),
            array('id' => '6', 'option_id' => '6', 'column_id' => '19'),
            array('id' => '7', 'option_id' => '5', 'column_id' => '21'),
            array('id' => '8', 'option_id' => '6', 'column_id' => '21'),
            array('id' => '9', 'option_id' => '5', 'column_id' => '23'),
            array('id' => '10', 'option_id' => '6', 'column_id' => '23'),
            array('id' => '11', 'option_id' => '5', 'column_id' => '25'),
            array('id' => '12', 'option_id' => '6', 'column_id' => '25'),
            array('id' => '13', 'option_id' => '5', 'column_id' => '27'),
            array('id' => '14', 'option_id' => '6', 'column_id' => '27'),
        ),
        'column' => array(
            array('column_id' => '1', 'table_id' => '1', 'name' => 'citizen_id', 'caption' => 'Citizen Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '2', 'table_id' => '1', 'name' => 'city_id', 'caption' => 'City', 'data_type' => 'int', 'data_size' => '10', 'role' => 'lookup', 'lookup_table_id' => '3', 'lookup_column_id' => '11', 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '3', 'table_id' => '1', 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => '50', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '2', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '4', 'table_id' => '1', 'name' => 'birthdate', 'caption' => 'Birthdate', 'data_type' => 'date', 'data_size' => '10', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '3', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '5', 'table_id' => '1', 'name' => 'job_id', 'caption' => 'Job', 'data_type' => 'int', 'data_size' => '10', 'role' => 'lookup', 'lookup_table_id' => '9', 'lookup_column_id' => '25', 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '4', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '6', 'table_id' => '2', 'name' => 'id', 'caption' => 'Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '7', 'table_id' => '2', 'name' => 'citizen_id', 'caption' => 'Citizen Id', 'data_type' => 'int', 'data_size' => '10', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '8', 'table_id' => '2', 'name' => 'hobby_id', 'caption' => 'Hobby Id', 'data_type' => 'int', 'data_size' => '10', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '2', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '9', 'table_id' => '3', 'name' => 'city_id', 'caption' => 'City Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '10', 'table_id' => '3', 'name' => 'country_id', 'caption' => 'Country', 'data_type' => 'int', 'data_size' => '10', 'role' => 'lookup', 'lookup_table_id' => '7', 'lookup_column_id' => '21', 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '11', 'table_id' => '3', 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => '20', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '2', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '12', 'table_id' => '4', 'name' => 'id', 'caption' => 'Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '13', 'table_id' => '4', 'name' => 'city_id', 'caption' => 'City Id', 'data_type' => 'int', 'data_size' => '10', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '14', 'table_id' => '4', 'name' => 'commodity_id', 'caption' => 'Commodity Id', 'data_type' => 'int', 'data_size' => '10', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '2', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '15', 'table_id' => '5', 'name' => 'id', 'caption' => 'Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '16', 'table_id' => '5', 'name' => 'city_id', 'caption' => 'City Id', 'data_type' => 'int', 'data_size' => '10', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '17', 'table_id' => '5', 'name' => 'tourism_id', 'caption' => 'Tourism Id', 'data_type' => 'int', 'data_size' => '10', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '2', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '18', 'table_id' => '6', 'name' => 'commodity_id', 'caption' => 'Commodity Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '19', 'table_id' => '6', 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => '20', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '20', 'table_id' => '7', 'name' => 'country_id', 'caption' => 'Country Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '21', 'table_id' => '7', 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => '20', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '22', 'table_id' => '8', 'name' => 'hobby_id', 'caption' => 'Hobby Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '23', 'table_id' => '8', 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => '20', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '24', 'table_id' => '9', 'name' => 'job_id', 'caption' => 'Job Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '25', 'table_id' => '9', 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => '20', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '26', 'table_id' => '10', 'name' => 'tourism_id', 'caption' => 'Tourism Id', 'data_type' => 'int', 'data_size' => '10', 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '0', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '27', 'table_id' => '10', 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => '20', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '1', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '28', 'table_id' => '1', 'name' => 'hobby', 'caption' => 'Hobby', 'data_type' => NULL, 'data_size' => NULL, 'role' => 'detail many to many', 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => '2', 'relation_table_column_id' => '7', 'relation_selection_column_id' => '8', 'relation_priority_column_id' => NULL, 'selection_table_id' => '8', 'selection_column_id' => '23', 'priority' => '5', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '29', 'table_id' => '3', 'name' => 'tourism', 'caption' => 'Tourism', 'data_type' => NULL, 'data_size' => NULL, 'role' => 'detail many to many', 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => '5', 'relation_table_column_id' => '16', 'relation_selection_column_id' => '17', 'relation_priority_column_id' => NULL, 'selection_table_id' => '10', 'selection_column_id' => '27', 'priority' => '3', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '30', 'table_id' => '4', 'name' => 'priority', 'caption' => 'Priority', 'data_type' => 'int', 'data_size' => '10', 'role' => NULL, 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '3', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '31', 'table_id' => '3', 'name' => 'commodity', 'caption' => 'Commodity', 'data_type' => NULL, 'data_size' => NULL, 'role' => 'detail many to many', 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => '4', 'relation_table_column_id' => '13', 'relation_selection_column_id' => '14', 'relation_priority_column_id' => '30', 'selection_table_id' => '6', 'selection_column_id' => '19', 'priority' => '4', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => '32', 'table_id' => '3', 'name' => 'citizen', 'caption' => 'Citizen', 'data_type' => NULL, 'data_size' => NULL, 'role' => 'detail one to many', 'lookup_table_id' => '0', 'lookup_column_id' => NULL, 'relation_table_id' => '1', 'relation_table_column_id' => '2', 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => '5', 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
        ),
        'project' => array(
            array('project_id' => '1', 'template_id' => '1', 'name' => 'example', 'db_server' => 'localhost', 'db_port' => '3306', 'db_schema' => 'town', 'db_user' => 'root', 'db_password' => 'toor', 'db_table_prefix' => 'twn'),
        ),
        'template_option' => array(
            array('option_id' => '1', 'template_id' => '1', 'name' => 'dont_make_form', 'description' => 'make form for this table', 'option_type' => 'table'),
            array('option_id' => '2', 'template_id' => '1', 'name' => 'dont_create_table', 'description' => 'don\'t create/drop table on installation', 'option_type' => 'table'),
            array('option_id' => '3', 'template_id' => '1', 'name' => 'make_frontpage', 'description' => 'Make front page for this table', 'option_type' => 'table'),
            array('option_id' => '4', 'template_id' => '1', 'name' => 'hide', 'description' => 'hide field', 'option_type' => 'column'),
            array('option_id' => '5', 'template_id' => '1', 'name' => 'required', 'description' => 'make field required', 'option_type' => 'column'),
            array('option_id' => '6', 'template_id' => '1', 'name' => 'unique', 'description' => 'make field unique', 'option_type' => 'column'),
            array('option_id' => '7', 'template_id' => '1', 'name' => 'validation_alpha', 'description' => 'alpha validation filter', 'option_type' => 'column'),
            array('option_id' => '8', 'template_id' => '1', 'name' => 'validation_numeric', 'description' => 'numeric validation filter', 'option_type' => 'column'),
            array('option_id' => '9', 'template_id' => '1', 'name' => 'validation_alpha_numeric', 'description' => 'alphanumeric validation filter', 'option_type' => 'column'),
            array('option_id' => '10', 'template_id' => '1', 'name' => 'validation_alpha_numeric_spaces', 'description' => 'alphanumeric & spaces validation filter', 'option_type' => 'column'),
            array('option_id' => '11', 'template_id' => '1', 'name' => 'validation_integer', 'description' => 'integer validation filter', 'option_type' => 'column'),
            array('option_id' => '12', 'template_id' => '1', 'name' => 'validation_natural', 'description' => 'natural number validation filter', 'option_type' => 'column'),
            array('option_id' => '13', 'template_id' => '1', 'name' => 'validation_natural_no_zero', 'description' => 'natural non zero number validation filter', 'option_type' => 'column'),
            array('option_id' => '14', 'template_id' => '1', 'name' => 'validation_valid_url', 'description' => 'url validation filter', 'option_type' => 'column'),
            array('option_id' => '15', 'template_id' => '1', 'name' => 'validation_valid_email', 'description' => 'email validation filter', 'option_type' => 'column'),
            array('option_id' => '16', 'template_id' => '1', 'name' => 'validation_valid_emails', 'description' => 'comma separated multiple email validation filter', 'option_type' => 'column'),
            array('option_id' => '17', 'template_id' => '1', 'name' => 'validation_valid_ip', 'description' => 'ip validation filter', 'option_type' => 'column'),
            array('option_id' => '18', 'template_id' => '1', 'name' => 'validation_valid_base64', 'description' => 'base64 character validation filter', 'option_type' => 'column'),
            array('option_id' => '19', 'template_id' => '1', 'name' => 'upload', 'description' => 'upload field', 'option_type' => 'column'),
        ),
        'template' => array(
            array('template_id' => '1', 'name' => 'No-CMS Module', 'generator_path' => 'nordrassil/default_generator/default_generator/index'),
        ),
    );

    //////////////////////////////////////////////////////////////////////////////
    // ACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_activate(){
        // TODO : write your module activation script here
    }

    //////////////////////////////////////////////////////////////////////////////
    // DEACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_deactivate(){
        // TODO : write your module deactivation script here
    }

    //////////////////////////////////////////////////////////////////////////////
    // UPGRADE
    //////////////////////////////////////////////////////////////////////////////
    // TODO: write your upgrade function: do_upgrade_to_x_x_x

    public function do_upgrade_to_0_0_4(){
        $this->cms_remove_navigation($this->n('project'));
        $this->cms_remove_navigation($this->n('template'));
    }

}
