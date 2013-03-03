<?php
	$real_input_id = 'md_real_field_'.$master_column_name.'_col';
	$detail_columns = $detail_table['columns'];
	$quoted_real_column_names = array(); // only column names which is primary or lookup or without role
	$quoted_set_column_names = array(); // only column names whis is enum
	$quoted_many_to_many_column_names = array();
	$many_to_many_relation_tables = array();
	$quoted_many_to_many_relation_table_columns = array();
	$quoted_many_to_many_relation_selection_columns = array();
	foreach($detail_columns as $detail_column){
		$name = $detail_column['name'];
		$role = $detail_column['role'];
		$selection_mode = $detail_column['value_selection_mode'];
		if($name == $detail_foreign_key_name) continue;
		if($role=='primary' || $role=='lookup' || $role==''){
			if($selection_mode == 'set'){
				$quoted_set_column_names = '\''.$name.'\'';
			}
			$quoted_real_column_names[] = '\''.$name.'\'';
		}else if($role=='detail many to many'){
			$quoted_many_to_many_column_names[] = '\''.$name.'\'';
			$many_to_many_relation_tables[] = callback_after_insert_well_table_name($project_db_table_prefix, $detail_column['relation_table_name']);
			$quoted_many_to_many_relation_table_columns[] = '\''.$detail_column['relation_table_column_name'].'\'';
			$quoted_many_to_many_relation_selection_columns[] = '\''.$detail_column['relation_selection_column_name'].'\'';
		}
	}

	function callback_after_insert_strip_table_prefix($project_db_table_prefix, $table_name){
        if(!isset($project_db_table_prefix) || $project_db_table_prefix == ''){
            return $table_name;
        }
        if(strpos($table_name, $project_db_table_prefix) === 0){
            $table_name = substr($table_name, strlen($project_db_table_prefix));
        }
        if($table_name[0]=='_'){
            $table_name = substr($table_name,1);
        }
        return $table_name;
    }

    function callback_after_insert_well_table_name($project_db_table_prefix, $table_name){
        return 'cms_module_table_name($module_path, \''.callback_after_insert_strip_table_prefix($project_db_table_prefix, $table_name).'\')';
    }
?>
		// save corresponding <?php echo $detail_table_name.PHP_EOL; ?>
		$data = json_decode($this->input->post('<?php echo $real_input_id; ?>'), TRUE);
		$insert_records = $data['insert'];
		$update_records = $data['update'];
		$delete_records = $data['delete'];
		$real_column_names = array(<?php echo implode(', ', $quoted_real_column_names); ?>);
		$set_column_names = array(<?php echo implode(', ', $quoted_set_column_names); ?>);
		$many_to_many_column_names = array(<?php echo implode(', ', $quoted_many_to_many_column_names); ?>);
		$many_to_many_relation_tables = array(<?php echo implode(', ', $many_to_many_relation_tables); ?>);
		$many_to_many_relation_table_columns = array(<?php echo implode(', ', $quoted_many_to_many_relation_table_columns); ?>);
		$many_to_many_relation_selection_columns = array(<?php echo implode(', ', $quoted_many_to_many_relation_selection_columns); ?>);
		// delete
		foreach($delete_records as $delete_record){
			$detail_primary_key = $delete_record['primary_key'];
			// delete many to many
			for($i=0; $i<count($many_to_many_column_names); $i++){
				$table_name = $many_to_many_relation_tables[$i];
				$relation_column_name = $many_to_many_relation_table_columns[$i];
				$relation_selection_column_name = $many_to_many_relation_selection_columns[$i];
				$where = array(
					$relation_column_name => $detail_primary_key
				);
				$this->db->delete($table_name, $where);
			}
			$this->db->delete(<?php echo $detail_table_name; ?>, array('<?php echo $detail_primary_key_name; ?>'=>$detail_primary_key));
		}
		// update
		foreach($update_records as $update_record){
			$detail_primary_key = $update_record['primary_key'];
			$data = array();
			foreach($update_record['data'] as $key=>$value){
				if(in_array($key, $set_column_names)){
					$data[$key] = implode(',', $value);
				}else if(in_array($key, $real_column_names)){
					$data[$key] = $value;
				}
			}
			$data['<?php echo $detail_foreign_key_name; ?>'] = $primary_key;
			$this->db->update(<?php echo $detail_table_name; ?>, $data, array('<?php echo $detail_primary_key_name; ?>'=>$detail_primary_key));
			// many to many fields
			for($i=0; $i<count($many_to_many_column_names); $i++){
				$key = 	$many_to_many_column_names[$i];
				$new_values = $update_record['data'][$key];
				$table_name = $many_to_many_relation_tables[$i];
				$relation_column_name = $many_to_many_relation_table_columns[$i];
				$relation_selection_column_name = $many_to_many_relation_selection_columns[$i];
				$query = $this->db->select($relation_column_name.','.$relation_selection_column_name)
					->from($table_name)
					->where($relation_column_name, $detail_primary_key)
					->get();
				// delete everything which is not in new_values
				$old_values = array();
				foreach($query->result_array() as $row){
					$old_values = array();
					if(!in_array($row[$relation_selection_column_name], $new_values)){
						$where = array(
							$relation_column_name => $detail_primary_key,
							$relation_selection_column_name => $row[$relation_selection_column_name]
						);
						$this->db->delete($table_name, $where);
					}else{
						$old_values[] = $row[$relation_selection_column_name];
					}
				}
				// add everything which is not in old_values but in new_values
				foreach($new_values as $new_value){
					if(!in_array($new_value, $old_values)){
						$data = array(
							$relation_column_name => $detail_primary_key,
							$relation_selection_column_name => $new_value
						);
						$this->db->insert($table_name, $data);
					}
				}
			}
		}
		// insert
		foreach($insert_records as $insert_record){
			$data = array();
			foreach($insert_record['data'] as $key=>$value){
				if(in_array($key, $set_column_names)){
					$data[$key] = implode(',', $value);
				}else if(in_array($key, $real_column_names)){
					$data[$key] = $value;
				}
			}
			$data['<?php echo $detail_foreign_key_name; ?>'] = $primary_key;
			$this->db->insert(<?php echo $detail_table_name; ?>, $data);
			$detail_primary_key = $this->db->insert_id();
			// many to many fields
			for($i=0; $i<count($many_to_many_column_names); $i++){
				$key = 	$many_to_many_column_names[$i];
				$new_values = $insert_record['data'][$key];
				$table_name = $many_to_many_relation_tables[$i];
				$relation_column_name = $many_to_many_relation_table_columns[$i];
				$relation_selection_column_name = $many_to_many_relation_selection_columns[$i];
				$query = $this->db->select($relation_column_name.','.$relation_selection_column_name)
					->from($table_name)
					->where($relation_column_name, $detail_primary_key)
					->get();
				// delete everything which is not in new_values
				$old_values = array();
				foreach($query->result_array() as $row){
					$old_values = array();
					if(!in_array($row[$relation_selection_column_name], $new_values)){
						$where = array(
							$relation_column_name => $detail_primary_key,
							$relation_selection_column_name => $row[$relation_selection_column_name]
						);
						$this->db->delete($table_name, $where);
					}else{
						$old_values[] = $row[$relation_selection_column_name];
					}
				}
				// add everything which is not in old_values but in new_values
				foreach($new_values as $new_value){
					if(!in_array($new_value, $old_values)){
						$data = array(
							$relation_column_name => $detail_primary_key,
							$relation_selection_column_name => $new_value
						);
						$this->db->insert($table_name, $data);
					}
				}
			}
		}
