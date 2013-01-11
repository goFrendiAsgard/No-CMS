<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer{
	protected $DEPENDENCIES = array();
	protected $NAME = 'gofrendi.noCMS.example';
	
    //put your code here
    public function do_install(){
    	$original_directory = 'example';
    	$module_url = $this->cms_module_path();
    	$module_main_controller_url = '';
    	if($module_url != $original_directory){
    		$module_main_controller_url = $module_url.'/'.$original_directory;
    	}else{
    		$module_main_controller_url = $module_url;
    	}
    	
        $this->add_navigation('example_index', 'Just an example', $module_main_controller_url, 1);
        $this->add_navigation('example_1', 'Example 1', $module_main_controller_url.'/view_1', 1, 'example_index');
        $this->add_navigation('example_2', 'Example 2', $module_main_controller_url.'/view_2', 1, 'example_index');
        $this->add_navigation('example_3', 'Example 3', $module_main_controller_url.'/view_3', 1, 'example_index');
        $this->add_navigation('example_4', 'Example 4', $module_main_controller_url.'/view_4', 1, 'example_index');
        
    }
    
    public function do_uninstall(){
    	$this->remove_navigation('example_1');
    	$this->remove_navigation('example_2');
    	$this->remove_navigation('example_3');
    	$this->remove_navigation('example_4');
        $this->remove_navigation('example_index');
    }
}

?>
