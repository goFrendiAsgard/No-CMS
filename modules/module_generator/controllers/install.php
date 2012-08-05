<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer{
	protected $DEPENDENCIES = array();
	protected $NAME = 'gofrendi.noCMS.moduleGenerator';
    
    //this should be what happen when user install this module
    protected function do_install(){
        $this->remove_all();
        $this->build_all();
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
        $this->remove_all();
    }
    
    private function remove_all(){
        $this->remove_navigation("module_generator_index");        
    }
    
    private function build_all(){  
    	$original_directory = 'module_generator';
    	$module_url = $this->cms_module_path();
    	$module_main_controller_url = '';
    	if($module_url != $original_directory){
    		$module_main_controller_url = $module_url.'/'.$original_directory;
    	}else{
    		$module_main_controller_url = $module_url;
    	}      
        $this->add_navigation("module_generator_index","Module Generator", $module_main_controller_url."/index", 4, 'main_management');
    }
}

?>
