<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Install extends CMS_Module_Installer {
    protected $DEPENDENCIES = array();
    protected $NAME         = 'gofrendi.noCMS.nordrassil';
    protected $DESCRIPTION  = 'A very cool and easy module generator. Choose your database schema, press the magical "generate" button, and enjoy your life';

    //this should be what happen when user install this module
    protected function do_activate(){
        $this->remove_all();
        $this->build_all();
    }
    //this should be what happen when user uninstall this module
    protected function do_deactivate(){
        $this->remove_all();        
    }

    private function remove_all(){
        $module_path = $this->cms_module_path();

        // remove navigations
        $this->remove_navigation($this->cms_complete_navigation_name('project'));
        $this->remove_navigation($this->cms_complete_navigation_name('template'));

        // remove parent of all navigations
        $this->remove_navigation($this->cms_complete_navigation_name('index'));

         // drop tables
        $this->dbforge->drop_table($this->cms_complete_table_name('column_option'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('column'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('table_option'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('table'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('project_option'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('project'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('template_option'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('template'), TRUE);

    }

    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->add_navigation($this->cms_complete_navigation_name('index'), "Module Generator",
            $module_path."/nordrassil/index", 4, "main_management");

        // add navigations
        $this->add_navigation($this->cms_complete_navigation_name('template'), "Generator Template",
            $module_path."/data/nds/template", 4, "nordrassil_index"
        );
        $this->add_navigation($this->cms_complete_navigation_name('project'), "Project",
            $module_path."/data/nds/project", 4, "nordrassil_index"
        );


        // create tables
        // template
        $fields = array(
            'template_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'name'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'generator_path'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('template_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('template'));

        // template_option
        $fields = array(
            'option_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'template_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'description'=> array("type"=>'text', "null"=>TRUE),
            'option_type'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('option_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('template_option'));

        // project
        $fields = array(
            'project_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'template_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_server'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_port'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_schema'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_user'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_password'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'db_table_prefix'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('project_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('project'));

        // project_option
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'project_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'option_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('project_option'));

        // table
        $fields = array(
            'table_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'project_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'caption'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'priority'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('table_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('table'));

        // table_option
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'option_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'table_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('table_option'));

        // column
        $fields = array(
            'column_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'table_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'name'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'caption'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'data_type'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'data_size'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'role'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'lookup_table_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'lookup_column_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'relation_table_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'relation_table_column_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'relation_selection_column_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'relation_priority_column_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'selection_table_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'selection_column_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'priority'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'value_selection_mode'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'value_selection_item'=> array("type"=>'varchar', "constraint"=>255, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('column_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('column'));

        // column_option
        $fields = array(
            'id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'option_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE),
            'column_id'=> array("type"=>'int', "constraint"=>10, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('column_option'));


		// install template
		$this->load->library($module_path.'/NordrassilLib');
		$template_name = 'No-CMS default Module';
		$generator_path = 'nordrassil/default_generator/generator/index';
		$project_options = array();
		$table_options = array(
			array('name'=>'dont_make_form', 'description'=>'make form for this table'),
			array('name'=>'dont_create_table', 'description'=>'don\'t create/drop table on installation'),
			array('name'=>'make_frontpage', 'description'=>'Make front page for this table'),
			array('name'=>'import_data', 'description'=>'Also create insert statement (e.g: for configuration table)'),
		);
		$column_options = array(
			array('name'=>'hide', 'description'=>'shown'),
		);
		$this->nordrassillib->install_template($template_name, $generator_path,
			$project_options, $table_options, $column_options);
    }

    // IMPORT SQL FILE
    private function import_sql($file_name){
        $this->execute_SQL(file_get_contents($file_name), '/*split*/');
    }
}

?>
