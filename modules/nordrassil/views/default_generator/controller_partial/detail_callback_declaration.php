<?php
	$view_path = $project_name.'/data/field_'.$project_name.'_'.$master_table_name.'_'.$master_column_name;
	$detail_columns = $detail_table['columns'];
	$detail_column_captions = array();
	$detail_column_names = array();
	foreach($detail_columns as $detail_column){
		$caption = $detail_column['caption'];
		$name = $detail_column['name'];
		if($name == $detail_foreign_key_name) continue;
		$detail_column_captions[] = $caption;
		$detail_column_names[] = $name;
	}	
?>

	public function callback_field_{{ field_name }}($value, $primary_key){
		// returned on insert and edit
		if(!isset($primary_key)) $primary_key = -1;
		$query = $this->db->select('<?php echo implode(', ', $detail_column_names); ?>')
			->from('<?php echo $detail_table_name; ?>')
			->where('<?php echo $detail_foreign_key_name; ?>', $primary_key)
			->get();
		$result = $query->result_array();
		$data = array(
			'result' => $result
		);
		return $this->load->view('<?php echo $view_path; ?>',$data, TRUE);
	}
	
	public function callback_column_{{ field_name }}($value, $row){
		// returned on view
		$query = $this->db->select('<?php echo implode(', ', $detail_column_names); ?>')
			->from('<?php echo $detail_table_name; ?>')
			->where('<?php echo $detail_foreign_key_name; ?>', $row-><?php echo $master_primary_key_name; ?>)
			->get();
		$num_row = $query->num_rows();
		// show how many records
		if($num_row>1){
			return $num_row .' <?php echo $detail_table['caption'] ?>s';
		}else if($num_row>0){
			return $num_row .' <?php echo $detail_table['caption'] ?>';
		}else{
			return 'No <?php echo $detail_table['caption'] ?>';
		}
	}
	
<?php
    /**
	echo var_dump($project_name);
	echo var_dump($master_table_name);
	echo var_dump($master_column_name);
	echo var_dump($detail_table_name);
	echo var_dump($detail_foreign_key_name);
	echo var_dump($master_primary_key_name);
	echo var_dump($detail_table);
	 **/
?>