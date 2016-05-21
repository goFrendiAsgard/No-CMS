<?php
    // Generate HTML. Parameters: column_name, caption, array_of_field_captions
    $HTML = build_md_html_table('layer', 'Layer', array('Layer Name', 'Group Name', 'Layer Desc', 'Z Index', 'Shown', 'Radius', 'Fill Color', 'Color', 'Weight', 'Opacity', 'Fill Opacity', 'Image Url', 'Json Sql', 'Json Shape Column', 'Json Popup Content', 'Json Label', 'Use Json Url', 'Json Url', 'Searchable', 'Search Sql', 'Search Result Content', 'Search Result X Column', 'Search Result Y Column', 'Use Search Url', 'Search Url'));
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
            group_name : '',
            layer_desc : '',
            z_index : '',
            shown : '',
            radius : '',
            fill_color : '',
            color : '',
            weight : '',
            opacity : '',
            fill_opacity : '',
            image_url : '',
            json_sql : '',
            json_shape_column : '',
            json_popup_content : '',
            json_label : '',
            use_json_url : '',
            json_url : '',
            searchable : '',
            search_sql : '',
            search_result_content : '',
            search_result_x_column : '',
            search_result_y_column : '',
            use_search_url : '',
            search_url : '',        };
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

        // FIELD "layer_desc"
        var input_id    = input_prefix + 'layer_desc' + row_index;
        var field_value = get_object_property_as_str(value, 'layer_desc');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="layer_desc" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "z_index"
        var input_id    = input_prefix + 'z_index' + row_index;
        var field_value = get_object_property_as_str(value, 'z_index');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="z_index" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "shown"
        var input_id    = input_prefix + 'shown' + row_index;
        var field_value = get_object_property_as_str(value, 'shown');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="shown" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "radius"
        var input_id    = input_prefix + 'radius' + row_index;
        var field_value = get_object_property_as_str(value, 'radius');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="radius" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "fill_color"
        var input_id    = input_prefix + 'fill_color' + row_index;
        var field_value = get_object_property_as_str(value, 'fill_color');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="fill_color" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "color"
        var input_id    = input_prefix + 'color' + row_index;
        var field_value = get_object_property_as_str(value, 'color');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="color" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "weight"
        var input_id    = input_prefix + 'weight' + row_index;
        var field_value = get_object_property_as_str(value, 'weight');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="weight" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "opacity"
        var input_id    = input_prefix + 'opacity' + row_index;
        var field_value = get_object_property_as_str(value, 'opacity');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="opacity" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "fill_opacity"
        var input_id    = input_prefix + 'fill_opacity' + row_index;
        var field_value = get_object_property_as_str(value, 'fill_opacity');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="fill_opacity" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "image_url"
        var input_id    = input_prefix + 'image_url' + row_index;
        var field_value = get_object_property_as_str(value, 'image_url');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="image_url" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "json_sql"
        var input_id    = input_prefix + 'json_sql' + row_index;
        var field_value = get_object_property_as_str(value, 'json_sql');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="json_sql" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "json_shape_column"
        var input_id    = input_prefix + 'json_shape_column' + row_index;
        var field_value = get_object_property_as_str(value, 'json_shape_column');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="json_shape_column" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "json_popup_content"
        var input_id    = input_prefix + 'json_popup_content' + row_index;
        var field_value = get_object_property_as_str(value, 'json_popup_content');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="json_popup_content" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "json_label"
        var input_id    = input_prefix + 'json_label' + row_index;
        var field_value = get_object_property_as_str(value, 'json_label');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="json_label" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "use_json_url"
        var input_id    = input_prefix + 'use_json_url' + row_index;
        var field_value = get_object_property_as_str(value, 'use_json_url');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="use_json_url" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "json_url"
        var input_id    = input_prefix + 'json_url' + row_index;
        var field_value = get_object_property_as_str(value, 'json_url');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="json_url" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "searchable"
        var input_id    = input_prefix + 'searchable' + row_index;
        var field_value = get_object_property_as_str(value, 'searchable');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="searchable" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "search_sql"
        var input_id    = input_prefix + 'search_sql' + row_index;
        var field_value = get_object_property_as_str(value, 'search_sql');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="search_sql" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "search_result_content"
        var input_id    = input_prefix + 'search_result_content' + row_index;
        var field_value = get_object_property_as_str(value, 'search_result_content');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="search_result_content" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "search_result_x_column"
        var input_id    = input_prefix + 'search_result_x_column' + row_index;
        var field_value = get_object_property_as_str(value, 'search_result_x_column');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="search_result_x_column" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "search_result_y_column"
        var input_id    = input_prefix + 'search_result_y_column' + row_index;
        var field_value = get_object_property_as_str(value, 'search_result_y_column');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="search_result_y_column" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "use_search_url"
        var input_id    = input_prefix + 'use_search_url' + row_index;
        var field_value = get_object_property_as_str(value, 'use_search_url');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+' numeric" column_name="use_search_url" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // FIELD "search_url"
        var input_id    = input_prefix + 'search_url' + row_index;
        var field_value = get_object_property_as_str(value, 'search_url');
        var html = '<input id="'+input_id+'" record_index="'+row_index+'" class="'+input_prefix+'" column_name="search_url" type="text" value="'+field_value+'"/>';
        inputs.push(html);

        // Return inputs
        return inputs;
    }

</script>
