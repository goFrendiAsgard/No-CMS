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
        redirect('artificial_intelligence/index');
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
        $this->remove_all();
        redirect('main');
    }
    
    private function remove_all(){
        $this->db->query("DROP TABLE IF EXISTS `ai_session`;");
         
        $this->remove_navigation("ai_nnga_monitor"); 
        $this->remove_navigation("ai_nnga_set");
        $this->remove_navigation("ai_nnga_index");
        $this->remove_navigation("ai_artificial_intelligence_index");
    }
    
    private function build_all(){
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `ai_session` (
              `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` int(20) unsigned NOT NULL,
              `identifier` varchar(100) NOT NULL,
              `data` longtext,
              PRIMARY KEY (`id`),
              UNIQUE KEY `identifier` (`identifier`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
        
        $this->add_navigation("ai_artificial_intelligence_index","Artificial Intelligence", "artificial_intelligence", 3);
        $this->add_navigation("ai_nnga_index","NNGA", "artificial_intelligence/nnga/index", 3, "ai_artificial_intelligence_index");
        $this->add_navigation("ai_nnga_monitor","Monitor", "artificial_intelligence/nnga/monitor", 3, "ai_nnga_index");
        $this->add_navigation("ai_nnga_set","Set Parameters", "artificial_intelligence/nnga/set", 3, "ai_nnga_index");
    }
}

?>
