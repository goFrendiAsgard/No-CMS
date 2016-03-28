<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('detail_language', 'Detail Language', array('Key', 'Translation'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('detail_language', 'detail_language_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('detail_language', '{{ module_site_url }}manage_language/index/insert', '{{ module_site_url }}manage_language/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_detail_language(){
        return {
            key : '',
            translation : '',        };
    }

    // Function to add row
    function add_table_row_detail_language(value){

        // Prepare some variables
        var input_prefix = 'md_field_detail_language_col';
        var row_index    = RECORD_INDEX_detail_language;
        var inputs       = new Array();
        
        // FIELD "key"
        var input_id    = input_prefix + 'key' + row_index;
        var field_value = get_object_property_as_str(value, 'key');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="key" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "translation"
        var input_id    = input_prefix + 'translation' + row_index;
        var field_value = get_object_property_as_str(value, 'translation');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="translation" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
