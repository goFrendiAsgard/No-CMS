<?php
class Nds_Model extends CMS_Model{
	public $available_data_type = array(
			'int','varchar','char','real','text','date',
			'tinyint', 'smallint', 'mediumint', 'integer', 'bigint', 'float', 'double',
			'decimal', 'numeric', 'datetime', 'timestamp', 'time',
			'year', 'tinyblob', 'tinytext', 'blob', 'mediumblob', 'mediumtext',
			'longblob', 'longtext',
		);
	public $type_without_length = array('text','date','datetime','timestamp','time','year',
			'float', 'double', 'decimal', 'tinyblob', 'tinytext', 'blob', 'mediumblob',
			'mediumtext', 'longblob', 'longtext'
		);
	public $auto_increment_data_type = array('int', 'tinyint', 'smallint', 'mediumint', 'integer', 'bigint');
	public $detault_data_type = 'varchar';

	public function get_all_project(){
		$query = $this->db->select('project_id, '.$this->cms_complete_table_name('project.name').
		      ', '.$this->cms_complete_table_name('template').'.generator_path')
			->from($this->cms_complete_table_name('project'))
			->join($this->cms_complete_table_name('template'),
			     $this->cms_complete_table_name('project').'.template_id = '.$this->cms_complete_table_name('template').'.template_id')
			->get();
		return $query->result();
	}
	public function get_template_option_by_template($template_id){
		$query = $this->db->select('option_id, name')
			->from($this->cms_complete_table_name('template_option'))
			->where('template_id', $template_id)
			->get();
		return $query->result();
	}
	public function get_table_by_project($project_id){
		$query = $this->db->select('table_id, name')
			->from($this->cms_complete_table_name('table'))
			->where('project_id', $project_id)
			->get();
		return $query->result();
	}
	public function get_column_by_table($table_id){
		$query = $this->db->select('column_id, name')
			->from($this->cms_complete_table_name('column'))
			->where('table_id', $table_id)
			->get();
		return $query->result();
	}

    private function strip_table_prefix($table_name, $db_table_prefix){
        if(!isset($db_table_prefix) || $db_table_prefix == ''){
            return $table_name;
        }
        if(strpos($table_name, $db_table_prefix) === 0){
            $table_name = substr($table_name, strlen($db_table_prefix));
        }
        if(strlen($table_name)>0 && $table_name[0]=='_'){
            $table_name = substr($table_name,1);
        }
        return $table_name;
    }

