<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    protected function check_subdomain(){
        if(CMS_SUBSITE != ''){
            $module_path = $this->cms_module_path();
            $this->view($module_path.'/install_subsite_fail', NULL, 'main_index');
            return TRUE;
        }else{
            return FALSE;
        }
    }

    //this should be what happen when user install this module
    public function do_activate(){
        if(!$this->check_subdomain()){
            $this->remove_all();
            $this->build_all();
        }
    }
    //this should be what happen when user uninstall this module
    public function do_deactivate(){
        if(!$this->check_subdomain()){
            $this->remove_all();
        }
    }

    // UPGRADE
    public function do_upgrade($old_version){
        $version_part = explode('.', $old_version);
        $major        = $version_part[0];
        $minor        = $version_part[1];
        $build        = $version_part[2];
        $module_path  = $this->cms_module_path();
        // TODO: Add your migration logic here.
        if($major <= 0 && $minor <= 0 && $build <= 2){
            $this->dbforge->add_column($this->t('table'),array(
                'data' => array("type"=>'text',"null"=>TRUE)
            ));
        }
    }

    private function remove_all(){
        $module_path = $this->cms_module_path();

        // remove navigations
        $this->cms_remove_navigation($this->n('project'));
        $this->cms_remove_navigation($this->n('template'));

        // remove parent of all navigations
        $this->cms_remove_navigation($this->n('index'));

         // drop tables
        $this->dbforge->drop_table($this->t('column_option'), TRUE);
        $this->dbforge->drop_table($this->t('column'), TRUE);
        $this->dbforge->drop_table($this->t('table_option'), TRUE);
        $this->dbforge->drop_table($this->t('table'), TRUE);
        $this->dbforge->drop_table($this->t('project_option'), TRUE);
        $this->dbforge->drop_table($this->t('project'), TRUE);
        $this->dbforge->drop_table($this->t('template_option'), TRUE);
        $this->dbforge->drop_table($this->t('template'), TRUE);

    }

    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->cms_add_navigation($this->n('index'), "Module Generator",
            $module_path."/nordrassil/index", 4, "main_management", NULL, "Nordrassil Module Generator");

        // add navigations
        $this->cms_add_navigation($this->n('template'), "Generator Template",
            $module_path."/data/nds/template", 4, "nordrassil_index",
            NULL, 'Add, edit, and delete generator template',
            NULL, NULL, 'default-one-column'
        );
        $this->cms_add_navigation($this->n('project'), "Project",
            $module_path."/data/nds/project", 4, "nordrassil_index",
            NULL, 'Add, edit, and delete project skeleton',
            NULL, NULL, 'default-one-column'
        );


        // create tables
        // template
        $fields = array(
            'template_id'       => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'              => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'generator_path'    => array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('template_id', TRUE);
        $this->dbforge->create_table($this->t('template'));

        // template_option
        $fields = array(
            'option_id'     => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'template_id'   => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'          => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'description'   => array("type"=>'text', "null"=>TRUE),
            'option_type'   => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('option_id', TRUE);
        $this->dbforge->create_table($this->t('template_option'));

        // project
        $fields = array(
            'project_id'        => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'template_id'       => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'              => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_server'         => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_port'           => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_schema'         => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_user'           => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_password'       => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_table_prefix'   => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('project_id', TRUE);
        $this->dbforge->create_table($this->t('project'));

        // project_option
        $fields = array(
            'id'            => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'project_id'    => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'option_id'     => array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->t('project_option'));

        // table
        $fields = array(
            'table_id'      => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'project_id'    => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'          => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'caption'       => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'priority'      => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'data'          =>array("type"=>'text',"null"=>TRUE),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('table_id', TRUE);
        $this->dbforge->create_table($this->t('table'));

        // table_option
        $fields = array(
            'id'        => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'option_id' => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'table_id'  => array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->t('table_option'));

        // column
        $fields = array(
            'column_id'                     => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'table_id'                      => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'                          => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'caption'                       => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'data_type'                     => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'data_size'                     => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'role'                          => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'lookup_table_id'               => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'lookup_column_id'              => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'relation_table_id'             => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'relation_table_column_id'      => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'relation_selection_column_id'  => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'relation_priority_column_id'   => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'selection_table_id'            => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'selection_column_id'           => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'priority'                      => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'value_selection_mode'          => array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'value_selection_item'          => array("type"=>'varchar', "constraint"=>255, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('column_id', TRUE);
        $this->dbforge->create_table($this->t('column'));

        // column_option
        $fields = array(
            'id'        => $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'option_id' => array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'column_id' => array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->t('column_option'));

        // install template
        $this->load->model($module_path.'/data/nds_model');
        $template_name = 'No-CMS Module';
        $generator_path = 'nordrassil/default_generator/default_generator/index';
        $project_options = array();
        $table_options = array(
            array('name'=>'dont_make_form', 'description'=>'make form for this table'),
            array('name'=>'dont_create_table', 'description'=>'don\'t create/drop table on installation'),
            array('name'=>'make_frontpage', 'description'=>'Make front page for this table'),
        );
        $column_options = array(
            array('name'=>'hide', 'description'=>'hide field'),
            array('name'=>'required', 'description'=>'make field required'),
            array('name'=>'unique', 'description'=>'make field unique'),
            array('name'=>'validation_alpha', 'description'=>'alpha validation filter'),
            array('name'=>'validation_numeric', 'description'=>'numeric validation filter'),
            array('name'=>'validation_alpha_numeric', 'description'=>'alphanumeric validation filter'),
            array('name'=>'validation_alpha_numeric_spaces', 'description'=>'alphanumeric & spaces validation filter'),
            array('name'=>'validation_integer', 'description'=>'integer validation filter'),
            array('name'=>'validation_natural', 'description'=>'natural number validation filter'),
            array('name'=>'validation_natural_no_zero', 'description'=>'natural non zero number validation filter'),
            array('name'=>'validation_valid_url', 'description'=>'url validation filter'),
            array('name'=>'validation_valid_email', 'description'=>'email validation filter'),
            array('name'=>'validation_valid_emails', 'description'=>'comma separated multiple email validation filter'),
            array('name'=>'validation_valid_ip', 'description'=>'ip validation filter'),
            array('name'=>'validation_valid_base64', 'description'=>'base64 character validation filter'),
            array('name'=>'upload', 'description'=>'upload field')
        );

        $this->nds_model->install_template($template_name, $generator_path,
            $project_options, $table_options, $column_options);


        // insert project
        $this->db->insert($this->t('project'),
            array(
                'project_id'=>1, 'template_id'=>1, 'name'=>'example', 'db_server'=>'localhost',
                'db_port'=>'3306', 'db_schema'=>'town', 'db_user'=>'root', 'db_password'=>'toor', 'db_table_prefix'=>'twn'));
        // insert table
        $this->db->insert_batch($this->t('table'), array(
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
        ));
        // insert table_option
        $this->db->insert_batch($this->t('table_option'), array(
            array('id' => 1, 'option_id' => 1, 'table_id' => 2),
            array('id' => 2, 'option_id' => 1, 'table_id' => 4),
            array('id' => 3, 'option_id' => 1, 'table_id' => 5),
            array('id' => 4, 'option_id' => 1, 'table_id' => 1),
            array('id' => 5, 'option_id' => 3, 'table_id' => 3),
        ));
        // insert column
        $this->db->insert_batch($this->t('column'), array(
            array('column_id' => 1, 'table_id' => 1, 'name' => 'citizen_id', 'caption' => 'Citizen Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 2, 'table_id' => 1, 'name' => 'city_id', 'caption' => 'City', 'data_type' => 'int', 'data_size' => 10, 'role' => 'lookup', 'lookup_table_id' => 3, 'lookup_column_id' => 11, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 3, 'table_id' => 1, 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => 50, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 2, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 4, 'table_id' => 1, 'name' => 'birthdate', 'caption' => 'Birthdate', 'data_type' => 'date', 'data_size' => 10, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 3, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 5, 'table_id' => 1, 'name' => 'job_id', 'caption' => 'Job', 'data_type' => 'int', 'data_size' => 10, 'role' => 'lookup', 'lookup_table_id' => 9, 'lookup_column_id' => 25, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 4, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 6, 'table_id' => 2, 'name' => 'id', 'caption' => 'Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 7, 'table_id' => 2, 'name' => 'citizen_id', 'caption' => 'Citizen Id', 'data_type' => 'int', 'data_size' => 10, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 8, 'table_id' => 2, 'name' => 'hobby_id', 'caption' => 'Hobby Id', 'data_type' => 'int', 'data_size' => 10, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 2, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 9, 'table_id' => 3, 'name' => 'city_id', 'caption' => 'City Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 10, 'table_id' => 3, 'name' => 'country_id', 'caption' => 'Country', 'data_type' => 'int', 'data_size' => 10, 'role' => 'lookup', 'lookup_table_id' => 7, 'lookup_column_id' => 21, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 11, 'table_id' => 3, 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => 20, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 2, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 12, 'table_id' => 4, 'name' => 'id', 'caption' => 'Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 13, 'table_id' => 4, 'name' => 'city_id', 'caption' => 'City Id', 'data_type' => 'int', 'data_size' => 10, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 14, 'table_id' => 4, 'name' => 'commodity_id', 'caption' => 'Commodity Id', 'data_type' => 'int', 'data_size' => 10, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 2, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 15, 'table_id' => 5, 'name' => 'id', 'caption' => 'Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 16, 'table_id' => 5, 'name' => 'city_id', 'caption' => 'City Id', 'data_type' => 'int', 'data_size' => 10, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 17, 'table_id' => 5, 'name' => 'tourism_id', 'caption' => 'Tourism Id', 'data_type' => 'int', 'data_size' => 10, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 2, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 18, 'table_id' => 6, 'name' => 'commodity_id', 'caption' => 'Commodity Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 19, 'table_id' => 6, 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => 20, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 20, 'table_id' => 7, 'name' => 'country_id', 'caption' => 'Country Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 21, 'table_id' => 7, 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => 20, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 22, 'table_id' => 8, 'name' => 'hobby_id', 'caption' => 'Hobby Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 23, 'table_id' => 8, 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => 20, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 24, 'table_id' => 9, 'name' => 'job_id', 'caption' => 'Job Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 25, 'table_id' => 9, 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => 20, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 26, 'table_id' => 10, 'name' => 'tourism_id', 'caption' => 'Tourism Id', 'data_type' => 'int', 'data_size' => 10, 'role' => 'primary', 'lookup_table_id' => NULL, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 0, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 27, 'table_id' => 10, 'name' => 'name', 'caption' => 'Name', 'data_type' => 'varchar', 'data_size' => 20, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 1, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 28, 'table_id' => 1, 'name' => 'hobby', 'caption' => 'Hobby', 'data_type' => '', 'data_size' => NULL, 'role' => 'detail many to many', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => 2, 'relation_table_column_id' => 7, 'relation_selection_column_id' => 8, 'relation_priority_column_id' => NULL, 'selection_table_id' => 8, 'selection_column_id' => 23, 'priority' => 5, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 29, 'table_id' => 3, 'name' => 'tourism', 'caption' => 'Tourism', 'data_type' => '', 'data_size' => NULL, 'role' => 'detail many to many', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => 5, 'relation_table_column_id' => 16, 'relation_selection_column_id' => 17, 'relation_priority_column_id' => NULL, 'selection_table_id' => 10, 'selection_column_id' => 27, 'priority' => 3, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 30, 'table_id' => 4, 'name' => 'priority', 'caption' => 'Priority', 'data_type' => 'int', 'data_size' => 10, 'role' => '', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => NULL, 'relation_table_column_id' => NULL, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 3, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 31, 'table_id' => 3, 'name' => 'commodity', 'caption' => 'Commodity', 'data_type' => '', 'data_size' => NULL, 'role' => 'detail many to many', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => 4, 'relation_table_column_id' => 13, 'relation_selection_column_id' => 14, 'relation_priority_column_id' => 30, 'selection_table_id' => 6, 'selection_column_id' => 19, 'priority' => 4, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
            array('column_id' => 32, 'table_id' => 3, 'name' => 'citizen', 'caption' => 'Citizen', 'data_type' => '', 'data_size' => NULL, 'role' => 'detail one to many', 'lookup_table_id' => 0, 'lookup_column_id' => NULL, 'relation_table_id' => 1, 'relation_table_column_id' => 2, 'relation_selection_column_id' => NULL, 'relation_priority_column_id' => NULL, 'selection_table_id' => NULL, 'selection_column_id' => NULL, 'priority' => 5, 'value_selection_mode' => NULL, 'value_selection_item' => NULL),
        ));
        // insert column_option
        $this->db->insert_batch($this->t('column_option'), array(
            array('id' => 1, 'column_id' => 3, 'option_id' => 5),
            array('id' => 2, 'column_id' => 3, 'option_id' => 6),
            array('id' => 3, 'column_id' => 11, 'option_id' => 5),
            array('id' => 4, 'column_id' => 11, 'option_id' => 6),
            array('id' => 5, 'column_id' => 19, 'option_id' => 5),
            array('id' => 6, 'column_id' => 19, 'option_id' => 6),
            array('id' => 7, 'column_id' => 21, 'option_id' => 5),
            array('id' => 8, 'column_id' => 21, 'option_id' => 6),
            array('id' => 9, 'column_id' => 23, 'option_id' => 5),
            array('id' => 10, 'column_id' => 23, 'option_id' => 6),
            array('id' => 11, 'column_id' => 25, 'option_id' => 5),
            array('id' => 12, 'column_id' => 25, 'option_id' => 6),
            array('id' => 13, 'column_id' => 27, 'option_id' => 5),
            array('id' => 14, 'column_id' => 27, 'option_id' => 6),
        ));
    }

    // IMPORT SQL FILE
    private function import_sql($file_name){
        $this->cms_execute_SQL(file_get_contents($file_name), '/*split*/');
    }

}
