<?php
    $integer_type = $auto_increment_data_type = array('int', 'tinyint', 'smallint', 'mediumint', 'integer', 'bigint');
    $view_path = $project_name.'/data/field_'.$project_name.'_'.$master_table_name.'_'.$master_column_name;
    $detail_columns = $detail_table['columns'];
    $detail_column_captions = array();
    $detail_column_names = array();
    $detail_column_data_types = array();
    $detail_column_role = array();
    $detail_value_selection_mode = array();
    foreach($detail_columns as $detail_column){
        $caption = $detail_column['caption'];
        $name = $detail_column['name'];
        $data_type = $detail_column['data_type'];
        $role = $detail_column['role'];
        $value_selection_mode = $detail_column['value_selection_mode'];
        if($name == $detail_primary_key_name) continue;
        if($name == $detail_foreign_key_name) continue;
        $detail_column_captions[] = $caption;
        $detail_column_names[] = $name;
        $detail_column_data_types[] = $data_type;
        $detail_column_role[] = $role;
        $detail_value_selection_mode[] = $value_selection_mode;
    }

    $detail_table_caption = $detail_table['caption'];

    $delete_button_class = 'md_field_'.$master_column_name.'_delete';
    $tr_class = 'md_field_'.$master_column_name.'_tr';
    $add_button_id = 'md_field_'.$master_column_name.'_add';
    $column_input_class = 'md_field_'.$master_column_name.'_col';
    $real_input_id = 'md_real_field_'.$master_column_name.'_col';
    $table_id = 'md_table_'.$master_column_name;

    $var_record_index = 'RECORD_INDEX_'.$master_column_name;
    $var_data = 'DATA_'.$master_column_name;
    $fn_synchronize = 'synchronize_'.$master_column_name;
    $fn_add_table_row = 'add_table_row_'.$master_column_name;
?>
&lt;?php
    $record_index = 0;
?&gt;
<link rel="stylesheet" type="text/css" href="&lt;?php echo base_url('assets/grocery_crud/css/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS); ?&gt;" />
<link rel="stylesheet" type="text/css" href="&lt;?php echo base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'); ?&gt;" />
<style type="text/css">
    /* set width of every detail input*/
    #<?php echo $table_id; ?> .<?php echo $column_input_class ?>{
        width:auto!important;
        min-width:50px!important;
        max-width:150px!important;
    }
    #<?php echo $table_id; ?> .datepicker-input{
        width:auto!important;
        min-width:50px!important;
        max-width:100px!important;
    }
    #<?php echo $table_id; ?> .chzn-container,
    #<?php echo $table_id; ?> .chzn-drop{
        width:auto!important;
        min-width:100px!important;
        max-width:250px!important;
    }
</style>

<table id="<?php echo $table_id; ?>" class="table table-striped table-bordered">
    <thead>
        <tr>
<?php
    foreach($detail_column_captions as $caption){
        echo '          <th>'.$caption.'</th>'.PHP_EOL;
    }
?>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- the data presentation be here -->
    </tbody>
</table>
<input id="<?php echo $add_button_id; ?>" class="btn" type="button" value="Add <?php echo $detail_table_caption; ?>" />
<br />
<!-- This is the real input. If you want to catch the data, please json_decode this input's value -->
<input id="<?php echo $real_input_id; ?>" name="<?php echo $real_input_id; ?>" type="hidden" />

