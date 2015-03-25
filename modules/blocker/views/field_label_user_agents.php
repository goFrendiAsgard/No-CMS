<?php
    $record_index = 0;
?>


<style type="text/css">
    #md_table_user_agents input[type="text"]{
        width:80px;
    }
    #md_table_user_agents th:last-child, #md_table_user_agents td:last-child{
        width: 60px;
    }
</style>

<div id="md_table_user_agents_container">
    <table id="md_table_user_agents" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>User agent (regex)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- the data presentation be here -->
        </tbody>
    </table>
    <div class="fbutton">
        <span id="md_field_user_agents_add" class="add">Add Useragent</span>
    </div>
    <br />
    <!-- This is the real input. If you want to catch the data, please json_decode this input's value -->
    <input id="md_real_field_user_agents_col" name="md_real_field_user_agents_col" type="hidden" />
</div>





<script type="text/javascript">

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // DATA INITIALIZATION
    //
    // * Prepare some global variables
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    var DATE_FORMAT = '<?php echo $date_format ?>';
    var OPTIONS_user_agents = <?php echo json_encode($options); ?>;
    var RECORD_INDEX_user_agents = <?php echo $record_index; ?>;
    var DATA_user_agents = {update:new Array(), insert:new Array(), delete:new Array()};
    var old_data = <?php echo json_encode($result); ?>;
    for(var i=0; i<old_data.length; i++){
        var row = old_data[i];
        var record_index = i;
        var primary_key = row['id'];
        var data = row;
        delete data['id'];
        DATA_user_agents.update.push({
            'record_index' : record_index,
            'primary_key' : primary_key,
            'data' : data,
        });
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // ADD ROW FUNCTION
    //
    // * When "Add Useragent" clicked, this function is called without parameter.
    // * When page loaded for the first time, this function is called with value parameter
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function add_table_row_user_agents(value){

        var component = '<tr id="md_field_user_agents_tr_'+RECORD_INDEX_user_agents+'" class="md_field_user_agents_tr">';
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "content"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        var field_value = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('content')){
          field_value = value.content;
        }
        component += '<td>';
        component += '<input type="text" id="md_field_user_agents_col_content_'+RECORD_INDEX_user_agents+'" record_index="'+RECORD_INDEX_user_agents+'" class="md_field_user_agents_col full-width" column_name="content" value="'+field_value+'" />';
        component += '</td>';



        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // Delete Button
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        component += '<td><span class="delete-icon md_field_user_agents_delete" record_index="'+RECORD_INDEX_user_agents+'"></span></td>';
        component += '</tr>';

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // Add component to table
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#md_table_user_agents tbody').append(component);
        mutate_input_user_agents();

    } // end of ADD ROW FUNCTION



    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Main event handling program
    //
    // * Initialization
    // * md_field_user_agents_add.click (Add row)
    // * md_field_user_agents_delete.click (Delete row)
    // * md_field_user_agents_col.change (Edit cell)
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(document).ready(function(){

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // INITIALIZATION
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        synchronize_user_agents_table_width();
        synchronize_user_agents();
        for(var i=0; i<DATA_user_agents.update.length; i++){
            add_table_row_user_agents(DATA_user_agents.update[i].data);
            RECORD_INDEX_user_agents++;
        }

        // on resize, adjust the table width
        $(window).resize(function() {
            synchronize_user_agents_table_width();
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_user_agents_add.click (Add row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#md_field_user_agents_add').click(function(){
            // new data
            var data = new Object();
            
          data.content = '';
            // insert data to the DATA_user_agents
            DATA_user_agents.insert.push({
                'record_index' : RECORD_INDEX_user_agents,
                'primary_key' : '',
                'data' : data,
            });

            // add table's row
            add_table_row_user_agents(data);
            // add  by 1
            RECORD_INDEX_user_agents++;

            // synchronize to the md_real_field_user_agents_col
            synchronize_user_agents();
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_user_agents_delete.click (Delete row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('.md_field_user_agents_delete').live('click', function(){
            var record_index = $(this).attr('record_index');
            // remove the component
            $('#md_field_user_agents_tr_'+record_index).remove();

            var record_index_found = false;
            for(var i=0; i<DATA_user_agents.insert.length; i++){
                if(DATA_user_agents.insert[i].record_index == record_index){
                    record_index_found = true;
                    // delete element from insert
                    DATA_user_agents.insert.splice(i,1);
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<DATA_user_agents.update.length; i++){
                    if(DATA_user_agents.update[i].record_index == record_index){
                        record_index_found = true;
                        var primary_key = DATA_user_agents.update[i].primary_key;
                        // delete element from update
                        DATA_user_agents.update.splice(i,1);
                        // add it to delete
                        DATA_user_agents.delete.push({
                            'record_index':record_index,
                            'primary_key':primary_key
                        });
                        break;
                    }
                }
            }
            synchronize_user_agents();
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_user_agents_col.change (Edit cell)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('.md_field_user_agents_col').live('change', function(){
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
            for(var i=0; i<DATA_user_agents.insert.length; i++){
                if(DATA_user_agents.insert[i].record_index == record_index){
                    record_index_found = true;
                    // insert value
                    eval('DATA_user_agents.insert['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<DATA_user_agents.update.length; i++){
                    if(DATA_user_agents.update[i].record_index == record_index){
                        record_index_found = true;
                        // edit value
                        eval('DATA_user_agents.update['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
                        break;
                    }
                }
            }
            synchronize_user_agents();
        });


    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // reset field on save
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(document).ajaxSuccess(function(event, xhr, settings) {        
        if (settings.url == "{{ module_site_url }}manage_label/index/insert") {
            response = $.parseJSON(xhr.responseText);
            if(response.success == true){
                DATA_user_agents = {update:new Array(), insert:new Array(), delete:new Array()};
                $('#md_table_user_agents tr').not(':first').remove();
                synchronize_user_agents();
            }
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // synchronize data to md_real_field_user_agents_col.
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function synchronize_user_agents(){
        $('#md_real_field_user_agents_col').val(JSON.stringify(DATA_user_agents));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // synchronize table width (called on resize).
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function synchronize_user_agents_table_width(){
        var parent_width = $("#md_table_user_agents_container").parent().parent().width();
        $("#md_table_user_agents_container").width(parent_width);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // function to mutate input
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function mutate_input_user_agents(){
        // datepikcer-input
        $('#md_table_user_agents .datepicker-input').datepicker({
                dateFormat: js_date_format,
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true
        });
        // date-picker-input-clear
        $('#md_table_user_agents .datepicker-input-clear').click(function(){
            $(this).parent().find('.datepicker-input').val('');
            return false;
        });
        // chzn-select
        $("#md_table_user_agents .chzn-select").chosen({allow_single_deselect: true});
        // numeric
        $('#md_table_user_agents .numeric').numeric();
        $('#md_table_user_agents .numeric').keydown(function(e){
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