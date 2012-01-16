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
    //put your code here
    public function do_install(){
        $this->add_navigation('help', 'No-CMS User guide', 'help', 1);
        redirect('help');
    }
    
    public function do_uninstall(){
        $this->remove_navigation('help');
        redirect('main');
    }
}

?>
