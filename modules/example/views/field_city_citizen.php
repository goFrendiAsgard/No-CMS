<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('citizen', 'Citizen', array('Name', 'Birthdate', 'Job', 'Hobby'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('citizen', 'citizen_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('citizen', '{{ module_site_url }}manage_city/index/insert', '{{ module_site_url }}manage_city/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_citizen(){
        return {
            name : '',
            birthdate : '',
            job_id : '',
            hobby : '',        };
    }

    // Function to add row
    function add_table_row_citizen(value){

        // Prepare some variables
        var input_prefix = 'md_field_citizen_col';
        var row_index    = RECORD_INDEX_citizen;
        var inputs       = new Array();
        
        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "birthdate"
        var input_id    = input_prefix + 'birthdate' + row_index;
        var field_value = get_object_property_as_str(value, 'birthdate');
        field_value     = php_date_to_js(field_value);
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' datepicker-input" column_name="birthdate" type="text" value="'+field_value+'"/>';
        html += '<a href="#" class="datepicker-input-clear btn">Clear</a>';
        inputs.push(html);

        // FIELD "job_id"
        var input_id    = input_prefix + 'job_id' + row_index;
        var field_value = get_object_property_as_str(value, 'job_id');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="job_id" >';
        html += build_single_select_option(field_value, OPTIONS_citizen.job_id);
        html += '</select>';
        inputs.push(html);

        // FIELD "hobby"
        var input_id    = input_prefix + 'hobby' + row_index;
        var field_value = get_object_property_as_str(value, 'hobby');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' chzn-select" column_name="hobby"  multiple = "multiple">';
        html += build_multiple_select_option(field_value, OPTIONS_citizen.hobby);
        html += '</select>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
