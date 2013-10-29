<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for static_accessories
 *
 * @author No-CMS Module Generator
 */
class Install extends CMS_Module_Installer {
    /////////////////////////////////////////////////////////////////////////////
    // Default Variables
    /////////////////////////////////////////////////////////////////////////////

    protected $DEPENDENCIES = array();
    protected $NAME         = 'gofrendi.noCms.static_accessories';
    protected $DESCRIPTION  = 'This widget contains several widgets such a slideshow, tabbed content, and visitor counter';
    protected $VERSION      = '0.0.0';


    /////////////////////////////////////////////////////////////////////////////
    // Default Functions
    /////////////////////////////////////////////////////////////////////////////

    // ACTIVATION
    protected function do_activate(){
        $this->remove_all();
        $this->build_all();
    }

    // DEACTIVATION
    protected function do_deactivate(){
	    /* This doesn't work with PDO
        $this->backup_database(array(
            $this->cms_complete_table_name('slide'),
            $this->cms_complete_table_name('tab_content'),
            $this->cms_complete_table_name('visitor_counter')
        ));
	    */
        $this->remove_all();
    }

    // UPGRADE
    protected function do_upgrade($old_version){
        // Add your migration logic here.
    }

    // OVERRIDE THIS FUNCTION TO PROVIDE "Module Setting" FEATURE
    public function setting(){
        $module_directory = $this->cms_module_path();
        $data = array();
        $data['IS_ACTIVE'] = $this->IS_ACTIVE;
        $data['module_directory'] = $module_directory;
        if(!$this->IS_ACTIVE){
            // get setting
            $module_table_prefix = $this->input->post('module_table_prefix');
            $module_prefix       = $this->input->post('module_prefix');
            // set values
            if(isset($module_table_prefix) && $module_table_prefix !== FALSE){
                cms_module_config($module_directory, 'module_table_prefix', $module_table_prefix);
            }
            if(isset($module_prefix) && $module_prefix !== FALSE){
                cms_module_prefix($module_directory, $module_prefix);
            }
            // get values
            $data['module_table_prefix'] = cms_module_config($module_directory, 'module_table_prefix');
            $data['module_prefix']       = cms_module_prefix($module_directory);
        }
        $this->view($module_directory.'/install_setting', $data, 'main_module_management');
    }

    /////////////////////////////////////////////////////////////////////////////
    // Private Functions
    /////////////////////////////////////////////////////////////////////////////

    // REMOVE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function remove_all(){
        $module_path = $this->cms_module_path();
        
        // remove widgets
        $this->remove_widget($this->cms_complete_navigation_name('slideshow'));
        $this->remove_widget($this->cms_complete_navigation_name('tab'));
        $this->remove_widget($this->cms_complete_navigation_name('visitor_count'));

        // remove navigations
        $this->remove_navigation($this->cms_complete_navigation_name('manage_visitor_counter'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_tab_content'));
        $this->remove_navigation($this->cms_complete_navigation_name('manage_slide'));


        // remove parent of all navigations
        $this->remove_navigation($this->cms_complete_navigation_name('index'));
        
        // drop tables
        $this->dbforge->drop_table($this->cms_complete_table_name('visitor_counter'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('tab_content'), TRUE);
        $this->dbforge->drop_table($this->cms_complete_table_name('slide'), TRUE);
        
        /*
        // import uninstall.sql (this is only works for MySQL)
        $this->import_sql(BASEPATH.'../modules/'.$module_path.
            '/assets/db/uninstall.sql');
        */
    }

    // CREATE ALL NAVIGATIONS, WIDGETS, AND PRIVILEGES
    private function build_all(){
        $module_path = $this->cms_module_path();

        // parent of all navigations
        $this->add_navigation($this->cms_complete_navigation_name('index'), 'Accessories Widgets',
            $module_path.'/static_accessories', $this->PRIV_AUTHORIZED, 'main_management');

        // add navigations
        $this->add_navigation($this->cms_complete_navigation_name('manage_slide'), 'Slideshow',
            $module_path.'/manage_slide', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_tab_content'), 'Tabbed Content',
            $module_path.'/manage_tab_content', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        $this->add_navigation($this->cms_complete_navigation_name('manage_visitor_counter'), 'Visitor',
            $module_path.'/manage_visitor_counter', $this->PRIV_AUTHORIZED, $this->cms_complete_navigation_name('index')
        );
        
        $this->add_widget($this->cms_complete_navigation_name('slideshow'), 'Slide Show',
            $this->PRIV_EVERYONE, $module_path.'/static_accessories_widget/slide');
        $this->add_widget($this->cms_complete_navigation_name('tab'), 'Tabbed Content',
            $this->PRIV_EVERYONE, $module_path.'/static_accessories_widget/tab');
        $this->add_widget($this->cms_complete_navigation_name('visitor_count'), 'Visitor Count',
            $this->PRIV_EVERYONE, $module_path.'/static_accessories_widget/visitor_counter');

        
        // create tables
        // slide
        $fields = array(
            'slide_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'image_url'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE),
            'content'=> array("type"=>'text', "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('slide_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('slide'));

        // tab_content
        $fields = array(
            'tab_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'caption'=> array("type"=>'varchar', "constraint"=>50, "null"=>TRUE),
            'content'=> array("type"=>'text', "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('tab_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('tab_content'));

        // visitor_counter
        $fields = array(
            'counter_id'=> $this->TYPE_INT_UNSIGNED_AUTO_INCREMENT,
            'ip'=> array("type"=>'varchar', "constraint"=>20, "null"=>TRUE),
            'time'=> $this->TYPE_DATETIME_NULL,
            'agent'=> array("type"=>'varchar', "constraint"=>100, "null"=>TRUE)
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('counter_id', TRUE);
        $this->dbforge->create_table($this->cms_complete_table_name('visitor_counter'));

        
        /*
        // import install.sql (this only works for MySQL)
        $this->import_sql(BASEPATH.'../modules/'.$module_path.
            '/assets/db/install.sql');
        */
    }

    // IMPORT SQL FILE
    private function import_sql($file_name){
        $this->execute_SQL(file_get_contents($file_name), '/*split*/');
    }

    // EXPORT DATABASE
    private function backup_database($table_names, $limit = 100){
        
	    /* this doesn't work with PDO
	     
	    
        $module_path = $this->cms_module_path();
        $this->load->dbutil();
        $sql = '';
        
        

        // create DROP TABLE syntax
        for($i=count($table_names)-1; $i>=0; $i--){
            $table_name = $table_names[$i];
            $sql .= 'DROP TABLE IF EXISTS `'.$table_name.'`; '.PHP_EOL;
        }
        if($sql !='')$sql.= PHP_EOL;

        // create CREATE TABLE and INSERT syntax 
        
        $prefs = array(
                'tables'      => $table_names,
                'ignore'      => array(),
                'format'      => 'txt',
                'filename'    => 'mybackup.sql',
                'add_drop'    => FALSE,
                'add_insert'  => TRUE,
                'newline'     => PHP_EOL
              );
        $sql.= $this->dbutil->backup($prefs);        

        //write file
        $file_name = 'backup_'.date('Y-m-d_G:i:s').'.sql';
        file_put_contents(
                BASEPATH.'../modules/'.$module_path.'/assets/db/'.$file_name,
                $sql
            );
        */

    }
}
