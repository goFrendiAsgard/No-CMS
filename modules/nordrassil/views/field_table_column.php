<style type="text/css">
    #md_table_column th:last-child, #md_table_column td:last-child{
        text-align:right;
        width:150px!important;
    }
</style>
<?php
    $manage_column_link = '<a class="btn btn-default save-on-click" href="{{ module_site_url }}manage_column/index/'.$primary_key.'"><i class="glyphicon glyphicon-list"></i> Manage Column</a>&nbsp;';
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('column', 'Column', array('Name', 'Caption', 'Data Type', 'Data Size', 'Column Option', 'Index'), TRUE, TRUE, $manage_column_link);
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('column', 'column_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('column', '{{ module_site_url }}manage_table/index/insert', '{{ module_site_url }}manage_table/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">
    var PRIORITY = 0;

    // Function to get default value
    function default_row_column(){
        return {
            name : '',
            caption : '',
            data_type : '',
            data_size : '',
            column_option : [],
            priority : PRIORITY,
        };
    }

    // Function to add row
    function add_table_row_column(value){

        // Prepare some variables
        var input_prefix = 'md_field_column_col';
        var row_index    = RECORD_INDEX_column;
        var inputs       = new Array();

        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'" style="width:100px;" />';
        inputs.push(html);

        // FIELD "caption"
        var input_id    = input_prefix + 'caption' + row_index;
        var field_value = get_object_property_as_str(value, 'caption');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="caption" type="text" value="'+field_value+'" style="width:120px;" />';
        inputs.push(html);

        // FIELD "data_type"
        var input_id    = input_prefix + 'data_type' + row_index;
        var field_value = get_object_property_as_str(value, 'data_type');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="data_type" type="text" value="'+field_value+'" style="width:70px;" />';
        inputs.push(html);

        // FIELD "data_size"
        var input_id    = input_prefix + 'data_size' + row_index;
        var field_value = get_object_property_as_str(value, 'data_size');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="data_size" type="text" value="'+field_value+'" style="width:70px;" />';
        inputs.push(html);

        // FIELD "column_option"
        var input_id    = input_prefix + 'column_option' + row_index;
        var field_value = get_object_property_as_str(value, 'column_option');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' chzn-select" column_name="column_option"  multiple = "multiple">';
        html += build_multiple_select_option(field_value, OPTIONS_column.column_option);
        html += '</select>';
        inputs.push(html);

        // FIELD "priority"
        var input_id    = input_prefix + 'priority' + row_index;
        var field_value = get_object_property_as_str(value, 'priority');
        var html = '<input type="hidden" id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="priority" type="text" value="'+field_value+'"/>' + field_value;
        inputs.push(html);
        PRIORITY = field_value;

        // Return inputs
        return inputs;
    }

    function add_table_row_column_action(value){
        actions = []
        for(var i=0; i<DATA_column.update.length; i++){
            row = DATA_column.update[i];
            if(row.data == value){
                actions.push('<a class="btn btn-default save-on-click" href="{{ module_site_url }}manage_column/index/<?php echo $primary_key; ?>/edit/' + row.primary_key + '"><i class="glyphicon glyphicon-pencil"></i> Edit</a>&nbsp;');
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
