<?php
    $integer_type = $auto_increment_data_type = array('int', 'tinyint', 'smallint', 'mediumint', 'integer', 'bigint');
    $view_path = $project_name.'/data/field_'.$project_name.'_'.$master_table_name.'_'.$master_column_name;
    $detail_columns = $detail_table['columns'];
    $detail_column_captions = array();
    $detail_column_names = array();
    $detail_column_data_types = array();
    $detail_column_role = array();
    $detail_value_selection_mode = array();
    foreach($detail_columns as $detail_column){
        $caption = $detail_column['caption'];
        $name = $detail_column['name'];
        $data_type = $detail_column['data_type'];
        $role = $detail_column['role'];
        $value_selection_mode = $detail_column['value_selection_mode'];
        if($name == $detail_primary_key_name) continue;
        if($name == $detail_foreign_key_name) continue;
        $detail_column_captions[] = $caption;
        $detail_column_names[] = $name;
        $detail_column_data_types[] = $data_type;
        $detail_column_role[] = $role;
        $detail_value_selection_mode[] = $value_selection_mode;
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
    $var_options = 'OPTIONS_'.$master_column_name;
    $fn_synchronize = 'synchronize_'.$master_column_name;
    $fn_synchronize_width = 'synchronize_'.$master_column_name.'_table_width';
    $fn_add_table_row = 'add_table_row_'.$master_column_name;
    $fn_default_row = 'default_row_'.$master_column_name;
    $fn_mutate_input = 'mutate_input_'.$master_column_name;

    $default_record = '';
    foreach($detail_column_names as $name){
        $default_record .= '            '.$name.' : \'\','.PHP_EOL;
    }

    // the html
    $field_captions = array();
    foreach($detail_column_captions as $caption){
        $field_captions[] = '\''.addslashes($caption).'\'';
    }
    echo '&lt;?php'.PHP_EOL;
    echo '    // Generate HTML. Parameters: column_name, caption, array_of_field_captions'.PHP_EOL;
    echo '    $HTML = build_md_html_table(\''.$master_column_name.'\', \''.$detail_table_caption.'\', array('.implode(', ', $field_captions).'));'.PHP_EOL;
    echo '    // Generate global variable and event-binding'.PHP_EOL;
    echo '    $JS   = build_md_global_variable_script(\''.$master_column_name.'\', \''.$detail_primary_key_name.'\', $date_format, $result, $options);'.PHP_EOL;
    echo '    $JS  .= build_md_event_script(\''.$master_column_name.'\', \'{{ module_site_url }}manage_'.underscore($stripped_master_table_name).'/index/insert\', \'{{ module_site_url }}manage_'.underscore($stripped_master_table_name).'/index/update\');'.PHP_EOL;
    echo '    // Show HTML'.PHP_EOL;
    echo '    echo $HTML;'.PHP_EOL;
    echo '    // Show JS'.PHP_EOL;
    echo '    echo \'<script type="text/javascript">\'.$JS.\'</script>\';'.PHP_EOL;
    echo '?&gt;'.PHP_EOL;
?>
<script type="text/javascript">

    // Function to get default value
    function <?php echo $fn_default_row; ?>(){
        return {
            <?php echo trim($default_record).PHP_EOL; ?>
        };
    }

    // Function to add row
    function <?php echo $fn_add_table_row; ?>(value){

        // Prepare some variables
        var input_prefix = '<?php echo $column_input_class; ?>';
        var row_index    = <?php echo $var_record_index; ?>;
        var inputs       = new Array();
        <?php
        $date_exist = FALSE;
        for($i=0; $i<count($detail_column_names); $i++){
            $name = $detail_column_names[$i];
            $caption = $detail_column_captions[$i];
            $data_type = $detail_column_data_types[$i];
            $role = $detail_column_role[$i];
            $selection_mode = $detail_value_selection_mode[$i];
            $additional_class_array = array();
            if($data_type=='date'){
                $additional_class_array[] = 'datepicker-input';
            }else if($data_type=='datetime'){
                $additional_class_array[] = 'datetime-input';
            }else if(in_array($data_type, $integer_type)){
                $additional_class_array[] = 'numeric';
            }
            if(count($additional_class_array)>0){
                $additional_class = ' '.implode(' ',$additional_class_array);
            }else{
                $additional_class = '';
            }
            echo PHP_EOL;
            echo '        // FIELD "'.$name.'"'.PHP_EOL;
            echo '        var input_id    = input_prefix + \''.$name.'\' + row_index;'.PHP_EOL;
            echo '        var field_value = get_object_property_as_str(value, \''.$name.'\');'.PHP_EOL;
            if($data_type == 'date'){
                echo '        field_value     = php_date_to_js(field_value);'.PHP_EOL;
            }else if($data_type == 'datetime'){
                echo '        field_value     = php_datetime_to_js(field_value);'.PHP_EOL;
            }

            // create input based on role and type
            if($role=='lookup' || $role=='detail many to many' || $selection_mode=='enum' || $selection_mode=='set'){
                // determine whether it is multiple select or single select
                $multiple = ($role=='lookup' || $selection_mode=='enum')? '': ' multiple = "multiple"';
                $var_field_options = $var_options.'.'.$name;
                // build select
                echo '        var html = \'<select id="\'+input_id+\'" record_index="\'+row_index+\'" class="\'+input_prefix+\''.$additional_class.' chzn-select" column_name="'.$name.'" '.$multiple.'>\';'.PHP_EOL;
                // build options
                if($role=='lookup' || $selection_mode=='enum'){
                    echo '        html += build_single_select_option(field_value, '.$var_field_options.');'.PHP_EOL;
                }else{
                    echo '        html += build_multiple_select_option(field_value, '.$var_field_options.');'.PHP_EOL;
                }
                // end of select
                echo '        html += \'</select>\';'.PHP_EOL;
            }else{
                echo '        var html = \'<input id="\'+input_id+\'" record_index="\'+row_index+\'" class="\'+input_prefix+\''.$additional_class.'" column_name="'.$name.'" type="text" value="\'+field_value+\'"/>\';'.PHP_EOL;
                if($data_type == 'date'){
                    echo '        html += \'<a href="#" class="datepicker-input-clear btn">Clear</a>\';'.PHP_EOL;
                }
            }
            echo'        inputs.push(html);'.PHP_EOL;
        }
        ?>

        // Return inputs
        return inputs;
    }

</script>
