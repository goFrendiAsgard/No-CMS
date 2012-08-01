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
        $this->add_navigation('example_index', 'Just an example', $this->cms_module_path().'/example', 1);
        $this->add_navigation('example_1', 'Example 1', $this->cms_module_path().'/example/view_1', 1, 'example_index');
        $this->add_navigation('example_2', 'Example 2', $this->cms_module_path().'/example/view_2', 1, 'example_index');
        $this->add_navigation('example_3', 'Example 3', $this->cms_module_path().'/example/view_3', 1, 'example_index');
        $this->add_navigation('example_4', 'Example 4', $this->cms_module_path().'/example/view_4', 1, 'example_index');
        
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
