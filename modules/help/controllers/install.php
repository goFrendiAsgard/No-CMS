<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of install
 *
 * @author theModuleGenerator
 */
class Install extends CMS_Module_Installer {
    protected $DEPENDENCIES = array();
    protected $NAME = 'gofrendi.noCMS.help';

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
        $this->remove_navigation("help_topic");
        $this->remove_navigation("help_group");
        $this->remove_navigation("help_index");

        $this->db->query('
            DROP TABLE IF EXISTS `help_topic`; 
        ');
        $this->db->query('
            DROP TABLE IF EXISTS `help_group`; 
        ');

    }
    
    private function build_all(){
    	$original_directory = 'help';
    	$module_url = $this->cms_module_path();
    	$module_main_controller_url = '';
    	if($module_url != $original_directory){
    		$module_main_controller_url = $module_url.'/'.$original_directory;
    	}else{
    		$module_main_controller_url = $module_url;
    	}
    	
        $this->add_navigation("help_index", "No-CMS User Guide", $module_main_controller_url."/index", 1);
        $this->add_navigation("help_group", "Topic Group", $module_main_controller_url."/data_group", 4, "help_index");
        $this->add_navigation("help_topic", "Topic", $module_main_controller_url."/data_topic", 4, "help_index");

        $this->db->query('
            #
            # TABLE STRUCTURE FOR: help_group
            #
            
            CREATE TABLE `help_group` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(60) NOT NULL,
        	  `url` varchar(60) NOT NULL,
        	  `content` text,
              PRIMARY KEY (`id`),
              UNIQUE KEY `name` (`name`),
        	  UNIQUE KEY `url`(`url`)
            ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1
        ');
        $this->db->query('
            #
            # TABLE STRUCTURE FOR: help_topic
            #
            
            CREATE TABLE `help_topic` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `group_id` int(11) NOT NULL,
              `title` varchar(60) NOT NULL,
        	  `url` varchar(60) NOT NULL,
              `content` text NOT NULL,
              PRIMARY KEY (`id`),
        	  UNIQUE KEY `title`(`title`),
        	  UNIQUE KEY `url`(`url`),
              KEY `group_id` (`group_id`),
              CONSTRAINT `help_topic_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `help_group` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1
        ');
        
        // import from external file
        $db_path = BASEPATH.'../modules/'.$this->cms_module_path().'/db/';
        $this->import_sql($db_path.'help_group.sql');
        $this->import_sql($db_path.'help_topic.sql');
    }
    
    private function import_sql($filename){
    	$str = file_get_contents($filename);
    	$this->db->query($str);
    }
}

?>
