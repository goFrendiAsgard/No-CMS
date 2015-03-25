<?php
    $record_index = 0;
?>


<style type="text/css">
    #md_table_translations input[type="text"]{
        /*max-width:100px;*/
    }
    #md_table_translations th:last-child, #md_table_translations td:last-child{
        width: 60px;
    }
</style>

<div id="md_table_translations_container">
    <div id="no-datamd_table_translations">No data</div>
    <table id="md_table_translations" class="table table-striped table-bordered" style="display:none">
        <thead>
            <tr>
                <th>Key</th>
                <th>Translation</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- the data presentation be here -->
        </tbody>
    </table>
    <div class="fbutton">
        <span id="md_field_translations_add" class="add btn btn-default">
            <i class="glyphicon glyphicon-plus-sign"></i> Add Translation </span>
    </div>
    <br />
    <!-- This is the real input. If you want to catch the data, please json_decode this input's value -->
    <input id="md_real_field_translations_col" name="md_real_field_translations_col" type="hidden" />
</div>





<script type="text/javascript">

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // DATA INITIALIZATION
    //
    // * Prepare some global variables
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    var DATE_FORMAT = '<?php echo $date_format ?>';
    var OPTIONS_translations = <?php echo json_encode($options); ?>;
    var RECORD_INDEX_translations = <?php echo $record_index; ?>;
    var DATA_translations = {update:new Array(), insert:new Array(), delete:new Array()};
    var old_data = <?php echo json_encode($result); ?>;
    for(var i=0; i<old_data.length; i++){
        var row = old_data[i];
        var record_index = i;
        var primary_key = row['detail_language_id'];
        var data = row;
        delete data['detail_language_id'];
        DATA_translations.update.push({
            'record_index' : record_index,
            'primary_key' : primary_key,
            'data' : data,
        });
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // ADD ROW FUNCTION
    //
    // * When "Add Main Detail Language" clicked, this function is called without parameter.
    // * When page loaded for the first time, this function is called with value parameter
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function add_table_row_translations(value){
        // hide no-data div
        $("#no-datamd_table_translations").hide();
        $("#md_table_translations").show();

        var component = '<tr id="md_field_translations_tr_'+RECORD_INDEX_translations+'" class="md_field_translations_tr">';

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "key"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        var field_value = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('key')){
          field_value = value.key;
        }
        component += '<td>';
        component += '<input id="md_field_translations_col_key_'+RECORD_INDEX_translations+'" record_index="'+RECORD_INDEX_translations+'" class="md_field_translations_col" column_name="key" type="text" value="'+field_value+'" style="width:90%;" />';
        component += '</td>';


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "translation"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        var field_value = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('translation')){
          field_value = value.translation;
        }
        component += '<td>';
        component += '<input id="md_field_translations_col_translation_'+RECORD_INDEX_translations+'" record_index="'+RECORD_INDEX_translations+'" class="md_field_translations_col" column_name="translation" type="text" value="'+field_value+'" style="width:90%;" />';
        component += '</td>';



        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // Delete Button
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        component += '<td><span class="delete-icon btn btn-default md_field_translations_delete" record_index="'+RECORD_INDEX_translations+'"><i class="glyphicon glyphicon-minus-sign"></i></span></td>';
        component += '</tr>';

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // Add component to table
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#md_table_translations tbody').append(component);
        mutate_input_translations();

    } // end of ADD ROW FUNCTION



    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Main event handling program
    //
    // * Initialization
    // * md_field_translations_add.click (Add row)
    // * md_field_translations_delete.click (Delete row)
    // * md_field_translations_col.change (Edit cell)
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(document).ready(function(){

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // INITIALIZATION
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        synchronize_translations();
        for(var i=0; i<DATA_translations.update.length; i++){
            add_table_row_translations(DATA_translations.update[i].data);
            RECORD_INDEX_translations++;
        }


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_translations_add.click (Add row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#md_field_translations_add').click(function(){
            // new data
            var data = new Object();

          data.key = '';
          data.translation = '';
            // insert data to the DATA_translations
            DATA_translations.insert.push({
                'record_index' : RECORD_INDEX_translations,
                'primary_key' : '',
                'data' : data,
            });

            // add table's row
            add_table_row_translations(data);
            // add  by 1
            RECORD_INDEX_translations++;

            // synchronize to the md_real_field_translations_col
            synchronize_translations();
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_translations_delete.click (Delete row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('.md_field_translations_delete').live('click', function(){
            var record_index = $(this).attr('record_index');
            // remove the component
            $('#md_field_translations_tr_'+record_index).remove();

            var record_index_found = false;
            for(var i=0; i<DATA_translations.insert.length; i++){
                if(DATA_translations.insert[i].record_index == record_index){
                    record_index_found = true;
                    // delete element from insert
                    DATA_translations.insert.splice(i,1);
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<DATA_translations.update.length; i++){
                    if(DATA_translations.update[i].record_index == record_index){
                        record_index_found = true;
                        var primary_key = DATA_translations.update[i].primary_key;
                        // delete element from update
                        DATA_translations.update.splice(i,1);
                        // add it to delete
                        DATA_translations.delete.push({
                            'record_index':record_index,
                            'primary_key':primary_key
                        });
                        break;
                    }
                }
            }
            synchronize_translations();
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_translations_col.change (Edit cell)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('.md_field_translations_col').live('change', function(){
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
            for(var i=0; i<DATA_translations.insert.length; i++){
                if(DATA_translations.insert[i].record_index == record_index){
                    record_index_found = true;
                    // insert value
                    eval('DATA_translations.insert['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<DATA_translations.update.length; i++){
                    if(DATA_translations.update[i].record_index == record_index){
                        record_index_found = true;
                        // edit value
                        eval('DATA_translations.update['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
                        break;
                    }
                }
            }
            synchronize_translations();
        });


    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // reset field on save
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url == "{{ module_site_url }}language_management/index/insert") {
            response = $.parseJSON(xhr.responseText);
            if(response.success == true){
                DATA_translations = {update:new Array(), insert:new Array(), delete:new Array()};
                $('#md_table_translations tr').not(':first').remove();
                synchronize_translations();
            }
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // synchronize data to md_real_field_translations_col.
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function synchronize_translations(){
        $('#md_real_field_translations_col').val(JSON.stringify(DATA_translations));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // synchronize table width (called on resize).
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function synchronize_translations_table_width(){
        var parent_width = $("#md_table_translations_container").parent().parent().width();
        $("#md_table_translations_container").width(parent_width);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // function to mutate input
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function mutate_input_translations(){
        // datepikcer-input
        $('#md_table_translations .datepicker-input').datepicker({
                dateFormat: js_date_format,
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
                yearRange: "c-100:c+100",
        });
        // date-picker-input-clear
        $('#md_table_translations .datepicker-input-clear').click(function(){
            $(this).parent().find('.datepicker-input').val('');
            return false;
        });
        // chzn-select
        $("#md_table_translations .chzn-select").chosen({allow_single_deselect: true});
        // numeric
        $('#md_table_translations .numeric').numeric();
        $('#md_table_translations .numeric').keydown(function(e){
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


    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // General Functions
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

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
            var date_array = js_date.split('/');
            day = date_array[0];
            month = date_array[1];
            year = date_array[2];
            php_date = year+'-'+month+'-'+day;
        }else if(DATE_FORMAT == 'us-date'){
            var date_array = js_date.split('/');
            day = date_array[1];
            month = date_array[0];
            year = date_array[2];
            php_date = year+'-'+month+'-'+day;
        }else if(DATE_FORMAT == 'sql-date'){
            var date_array = js_date.split('-');
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

</script>