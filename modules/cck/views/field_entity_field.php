<style type="text/css">
    #md_table_field th:last-child, #md_table_field td:last-child{
        text-align:right;
        width:150px!important;
    }
</style>
<?php
    $manage_field_link = '<a class="btn btn-default save-on-click" href="{{ module_site_url }}manage_field/index/'.$primary_key.'"><i class="glyphicon glyphicon-list"></i> Manage Field</a>&nbsp;';
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('field', 'Field', array('Order Index', 'Name', 'Template (Leave blank for custom)'), TRUE, TRUE, $manage_field_link);
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('field', 'id', $date_format, $result, $options);
    $JS  .= build_md_event_script('field', '{{ module_site_url }}manage_entity/index/insert', '{{ module_site_url }}manage_entity/index/update');
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
            id_template : '',
        };
    }

    // Function to add row
    function add_table_row_field(value){

        // Prepare some variables
        var input_prefix = 'md_field_field_col';
        var row_index    = RECORD_INDEX_field;
        var inputs       = new Array();

        // FIELD "order_index"
        var input_id    = input_prefix + 'order_index' + row_index;
        var field_value = get_object_property_as_str(value, 'order_index');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="order_index" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "id_template"
        var input_id    = input_prefix + 'id_template' + row_index;
        var field_value = get_object_property_as_str(value, 'id_template');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric chzn-select" column_name="id_template" >';
        html += build_single_select_option(field_value, OPTIONS_field.id_template);
        html += '</select>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

    function add_table_row_field_action(value){
        actions = [];
        for(var i=0; i<DATA_field.update.length; i++){
            row = DATA_field.update[i];
            if(row.data == value){
                actions.push('<a class="btn btn-default save-on-click" href="{{ module_site_url }}manage_field/index/<?php echo $primary_key; ?>/edit/' + row.primary_key + '"><i class="glyphicon glyphicon-pencil"></i> Edit</a>&nbsp;');
                break;
            }
        }
        return actions;
    }

    // submit the form, save the next url, and when the ajax is complete, do redirection
    var _next_url = '';
    $('body').on('click', '.save-on-click', function(event){
        $('#crudForm').trigger('submit');
        _next_url = $(this).attr('href');
        event.preventDefault();
    });
    $(document).ajaxComplete(function(event){
        if(_next_url != ''){
            window.location = _next_url;
        }
    });

</script>
