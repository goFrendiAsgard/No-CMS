<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('field', 'Field', array('Name', 'Id Entity', 'Add Input', 'Edit Input', 'View Html', 'Shown On Add', 'Shown On Edit', 'Shown On Delete', 'Option'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('field', 'id', $date_format, $result, $options);
    $JS  .= build_md_event_script('field', '{{ module_site_url }}manage_template/index/insert', '{{ module_site_url }}manage_template/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_field(){
        return {
            name : '',
            id_entity : '',
            add_input : '',
            edit_input : '',
            view_html : '',
            shown_on_add : '',
            shown_on_edit : '',
            shown_on_delete : '',
            option : '',
        };
    }

    // Function to add row
    function add_table_row_field(value){

        // Prepare some variables
        var input_prefix = 'md_field_field_col';
        var row_index    = RECORD_INDEX_field;
        var inputs       = new Array();
        
        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "id_entity"
        var input_id    = input_prefix + 'id_entity' + row_index;
        var field_value = get_object_property_as_str(value, 'id_entity');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="id_entity" >';
        html += build_single_select_option(field_value, OPTIONS_field.id_entity);
        html += '</select>';
        inputs.push(html);

        // FIELD "add_input"
        var input_id    = input_prefix + 'add_input' + row_index;
        var field_value = get_object_property_as_str(value, 'add_input');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="add_input" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "edit_input"
        var input_id    = input_prefix + 'edit_input' + row_index;
        var field_value = get_object_property_as_str(value, 'edit_input');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="edit_input" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "view_html"
        var input_id    = input_prefix + 'view_html' + row_index;
        var field_value = get_object_property_as_str(value, 'view_html');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="view_html" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "shown_on_add"
        var input_id    = input_prefix + 'shown_on_add' + row_index;
        var field_value = get_object_property_as_str(value, 'shown_on_add');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="shown_on_add" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "shown_on_edit"
        var input_id    = input_prefix + 'shown_on_edit' + row_index;
        var field_value = get_object_property_as_str(value, 'shown_on_edit');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="shown_on_edit" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "shown_on_delete"
        var input_id    = input_prefix + 'shown_on_delete' + row_index;
        var field_value = get_object_property_as_str(value, 'shown_on_delete');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="shown_on_delete" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "option"
        var input_id    = input_prefix + 'option' + row_index;
        var field_value = get_object_property_as_str(value, 'option');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="option" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
