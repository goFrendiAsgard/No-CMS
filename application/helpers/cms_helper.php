<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function cms_table_prefix($new_prefix = NULL){
    $file_name = APPPATH.'config/cms_config.php';
    if(!file_exists($file_name)) return '';
    if(!isset($new_prefix)){
        $CI =& get_instance();
        $CI->config->load('cms_config');
        $table_prefix = $CI->config->item('table_prefix');
        return $table_prefix; 
    }else{        
        $str = file_get_contents($file_name);
        $pattern = array();
        $pattern[] = '/(config\[(\'|")table_prefix(\'|")\] *= *")(.*?)(")/si';
        $pattern[] = "/(config\[('|\")table_prefix('|\")\] *= *')(.*?)(')/si"; 
        $replacement = '$1'.$new_prefix.'$5';        
        $str = preg_replace($pattern, $replacement, $str);
        file_put_contents($file_name, $str);            
    }    
}

function cms_module_table_prefix($module_directory, $new_prefix = NULL){
    $file_name = BASEPATH.'../modules/'.$module_directory.'/config/module_config.php';
    if(!file_exists($file_name)) return '';
    if(!isset($new_prefix)){
        $CI =& get_instance();
        $CI->config->load($module_directory.'/module_config');
        $module_prefix = $CI->config->item('table_prefix');
        $cms_table_prefix = $this->cms_table_prefix();
        if($cms_table_prefix != ''){
            return $cms_table_prefix.'_'.$module_table_prefix;
        }else{
            return $module_table_prefix;
        }
    }else{        
        $str = file_get_contents($file_name);
        $pattern = array();
        $pattern[] = '/(config\[(\'|")table_prefix(\'|")\] *= *")(.*?)(")/si';
        $pattern[] = "/(config\[('|\")table_prefix('|\")\] *= *')(.*?)(')/si"; 
        $replacement = '$1'.$new_prefix.'$5';        
        $str = preg_replace($pattern, $replacement, $str);
        file_put_contents($file_name, $str); 
    }
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
