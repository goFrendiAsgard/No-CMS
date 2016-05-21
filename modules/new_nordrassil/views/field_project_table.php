<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('table', 'Table', array('Name', 'Caption', 'Order Index'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('table', 'table_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('table', '{{ module_site_url }}manage_project/index/insert', '{{ module_site_url }}manage_project/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">
    var PRIORITY = 0;

    // Function to get default value
    function default_row_table(){
        PRIORITY++;
        return {
            name : '',
            caption : '',
            priority : PRIORITY,
        };
    }

    // Function to add row
    function add_table_row_table(value){

        // Prepare some variables
        var input_prefix = 'md_field_table_col';
        var row_index    = RECORD_INDEX_table;
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

        // FIELD "priority"
        var input_id    = input_prefix + 'priority' + row_index;
        var field_value = get_object_property_as_str(value, 'priority');
        var html = '<input type="hidden" id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="priority" type="text" value="'+field_value+'"/>' + field_value;
        inputs.push(html);
        PRIORITY = field_value;

        // Return inputs
        return inputs;
    }

    function add_table_row_table_action(value){
        actions = []
        for(var i=0; i<DATA_table.update.length; i++){
            row = DATA_table.update[i];
            if(row.data == value){
                actions.push('primary key : ' + row.primary_key);
                break;
            }
        }
        return actions;
    }

</script>
