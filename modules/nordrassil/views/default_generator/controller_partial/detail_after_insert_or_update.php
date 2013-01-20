<?php
	$real_input_id = 'md_real_field_'.$master_column_name.'_col';
?>
		//save corresponding <?php echo $detail_table_name.PHP_EOL; ?>
		$data = json_decode($this->input->post('<?php echo $real_input_id; ?>'), TRUE);
		$insert_records = $data['insert'];
		$update_records = $data['update'];
		$delete_records = $data['delete'];
		foreach($delete_records as $delete_record){
			// delete
			$detail_primary_key = $delete_record['primary_key'];
			$this->db->delete('<?php echo $detail_table_name; ?>', array('<?php echo $detail_primary_key_name; ?>'=>$detail_primary_key));
		}
		foreach($update_records as $update_record){
			// update
			$detail_primary_key = $update_record['primary_key'];
			$data = $update_record['data'];
			$data['<?php echo $detail_foreign_key_name; ?>'] = $primary_key;
			$this->db->update('<?php echo $detail_table_name; ?>', $data, array('<?php echo $detail_primary_key_name; ?>'=>$detail_primary_key));
		}
		foreach($insert_records as $insert_record){
			// insert
			$data = $insert_record['data'];
			$data['<?php echo $detail_foreign_key_name; ?>'] = $primary_key;
			$this->db->insert('<?php echo $detail_table_name; ?>', $data);
		}
