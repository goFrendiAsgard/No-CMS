<?php
    $record_index = 0;
?>
<style type="text/css">
    .sub-caption{
        min-width : 80px;
        font-weight : bold;
        display : inline-block;
    }
</style>

<table id="md_table_comments" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width:450px;">Comment Summary</th>
        </tr>
    </thead>
    <tbody>
        <!-- the data presentation be here -->
    </tbody>
</table>
<br />
<!-- This is the real input. If you want to catch the data, please json_decode this input's value -->
<input id="md_real_field_comments_col" name="md_real_field_comments_col" type="hidden" />

<script type="text/javascript">

    /////////////////////////////////////////////////////////////////////////
    // DATA INITIALIZATION
    //
    // * Prepare some global variables
    //
    /////////////////////////////////////////////////////////////////////////
    var DATE_FORMAT = '<?php echo $date_format ?>';
    var OPTIONS = <?php echo json_encode($options); ?>;
    var RECORD_INDEX_comments = <?php echo $record_index; ?>;
    var DATA_comments = {update:new Array(), insert:new Array(), delete:new Array()};
    var old_data = <?php echo json_encode($result); ?>;
    for(var i=0; i<old_data.length; i++){
        var row = old_data[i];
        var record_index = i;
        var primary_key = row['comment_id'];
        var data = row;
        delete data['comment_id'];
        DATA_comments.update.push({
            'record_index' : record_index,
            'primary_key' : primary_key,
            'data' : data,
        });
    }


    /////////////////////////////////////////////////////////////////////////
    // ADD ROW FUNCTION
    //
    // * When "Add Comment" clicked, this function is called without parameter.
    // * When page loaded for the first time, this function is called with value parameter
    //
    /////////////////////////////////////////////////////////////////////////
    function add_table_row_comments(value){

        var component = '<tr id="md_field_comments_tr_'+RECORD_INDEX_comments+'" class="md_field_comments_tr">';

        /////////////////////////////////////////////////////////////////////////////
        //    FIELD "date"
        /////////////////////////////////////////////////////////////////////////////
        var comment_date = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('date')){
            comment_date = value.date;
        }
        /////////////////////////////////////////////////////////////////////////////
        //    FIELD "name"
        /////////////////////////////////////////////////////////////////////////////
        var comment_name = 'anonymous';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('name')){
            comment_name = value.name;
        }

        /////////////////////////////////////////////////////////////////////////////
        //    FIELD "email"
        /////////////////////////////////////////////////////////////////////////////
        var comment_email = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('email')){
            comment_email = value.email;
        }


        /////////////////////////////////////////////////////////////////////////////
        //    FIELD "website"
        /////////////////////////////////////////////////////////////////////////////
        var comment_website = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('website')){
            comment_website = value.website;
        }


        /////////////////////////////////////////////////////////////////////////////
        //    FIELD "content"
        /////////////////////////////////////////////////////////////////////////////
        var comment_content = '';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('content')){
            comment_content = value.content;
        }

        var comment_approved = 0;
        var caption          = 'Approve';
        var status           = 'Waiting for Approval';
        if(typeof(value) != 'undefined' && value.hasOwnProperty('approved')){
            comment_approved = value.approved;
            if(comment_approved == 1){
                caption     = 'Cancel Approval';
                status      = 'Approved';
            }
        }

        component += '<td>';
        component += '<div style="margin-bottom:10px;">';
        component += '<div class="sub-caption">author</div> : '+comment_name+', '+comment_date;
        component += '<br />';
        component += '<div class="sub-caption">email</div> : '+(comment_email==''?
          'Not available': '<a href="mailto:'+comment_email+'">'+comment_email+'</a>');
        component += '<br />';
        component += '<div class="sub-caption">website</div> : '+(comment_website==''?
          'Not available': '<a href="'+comment_website+'" target="BLANK">'+comment_website+'</a>');
        component += '<br />';
        component += '<div class="sub-caption">approved</div> : <span id="comment_approved_'+RECORD_INDEX_comments+'">'+status;
        component += '</div>';
        component += '<div">';
        component += comment_content;
        component += '</div>';

        /////////////////////////////////////////////////////////////////////////////
        // Delete Button
        /////////////////////////////////////////////////////////////////////////////

        component += '<div style="clear:both;"></div>';
        component += '<div class="pull-right">';
        component += '<a class="btn btn-default toggle_comment_approve" record_index="'+RECORD_INDEX_comments+'" href="#">' + caption + '</a>&nbsp;';
        component += '<input class="md_field_comments_delete btn btn-default" record_index="'+RECORD_INDEX_comments+'" primary_key="" type="button" value="Delete" />';
        component += '</div>';
        component += '<div style="clear:both;"></div>';
        
        component += '</td>';
        component += '</tr>';

        /////////////////////////////////////////////////////////////////////////////
        // Add component to table
        /////////////////////////////////////////////////////////////////////////////
        $('#md_table_comments tbody').append(component);
        mutate_input();

    } // end of ADD ROW FUNCTION



    /////////////////////////////////////////////////////////////////////////
    // Main event handling program
    //
    // * Initialization
    // * md_field_comments_add.click (Add row)
    // * md_field_comments_delete.click (Delete row)
    // * md_field_comments_col.change (Edit cell)
    //
    /////////////////////////////////////////////////////////////////////////
    $(document).ready(function(){

        /////////////////////////////////////////////////////////////////////////////
        // INITIALIZATION
        /////////////////////////////////////////////////////////////////////////////
        synchronize_comments();
        for(var i=0; i<DATA_comments.update.length; i++){
            add_table_row_comments(DATA_comments.update[i].data);
            RECORD_INDEX_comments++;
        }


        /////////////////////////////////////////////////////////////////////////////
        // md_field_comments_delete.click (Delete row)
        /////////////////////////////////////////////////////////////////////////////
        $('body').on('click', '.md_field_comments_delete', function(){
            var record_index = $(this).attr('record_index');
            // remove the component
            $('#md_field_comments_tr_'+record_index).remove();

            var record_index_found = false;
            for(var i=0; i<DATA_comments.insert.length; i++){
                if(DATA_comments.insert[i].record_index == record_index){
                    record_index_found = true;
                    // delete element from insert
                    DATA_comments.insert.splice(i,1);
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<DATA_comments.update.length; i++){
                    if(DATA_comments.update[i].record_index == record_index){
                        record_index_found = true;
                        var primary_key = DATA_comments.update[i].primary_key
                        // delete element from update
                        DATA_comments.update.splice(i,1);
                        // add it to delete
                        DATA_comments.delete.push({
                            'record_index':record_index,
                            'primary_key':primary_key
                        });
                        break;
                    }
                }
            }
            synchronize_comments();
        });

        $('.toggle_comment_approve').click(function(event){
            event.preventDefault();
            var record_index = $(this).attr('record_index');
            var record_index_found = false;
            var new_val = 0;
            for(var i=0; i<DATA_comments.insert.length; i++){
                if(DATA_comments.insert[i].record_index == record_index){
                    record_index_found = true;
                    // insert value
                    new_val = DATA_comments.insert[i].data.approved == 1 ? 0 : 1;
                    DATA_comments.insert[i].data.approved = JSON.stringify(new_val);
                    break;
                }
            }
            if(!record_index_found){
                for(var i=0; i<DATA_comments.update.length; i++){
                    if(DATA_comments.update[i].record_index == record_index){
                        record_index_found = true;
                        // edit value
                        new_val = DATA_comments.update[i].data.approved == 1 ? 0 : 1;
                        DATA_comments.update[i].data.approved = JSON.stringify(new_val);
                        break;
                    }
                }
            }
            if(record_index_found){
                if(new_val == 0){
                    $(this).html('Approve');
                    $('#comment_approved_'+record_index).html('Waiting for Approval');
                }else{
                    $(this).html('Cancel Approval');
                    $('#comment_approved_'+record_index).html('Approved');
                }
            }
            synchronize_comments();
        });


    });

    /////////////////////////////////////////////////////////////////////////
    // reset field on save
    /////////////////////////////////////////////////////////////////////////
    $(document).ajaxSuccess(function(event, xhr, settings) {
        if (settings.url == "{{ module_site_url }}manage_article/index/insert") {
            response = $.parseJSON(xhr.responseText);
            if(response.success == true){
                DATA_comments = {update:new Array(), insert:new Array(), delete:new Array()};
                $('#md_table_comments tr').not(':first').remove();
                synchronize_comments();
            }
        }
    });




    /////////////////////////////////////////////////////////////////////////
    // General Functions
    /////////////////////////////////////////////////////////////////////////

    // synchronize data to md_real_field_comments_col.
    function synchronize_comments(){
        $('#md_real_field_comments_col').val(JSON.stringify(DATA_comments));
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

</script>
