&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function module_table_prefix($new_prefix = NULL){
    if(!isset($new_prefix)){
        $CI =& get_instance();
        $CI->config->load('{{ project_name }}/config');
        $table_prefix = $CI->config->item('table_prefix');
        return $table_prefix; 
    }else{
        $file_name = BASEPATH.'../modules/{{ project_name }}/config/config.php';
        $str = file_get_contents($file_name);
        $pattern = array();
        $pattern[] = '/(config\[(\'|")table_prefix(\'|")\] *= *")(.*?)(")/si';
        $pattern[] = "/(config\[('|\")table_prefix('|\")\] *= *')(.*?)(')/si"; 
        $replacement = '$1'.$new_prefix.'$5';        
        $str = preg_replace($pattern, $replacement, $str);
        file_put_contents($file_name, $str);
            
    }    
}

function module_table_name($table_name){
    $table_prefix = table_prefix();
    return $table_prefix.'_'.$table_name;
}
