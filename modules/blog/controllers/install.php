<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer {
	protected $DEPENDENCIES = array();
	protected $NAME = 'gofrendi.blog';
	
    //this should be what happen when user install this module
    protected function do_install(){
        $this->remove_all();
        $this->build_all();
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
    	$this->backup_database(
    			array('blog_category', 'blog_article', 'blog_category_article', 
    				'blog_comment', 'blog_photo'));
        $this->remove_all();
    }
    
    private function remove_all(){
    	$module_path = $this->cms_module_path();
        
        $this->remove_quicklink("blog_index");        
        
        $this->remove_navigation("blog_comment");
        $this->remove_navigation("blog_photo");
        $this->remove_navigation("blog_article");
        $this->remove_navigation("blog_category");
        $this->remove_navigation("blog_management");
        $this->remove_navigation("blog_index");
		 
		$this->remove_widget('blog_widget_newest');

        // import uninstall.sql
        $this->import_sql(BASEPATH.'../modules/'.$module_path.
        		'/assets/db/uninstall.sql');
    }
    
    private function build_all(){
        $module_path = $this->cms_module_path();
        
        $original_directory = 'blog';
        $module_main_controller_url = '';
        if($module_path != $original_directory){
        	$module_main_controller_url = $module_path.'/'.$original_directory;
        }else{
        	$module_main_controller_url = $module_path;
        }
        
        $this->add_navigation("blog_index","Blog", $module_main_controller_url);
        $this->add_navigation("blog_management", "Manage Blog", $module_main_controller_url."/manage", 4);
        $this->add_navigation("blog_category", "Manage Category", $module_main_controller_url."/category", 4, "blog_management");
        $this->add_navigation("blog_article", "Manage Article", $module_main_controller_url."/article", 4, "blog_management");
        $this->add_navigation("blog_photo", "Manage Photo", $module_main_controller_url."/photo", 4, "blog_management");
        $this->add_navigation("blog_comment", "Manage Comment", $module_main_controller_url."/comment", 4, "blog_management");
        
        $this->add_quicklink('blog_index');
		
		$this->add_widget('blog_widget_newest','Article',1,$module_path.'/widget/newest','sidebar');
        
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