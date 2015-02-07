<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function __cms_config($key, $value = NULL, $delete = FALSE, $file_name, $config_load_alias){
    if(!file_exists($file_name)) return FALSE;
    $pattern = array();
    $pattern[] = '/(\$config\[(\'|")'.$key.'(\'|")\] *= *")(.*?)(";)/si';
    $pattern[] = "/(".'\$'."config\[('|\")".$key."('|\")\] *= *')(.*?)(';)/si";

    if($delete){
        $replacement = '';
        $str = file_get_contents($file_name);
        $str = preg_replace($pattern, $replacement, $str);
        @chmod($file_name,0777);
        if(strpos($str, '<?php') !== FALSE && strpos($str, '$config') !== FALSE){
            @file_put_contents($file_name, $str);
            @chmod($file_name,0555);
        }
        return FALSE;
    }else{
        if($value === NULL){

            // enforce refresh
            if(function_exists('opcache_invalidate')){
                opcache_invalidate($file_name);
            }
            include($file_name);

            if(!isset($config)){
                $config = array();
            }
            if(key_exists($key, $config)){
                $value = $config[$key];
            }else{
                $value = '';
            }
            return $value;
        }else{
            $str = file_get_contents($file_name);
            $replacement = '${1}'.addslashes($value).'${5}';
            $found = FALSE;
            foreach($pattern as $single_pattern){
                if(preg_match($single_pattern,$str)){
                    $found = TRUE;
                    break;
                }
            }
            if(!$found){
                $str .= PHP_EOL.'$config[\''.$key.'\'] = \''.addslashes($value).'\';';
            }
            else{
                $str = preg_replace($pattern, $replacement, $str);
            }
            @chmod($file_name,0777);
            if(strpos($str, '<?php') !== FALSE && strpos($str, '$config') !== FALSE){
                @file_put_contents($file_name, $str, LOCK_EX);
                @chmod($file_name,0555);
            }
            return $value;
        }
    }

}

/**
 * @author goFrendiAsgard
 * @param string $key
 * @param string $value
 * @param bool $delete
 * @desc get/set cms configuration value. if delete == TRUE, then the key will be deleted
 */
function cms_config($key, $value = NULL, $delete = FALSE){
    if(defined('CMS_SUBSITE') && CMS_SUBSITE != ''){
        $file_name = APPPATH.'config/site-'.CMS_SUBSITE.'/cms_config.php';
    }else{
        $file_name = APPPATH.'config/cms_config.php';
    }
    $config_load_alias = 'cms_config';
    return __cms_config($key, $value, $delete, $file_name, $config_load_alias);
}

/**
 * @author goFrendiAsgard
 * @param string $key
 * @param string $value
 * @param bool $delete
 * @desc get/set module configuration value. if delete == TRUE, then the key will be deleted
 */
function cms_module_config($module_directory, $key, $value = NULL, $delete = FALSE){
    $main_config_file_name = FCPATH.'modules/'.$module_directory.'/config/module_config.php';
    if(!file_exists($main_config_file_name)){
        $content  = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');".PHP_EOL.PHP_EOL;
        $content .= '$config[\'module_table_prefix\']  = \'\';'.PHP_EOL;
        $content .= '$config[\'module_prefix\']        = \'\';'.PHP_EOL;
        file_put_contents($main_config_file_name, $content);
    }
    if(defined('CMS_SUBSITE') && CMS_SUBSITE != ''){
        $file_name = FCPATH.'modules/'.$module_directory.'/config/module_config_'.CMS_SUBSITE.'.php';
        if(!file_exists($file_name)){
            copy($main_config_file_name, $file_name);
        }
    }else{
        $file_name = $main_config_file_name;
    }
    $config_load_alias = $module_directory.'/module_config';
    return __cms_config($key, $value, $delete, $file_name, $config_load_alias);
}


function cms_table_prefix($new_prefix = NULL){
    return cms_config('__cms_table_prefix', $new_prefix);
}

