<?php

/**
 * Description of install
 *
 * @author gofrendi
 */
class Install extends CMS_Module_Installer {
    //this should be what happen when user install this module
    protected function do_install(){
        $this->remove_all();
        $this->build_all();
        redirect('multiplayer');
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
        $this->remove_all();
        redirect('main');
    }
    
    private function remove_all(){
        $this->db->query("DROP TABLE IF EXISTS `multiplayer_position`;");
        
        $this->remove_navigation("multiplayer");
    }
    
    private function build_all(){
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `multiplayer_position` (
              `user_id` int(20) unsigned NOT NULL,
              `x` int(20) unsigned NOT NULL DEFAULT 0,
              `y` int(20) unsigned NOT NULL DEFAULT 0,
              `z` int(20) unsigned NOT NULL DEFAULT 0,
              `r` int(20) unsigned NOT NULL DEFAULT 0,
              `g` int(20) unsigned NOT NULL DEFAULT 0,
              `b` int(20) unsigned NOT NULL DEFAULT 0,
              PRIMARY KEY (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
        
        $this->add_navigation("multiplayer","Multiplayer game prototype with HTML5", "multiplayer", 3);
    }
}

?>
