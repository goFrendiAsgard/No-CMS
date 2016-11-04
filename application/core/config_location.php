<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
function get_config_location($file){
    // The configurations that usually the same as main site
    $main_configuration_list = array("autoload", "constants", "hooks", "memcached", "profiler", "smileys", "database", "foreign_chars", "migration", "rest", "user_agents", "doctypes", "grocery_crud", "mimes");
    // the config location
    $config_location = ENVIRONMENT.DIRECTORY_SEPARATOR.$file;
    if(!file_exists(APPPATH.'config/'.$config_location) && ENVIRONMENT == 'site-'.CMS_SUBSITE && in_array($file, $main_configuration_list)){
        $config_location = 'main'.DIRECTORY_SEPARATOR.$file;
    }
    return $config_location; 
}
