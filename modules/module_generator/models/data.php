<?php

/**
 * Description of data
 *
 * @author gofrendi
 */
class data extends CMS_Model{
    public function __construct() {
        parent::__construct();
    }
    public function get_tables(){
        $tables = $this->db->list_tables();
        return $tables;
    }
    
    public function get_fields($tableName){
        $fields = $this->db->field_data($tableName);
        return $fields;
    }
    
    public function get_create_syntax($tables = array()){
        $this->load->dbutil();
        $result = '';
        foreach($tables as $table){        	
            $prefs = array(
                    'tables'      => array($table),      // Array of tables to backup.
                    'ignore'      => array(),            // List of tables to omit from the backup
                    'format'      => 'txt',              // gzip, zip, txt
                    'filename'    => 'mybackup.sql',     // File name - NEEDED ONLY WITH ZIP FILES
                    'add_drop'    => FALSE,              // Whether to add DROP TABLE statements to backup file
                    'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                    'newline'     => PHP_EOL,        // Newline character used in backup file
                  );
            $sqls = addslashes($this->dbutil->backup($prefs));            
            $sqls = '          '.$sqls;  
            $sqls = explode(';', $sqls);
            for($i=0; $i<count($sqls)-1; $i++){
            	$sql = $sqls[$i]; 
            	$sql = trim($sql);
            	$sql = str_replace(PHP_EOL, PHP_EOL.'          ', $sql);
	            $result .= '        ';
	            $result .= '$this->db->query(\''.PHP_EOL;
	            $result .= $sql.PHP_EOL;
	            $result .= '        ';
	            $result .= '\');'.PHP_EOL;
            }
        }
        return $result;
    }
    
    public function get_drop_syntax($tables = array()){
        $result = '';
        foreach($tables as $table){
            $result .= '        ';
            $result .= '$this->db->query(\''.PHP_EOL;
            $result .= '          ';
            $result .= 'DROP TABLE IF EXISTS `'.$table.'`; '.PHP_EOL;
            $result .= '        ';
            $result .= '\');'.PHP_EOL;
        }
        return $result;
    }
    
    public function get_add_navigation($moduleName, $tables = array()){
        $result = '';
        $result .= '        ';
        $result .= '$this->add_navigation("'.$moduleName.'_index", "'.$moduleName.'", $this->cms_module_path()."/index", 4);'.PHP_EOL;
        foreach($tables as $table){
            $result .= '        ';
            $result .= '$this->add_navigation("'.$moduleName.'_'.$table.'", "Data '.$table.'", $this->cms_module_path()."/'.$table.'", 4, "'.$moduleName.'_index");'.PHP_EOL;
        }
        return $result;
    }
    
    public function get_remove_navigation($moduleName, $tables = array()){
        $result = '';
        foreach($tables as $table){
            $result .= '        ';
            $result .= '$this->remove_navigation("'.$moduleName.'_'.$table.'");'.PHP_EOL;
        }
        $result .= '        ';
        $result .= '$this->remove_navigation("'.$moduleName.'_index");'.PHP_EOL;
        return $result;
    }
    
    public function get_functions($moduleName, $tables = array()){
        $result = '';
        foreach($tables as $table){
            $result .= '    public function '.$table.'(){'.PHP_EOL;
            $result .= '        $crud = new grocery_CRUD();'.PHP_EOL;
            $result .= '        $crud->set_table("'.$table.'");'.PHP_EOL;
            $result .= '        $output = $crud->render();'.PHP_EOL;
            $result .= '        $this->view("grocery_CRUD", $output, "'.$moduleName.'_'.$table.'");'.PHP_EOL;
            $result .= '    }'.PHP_EOL.PHP_EOL;
        }
        return $result;
    }
}

?>
