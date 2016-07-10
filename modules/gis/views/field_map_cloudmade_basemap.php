<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('cloudmade_basemap', 'Basemap', array('Basemap Name', 'Url', 'Max Zoom', 'Attribution'));
    // Generate global variable and event-binding
    $JS   = build_md_global_variable_script('cloudmade_basemap', 'basemap_id', $date_format, $result, $options);
    $JS  .= build_md_event_script('cloudmade_basemap', '{{ module_site_url }}manage_map/index/insert', '{{ module_site_url }}manage_map/index/update');
    // Show HTML
    echo $HTML;
    // Show JS
    echo '<script type="text/javascript">'.$JS.'</script>';
?>
<script type="text/javascript">

    // Function to get default value
    function default_row_cloudmade_basemap(){
        return {
            basemap_name : '',
            url : '',
            max_zoom : '',
            attribution : '',        };
    }

    // Function to add row
    function add_table_row_cloudmade_basemap(value){

        // Prepare some variables
        var input_prefix = 'md_field_cloudmade_basemap_col';
        var row_index    = RECORD_INDEX_cloudmade_basemap;
        var inputs       = new Array();

        // FIELD "basemap_name"
        var input_id    = input_prefix + 'basemap_name' + row_index;
        var field_value = get_object_property_as_str(value, 'basemap_name');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="basemap_name" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "url"
        var input_id    = input_prefix + 'url' + row_index;
        var field_value = get_object_property_as_str(value, 'url');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="url" type="text" style="width:250px;" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "max_zoom"
        var input_id    = input_prefix + 'max_zoom' + row_index;
        var field_value = get_object_property_as_str(value, 'max_zoom');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="max_zoom" type="text" style="width:80px;" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "attribution"
        var input_id    = input_prefix + 'attribution' + row_index;
        var field_value = get_object_property_as_str(value, 'attribution');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="attribution" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
