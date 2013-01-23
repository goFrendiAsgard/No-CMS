<?php
	$view_path = $project_name.'/data/field_'.$project_name.'_'.$master_table_name.'_'.$master_column_name;
	$detail_columns = $detail_table['columns'];
	$detail_column_captions = array();
	$detail_column_names = array();
	$detail_column_data_types = array();
	foreach($detail_columns as $detail_column){
		$caption = $detail_column['caption'];
		$name = $detail_column['name'];
		$data_type = $detail_column['data_type'];
		if($name == $detail_primary_key_name) continue;
		if($name == $detail_foreign_key_name) continue;
		$detail_column_captions[] = $caption;
		$detail_column_names[] = $name;
		$detail_column_data_types[] = $data_type;
	}
	
	$detail_table_caption = $detail_table['caption'];
	
	$delete_button_class = 'md_field_'.$master_column_name.'_delete';
	$tr_class = 'md_field_'.$master_column_name.'_tr';
	$add_button_id = 'md_field_'.$master_column_name.'_add';
	$column_input_class = 'md_field_'.$master_column_name.'_col';
	$real_input_id = 'md_real_field_'.$master_column_name.'_col';
	$table_id = 'md_table_'.$master_column_name;
	
	$var_record_index = 'RECORD_INDEX_'.$master_column_name;
	$var_data = 'DATA_'.$master_column_name;
	$fn_synchronize = 'synchronize_'.$master_column_name;
	$fn_add_table_row = 'add_table_row_'.$master_column_name;	
?>
&lt;?php
	$record_index = 0;
?&gt;
<style type="text/css">
	/* set width of every detail input*/
	.<?php echo $column_input_class ?>{
		width:100px!important;
	}
</style>

<table id="<?php echo $table_id; ?>" class="table table-striped table-bordered">
	<thead>
		<tr>
<?php
	foreach($detail_column_captions as $caption){
		echo '			<th>'.$caption.'</th>'.PHP_EOL;
	}		
?>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>	
		<!-- the content will be here -->
	</tbody>	
</table>
<input id="<?php echo $add_button_id; ?>" class="btn" type="button" value="Add <?php echo $detail_table_caption; ?>" />
<br />
<input id="<?php echo $real_input_id; ?>" name="<?php echo $real_input_id; ?>" type="hidden" />