function cms_module_table_prefix($module_directory, $new_prefix = NULL){
    $module_table_prefix = cms_module_config($module_directory, 'module_table_prefix', $new_prefix);
    if($module_table_prefix == ''){
        return cms_table_prefix();
    }else{
        return cms_table_name($module_table_prefix);
    }
}

function cms_module_prefix($module_directory, $new_prefix = NULL){
    return $module_table_prefix = cms_module_config($module_directory, 'module_prefix', $new_prefix);
}

function cms_table_name($table_name){
    $table_prefix = cms_table_prefix();
    if($table_prefix != ''){
        return $table_prefix.'_'.$table_name;
    }else{
        return $table_name;
    }
}

function cms_module_table_name($module_directory, $table_name){
    $table_prefix = cms_module_table_prefix($module_directory);
    if($table_prefix != ''){
        return $table_prefix.'_'.$table_name;
    }else{
        return $table_name;
    }
}

function cms_module_navigation_name($module_directory, $name){
    $module_prefix = cms_module_prefix($module_directory);
    if($module_prefix != ''){
        return $module_prefix.'_'.$name;
    }else{
        return $name;
    }
}

function cms_half_md5($data){
    return md5(md5(md5($data)));
}
function cms_md5($data, $chipper = NULL){
    $chipper = $chipper === NULL? cms_config('__cms_chipper') : $chipper;
    $return = crypt(cms_half_md5(cms_half_md5($data)), $chipper);
    return $return;
}
function _xor($data, $chipper=array(1,2,3,4,5,6,7)){
    while(count($chipper) < count($data)){
        $chipper = array_merge($chipper, $chipper);
    }
    $new_data = array();
    for($i=0; $i<count($data); $i++){
        $new_data[] = ($data[$i]+0) ^ ($chipper[$i]+0);
    }
    return $new_data;
}
function cms_encode($data, $chipper = NULL){
    $chipper = $chipper === NULL? cms_config('__cms_chipper') : $chipper;
    $data_array = array();
    $chipper_array = array();
    for($i=0; $i<strlen($data); $i++){
        $data_array[] = ord($data[$i]);
    }
    for($i=0; $i<strlen($chipper); $i++){
        $chipper_array[] = ord($chipper[$i]);
    }
    $encoded_array = _xor($data_array, $chipper_array);
    $encoded_str = implode('-', $encoded_array);
    return $encoded_str;
}
function cms_decode($data, $chipper = NULL){
    $chipper = $chipper === NULL? cms_config('__cms_chipper') : $chipper;
    $data_array = explode('-', $data);
    $chipper_array = array();
    for($i=0; $i<strlen($chipper); $i++){
        $chipper_array[] = ord($chipper[$i]);
    }
    $decoded_array = _xor($data_array, $chipper_array);
    $decoded_str = '';
    for($i=0; $i<count($decoded_array); $i++){
        $decoded_str .= chr($decoded_array[$i]);
    }
    return $decoded_str;
}

/*
 Rename Install into _Info
*/
function cms_update_module_installer(){
    $ci =& get_instance();
    $ci->load->helper('directory');
    $directories = directory_map(FCPATH.'modules', 1);
    sort($directories);
    $module      = array();
    foreach ($directories as $directory) {
        $directory = str_replace(array('/','\\'),'',$directory);
        if (!is_dir(FCPATH.'modules/' . $directory))
            continue;

        // get old and new installer name
        $old_installer = FCPATH.'modules/' . $directory . '/controllers/install.php';
        $new_installer = FCPATH.'modules/' . $directory . '/controllers/_info.php';

        if (!file_exists($old_installer) || file_exists($new_installer))
            continue;

        // make new installer
        $content = file_get_contents($old_installer);
        $content = preg_replace('/class( *)Install( *)extends( *)CMS_Module_Installer/i', 
            'class _Info extends CMS_Module_Info_Controller', $content);
        file_put_contents($new_installer, $content);
        unlink($old_installer);
    }

}