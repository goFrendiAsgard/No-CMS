<?php

class Generator_Model extends CMS_Model{
	
	public function __construct(){
		parent::__construct();
		$config = $this->session->userdata("db_config");
		if($config){
			$this->db = $this->load->database($config, TRUE);
		}	
	}
	
	// get tables & fields list 
	public function list_tables(){
		$list_tables = $this->db->list_tables();
		$return = array();
		foreach($list_tables as $table_name){
			$return[] = array(
					"table_name" => $table_name,
					"fields" => $this->list_fields($table_name),
				);
		}
		return $return;		
	}
	
	// get fields of certain table
	public function list_fields($table_name){
		$return = $this->db->list_fields($table_name);
		return $return;
	}

	// get auto increment field
	public function auto_increment_field($table_name){
		$table_name = addslashes($table_name);
		$sql = "SHOW COLUMNS 
			FROM `$table_name` 
			WHERE Extra LIKE '%auto_increment%'";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$row = $query->row();
			return $row->Field;
		}else{
			return '';
		}		
	}
	
	// get create tables syntax
	public function get_create_tables_syntax($table_names){
		$result_create = '';
		$result_insert = '';
		for($i=0; $i<count($table_names); $i++){
			$table = addslashes($table_names[$i]);
			
			// get create syntax
			$query = $this->db->query("SHOW CREATE TABLE `$table`");
			$row = $query->row_array();
			$result_create .= $row["Create Table"].';'.PHP_EOL; 
			
			// add splitter
			if($i<count($table_names)-1){
				$result_create.="/*split*/".PHP_EOL;
			}
			
			// get insert syntax			
			$query = $this->db->get($table);
			if($query->num_rows()>0){
				$list_fields = $this->list_fields($table);
				// get quoted list fields
				$quoted_list_fields = array();
				foreach($list_fields as $field){
					$quoted_list_fields[] .= "`$field`";
				}
				// add splitter
				$result_insert .= PHP_EOL.'/*split*/'.PHP_EOL;
				// insert into segment
				$result_insert .= "INSERT INTO `$table`(".
					implode(',', $quoted_list_fields).") VALUES".PHP_EOL;
				// values segment
				$values_array = array();
				foreach($query->result_array() as $row){
					$value_str = "  (";
					$field_value = array();
					foreach($list_fields as $field){
						$field_value[$field] = "'".addslashes($row[$field])."'";
					}					
					$value_str .= implode(", ", $field_value);
					$value_str .= ")";	
					$values_array[] = $value_str;
				}
				// combine them all
				$result_insert .= implode(",".PHP_EOL, $values_array).';'.PHP_EOL;
			}			
		}
		
		//combine all the result
		$result = $result_create.$result_insert;
		
		return $result;
	}
	
	public function get_drop_tables_syntax($table_names){
		$result = '';
		for($i=0; $i<count($table_names); $i++){
			
			// create drop syntax
			$table = $table_names[$i];
			$result .= 'DROP TABLE IF EXISTS `'.$table.'`; '.PHP_EOL;
			
			// add splitter
			if($i<count($table_names)-1){
				$result.="/*split*/".PHP_EOL;
			}
		}	
		return $result;	
	}
	
}