<script type="text/javascript" src="&lt;?php echo base_url('assets/grocery_crud/js/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS); ?&gt;"></script>
<script type="text/javascript" src="&lt;?php echo base_url('assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js'); ?&gt;"></script>
<script type="text/javascript" src="&lt;?php echo base_url('assets/grocery_crud/js/jquery_plugins/jquery.ui.datetime.js'); ?&gt;"></script>
<script type="text/javascript" src="&lt;?php echo base_url('assets/grocery_crud/js/jquery_plugins/jquery.numeric.min.js'); ?&gt;"></script>
<script type="text/javascript">

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // DATA INITIALIZATION
    //
    // * Prepare some global variables
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    var DATE_FORMAT = '&lt;?php echo $date_format ?&gt;';
    var OPTIONS = &lt;?php echo json_encode($options); ?&gt;;
    var <?php echo $var_record_index; ?> = &lt;?php echo $record_index; ?&gt;;
    var <?php echo $var_data; ?> = {update:new Array(), insert:new Array(), delete:new Array()};
    var old_data = &lt;?php echo json_encode($result); ?&gt;;
    for(var i=0; i<old_data.length; i++){
        var row = old_data[i];
        var record_index = i;
        var primary_key = row['<?php echo $detail_primary_key_name; ?>'];
        var data = row;
        delete data['<?php echo $detail_primary_key_name; ?>'];
        <?php echo $var_data; ?>.update.push({
            'record_index' : record_index,
            'primary_key' : primary_key,
            'data' : data,
        });
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // ADD ROW FUNCTION
    //
    // * When "Add <?php echo $detail_table_caption; ?>" clicked, this function is called without parameter.
    // * When page loaded for the first time, this function is called with value parameter
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function <?php echo $fn_add_table_row; ?>(value){

        var component = '<tr id="<?php echo $tr_class ?>_'+<?php echo $var_record_index; ?>+'" class="<?php echo $tr_class ?>">';
        <?php
        $date_exist = FALSE;
        for($i=0; $i<count($detail_column_names); $i++){
            $name = $detail_column_names[$i];
            $caption = $detail_column_captions[$i];
            $data_type = $detail_column_data_types[$i];
            $role = $detail_column_role[$i];
            $selection_mode = $detail_value_selection_mode[$i];
            $additional_class_array = array();
            if($data_type=='date'){
                $additional_class_array[] = 'datepicker-input';
            }else if(in_array($data_type, $integer_type)){
                $additional_class_array[] = 'numeric';
            }
            if(count($additional_class_array)>0){
                $additional_class = ' '.implode(' ',$additional_class_array);
            }else{
                $additional_class = '';
            }
            echo PHP_EOL;
            echo '        /////////////////////////////////////////////////////////////////////////////////////////////////////'.PHP_EOL;
            echo '        //    FIELD "'.$name.'"'.PHP_EOL;
            echo '        /////////////////////////////////////////////////////////////////////////////////////////////////////'.PHP_EOL;
            echo '      var field_value = \'\''.PHP_EOL;
            echo '      if(typeof(value) != \'undefined\' && value.hasOwnProperty(\''.$name.'\')){'.PHP_EOL;
            if($data_type=='date'){
                echo '          field_value = php_date_to_js(value.'.$name.');'.PHP_EOL;
            }else{
                echo '          field_value = value.'.$name.';'.PHP_EOL;
            }
            echo '      }'.PHP_EOL;
            echo '      component += \'<td>\';'.PHP_EOL;
            // create input based on role and type
            if($role=='lookup' || $role=='detail many to many' || $selection_mode=='enum' || $selection_mode=='set'){
                $multiple = '';
                if($role=='lookup' || $selection_mode=='enum'){
                    $multiple = '';
                }else{
                    $multiple = ' multiple = "multiple"';
                }
                echo '      component += \'<select id="'.$column_input_class.'_'.$name.'_\'+'.$var_record_index.'+\'" record_index="\'+'.$var_record_index.
                    '+\'" class="'.$column_input_class.$additional_class.' chzn-select" column_name="'.$name.'" '.$multiple.'>\';'.PHP_EOL;
                echo '      var options = OPTIONS.'.$name.';'.PHP_EOL;
                echo '      component += \'<option value></option>\';'.PHP_EOL;
                echo '      for(var i=0; i<options.length; i++){'.PHP_EOL;
                echo '          var option = options[i];'.PHP_EOL;
                if($role=='lookup' || $selection_mode=='enum'){
                    echo '          var selected = \'\';'.PHP_EOL;
                    echo '          if(option[\'value\'] == field_value){'.PHP_EOL;
                    echo '              selected = \'selected="selected"\';'.PHP_EOL;
                    echo '          }'.PHP_EOL;
                }else{
                    echo '          var selected = \'\';'.PHP_EOL;
                    echo '          if($.inArray(option[\'value\'],field_value)>-1){'.PHP_EOL;
                    echo '              selected = \'selected="selected"\';'.PHP_EOL;
                    echo '          }'.PHP_EOL;
                }
                echo '          component += \'<option value="\'+option[\'value\']+\'" \'+selected+\'>\'+option[\'caption\']+\'</option>\';'.PHP_EOL;
                echo '      }'.PHP_EOL;
                echo '      component += \'</select>\';'.PHP_EOL;
            }else{
                echo '      component += \'<input id="'.$column_input_class.'_'.$name.'_\'+'.$var_record_index.'+\'" record_index="\'+'.$var_record_index.
                    '+\'" class="'.$column_input_class.$additional_class.'" column_name="'.$name.'" type="text" value="\'+field_value+\'"/>\';'.PHP_EOL;
                if($data_type == 'date'){
                    echo '      component += \'<a href="#" class="datepicker-input-clear btn">Clear</a>\';'.PHP_EOL;
                }
            }
            echo'       component += \'</td>\';'.PHP_EOL.PHP_EOL;
        }
        ?>


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // Delete Button
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        component += '<td><input class="<?php echo $delete_button_class; ?> btn" record_index="'+<?php echo $var_record_index; ?>+'" primary_key="" type="button" value="Delete <?php echo $detail_table_caption; ?>" /></td>';
        component += '</tr>';

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // Add component to table
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#<?php echo $table_id; ?> tbody').append(component);
        mutate_input();

    } // end of ADD ROW FUNCTION



    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Main event handling program
    //
    // * Initialization
    // * <?php echo $add_button_id; ?>.click (Add row)
    // * <?php echo $delete_button_class; ?>.click (Delete row)
    // * <?php echo $column_input_class; ?>.change (Edit cell)
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(document).ready(function(){

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // INITIALIZATION
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        <?php echo $fn_synchronize; ?>();
        for(var i=0; i<<?php echo $var_data; ?>.update.length; i++){
            <?php echo $fn_add_table_row; ?>(<?php echo $var_data; ?>.update[i].data);
            <?php echo $var_record_index; ?>++;
        }


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // <?php echo $add_button_id; ?>.click (Add row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#<?php echo $add_button_id; ?>').click(function(){
            // new data
            var data = new Object();
            <?php echo PHP_EOL;
            foreach($detail_column_names as $name){
                echo '          data.'.$name.' = \'\';'.PHP_EOL;
            }
            ?>
            // insert data to the <?php echo $var_data.PHP_EOL; ?>
            <?php echo $var_data; ?>.insert.push({
                'record_index' : <?php echo $var_record_index; ?>,
                'primary_key' : '',
                'data' : data,
            });

            // add table's row
            <?php echo $fn_add_table_row; ?>(data);
            // add <?php $var_record_index; ?> by 1
            <?php echo $var_record_index; ?>++;

            // synchronize to the <?php echo $real_input_id.PHP_EOL; ?>
            <?php echo $fn_synchronize; ?>();
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // <?php echo $delete_button_class; ?>.click (Delete row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('.<?php echo $delete_button_class ?>').live('click', function(){
            var record_index = $(this).attr('record_index');
            // remove the component
            $('#<?php echo $tr_class ?>_'+record_index).remove();

            var record_index_found = false;
            for(var i=0; i<<?php echo $var_data; ?>.insert.length; i++){
                if(<?php echo $var_data; ?>.insert[i].record_index == record_index){
                    record_index_found = true;
                    // delete element from insert
                    <?php echo $var_data; ?>.insert.splice(i,1);
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<<?php echo $var_data; ?>.update.length; i++){
                    if(<?php echo $var_data; ?>.update[i].record_index == record_index){
                        record_index_found = true;
                        var primary_key = <?php echo $var_data; ?>.update[i].primary_key
                        // delete element from update
                        <?php echo $var_data; ?>.update.splice(i,1);
                        // add it to delete
                        <?php echo $var_data; ?>.delete.push({
                            'record_index':record_index,
                            'primary_key':primary_key
                        });
                        break;
                    }
                }
            }
            <?php echo $fn_synchronize; ?>();
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // <?php echo $column_input_class; ?>.change (Edit cell)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('.<?php echo $column_input_class; ?>').live('change', function(){
            var value = $(this).val();
            var column_name = $(this).attr('column_name');
            var record_index = $(this).attr('record_index');
            var record_index_found = false;
            // date picker
            if($(this).hasClass('datepicker-input')){
                value = js_date_to_php(value);
            }
            if(typeof(value)=='undefined'){
                value = '';
            }
            for(var i=0; i<<?php echo $var_data; ?>.insert.length; i++){
                if(<?php echo $var_data; ?>.insert[i].record_index == record_index){
                    record_index_found = true;
                    // insert value
                    eval('<?php echo $var_data; ?>.insert['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<<?php echo $var_data; ?>.update.length; i++){
                    if(<?php echo $var_data; ?>.update[i].record_index == record_index){
                        record_index_found = true;
                        // edit value
                        eval('<?php echo $var_data; ?>.update['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
                        break;
                    }
                }
            }
            <?php echo $fn_synchronize; ?>();
        });


    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // reset field on save
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(document).ajaxSuccess(function(event, xhr, settings) {        
        if (settings.url == "{{ module_site_url }}manage_<?php echo underscore($stripped_master_table_name) ?>/index/insert") {
            response = $.parseJSON(xhr.responseText);
            if(response.success == true){
                DATA_citizen = {update:new Array(), insert:new Array(), delete:new Array()};
                $('#md_table_<?php echo $master_column_name; ?> tr').not(':first').remove();
                <?php echo $fn_synchronize; ?>();
            }
    });


    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // General Functions
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    // synchronize data to <?php echo $real_input_id; ?>.
    function <?php echo $fn_synchronize; ?>(){
        $('#<?php echo $real_input_id; ?>').val(JSON.stringify(<?php echo $var_data; ?>));
    }

    function js_datetime_to_php(js_datetime){
        var datetime_array = js_datetime.split(' ');
        var js_date = datetime_array[0];
        var time = datetime_array[1];
        var php_date = js_date_to_php(js_date);
        return php_date + ' ' + time;
    }
    function php_datetime_to_js(php_datetime){
        var datetime_array = php_datetime.split(' ');
        var php_date = datetime_array[0];
        var time = datetime_array[1];
        var js_date = php_date_to_js(php_date);
        return js_date + ' ' + time;
    }

    function js_date_to_php(js_date){
        if(typeof(js_date)=='undefined' || js_date == ''){
            return '';
        }
        var date = '';
        var month = '';
        var year = '';
        var php_date = '';
        if(DATE_FORMAT == 'uk-date'){
            var date_array = js_date.split('/')
            day = date_array[0];
            month = date_array[1];
            year = date_array[2];
            php_date = year+'-'+month+'-'+day;
        }else if(DATE_FORMAT == 'us-date'){
            var date_array = js_date.split('/')
            day = date_array[1];
            month = date_array[0];
            year = date_array[2];
            php_date = year+'-'+month+'-'+day;
        }else if(DATE_FORMAT == 'sql-date'){
            var date_array = js_date.split('-')
            day = date_array[2];
            month = date_array[1];
            year = date_array[0];
            php_date = year+'-'+month+'-'+day;
        }
        return php_date;
    }


    function php_date_to_js(php_date){
        if(typeof(php_date)=='undefined' || php_date == ''){
            return '';
        }
        var date_array = php_date.split('-');
        var year = date_array[0];
        var month = date_array[1];
        var day = date_array[2];
        if(DATE_FORMAT == 'uk-date'){
            return day+'/'+month+'/'+year;
        }else if(DATE_FORMAT == 'us-date'){
            return month+'/'+date+'/'+year;
        }else if(DATE_FORMAT == 'sql-date'){
            return year+'-'+month+'-'+day;
        }else{
            return '';
        }
    }

    function IsNumeric(input){
        return (input - 0) == input && input.length > 0;
    }

    function mutate_input(){
        // datepikcer-input
        $('#<?php echo $table_id; ?> .datepicker-input').datepicker({
                dateFormat: js_date_format,
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true
        });
        // date-picker-input-clear
        $('#<?php echo $table_id; ?> .datepicker-input-clear').click(function(){
            $(this).parent().find('.datepicker-input').val('');
            return false;
        });
        // chzn-select
        $("#<?php echo $table_id; ?> .chzn-select").chosen({allow_single_deselect: true});
        // numeric
        $('#<?php echo $table_id; ?> .numeric').numeric();
        $('#<?php echo $table_id; ?> .numeric').keydown(function(e){
            if(e.keyCode == 38)
            {
                if(IsNumeric($(this).val()))
                {
                    var new_number = parseInt($(this).val()) + 1;
                    $(this).val(new_number);
                }else if($(this).val().length == 0)
                {
                    var new_number = 1;
                    $(this).val(new_number);
                }
            }
            else if(e.keyCode == 40)
            {
                if(IsNumeric($(this).val()))
                {
                    var new_number = parseInt($(this).val()) - 1;
                    $(this).val(new_number);
                }else if($(this).val().length == 0)
                {
                    var new_number = -1;
                    $(this).val(new_number);
                }
            }
            $(this).trigger('change');
        });

    }

</script>