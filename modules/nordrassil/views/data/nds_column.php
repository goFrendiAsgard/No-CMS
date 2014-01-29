<style type="text/css">
	.hidden {
		display: none!important;
		visibility: hidden;
	}
</style>
<?php
	$asset = new CMS_Asset();
	foreach($css_files as $file){
		$asset->add_css($file);
	}
	echo $asset->compile_css();

	foreach($js_files as $file){
		$asset->add_js($file);
	}
	// also add adjust.js which contain of field adjustment function
	$asset->add_module_js('scripts/adjust.js', '{{ module_path }}');
	echo $asset->compile_js();
	echo '<h4>Column</h4>';
	if(isset($project_id)){
		echo anchor(site_url('{{ module_path }}/data/nds/project/edit/'.$project_id),'Project "<b>'.$project_name.'</b>"','class="btn btn-primary"');
		echo '&nbsp;';
		echo anchor(site_url('{{ module_path }}/data/nds/table/'.$project_id.'/edit/'.$table_id),'Table "<b>'.$table_name.'</b>"','class="btn btn-primary"');
	}
	echo $output;
?>

<script type="text/javascript">
	// if document ready, call adjust when needed
	$(document).ready(function(){
		// when table_id changed
		var changing_field_1 = 'table_id';
		var affected_field_1 = Array('options', 'lookup_table_id','relation_table_id', 'selection_table_id');
		var get_restricted_path_1 = Array('<?php echo site_url('{{ module_path }}'); ?>'+'/data/ajax/get_restricted_column_option/',
				 '<?php echo site_url('{{ module_path }}'); ?>'+'/data/ajax/get_restricted_table_sibling/',
				 '<?php echo site_url('{{ module_path }}'); ?>'+'/data/ajax/get_restricted_table_sibling/',
				 '<?php echo site_url('{{ module_path }}'); ?>'+'/data/ajax/get_restricted_table_sibling/'
			);

		for(var i=0; i<affected_field_1.length; i++){
			adjust(changing_field_1, affected_field_1[i], get_restricted_path_1[i]);
		}
		$("select#field-"+changing_field_1).change(function(){
			for(var i=0; i<affected_field_1.length; i++){
				adjust(changing_field_1, affected_field_1[i], get_restricted_path_1[i]);
			}
		});

		// when lookup_table_id changed
		var changing_field_2 = 'lookup_table_id';
		var affected_field_2 = 'lookup_column_id';
		var get_restricted_path_2 = '<?php echo site_url('{{ module_path }}'); ?>'+'/data/ajax/get_restricted_column/';
		adjust(changing_field_2, affected_field_2, get_restricted_path_2);
		$("select#field-"+changing_field_2).change(function(){
			adjust(changing_field_2, affected_field_2, get_restricted_path_2);
		});

		// when relation_table_id changed
		var changing_field_3 = 'relation_table_id';
		var affected_field_3 = Array('relation_table_column_id','relation_selection_column_id','relation_priority_column_id');
		var get_restricted_path_3 = '<?php echo site_url('{{ module_path }}'); ?>'+'/data/ajax/get_restricted_column/';
		for(var i=0; i<affected_field_3.length; i++){
			adjust(changing_field_3, affected_field_3[i], get_restricted_path_3);
		}
		$("select#field-"+changing_field_3).change(function(){
			for(var i=0; i<affected_field_3.length; i++){
				adjust(changing_field_3, affected_field_3[i], get_restricted_path_3);
			}
		});

		// when selection_table_id changed
		var changing_field_4 = 'selection_table_id';
		var affected_field_4 = 'selection_column_id';
		var get_restricted_path_4 = '<?php echo site_url('{{ module_path }}'); ?>'+'/data/ajax/get_restricted_column/';
		adjust(changing_field_4, affected_field_4, get_restricted_path_4);
		$("select#field-"+changing_field_4).change(function(){
			adjust(changing_field_4, affected_field_4, get_restricted_path_4);
		});

		// when role changed

		$("select#field-role").change(function(){
			adjust_form_by_role_and_data_type();
		});
		$("select#field-data_type").change(function(){
			adjust_form_by_role_and_data_type();
		});

		adjust_form_by_role_and_data_type();

	});

	function adjust_form_by_role_and_data_type(){
		var role = $("select#field-role").val();
		var data_type = $("select#field-data_type").val();
		$("#data_type_field_box").hide();
		$("#data_size_field_box").hide();
		$("#lookup_table_id_field_box").hide();
		$("#lookup_column_id_field_box").hide();
		$("#relation_table_id_field_box").hide();
		$("#relation_table_column_id_field_box").hide();
		$("#relation_selection_column_id_field_box").hide();
		$("#relation_priority_column_id_field_box").hide();
		$("#selection_table_id_field_box").hide();
		$("#selection_column_id_field_box").hide();
		$("#value_selection_mode_field_box").hide();
		$("#value_selection_item_field_box").hide();
		if(role=='' || role=='primary' || role=='lookup'){
			$("#data_type_field_box").show();
			$("#data_size_field_box").show();
		}
		if(role=='lookup'){
			$("#lookup_table_id_field_box").show();
			$("#lookup_column_id_field_box").show();
		}
		if(role=='detail many to many' || role=='detail one to many'){
			$("#relation_table_id_field_box").show();
			$("#relation_table_column_id_field_box").show();
		}
		if(role=='detail many to many'){
			$("#relation_selection_column_id_field_box").show();
			$("#relation_priority_column_id_field_box").show();
			$("#selection_table_id_field_box").show();
			$("#selection_column_id_field_box").show();
		}
		if(role == '' && data_type=='varchar'){
			$("#value_selection_mode_field_box").show();
			$("#value_selection_item_field_box").show();
		}

	}

</script>
