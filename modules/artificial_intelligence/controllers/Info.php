<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Info extends CMS_Module{
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
        $this->cms_remove_navigation("ai_nnga_monitor");
        $this->cms_remove_navigation("ai_nnga_set");
        $this->cms_remove_navigation("ai_nnga_index");
        $this->cms_remove_navigation("ai_artificial_intelligence_index");
    }

    private function build_all(){
    	$original_directory = 'artificial_intelligence';
    	$module_url = $this->cms_module_path();
    	$module_main_controller_url = '';
    	if($module_url != $original_directory){
    		$module_main_controller_url = $module_url.'/'.$original_directory;
    	}else{
    		$module_main_controller_url = $module_url;
    	}

        $this->cms_add_navigation("ai_artificial_intelligence_index","Artificial Intelligence", $module_main_controller_url, 3);
        $this->cms_add_navigation("ai_nnga_index","NNGA", $module_url."/nnga/index", 3, "ai_artificial_intelligence_index");
        $this->cms_add_navigation("ai_nnga_monitor","Monitor", $module_url."/nnga/monitor", 3, "ai_nnga_index");
        $this->cms_add_navigation("ai_nnga_set","Set Parameters", $module_url."/nnga/set", 3, "ai_nnga_index");
    }
}
