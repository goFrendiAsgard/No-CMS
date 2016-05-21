<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('column', 'Column', array('Name', 'Caption', 'Data Type', 'Data Size', 'Role', 'Lookup Table', 'Lookup Column Id', 'Relation Table', 'Relation Table Column', 'Relation Selection Column Id', 'Relation Priority Column Id', 'Selection Table', 'Selection Column Id', 'Priority', 'Value Selection Mode', 'Value Selection Item', 'Column Option'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('column', 'column_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('column', '{{ module_site_url }}manage_table/index/insert', '{{ module_site_url }}manage_table/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_column(){
        return {
            name : '',
            caption : '',
            data_type : '',
            data_size : '',
            role : '',
            lookup_table_id : '',
            lookup_column_id : '',
            relation_table_id : '',
            relation_table_column_id : '',
            relation_selection_column_id : '',
            relation_priority_column_id : '',
            selection_table_id : '',
            selection_column_id : '',
            priority : '',
            value_selection_mode : '',
            value_selection_item : '',
            column_option : '',        };
    }

    // Function to add row
    function add_table_row_column(value){

        // Prepare some variables
        var input_prefix = 'md_field_column_col';
        var row_index    = RECORD_INDEX_column;
        var inputs       = new Array();
        
        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "caption"
        var input_id    = input_prefix + 'caption' + row_index;
        var field_value = get_object_property_as_str(value, 'caption');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="caption" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "data_type"
        var input_id    = input_prefix + 'data_type' + row_index;
        var field_value = get_object_property_as_str(value, 'data_type');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="data_type" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "data_size"
        var input_id    = input_prefix + 'data_size' + row_index;
        var field_value = get_object_property_as_str(value, 'data_size');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="data_size" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "role"
        var input_id    = input_prefix + 'role' + row_index;
        var field_value = get_object_property_as_str(value, 'role');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="role" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "lookup_table_id"
        var input_id    = input_prefix + 'lookup_table_id' + row_index;
        var field_value = get_object_property_as_str(value, 'lookup_table_id');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="lookup_table_id" >';
        html += build_single_select_option(field_value, OPTIONS_column.lookup_table_id);
        html += '</select>';
        inputs.push(html);

        // FIELD "lookup_column_id"
        var input_id    = input_prefix + 'lookup_column_id' + row_index;
        var field_value = get_object_property_as_str(value, 'lookup_column_id');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="lookup_column_id" >';
        html += build_single_select_option(field_value, OPTIONS_column.lookup_column_id);
        html += '</select>';
        inputs.push(html);

        // FIELD "relation_table_id"
        var input_id    = input_prefix + 'relation_table_id' + row_index;
        var field_value = get_object_property_as_str(value, 'relation_table_id');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="relation_table_id" >';
        html += build_single_select_option(field_value, OPTIONS_column.relation_table_id);
        html += '</select>';
        inputs.push(html);

        // FIELD "relation_table_column_id"
        var input_id    = input_prefix + 'relation_table_column_id' + row_index;
        var field_value = get_object_property_as_str(value, 'relation_table_column_id');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="relation_table_column_id" >';
        html += build_single_select_option(field_value, OPTIONS_column.relation_table_column_id);
        html += '</select>';
        inputs.push(html);

        // FIELD "relation_selection_column_id"
        var input_id    = input_prefix + 'relation_selection_column_id' + row_index;
        var field_value = get_object_property_as_str(value, 'relation_selection_column_id');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="relation_selection_column_id" >';
        html += build_single_select_option(field_value, OPTIONS_column.relation_selection_column_id);
        html += '</select>';
        inputs.push(html);

        // FIELD "relation_priority_column_id"
        var input_id    = input_prefix + 'relation_priority_column_id' + row_index;
        var field_value = get_object_property_as_str(value, 'relation_priority_column_id');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="relation_priority_column_id" >';
        html += build_single_select_option(field_value, OPTIONS_column.relation_priority_column_id);
        html += '</select>';
        inputs.push(html);

        // FIELD "selection_table_id"
        var input_id    = input_prefix + 'selection_table_id' + row_index;
        var field_value = get_object_property_as_str(value, 'selection_table_id');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="selection_table_id" >';
        html += build_single_select_option(field_value, OPTIONS_column.selection_table_id);
        html += '</select>';
        inputs.push(html);

        // FIELD "selection_column_id"
        var input_id    = input_prefix + 'selection_column_id' + row_index;
        var field_value = get_object_property_as_str(value, 'selection_column_id');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="selection_column_id" >';
        html += build_single_select_option(field_value, OPTIONS_column.selection_column_id);
        html += '</select>';
        inputs.push(html);

        // FIELD "priority"
        var input_id    = input_prefix + 'priority' + row_index;
        var field_value = get_object_property_as_str(value, 'priority');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="priority" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "value_selection_mode"
        var input_id    = input_prefix + 'value_selection_mode' + row_index;
        var field_value = get_object_property_as_str(value, 'value_selection_mode');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="value_selection_mode" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "value_selection_item"
        var input_id    = input_prefix + 'value_selection_item' + row_index;
        var field_value = get_object_property_as_str(value, 'value_selection_item');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="value_selection_item" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "column_option"
        var input_id    = input_prefix + 'column_option' + row_index;
        var field_value = get_object_property_as_str(value, 'column_option');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="column_option" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
