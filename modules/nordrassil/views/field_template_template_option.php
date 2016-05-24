<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('template_option', 'Template Option', array('Name', 'Description', 'Option Type'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('template_option', 'option_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('template_option', '{{ module_site_url }}manage_template/index/insert', '{{ module_site_url }}manage_template/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_template_option(){
        return {
            name : '',
            description : '',
            option_type : '',
        };
    }

    // Function to add row
    function add_table_row_template_option(value){

        // Prepare some variables
        var input_prefix = 'md_field_template_option_col';
        var row_index    = RECORD_INDEX_template_option;
        var inputs       = new Array();

        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "description"
        var input_id    = input_prefix + 'description' + row_index;
        var field_value = get_object_property_as_str(value, 'description');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="description" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "option_type"
        var input_id    = input_prefix + 'option_type' + row_index;
        var field_value = get_object_property_as_str(value, 'option_type');
        var options = ['project', 'table', 'column']
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="option_type">';
        for(var i=0; i<options.length; i++){
            var option = options[i];
            var selected = '';
            if(option == field_value){
                selected = 'selected';
            }
            html += '<option value="'+option+'" '+selected+'>'+option+'</option>';
        }
        html += '</select>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
