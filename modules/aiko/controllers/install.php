<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer{
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
        $this->remove_navigation("aiko_index");
    }
    
    private function build_all(){        
        $this->add_navigation("aiko_index","AIKO-PHP (Artificial Intelligence Kit On PHP)", "aiko", 3);
    }
}

?>
