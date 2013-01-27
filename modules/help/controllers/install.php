<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of install
 *
 * @author goFrendiAsgard
 */
class Install extends CMS_Module_Installer {
    protected $DEPENDENCIES = array();
    protected $NAME = 'gofrendi.noCMS.help';

    //this should be what happen when user install this module
    protected function do_install(){
        $this->remove_all();
        $this->build_all();
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
    	$this->backup_database(array('help_group', 'help_topic'));
        $this->remove_all();
    }
    
    private function remove_all(){
    	$module_path = $this->cms_module_path();
    	
    	$this->remove_quicklink("help_index");
    	
        $this->remove_navigation("help_topic");
        $this->remove_navigation("help_group");
        $this->remove_navigation("help_index");
        
        // import uninstall.sql
        $this->import_sql(BASEPATH.'../modules/'.$module_path.
        		'/assets/db/uninstall.sql');

    }
    
    private function build_all(){
    	$module_path = $this->cms_module_path();
    	
    	$original_directory = 'help';
    	$module_main_controller_url = '';
    	if($module_path != $original_directory){
    		$module_main_controller_url = $module_path.'/'.$original_directory;
    	}else{
    		$module_main_controller_url = $module_path;
    	}
    	
        $this->add_navigation("help_index", "No-CMS User Guide", $module_main_controller_url."/index", 1);
        $this->add_navigation("help_group", "Topic Group", $module_main_controller_url."/data_group", 4, "help_index");
        $this->add_navigation("help_topic", "Topic", $module_main_controller_url."/data_topic", 4, "help_index");
        
        $this->add_quicklink("help_index");

        // import install.sql
        $this->import_sql(BASEPATH.'../modules/'.$module_path.
            '/assets/db/install.sql');
    }
    
    private function import_sql($file_name){
    	$sql_array = explode('/*split*/',
    			file_get_contents($file_name)
    	);
    	foreach($sql_array as $sql){
    		$this->db->query($sql);
    	}
    }
    
    private function backup_database($table_names, $limit = 100){
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
    
    }
}
