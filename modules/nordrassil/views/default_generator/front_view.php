<?php
    $fields = array();
    $captions = array();
    $primary_key = '';
    foreach($columns as $column){
        $column_name = $column['name'];
        $column_role = $column['role'];
        $column_caption = $column['caption'];
        if($column_role == 'primary'){
            $primary_key = $column_name;
        }else if($column_role == ''){
            $fields[] = $column_name;
            $captions[] = $column_caption;
        }else if($column_role == 'lookup'){
            $lookup_table_name = $column['lookup_table_name'];
            $lookup_column_name = $column['lookup_column_name'];
            $fields[] = $lookup_table_name.'_'.$lookup_column_name;
            $captions[] = $column_caption;
        }
    }
?>
&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?&gt;
<style type="text/css">
    #record_content{
        margin-top: 5px;
        margin-bottom: 10px;
    }
    .record_container{
        margin:10px;
    }
    .edit_delete_record_container{
        margin-top: 10px;
    }
</style>
<div class="form form-inline">
    <div class="form-group">
        <input type="text" name="search" value="" id="input_search" class="input-medium search-query form-control" placeholder="keyword" />    
    </div>
    <input type="submit" name="submit" value="Search" id="btn_search" class="btn btn-primary" />
    &lt;?php
        if($allow_navigate_backend){
            echo '<a href="'.$backend_url.'/add/" class="btn btn-default add_record">Add</a>'.PHP_EOL;
        }
    ?&gt;
</div>
<div id="record_content"></div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>
<script type="text/javascript">
    var PAGE = 0;
    var URL = '&lt;?php echo site_url($module_path."/{{ front_controller_import_name }}/get_data"); ?&gt;';
    var ALLOW_NAVIGATE_BACKEND = &lt;?php echo $allow_navigate_backend ? "true" : "false"; ?&gt;;
    var BACKEND_URL = '&lt;?php echo $backend_url; ?&gt;';
    var LOADING = false;
    var REQUEST;
    var RUNNING_REQUEST = false;
    var STOP_REQUEST = false;

    function fetch_more_data(async){
        if(typeof(async) == 'undefined'){
            async = true;
        }
        $('#content-bottom').html('Load more {{ table_caption }} ...');
        var keyword = $('#input_search').val();
        // kill all previous AJAX
        if(RUNNING_REQUEST){
            REQUEST.abort();
        }
        RUNNING_REQUEST = true;
        REQUEST = $.ajax({
            'url'  : URL,
            'type' : 'POST',
            'async': async,
            'data' : {
                'keyword' : keyword,
                'page' : PAGE,
            },
            'success'  : function(response){
                // show contents
                $('#record_content').append(response);
                // stop request if response is empty
                if(response == ''){
                    STOP_REQUEST = true;
                }

                // show bottom contents
                var bottom_content = 'No more {{ table_caption }} to show.';
                if(ALLOW_NAVIGATE_BACKEND){
                    bottom_content += '&nbsp; <a href="&lt;?php echo $backend_url; ?&gt;/add/" class="add_record">Add new</a>';
                }
                $('#record_content_bottom').html(bottom_content);
                RUNNING_REQUEST = false;
                PAGE ++;
            }
        });

    }

    function reset_content(){
        $('#record_content').html('');
        PAGE = 0;
        fetch_more_data();
    }

    // main program
    $(document).ready(function(){
        fetch_more_data();

        // delete click
        $('.delete_record').live('click',function(){
            var url = $(this).attr('href');
            var primary_key = $(this).attr('primary_key');
            if (confirm("Do you really want to delete?")) {
                $.ajax({
                    url : url,
                    dataType : 'json',
                    success : function(response){
                        if(response.success){
                            $('div#record_'+primary_key).remove();
                        }
                    }
                });
            }
            return false;
        });

        // input keyup
        $('#input_search').keyup(function(){
            reset_content();
        });

        // button search click
        $('#btn_search').click(function(){
            reset_content();
        });

        // scroll
        $(window).scroll(function(){
            if(!STOP_REQUEST && !LOADING){
                if($('#record_content_bottom').position().top <= $(window).scrollTop() + $(window).height() ){
                    LOADING = true;
                    fetch_more_data(false);
                    LOADING = false;
                }
            }
        });

    });

</script>