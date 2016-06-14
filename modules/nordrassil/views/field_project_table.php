<style type="text/css">
    #md_table_table th:last-child, #md_table_table td:last-child{
        text-align:right;
        width:150px!important;
    }
</style>
<?php
    $export_link = '<a target="blank" href="{{ module_site_url }}manage_project/get_seed/'.$primary_key.'" class="btn btn-default" title="Export"><i class="glyphicon glyphicon-export"></i> Export</a>&nbsp;';
    $resync_link = '<a class="btn btn-default save-on-click" href="{{ module_site_url }}manage_project/resynchronize_from_db/'.$primary_key.'"><i class="glyphicon glyphicon-repeat"></i> Synchronize</a>&nbsp;';
    $manage_table_link = '<a class="btn btn-default save-on-click" href="{{ module_site_url }}manage_table/index/'.$primary_key.'"><i class="glyphicon glyphicon-list"></i> Manage Table</a>&nbsp;';
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('table', 'Table', array('Name', 'Caption', 'Options', 'Index'), TRUE, TRUE, $export_link . $resync_link .  $manage_table_link);
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('table', 'table_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('table', '{{ module_site_url }}manage_project/index/insert', '{{ module_site_url }}manage_project/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">
    var PRIORITY = 0;

    // Function to get default value
    function default_row_table(){
        PRIORITY++;
        return {
            name : '',
            caption : '',
            table_option : [],
            priority : PRIORITY,
        };
    }

    // Function to add row
    function add_table_row_table(value){

        // Prepare some variables
        var input_prefix = 'md_field_table_col';
        var row_index    = RECORD_INDEX_table;
        var inputs       = new Array();

        // FIELD "name"
        var input_id    = input_prefix + 'name' + row_index;
        var field_value = get_object_property_as_str(value, 'name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "caption"
        var input_id    = input_prefix + 'caption' + row_index;
        var field_value = get_object_property_as_str(value, 'caption');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="caption" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "table_option"
        var input_id    = input_prefix + 'table_option' + row_index;
        var field_value = get_object_property_as_str(value, 'table_option');
        var html = '<select id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' chzn-select" column_name="table_option"  multiple = "multiple">';
        html += build_multiple_select_option(field_value, OPTIONS_table.table_option);
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

    function add_table_row_table_action(value){
        actions = []
        for(var i=0; i<DATA_table.update.length; i++){
            row = DATA_table.update[i];
            if(row.data == value){
                actions.push('<a class="btn btn-default save-on-click" href="{{ module_site_url }}manage_table/index/<?php echo $primary_key; ?>/edit/' + row.primary_key + '"><i class="glyphicon glyphicon-pencil"></i> Edit</a>&nbsp;');
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
    });;

</script>
