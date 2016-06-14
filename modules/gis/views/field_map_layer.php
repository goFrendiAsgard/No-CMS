<style type="text/css">
    #md_table_layer th:last-child, #md_table_layer td:last-child{
        text-align:right;
        width:150px!important;
    }
</style>
<?php
    $manage_layer_link = '<a class="btn btn-default save-on-click" href="{{ module_site_url }}manage_layer/index/'.$primary_key.'"><i class="glyphicon glyphicon-list"></i> Manage Layers</a>&nbsp;';
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('layer', 'Layer', array('Layer Name', 'Group Name'), TRUE, TRUE, $manage_layer_link);
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('layer', 'layer_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('layer', '{{ module_site_url }}manage_map/index/insert', '{{ module_site_url }}manage_map/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_layer(){
        return {
            layer_name : '',
            group_name : ''
        }
    }

    // Function to add row
    function add_table_row_layer(value){

        // Prepare some variables
        var input_prefix = 'md_field_layer_col';
        var row_index    = RECORD_INDEX_layer;
        var inputs       = new Array();

        // FIELD "layer_name"
        var input_id    = input_prefix + 'layer_name' + row_index;
        var field_value = get_object_property_as_str(value, 'layer_name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="layer_name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "group_name"
        var input_id    = input_prefix + 'group_name' + row_index;
        var field_value = get_object_property_as_str(value, 'group_name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="group_name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

    function add_table_row_layer_action(value){
        actions = []
        for(var i=0; i<DATA_layer.update.length; i++){
            row = DATA_layer.update[i];
            if(row.data == value){
                actions.push('<a class="btn btn-default save-on-click" href="{{ module_site_url }}manage_layer/index/<?php echo $primary_key; ?>/edit/' + row.primary_key + '"><i class="glyphicon glyphicon-pencil"></i> Edit</a>&nbsp;');
                break;
            }
        }
        return actions;
    }

    $('body').on('click', '.save-on-click', function(event){
        $("#form-button-save").trigger('click');
    });

</script>
