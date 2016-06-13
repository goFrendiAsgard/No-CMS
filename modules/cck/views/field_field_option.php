<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('option', 'Option', array('Name', 'Shown'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('option', 'id', $date_format, $result, $options);
    $JS  .= build_md_event_script('option', '{{ module_site_url }}manage_field/index/insert', '{{ module_site_url }}manage_field/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_option(){
        return {
            name : '',
            shown : '',
        };
    }

    // Function to add row
    function add_table_row_option(value){

        // Prepare some variables
        var input_prefix = 'md_field_option_col';
        var row_index    = RECORD_INDEX_option;
        var inputs       = new Array();

        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "shown"
        var input_id    = input_prefix + 'shown' + row_index;
        var field_value = get_object_property_as_str(value, 'shown');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="shown" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
