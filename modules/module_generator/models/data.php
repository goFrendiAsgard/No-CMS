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
                    'add_insert'  => FALSE,              // Whether to add INSERT data to backup file
                    'newline'     => "\n"        // Newline character used in backup file
                  );
            $result .= '        ';
            $result .= '$this->db->query(\''.PHP_EOL;
            $result .= addslashes($this->dbutil->backup($prefs)).PHP_EOL;
            $result .= '        ';
            $result .= '\');'.PHP_EOL;
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
        $result .= '$this->add_navigation("'.$moduleName.'_index", "'.$moduleName.'", "'.$moduleName.'/index", 4);'.PHP_EOL;
        foreach($tables as $table){
            $result .= '        ';
            $result .= '$this->add_navigation("'.$moduleName.'_'.$table.'", "Data '.$table.'", "'.$moduleName.'/'.$table.'", 4, "'.$moduleName.'_index");'.PHP_EOL;
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
            $result .= '    public function '.$table.'(){';
            $result .= '        $crud = new grocery_CRUD();';
            $result .= '        $crud->set_table("'.$table.'");';
            $result .= '        $output = $crud->render();';
            $result .= '        $this->view("grocery_CRUD", $output, "'.$moduleName.'_'.$table.'");';
            $result .= '    }'.PHP_EOL.PHP_EOL;
        }
        return $result;
    }
}

?>
