<?php

/**
 * Description of data
 *
 * @author gofrendi
 */
class data extends CMS_Model{
	public $_ = '    ';
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
    	$_ = $this->_;
        $this->load->dbutil();
        $result = '';
        $create_table = '';
        $insert_record = '';
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
            $sqls = $this->dbutil->backup($prefs);            
            $sqls = '          '.$sqls;  
            $sqls = explode(';', $sqls);
            for($i=0; $i<count($sqls)-1; $i++){
            	$sql = $sqls[$i]; 
            	$sql = trim($sql);
            	$sql = str_replace(PHP_EOL, PHP_EOL.$_.$_.$_, $sql);
            	$str = '';
            	$str .= $_.$_. '$this->db->query("'.PHP_EOL;
            	$str .= $_.$_.$_. $sql.PHP_EOL;
            	$str .= $_.$_. '");'.PHP_EOL;
            	if($i==0){
		            $create_table .= $str;
            	}else{
            		$insert_record .= $str;
            	}
            }
        }
        $result = $create_table.$insert_record;
        return $result;
    }
    
    public function get_drop_syntax($tables = array()){
    	$_ = $this->_;
        $result = '';
        foreach($tables as $table){
            $result .= $_.$_. '$this->db->query("'.PHP_EOL;
            $result .= $_.$_.$_. 'DROP TABLE IF EXISTS `'.$table.'`; '.PHP_EOL;
            $result .= $_.$_. '");'.PHP_EOL;
        }
        return $result;
    }
    
    public function get_add_navigation($moduleDirectory, $tables = array()){
    	$_ = $this->_;
        $result = '';
        $result .= $_.$_.'$original_directory = "'.$moduleDirectory.'";'.PHP_EOL;    
    	$result .= $_.$_.'$module_url = $this->cms_module_path();'.PHP_EOL;
    	$result .= $_.$_.'$module_main_controller_url = "";'.PHP_EOL;
    	$result .= $_.$_.'if($module_url != $original_directory){'.PHP_EOL;
    	$result .= $_.$_.'	$module_main_controller_url = $module_url."/".$original_directory;'.PHP_EOL;
    	$result .= $_.$_.'}else{'.PHP_EOL;
    	$result .= $_.$_.'	$module_main_controller_url = $module_url;'.PHP_EOL;
    	$result .= $_.$_.'}'.PHP_EOL;
        $result .= $_.$_.'$this->add_navigation("'.$moduleDirectory.'_index", "'.$moduleDirectory.'", $module_main_controller_url."/index", 4);'.PHP_EOL;
        foreach($tables as $table){
            $result .= $_.$_.'$this->add_navigation("'.$moduleDirectory.'_'.$table.'", "Data '.$table.'", $module_main_controller_url."/'.$moduleDirectory.'_'.$table.'", 4, "'.$moduleDirectory.'_index");'.PHP_EOL;
        }
        return $result;
    }
    
    public function get_remove_navigation($moduleDirectory, $tables = array()){
    	$_ = $this->_;
    	$result = '';
        foreach($tables as $table){
            $result .= $_.$_.'$this->remove_navigation("'.$moduleDirectory.'_'.$table.'");'.PHP_EOL;
        }
        $result .= $_.$_.'$this->remove_navigation("'.$moduleDirectory.'_index");'.PHP_EOL;
        return $result;
    }
    
    public function get_functions($moduleDirectory, $tables = array()){
    	$_ = $this->_;
    	
    	$this->load->helper('inflector');
        $result = '';
        foreach($tables as $table){
        	// get column list
        	$column_list = '';
        	$display_as = '';
        	$columns = $this->get_fields($table);
        	for($i=0; $i<count($columns); $i++){
        		$column = $columns[$i];
        		$column_primary = $column->primary_key;
        		if($column_primary != 1){
	        		$column_name = $column->name;
	        		$humanize_column_name = humanize($column_name);
	        		$column_list .= "'".$column_name."'";
	        		$display_as .= "->display_as('$column_name','$humanize_column_name')";
	        		if($i<count($columns)-1){
	        			$column_list .= ", ";
	        			$display_as .= PHP_EOL.$_.$_.$_;
	        		}
        		}
        	}
        	
            $result .= $_.'public function '.$moduleDirectory.'_'.$table.'(){'.PHP_EOL;
            $result .= $_.$_.'$crud = new grocery_CRUD();'.PHP_EOL;
            $result .= $_.$_.PHP_EOL;
            $result .= $_.$_.'// table name'.PHP_EOL;
            $result .= $_.$_.'$crud->set_table("'.$table.'");'.PHP_EOL;
            $result .= $_.$_.PHP_EOL;
            $result .= $_.$_.'// displayed columns on list'.PHP_EOL;
            $result .= $_.$_.'$crud->columns('.$column_list.');'.PHP_EOL;
            $result .= $_.$_.'// displayed columns on edit operation'.PHP_EOL;
            $result .= $_.$_.'$crud->edit_fields('.$column_list.');'.PHP_EOL;
            $result .= $_.$_.'// displayed columns on add operation'.PHP_EOL;
            $result .= $_.$_.'$crud->add_fields('.$column_list.');'.PHP_EOL;
            $result .= $_.$_.PHP_EOL;
            $result .= $_.$_.'// caption of each columns'.PHP_EOL;
            $result .= $_.$_.'$crud'.$display_as.';'.PHP_EOL;
            $result .= $_.$_.PHP_EOL;
            $result .= $_.$_.'// render'.PHP_EOL;
            $result .= $_.$_.'$output = $crud->render();'.PHP_EOL;
            $result .= $_.$_.'$this->view("grocery_CRUD", $output, "'.$moduleDirectory.'_'.$table.'");'.PHP_EOL;
            $result .= $_.'}'.PHP_EOL.PHP_EOL;
        }
        return $result;
    }
}

?>
