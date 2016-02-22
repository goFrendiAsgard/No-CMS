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
        var data = new Object();
        // Default values
        data.name = '';
        data.birthdate = '';
        data.job_id = '';
        data.hobby = '';
        return data;
    }

    // Function to add row
    function add_table_row_citizen(value){
        // Hide div#no-data
        $("#no-datamd_table_citizen").hide();
        $("#md_table_citizen").show();

        // Prepare some variables
        var input_prefix = 'md_field_citizen_col';
        var row_index    = RECORD_INDEX_citizen;
        var html         = '<tr id="md_field_citizen_tr_'+row_index+'" class="md_field_citizen_tr">';
        
        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        html += '<td>';
        html += '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'"/>';
        html += '</td>';

        // FIELD "birthdate"
        var input_id    = input_prefix + 'birthdate' + row_index;
        var field_value = get_object_property_as_str(value, 'birthdate');
        field_value     = php_date_to_js(field_value);
        html += '<td>';
        html += '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' datepicker-input" column_name="birthdate" type="text" value="'+field_value+'"/>';
        html += '<a href="#" class="datepicker-input-clear btn">Clear</a>';
        html += '</td>';

        // FIELD "job_id"
        var input_id    = input_prefix + 'job_id' + row_index;
        var field_value = get_object_property_as_str(value, 'job_id');
        html += '<td>';
        html += '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="job_id" >';
        html += build_single_select_option(field_value, OPTIONS_citizen.job_id);
        html += '</select>';
        html += '</td>';

        // FIELD "hobby"
        var input_id    = input_prefix + 'hobby' + row_index;
        var field_value = get_object_property_as_str(value, 'hobby');
        html += '<td>';
        html += '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' chzn-select" column_name="hobby"  multiple = "multiple">';
        html += build_multiple_select_option(field_value, OPTIONS_citizen.hobby);
        html += '</select>';
        html += '</td>';

        // Delete Button
        html += '<td>';
        html += '<span class="delete-icon btn btn-default md_field_citizen_delete" record_index="'+row_index+'">';
        html += '<i class="glyphicon glyphicon-minus-sign"></i>';
        html += '</span>';
        html += '</td>';


        html += '</tr>';

        // Add html to table
        $('#md_table_citizen tbody').append(html);
        __mutate_input('md_table_citizen');

    }

</script>