	// data to generate
	public function get_project($project_id){
	    $db_table_prefix = '';
		$data = FALSE;
		$query = $this->db->select('project_id, template_id, name, db_server, db_port, db_schema, db_user, db_password, db_table_prefix')
			->from($this->cms_complete_table_name('project'))
			->where('project_id', $project_id)
			->get();
		if($query->num_rows()>0){
			$data = $query->row_array();
			$template_id = $data['template_id'];
            $db_table_prefix = $data['db_table_prefix'];
			$project_id = $data['project_id'];
			unset($data['template_id']);
			unset($data['project_id']);

			// get project, table and column option's header
			$query = $this->db->select('option_id, name, option_type')
				->from($this->cms_complete_table_name('template_option'))
				->where('template_id', $template_id)
				->get();
			$project_option_headers = array();
			$table_option_headers = array();
			$column_option_headers = array();
			foreach($query->result() as $row){
				$name = $row->name;
				$option_type = $row->option_type;
				$option_id = $row->option_id;
				switch($row->option_type){
					case 'project': $project_option_headers[$name] = $option_id; break;
					case 'table' : $table_option_headers[$name] = $option_id; break;
					case 'column' : $column_option_headers[$name] = $option_id; break;
				}
			}

			// add project template_option
			$project_options = array();
			foreach($project_option_headers as $name=>$option_id){
				$query = $this->db->select('project_id, option_id')
					->from($this->cms_complete_table_name('project_option'))
					->where(array('project_id'=>$project_id, 'option_id'=>$option_id))
					->get();
				$project_options[$name] = $query->num_rows()>0;
			}
			$data['options'] = $project_options;

			// add tables
			$tables = array();
			$query = $this->db->select('table_id, name, caption')
				->from($this->cms_complete_table_name('table'))
				->where('project_id', $project_id)
				->order_by('priority')
				->get();
			foreach($query->result_array() as $row){
				$table = $row;
				$table_id = $table['table_id'];
				unset($table['table_id']);
				// get table name
				$table['name'] = addslashes($table['name']);				
	            if($db_table_prefix != '' && $db_table_prefix !== NULL){
	                if(strpos($table['name'], $db_table_prefix.'_') !== 0){
	                    $table['name'] = $db_table_prefix.'_'.$table['name'];
	                }
	            }
                $table['stripped_name'] = $this->strip_table_prefix($table['name'], $db_table_prefix);
				$table['caption'] = addslashes($table['caption']);

				// get table options
				$table_options = array();
				foreach($table_option_headers as $name=>$option_id){
					$query = $this->db->select('table_id, option_id')
						->from($this->cms_complete_table_name('table_option'))
						->where(array('table_id'=>$table_id, 'option_id'=>$option_id))
						->get();
					$table_options[$name] = $query->num_rows()>0;
				}
				$table['options'] = $table_options;

				// get column options
				$columns = array();
				$query = $this->db->select('column_id, caption, name, data_type, data_size, role, lookup_table_id, lookup_column_id,
					relation_table_id, relation_table_column_id, relation_selection_column_id, relation_priority_column_id,
					selection_table_id, selection_column_id, value_selection_mode, value_selection_item')
					->from($this->cms_complete_table_name('column'))
					->where('table_id', $table_id)
					->order_by('priority')
					->get();
				foreach($query->result_array() as $row){
					$column = $row;
					$column_id = $column['column_id'];
					$column['name'] = addslashes($column['name']);
					$column['caption'] = addslashes($column['caption']);
					unset($column['column_id']);
					// lookup
					$column ['lookup_table_name'] = $this->get_table_name($column['lookup_table_id']);
                    $column ['lookup_stripped_table_name'] = $this->strip_table_prefix($column['lookup_table_name'], $db_table_prefix);
					$column ['lookup_column_name'] = $this->get_column_name($column['lookup_column_id']);
					$column['lookup_table_primary_key'] = $this->get_primary_key($column['lookup_table_id']);
					unset($column['lookup_table_id']);
					unset($column['lookup_column_id']);
					// relation
					$column ['relation_table_name'] = $this->get_table_name($column['relation_table_id']);
                    $column ['relation_stripped_table_name'] = $this->strip_table_prefix($column['relation_table_name'], $db_table_prefix);
					$column ['relation_table_column_name'] = $this->get_column_name($column['relation_table_column_id']);
					$column ['relation_priority_column_name'] = $this->get_column_name($column['relation_priority_column_id']);
					$column ['relation_selection_column_name'] = $this->get_column_name($column['relation_selection_column_id']);
					unset($column['relation_selection_column_id']);
					unset($column['relation_priority_column_id']);
					unset($column['relation_table_id']);
					unset($column['relation_table_column_id']);
					// selection
					$column ['selection_table_name'] = $this->get_table_name($column['selection_table_id']);
                    $column ['selection_stripped_table_name'] = $this->strip_table_prefix($column['selection_table_name'], $db_table_prefix);
					$column ['selection_column_name'] = $this->get_column_name($column['selection_column_id']);
					$column['selection_table_primary_key'] = $this->get_primary_key($column['selection_table_id']);
					unset($column['selection_column_id']);
					unset($column['selection_table_id']);
					// value selection (for enum and set)
					$column['value_selection_item'] = isset($column['value_selection_item'])?$column['value_selection_item']:'';
					$column['value_selection_mode'] = isset($column['value_selection_mode'])?$column['value_selection_mode']:'';
					if($column['value_selection_mode']!=''){
						$column['data_size'] = 255;
					}

					// get table options
					$column_options = array();
					foreach($column_option_headers as $name=>$option_id){
						$query = $this->db->select('column_id, option_id')
							->from($this->cms_complete_table_name('column_option'))
							->where(array('column_id'=>$column_id, 'option_id'=>$option_id))
							->get();
						$column_options[$name] = $query->num_rows()>0;
					}
					$column['options'] = $column_options;

					$columns[] = $column;
				}
				$table['columns'] = $columns;

				$tables[] = $table;
			}
			$data['tables'] = $tables;


		}
		return $data;
	}

	public function get_project_name($project_id){
		$query = $this->db->select('name')->from($this->cms_complete_table_name('project'))->where('project_id',$project_id)->get();
		if($query->num_rows()>0){
			$row = $query->row();
			return addslashes($row->name);
		}else{
			return '';
		}
	}

	public function get_table_name($table_id){
		$query = $this->db->select('name')->from($this->cms_complete_table_name('table'))->where('table_id',$table_id)->get();
		if($query->num_rows()>0){
			$row = $query->row();
			return addslashes($row->name);
		}else{
			return '';
		}
	}

	public function get_column_name($column_id){
		$query = $this->db->select('name')->from($this->cms_complete_table_name('column'))->where('column_id',$column_id)->get();
		if($query->num_rows()>0){
			$row = $query->row();
			return addslashes($row->name);
		}else{
			return '';
		}
	}

	public function get_primary_key($table_id){
		$query = $this->db->select('name')
			->from($this->cms_complete_table_name('column'))
			->where(array('table_id'=>$table_id, 'role'=>'primary'))
			->get();
		if($query->num_rows()>0){
			$row = $query->row();
			return addslashes($row->name);
		}else{
			return '';
		}
	}

	// to install new template
	public function install_template($template_name, $generator_path, $project_options = array(), $table_options = array(), $column_options = array()){
		$data = array(
			'name'=>$template_name,
			'generator_path'=>$generator_path,
		);
		$this->db->insert($this->cms_complete_table_name('template'), $data);
		$query = $this->db->select('template_id')->from($this->cms_complete_table_name('template'))->where('name', $template_name)->get();
		if($query->num_rows()<=0) return FALSE;
		$row = $query->row();
		$template_id = $row->template_id;
		$this->add_option($template_id, 'project', $project_options);
		$this->add_option($template_id, 'table', $table_options);
		$this->add_option($template_id, 'column', $column_options);
		return TRUE;
	}

	private function add_option($template_id, $option_type, $options){
		foreach($options as $option){
			$data = array();
			if(is_array($option)>0){
				if(isset($option['name']) && isset($option['description'])){
					$data = $option;
				}else if(count($option)>1){
					$data['name'] = $option[0];
					$data['description'] = $option[1];
				}else{
					$data['name'] = $option[0];
				}
			}else{ // string
				$data['name'] = $option;
			}
			$data['option_type'] = $option_type;
			$data['template_id'] = $template_id;
			$this->db->insert($this->cms_complete_table_name('template_option'),$data);
		}
	}
    
    public function get_create_table_forge($tables){
        $php = array();        
        foreach($tables as $table){            
            $table_name = $table['stripped_name'];
            $columns = $table['columns'];
            $primary_key_name = NULL;
            $field_list = array();
            foreach($columns as $column){
                $column_name = $column['name'];
                $column_type = $column['data_type'];
                $column_size = $column['data_size'];
                $column_value_selection_mode = $column['value_selection_mode'];
                $column_value_selection_item = $column['value_selection_item'];
                if($column['role'] == 'primary'){
                    $primary_key_name = $column_name;
                    
                }
                $composed_type = '$this->TYPE_VARCHAR_50_NULL';
                if($column['role'] == 'primary'){
                    $composed_type = '$this->TYPE_INT_UNSIGNED_AUTO_INCREMENT';
                }else{
                    if($column_type == 'varchar' && $column_value_selection_mode != ''){ // SET and ENUM
                        $composed_type = 'array("type"=>\''.$column_value_selection_mode.'\', "constraint"=>array('.$column_value_selection_item.'), "null"=>TRUE)';
                    }else if(in_array($column_type, $this->type_without_length)){ // column without length
                        $composed_type = 'array("type"=>\''.$column_type.'\', "null"=>TRUE)';
                    }else{ // normal column
                        if(!isset($column_size) || $column_size == ''){
                            $column_size = 11;
                        }
                        if(!in_array($column_type, $this->available_data_type)){
                            $column_type = $this->detault_data_type;
                            $column_size = 255;
                        }
                        $composed_type = 'array("type"=>\''.$column_type.'\', "constraint"=>'.$column_size.', "null"=>TRUE)';
                    }
                    
                }
                $field_list[] = "'$column_name'". '=> '.$composed_type;
            }
            $create_forge  = '// '.$table_name.PHP_EOL;
            $create_forge .= '        $fields = array('.PHP_EOL.'            '.implode(','.PHP_EOL.'            ', $field_list).PHP_EOL.'        );'.PHP_EOL;
            $create_forge .= '        $this->dbforge->add_field($fields);'.PHP_EOL;
            if(isset($primary_key_name)){
                $create_forge .= '        $this->dbforge->add_key(\''.$primary_key_name.'\', TRUE);'.PHP_EOL;    
            }
            $create_forge .= '        $this->dbforge->create_table($this->cms_complete_table_name(\''.$table_name.'\'));'.PHP_EOL;
            
            $php[] = $create_forge;
        }
        return implode(PHP_EOL.'        ',$php);
    }
    
    public function get_drop_table_forge($tables){
        $php = array();
        foreach($tables as $table){
            $table_name = $table['stripped_name'];
            $php[] = '$this->dbforge->drop_table($this->cms_complete_table_name(\''.$table_name.'\'), TRUE);';
        }
        $php = array_reverse($php);
        return implode(PHP_EOL.'        ',$php);
    }

	public function get_create_table_syntax($tables){

		$result_array = array();
		foreach($tables as $table){
			// create drop syntax
			$table_name = addslashes($table['name']);
			$create_table_syntax = 'CREATE TABLE `'.$table_name.'` ('.PHP_EOL;
			// add columns
			$columns = $table['columns'];
			$column_array = array();
			$primary = NULL;
			foreach($columns as $column){
				$column_name = $column['name'];
				$column_type = $column['data_type'];
				$column_size = $column['data_size'];
                $column_value_selection_mode = $column['value_selection_mode'];
                $column_value_selection_item = $column['value_selection_item'];
				$role = $column['role'];
				if($role == 'primary'){
				    if(!isset($column_size) || $column_size == ''){
                        $column_size = 11;
                    }
					if(in_array($column_type, $this->auto_increment_data_type)){
						$column_array[] = '  `'.$column_name.'` '.$column_type.'('.$column_size.') unsigned NOT NULL AUTO_INCREMENT';
					}else{
						$column_array[] = '  `'.$column_name.'` '.$column_type.'('.$column_size.') NOT NULL';
					}
					$primary = '  PRIMARY KEY (`'.$column_name.'`)';
				}else if($role == '' || $role == 'lookup'){
				    if($column_type == 'varchar' && $column_value_selection_mode != ''){
                        $column_array[] = '  `'.$column_name.'` '.$column_value_selection_mode.'('.$column_value_selection_item.')';
				    }else if(in_array($column_type, $this->type_without_length)){
						$column_array[] = '  `'.$column_name.'` '.$column_type;
					}else{
						if(!isset($column_size) || $column_size == ''){
							$column_size = 11;
						}
						if(!in_array($column_type, $this->available_data_type)){
							$column_type = $this->detault_data_type;
							$column_size = 255;
						}
						$column_array[] = '  `'.$column_name.'` '.$column_type.'('.$column_size.')';
					}
				}
			}
			$column_string = implode(','.PHP_EOL, $column_array);
			if(isset($primary)){
				$column_string.=','.PHP_EOL.$primary;
			}
			$create_table_syntax .= $column_string.PHP_EOL;
			$create_table_syntax .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8;';
			$result_array[] = $create_table_syntax;
		}
		$result = implode(PHP_EOL.'/*split*/'.PHP_EOL, $result_array);
		return $result;
	}

	public function get_insert_table_syntax($project_id, $tables){

		$query = $this->db->select()->from($this->cms_complete_table_name('project'))->where('project_id', $project_id)->get();
		if($query->num_rows()>0){
			$row = $query->row();
			$db_server = $row->db_server;
			$db_port = $row->db_port;
			$db_user = $row->db_user;
			$db_password = $row->db_password;
			$db_schema = $row->db_schema;

			$connection = @mysqli_connect($db_server, $db_user, $db_password, $db_schema, $db_port);
			@mysqli_select_db($connection, $db_schema);

			if($connection === FALSE){
				return '';
			}

			$result_array = array();
			foreach($tables as $table){
				// create drop syntax
				$table_name = addslashes($table['name']);
				// add columns
				$columns = $table['columns'];
				$column_names = array();
				foreach($columns as $column){
					$column_names[] = $column['name'];
				}

				$raw_available_columns = array();
				$available_columns = array();
				$available_values = array();
				$SQL = "SELECT * FROM `".$table_name."`;";
				$result = mysqli_query($connection, $SQL);
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_assoc($result)){
						$values = array();
						foreach($row as $key=>$value){
							// get available columns (the lazy way)
							if(!in_array($key,$raw_available_columns) && in_array($key,$column_names)){
								$raw_available_columns[] = $key;
								$available_columns[] = '`'.addslashes($key).'`';
							}
							if(in_array($key,$raw_available_columns)){
								$values[] = '\''.addslashes($value).'\'';
							}
						}
						$available_values[] = '('.implode(', ',$values).')';
					}

					$available_column_list = implode(', ',$available_columns);
					$available_value_list = implode(','.PHP_EOL, $available_values);


					$insert_syntax = 'INSERT INTO `'.$table_name.'` ('.$available_column_list.') VALUES'.PHP_EOL;
					$insert_syntax .= $available_value_list.';';
					$result_array[] = $insert_syntax;

				}



			}
			$result = implode(PHP_EOL.'/*split*/'.PHP_EOL, $result_array);
			return $result;

		}
		return '';

	}

	public function get_drop_table_syntax($tables){
		$result_array = array();
		foreach($tables as $table){
			// create drop syntax
			$table_name = addslashes($table['name']);
			$result_array[] = 'DROP TABLE IF EXISTS `'.$table_name.'`; ';
		}
		$result_array = array_reverse($result_array);
		$result = implode(PHP_EOL.'/*split*/'.PHP_EOL, $result_array);
		return $result;
	}

	public function before_delete_template($id){
		$query = $this->db->select('project_id')->from($this->cms_complete_table_name('project'))->where('template_id',$id)->get();
		foreach($query->result() as $row){
			$this->before_delete_project($row->project_id);
			$this->db->delete($this->cms_complete_table_name('project'),array('project_id'=>$row->project_id));
		}
		$query = $this->db->select('option_id')->from($this->cms_complete_table_name('template_option'))->where('template_id',$id)->get();
		foreach($query->result() as $row){
			$this->before_delete_template_option($row->option_id);
			$this->db->delete($this->cms_complete_table_name('template_option'),array('option_id'=>$row->option_id));
		}
	}

	public function before_delete_template_option($id){
		$this->db->delete($this->cms_complete_table_name('project_option'),array('option_id'=>$id));
		$this->db->delete($this->cms_complete_table_name('table_option'),array('option_id'=>$id));
		$this->db->delete($this->cms_complete_table_name('column_option'),array('option_id'=>$id));
	}

	public function before_delete_project($id){
		$query = $this->db->select('table_id')->from($this->cms_complete_table_name('table'))->where('project_id',$id)->get();
		foreach($query->result() as $row){
			$this->before_delete_table($row->table_id);
			$this->db->delete($this->cms_complete_table_name('table'),array('table_id'=>$row->table_id));
		}
		$this->db->delete($this->cms_complete_table_name('project_option'),array('project_id'=>$id));
	}

	public function before_delete_table($id){
		$query = $this->db->select('column_id')->from($this->cms_complete_table_name('column'))->where('table_id',$id)->get();
		foreach($query->result() as $row){
			$this->before_delete_column($row->column_id);
			$this->db->delete($this->cms_complete_table_name('column'),array('column_id'=>$row->column_id));
		}
		$this->db->delete($this->cms_complete_table_name('table_option'),array('table_id'=>$id));
	}

	public function before_delete_column($id){
		$this->db->delete($this->cms_complete_table_name('column_option'),array('column_id'=>$id));
	}

}
?>