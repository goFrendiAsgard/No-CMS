<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('project', 'Project', array('Name', 'Db Server', 'Db Port', 'Db Schema', 'Db User', 'Db Password', 'Db Table Prefix', 'Table', 'Project Option'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('project', 'project_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('project', '{{ module_site_url }}manage_template/index/insert', '{{ module_site_url }}manage_template/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_project(){
        return {
            name : '',
            db_server : '',
            db_port : '',
            db_schema : '',
            db_user : '',
            db_password : '',
            db_table_prefix : '',
            table : '',
            project_option : '',        };
    }

    // Function to add row
    function add_table_row_project(value){

        // Prepare some variables
        var input_prefix = 'md_field_project_col';
        var row_index    = RECORD_INDEX_project;
        var inputs       = new Array();

        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "db_server"
        var input_id    = input_prefix + 'db_server' + row_index;
        var field_value = get_object_property_as_str(value, 'db_server');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="db_server" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "db_port"
        var input_id    = input_prefix + 'db_port' + row_index;
        var field_value = get_object_property_as_str(value, 'db_port');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="db_port" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "db_schema"
        var input_id    = input_prefix + 'db_schema' + row_index;
        var field_value = get_object_property_as_str(value, 'db_schema');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="db_schema" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "db_user"
        var input_id    = input_prefix + 'db_user' + row_index;
        var field_value = get_object_property_as_str(value, 'db_user');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="db_user" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "db_password"
        var input_id    = input_prefix + 'db_password' + row_index;
        var field_value = get_object_property_as_str(value, 'db_password');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="db_password" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "db_table_prefix"
        var input_id    = input_prefix + 'db_table_prefix' + row_index;
        var field_value = get_object_property_as_str(value, 'db_table_prefix');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="db_table_prefix" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "table"
        var input_id    = input_prefix + 'table' + row_index;
        var field_value = get_object_property_as_str(value, 'table');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="table" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "project_option"
        var input_id    = input_prefix + 'project_option' + row_index;
        var field_value = get_object_property_as_str(value, 'project_option');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="project_option" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