<script type="text/javascript">
	/**
	 * DATA INITIALIZATION ==================================================================================
	 */
	var DATE_FORMAT = '&lt;? echo $date_format ?&gt;';
	var <?php echo $var_record_index; ?> = &lt;?php echo $record_index; ?&gt;;	 
	var <?php echo $var_data; ?> = {update:new Array(), insert:new Array(), delete:new Array()};
	var old_data = &lt;?php echo json_encode($result); ?&gt;;
	for(var i=0; i<old_data.length; i++){
		var row = old_data[i];
		var record_index = i;
		var primary_key = row['<?php echo $detail_primary_key_name; ?>'];
		var data = row;
		delete data['<?php echo $detail_primary_key_name; ?>']; 
		<?php echo $var_data; ?>.update.push({
			'record_index' : record_index,
			'primary_key' : primary_key,
			'data' : data,
		});
	}
	
	/**
	 * FUNCTIONS ============================================================================================
	 */
	
	// syncrhonize data to <?php echo $real_input_id; ?>.
	function <?php echo $fn_synchronize; ?>(){
		$('#<?php echo $real_input_id; ?>').val(JSON.stringify(<?php echo $var_data; ?>));
	}
	// add component to the table
	function <?php echo $fn_add_table_row; ?>(value){
		var component = '<tr id="<?php echo $tr_class ?>_'+<?php echo $var_record_index; ?>+'" class="<?php echo $tr_class ?>">';
		<?php
		$date_exist = FALSE; 		
		for($i=0; $i<count($detail_column_names); $i++){
			$name = $detail_column_names[$i];
			$data_type = $detail_column_data_types[$i];
			$additional_class_array = array();
			if($data_type=='date'){
				$additional_class_array[] = 'datepicker-input';
				$date_exist = TRUE;
			}
			if(count($additional_class_array)>0){
				$additional_class = ' '.implode(' ',$additional_class_array);	
			}else{
				$additional_class = '';
			}
			echo PHP_EOL;
			echo '		// field "'.$name.'"'.PHP_EOL;
			echo '		var field_value = \'\''.PHP_EOL;
			echo '		if(typeof(value) != \'undefined\' && value.hasOwnProperty(\''.$name.'\')){'.PHP_EOL;
			if($data_type=='date'){
				echo '			field_value = php_date_to_js(value.'.$name.');'.PHP_EOL;
			}else{
				echo '			field_value = value.'.$name.';'.PHP_EOL;
			}			
			echo '		}'.PHP_EOL;
			echo '		component += \'<td>\';'.PHP_EOL;
			echo '		component += \'<input id="'.$column_input_class.'_'.$name.'_\'+'.$var_record_index.'+\'" record_index="\'+'.$var_record_index.
				'+\'" class="'.$column_input_class.$additional_class.'" column_name="'.$name.'" type="text" value="\'+field_value+\'"/>\';'.PHP_EOL;
			if($data_type == 'date'){
				echo '		component += \'<a href="#" class="datepicker-input-clear btn">Clear</a>\''.PHP_EOL;
			}
			echo'		component += \'</td>\';'.PHP_EOL;
		}		
		?>
		
		// delete button
		component += '<td><input class="<?php echo $delete_button_class; ?> btn" record_index="'+<?php echo $var_record_index; ?>+'" primary_key="" type="button" value="Delete <?php echo $detail_table_caption; ?>" /></td>';
		component += '</tr>';
		
		// add to the table
		$('#<?php echo $table_id; ?> tbody').append(component);
		
		<?php
		if($date_exist){
		echo PHP_EOL."
		// change into datepicker
		$('.datepicker-input').datepicker({
				dateFormat: js_date_format,
				showButtonPanel: true,
				changeMonth: true,
				changeYear: true
		});
		// clear event
		$('.datepicker-input-clear').click(function(){
			$(this).parent().find('.datepicker-input').val(\"\");
			return false;
		});".PHP_EOL;			
		}
		?>
		
	}
	
	function js_date_to_php(js_date){
		if(typeof(js_date)=='undefined' || js_date == ''){
			return '';
		}
		var date = '';
		var month = '';
		var year = '';	
		var php_date = '';	
		if(DATE_FORMAT == 'uk-date'){
			var date_array = js_date.split('/')
			day = date_array[0];
			month = date_array[1];
			year = date_array[2];
			php_date = year+'-'+month+'-'+day;
		}else if(DATE_FORMAT == 'us-date'){
			var date_array = js_date.split('/')
			day = date_array[1];
			month = date_array[0];
			year = date_array[2];
			php_date = year+'-'+month+'-'+day;
		}else if(DATE_FORMAT == 'sql-date'){
			var date_array = js_date.split('-')
			day = date_array[2];
			month = date_array[1];
			year = date_array[0];
			php_date = year+'-'+month+'-'+day;
		}
		return php_date;
	}
	
	function php_date_to_js(php_date){
		if(typeof(php_date)=='undefined' || php_date == ''){
			return '';
		}
		var date_array = php_date.split('-');
		var year = date_array[0];
		var month = date_array[1];
		var day = date_array[2];
		if(DATE_FORMAT == 'uk-date'){
			return day+'/'+month+'/'+year;
		}else if(DATE_FORMAT == 'us-date'){
			return month+'/'+date+'/'+year;
		}else if(DATE_FORMAT == 'sql-date'){
			return year+'-'+month+'-'+day;
		}else{
			return '';
		}
	}

	
	/**
	 * MAIN PROGRAM ==========================================================================================
	 */
	$(document).ready(function(){
		/**
		 * INITIALIZATION
		 */
		<?php echo $fn_synchronize; ?>();
		for(var i=0; i<old_data.length; i++){
			<?php echo $fn_add_table_row; ?>(old_data[i]);
			<?php echo $var_record_index; ?>++;
		}
		
		
		/**
		 * ADD RECORD EVENT : <?php echo $add_button_id; ?>.click ===========================================
		 */
		$('#<?php echo $add_button_id; ?>').click(function(){
			// new data
			var data = new Object();			
			<?php echo PHP_EOL;
			foreach($detail_column_names as $name){
				echo '			data.'.$name.' = \'\';'.PHP_EOL;
			}
			?>
			// insert data to the <?php echo $var_data.PHP_EOL; ?>
			<?php echo $var_data; ?>.insert.push({
				'record_index' : <?php echo $var_record_index; ?>,
				'primary_key' : '',
				'data' : data,
			});
			
			// add table's row
			<?php echo $fn_add_table_row; ?>(data);			
			// add <?php $var_record_index; ?> by 1
			<?php echo $var_record_index; ?>++;
			
			// synchronize to the <?php echo $real_input_id.PHP_EOL; ?>
			<?php echo $fn_synchronize; ?>();
		});
		
		
		/** 
		 * DELETE RECORD EVENT : <?php echo $delete_button_class; ?>.click =================================
		 */
		$('.<?php echo $delete_button_class ?>').live('click', function(){
			var record_index = $(this).attr('record_index');
			// remove the component
			$('#<?php echo $tr_class ?>_'+record_index).remove();
			
			var record_index_found = false;
			for(var i=0; i<<?php echo $var_data; ?>.insert.length; i++){
				if(<?php echo $var_data; ?>.insert[i].record_index == record_index){
					record_index_found = true;
					// delete element from insert
					<?php echo $var_data; ?>.insert.splice(i,1);
					break;
				}
			}
			if(!record_index_found){
				for(var i=0; i<<?php echo $var_data; ?>.update.length; i++){
					if(<?php echo $var_data; ?>.update[i].record_index == record_index){
						record_index_found = true;
						var primary_key = <?php echo $var_data; ?>.update[i].primary_key
						// delete element from update
						<?php echo $var_data; ?>.update.splice(i,1);
						// add it to delete
						<?php echo $var_data; ?>.delete.push({
							'record_index':record_index,
							'primary_key':primary_key
						});
						break;
					}
				}
			}			
			<?php echo $fn_synchronize; ?>();
		});
				
		
		/** 
		 * UPDATE FIELD EVENT : <?php echo $column_input_class; ?>.change ==================================
		 */
		$('.<?php echo $column_input_class; ?>').live('change', function(){				
			var value = $(this).val();
			var column_name = $(this).attr('column_name');
			var record_index = $(this).attr('record_index');
			var record_index_found = false;
			if($(this).hasClass('datepicker-input')){
				value = js_date_to_php(value);
			}
			for(var i=0; i<<?php echo $var_data; ?>.insert.length; i++){
				if(<?php echo $var_data; ?>.insert[i].record_index == record_index){
					record_index_found = true;
					// edit value
					eval('<?php echo $var_data; ?>.insert['+i+'].data.'+column_name+' = \''+value+'\';');
					break;
				}
			}
			if(!record_index_found){
				for(var i=0; i<<?php echo $var_data; ?>.update.length; i++){
					if(<?php echo $var_data; ?>.update[i].record_index == record_index){
						record_index_found = true;
						// edit value
						eval('<?php echo $var_data; ?>.update['+i+'].data.'+column_name+' = \''+value+'\';');
						break;
					}
				}
			}
			<?php echo $fn_synchronize; ?>();				
		});
		
		
	})
	
</script>