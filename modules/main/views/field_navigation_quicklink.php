<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('quicklink', 'Quicklink', array('Index'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('quicklink', 'quicklink_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('quicklink', '{{ module_site_url }}manage_navigation/index/insert', '{{ module_site_url }}manage_navigation/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_quicklink(){
        return {
            index : '',        };
    }

    // Function to add row
    function add_table_row_quicklink(value){

        // Prepare some variables
        var input_prefix = 'md_field_quicklink_col';
        var row_index    = RECORD_INDEX_quicklink;
        var inputs       = new Array();
        
        // FIELD "index"
        var input_id    = input_prefix + 'index' + row_index;
        var field_value = get_object_property_as_str(value, 'index');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="index" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
