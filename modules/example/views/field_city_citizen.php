<?php
    $record_index = 0;
?>
<style type="text/css">
    #md_table_citizen .chzn-drop input[type="text"]{
        max-width:240px;
    }
    #md_table_citizen th:last-child, #md_table_citizen td:last-child{
        width: 60px;
    }
</style>

<div id="md_table_citizen_container">
    <div id="no-datamd_table_citizen">No data</div>
    <table id="md_table_citizen" class="table table-striped table-bordered" style="display:none">
        <thead>
            <tr>
                <th>Name</th>
                <th>Birthdate</th>
                <th>Job</th>
                <th>Hobby</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- the data presentation be here -->
        </tbody>
    </table>
    <div class="fbutton">
        <span id="md_field_citizen_add" class="add btn btn-default">
            <i class="glyphicon glyphicon-plus-sign"></i> Add Citizen        </span>
    </div>
    <br />
    <!-- This is the real input. If you want to catch the data, please json_decode this input's value -->
    <input id="md_real_field_citizen_col" name="md_real_field_citizen_col" type="hidden" />
</div>

<script type="text/javascript">

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // DATA INITIALIZATION
    //
    // * Prepare some global variables
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    var DATE_FORMAT = '<?php echo $date_format ?>';
    var OPTIONS_citizen = <?php echo json_encode($options); ?>;
    var RECORD_INDEX_citizen = <?php echo $record_index; ?>;
    var DATA_citizen = {update:new Array(), insert:new Array(), delete:new Array()};
    var old_data = <?php echo json_encode($result); ?>;
    for(var i=0; i<old_data.length; i++){
        var row = old_data[i];
        var record_index = i;
        var primary_key = row['citizen_id'];
        var data = row;
        delete data['citizen_id'];
        DATA_citizen.update.push({
            'record_index' : record_index,
            'primary_key' : primary_key,
            'data' : data,
        });
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // ADD ROW FUNCTION
    //
    // * When "Add Citizen" clicked, this function is called without parameter.
    // * When page loaded for the first time, this function is called with value parameter
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    function add_table_row_citizen(value){
        // hide no-data div
        $("#no-datamd_table_citizen").hide();
        $("#md_table_citizen").show();

        var component = '<tr id="md_field_citizen_tr_'+RECORD_INDEX_citizen+'" class="md_field_citizen_tr">';
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "name"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        var field_value = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('name')){
          field_value = value.name;
        }
        component += '<td>';
        component += '<input id="md_field_citizen_col_name_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col" column_name="name" type="text" value="'+field_value+'"/>';
        component += '</td>';


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "birthdate"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        var field_value = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('birthdate')){
          field_value = php_date_to_js(value.birthdate);
        }
        component += '<td>';
        component += '<input id="md_field_citizen_col_birthdate_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col datepicker-input" column_name="birthdate" type="text" value="'+field_value+'"/>';
        component += '<a href="#" class="datepicker-input-clear btn">Clear</a>';
        component += '</td>';


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "job_id"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        var field_value = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('job_id')){
          field_value = value.job_id;
        }
        component += '<td>';
        component += '<select id="md_field_citizen_col_job_id_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col numeric chzn-select" column_name="job_id" >';
        var options = OPTIONS_citizen.job_id;
        component += '<option value></option>';
        for(var i=0; i<options.length; i++){
          var option = options[i];
          var selected = '';
          if(option['value'] == field_value){
              selected = 'selected="selected"';
          }
          component += '<option value="'+option['value']+'" '+selected+'>'+option['caption']+'</option>';
        }
        component += '</select>';
        component += '</td>';


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "hobby"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        var field_value = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('hobby')){
          field_value = value.hobby;
        }
        component += '<td>';
        component += '<select id="md_field_citizen_col_hobby_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col chzn-select" column_name="hobby"  multiple = "multiple">';
        var options = OPTIONS_citizen.hobby;
        component += '<option value></option>';
        for(var i=0; i<options.length; i++){
          var option = options[i];
          var selected = '';
          if($.inArray(option['value'],field_value)>-1){
              selected = 'selected="selected"';
          }
          component += '<option value="'+option['value']+'" '+selected+'>'+option['caption']+'</option>';
        }
        component += '</select>';
        component += '</td>';



        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // Delete Button
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        component += '<td><span class="delete-icon btn btn-default md_field_citizen_delete" record_index="'+RECORD_INDEX_citizen+'"><i class="glyphicon glyphicon-minus-sign"></i></span></td>';
        component += '</tr>';

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // Add component to table
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#md_table_citizen tbody').append(component);
        __mutate_input('md_table_citizen');

    } // end of ADD ROW FUNCTION



    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Main event handling program
    //
    // * Initialization
    // * md_field_citizen_add.click (Add row)
    // * md_field_citizen_delete.click (Delete row)
    // * md_field_citizen_col.change (Edit cell)
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(document).ready(function(){

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // INITIALIZATION
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        __synchronize('md_real_field_citizen_col', DATA_citizen);
        for(var i=0; i<DATA_citizen.update.length; i++){
            add_table_row_citizen(DATA_citizen.update[i].data);
            RECORD_INDEX_citizen++;
        }


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_citizen_add.click (Add row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#md_field_citizen_add').click(function(){
            // new data
            var data = new Object();
            
            data.name = '';
            data.birthdate = '';
            data.job_id = '';
            data.hobby = '';
            // insert data to the DATA_citizen
            DATA_citizen.insert.push({
                'record_index' : RECORD_INDEX_citizen,
                'primary_key' : '',
                'data' : data,
            });

            // add table's row
            add_table_row_citizen(data);
            // add  by 1
            RECORD_INDEX_citizen++;

            // synchronize to the md_real_field_citizen_col
            __synchronize('md_real_field_citizen_col', DATA_citizen);
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_citizen_delete.click (Delete row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('.md_field_citizen_delete').live('click', function(){
            var record_index = $(this).attr('record_index');
            // remove the component
            $('#md_field_citizen_tr_'+record_index).remove();

            var record_index_found = false;
            for(var i=0; i<DATA_citizen.insert.length; i++){
                if(DATA_citizen.insert[i].record_index == record_index){
                    record_index_found = true;
                    // delete element from insert
                    DATA_citizen.insert.splice(i,1);
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<DATA_citizen.update.length; i++){
                    if(DATA_citizen.update[i].record_index == record_index){
                        record_index_found = true;
                        var primary_key = DATA_citizen.update[i].primary_key;
                        // delete element from update
                        DATA_citizen.update.splice(i,1);
                        // add it to delete
                        DATA_citizen.delete.push({
                            'record_index':record_index,
                            'primary_key':primary_key
                        });
                        break;
                    }
                }
            }
            __synchronize('md_real_field_citizen_col', DATA_citizen);
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_citizen_col.change (Edit cell)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('.md_field_citizen_col').live('change', function(){
            var value = $(this).val();
            var column_name = $(this).attr('column_name');
            var record_index = $(this).attr('record_index');
            var record_index_found = false;
            // date picker
            if($(this).hasClass('datepicker-input')){
                value = js_date_to_php(value);
            }
            else if($(this).hasClass('datetime-input')){
                value = js_datetime_to_php(value);
            }
            if(typeof(value)=='undefined'){
                value = '';
            }
            for(var i=0; i<DATA_citizen.insert.length; i++){
                if(DATA_citizen.insert[i].record_index == record_index){
                    record_index_found = true;
                    // insert value
                    eval('DATA_citizen.insert['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<DATA_citizen.update.length; i++){
                    if(DATA_citizen.update[i].record_index == record_index){
                        record_index_found = true;
                        // edit value
                        eval('DATA_citizen.update['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
                        break;
                    }
                }
            }
            __synchronize('md_real_field_citizen_col', DATA_citizen);
        });


    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // reset field on save
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url == "{{ module_site_url }}manage_city/index/insert") {
            response = $.parseJSON(xhr.responseText);
            if(response.success == true){
                DATA_citizen = {update:new Array(), insert:new Array(), delete:new Array()};
                $('#md_table_citizen tr').not(':first').remove();
                __synchronize('md_real_field_citizen_col', DATA_citizen);
            }
        }else{
            // avoid detail inserted twice on update
            update_url = "{{ module_site_url }}manage_city/index/update";
            if(settings.url.substr(0, update_url.length) == update_url){
                response = $.parseJSON(xhr.responseText);
                if(response.success == true){
                    $('#form-button-save').attr('disabled', 'disabled');
                    $('#save-and-go-back-button').attr('disabled', 'disabled');
                    $('#cancel-button').attr('disabled', 'disabled');
                }
            }
        }
    });

</script>
