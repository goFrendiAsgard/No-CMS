<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer{
	protected $DEPENDENCIES = array();
    protected $NAME = 'gofrendi.artificial_intelligence';
	
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
        $this->remove_navigation("ai_nnga_monitor"); 
        $this->remove_navigation("ai_nnga_set");
        $this->remove_navigation("ai_nnga_index");
        $this->remove_navigation("ai_artificial_intelligence_index");
    }
    
    private function build_all(){        
        $this->add_navigation("ai_artificial_intelligence_index","Artificial Intelligence", $this->cms_module_path()."/artificial_intelligence", 3);
        $this->add_navigation("ai_nnga_index","NNGA", $this->cms_module_path()."/nnga/index", 3, "ai_artificial_intelligence_index");
        $this->add_navigation("ai_nnga_monitor","Monitor", $this->cms_module_path()."/nnga/monitor", 3, "ai_nnga_index");
        $this->add_navigation("ai_nnga_set","Set Parameters", $this->cms_module_path()."/nnga/set", 3, "ai_nnga_index");
    }
}

?>
