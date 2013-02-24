<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer {
	protected $DEPENDENCIES = array();
	protected $NAME = 'gofrendi.noCMS.wysiwyg';
    protected $DESCRIPTION  = 'Manage No-CMS visually, because what you see is what you get';
	
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
        
        $this->remove_navigation("wysiwyg_index");         
    }
    
    private function build_all(){ 
    	$original_directory = 'wysiwyg';
    	$module_url = $this->cms_module_path();
    	$module_main_controller_url = '';
    	if($module_url != $original_directory){
    		$module_main_controller_url = $module_url.'/'.$original_directory;
    	}else{
    		$module_main_controller_url = $module_url;
    	}       
        $this->add_navigation("wysiwyg_index","WYSIWYG", $module_main_controller_url.'/index', 4, "main_management");
    }
}