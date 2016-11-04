<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo '<div style="padding-bottom: 10px;">';
echo anchor(site_url('{{ module_path }}/manage_project/index'),'All Projects','class="btn btn-primary"');
if(isset($project_id)){
    echo '&nbsp;';
    echo anchor(site_url('{{ module_path }}/manage_project/index/edit/'.$project_id),'Project "<b>'.$project_name.'</b>"','class="btn btn-primary"');
    echo '&nbsp;';
    echo anchor(site_url('{{ module_path }}/manage_table/index/'.$project_id),'All Tables in "<b>'.$project_name.'</b>"','class="btn btn-primary"');
    echo '&nbsp;';
    echo anchor(site_url('{{ module_path }}/manage_table/index/'.$project_id.'/edit/'.$table_id),'Table "<b>'.$table_name.'</b>"','class="btn btn-primary"');
}
echo '</div>';
echo $output;
?>
<script type="text/javascript" src="{{ module_base_url }}assets/scripts/adjust.js"></script>
<script type="text/javascript">
    // This function will add checkboxes and "Delete selected" button.
    add_delete_all_feature(
        '{{ MODULE_SITE_URL }}Manage_column/delete_selection', // url
        '{{ language:Delete Selected }}', // button caption
        '{{ language:Selected row deleted }}' // notification caption
    );


    $(document).ajaxComplete(function(){
        // TODO: Put your custom code here
    });

    $(document).ready(function(){
	    $('.field-sorting').removeClass('field-sorting');
		// when table_id changed
		var changing_field_1 = 'table_id';
		var affected_field_1 = Array('options', 'lookup_table_id','relation_table_id', 'selection_table_id');
		var get_ajax_path_1 = Array('<?php echo site_url('{{ module_path }}'); ?>'+'/ajax/get_column_option/',
				 '<?php echo site_url('{{ module_path }}'); ?>'+'/ajax/get_table_sibling/',
				 '<?php echo site_url('{{ module_path }}'); ?>'+'/ajax/get_table_sibling/',
				 '<?php echo site_url('{{ module_path }}'); ?>'+'/ajax/get_table_sibling/'
			);

		for(var i=0; i<affected_field_1.length; i++){
			adjust(changing_field_1, affected_field_1[i], get_ajax_path_1[i]);
		}
		$("select#field-"+changing_field_1).change(function(){
			for(var i=0; i<affected_field_1.length; i++){
				adjust(changing_field_1, affected_field_1[i], get_ajax_path_1[i]);
			}
		});

		// when lookup_table_id changed
		var changing_field_2 = 'lookup_table_id';
		var affected_field_2 = 'lookup_column_id';
		var get_ajax_path_2 = '<?php echo site_url('{{ module_path }}'); ?>'+'/ajax/get_column/';
		adjust(changing_field_2, affected_field_2, get_ajax_path_2);
		$("select#field-"+changing_field_2).change(function(){
			adjust(changing_field_2, affected_field_2, get_ajax_path_2);
		});

		// when relation_table_id changed
		var changing_field_3 = 'relation_table_id';
		var affected_field_3 = Array('relation_table_column_id','relation_selection_column_id','relation_priority_column_id');
		var get_ajax_path_3 = '<?php echo site_url('{{ module_path }}'); ?>'+'/ajax/get_column/';
		for(var i=0; i<affected_field_3.length; i++){
			adjust(changing_field_3, affected_field_3[i], get_ajax_path_3);
		}
		$("select#field-"+changing_field_3).change(function(){
			for(var i=0; i<affected_field_3.length; i++){
				adjust(changing_field_3, affected_field_3[i], get_ajax_path_3);
			}
		});

		// when selection_table_id changed
		var changing_field_4 = 'selection_table_id';
		var affected_field_4 = 'selection_column_id';
		var get_ajax_path_4 = '<?php echo site_url('{{ module_path }}'); ?>'+'/ajax/get_column/';
		adjust(changing_field_4, affected_field_4, get_ajax_path_4);
		$("select#field-"+changing_field_4).change(function(){
			adjust(changing_field_4, affected_field_4, get_ajax_path_4);
		});

		// when role changed

		$("select#field-role").change(function(){
			adjust_form_by_role_and_data_type();
		});
		$("select#field-data_type").change(function(){
			adjust_form_by_role_and_data_type();
		});

		adjust_form_by_role_and_data_type();

        $('.breadcrumb a').each(function(){
            if($(this).attr('href') == '{{ module_site_url }}manage_table'){
                $(this).attr('href', '{{ module_site_url }}manage_table/index/<?php echo $project_id; ?>');
            }else if($(this).attr('href') == '{{ module_site_url }}manage_column'){
                $(this).attr('href', '{{ module_site_url }}manage_column/index/<?php echo $table_id; ?>');
            }
        });

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
