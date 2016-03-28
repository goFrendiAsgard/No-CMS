<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('module', 'Module', array('Module Name', 'Module Path', 'Version', 'Module Dependency'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('module', 'module_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('module', '{{ module_site_url }}manage_user/index/insert', '{{ module_site_url }}manage_user/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_module(){
        return {
            module_name : '',
            module_path : '',
            version : '',
            module_dependency : '',        };
    }

    // Function to add row
    function add_table_row_module(value){

        // Prepare some variables
        var input_prefix = 'md_field_module_col';
        var row_index    = RECORD_INDEX_module;
        var inputs       = new Array();
        
        // FIELD "module_name"
        var input_id    = input_prefix + 'module_name' + row_index;
        var field_value = get_object_property_as_str(value, 'module_name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="module_name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "module_path"
        var input_id    = input_prefix + 'module_path' + row_index;
        var field_value = get_object_property_as_str(value, 'module_path');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="module_path" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "version"
        var input_id    = input_prefix + 'version' + row_index;
        var field_value = get_object_property_as_str(value, 'version');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="version" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "module_dependency"
        var input_id    = input_prefix + 'module_dependency' + row_index;
        var field_value = get_object_property_as_str(value, 'module_dependency');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="module_dependency" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
