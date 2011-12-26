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
        redirect('neural_network');
    }
    //this should be what happen when user uninstall this module
    protected function do_uninstall(){
        $this->remove_all();
        redirect('main');
    }
    
    private function remove_all(){
        $this->db->query("DROP TABLE IF EXISTS `nn_session`;");
        
        $this->remove_navigation("neural_network");      
    }
    
    private function build_all(){
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `nn_session` (
              `nn_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
              `nn_name` varchar(100) NOT NULL,
              `data` text,
              PRIMARY KEY (`nn_id`),
              UNIQUE KEY `nn_name` (`nn_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
         ");
        
        $this->add_navigation("neural_network","Neural network", "neural_network");
    }
}

?>
