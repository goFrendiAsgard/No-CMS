<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
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
        <input type="text" name="search" value="" id="input_search" class="input-medium search-query form-control" placeholder="keyword" />&nbsp;
    </div>
    <button name="submit" id="btn_search" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Search</button>&nbsp;
    <?php
        if($allow_navigate_backend && $have_add_privilege){
            echo '<a href="'.$backend_url.'/add/" class="btn btn-default add_record"><i class="glyphicon glyphicon-plus"></i> Add</a>'.PHP_EOL;
        }
        if($have_edit_template_privilege){
            echo '<a href="{{ module_site_url }}browse_portfolio/template_config" class="btn btn-default"><i class="glyphicon glyphicon-cog"></i> Edit Record Template</a>'.PHP_EOL;
        }
    ?>
</div>
<div id="record_content"><?php echo $first_data ?></div>
<div class="row" style="padding-bottom:20px">
    <a id="btn_load_more" class="btn btn-default col-xs-12" style="display:none;">{{ language:Load More }}</a>
</div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>
<script type="text/javascript">
    var PAGE                   = 1;
    var URL                    = '<?php echo site_url($module_path."/browse_portfolio/get_data"); ?>';
    var ALLOW_NAVIGATE_BACKEND = <?php echo $allow_navigate_backend ? "true" : "false"; ?>;
    var HAVE_ADD_PRIVILEGE     = <?php echo $have_add_privilege ? "true" : "false"; ?>;
    var BACKEND_URL            = '<?php echo $backend_url; ?>';
    var LOADING                = false;
    var RUNNING_REQUEST        = false;
    var STOP_REQUEST           = false;
    var REQUEST;


    function adjust_load_more_button(){
        if(screen.width >= 1024){
            $('#btn_load_more').hide();
            $('#record_content_bottom').show();
        }else{
            $('#btn_load_more').show();
            $('#record_content_bottom').hide();
        }
    }

    function fetch_more_data(async){
        if(typeof(async) == 'undefined'){
            async = true;
        }
        $('#record_content_bottom').html('Load more Portfolio &nbsp;<img src="{{ BASE_URL }}assets/nocms/images/ajax-loader.gif" />');
        var keyword = $('#input_search').val();
        // Don't send another request before the first one completed
        if(RUNNING_REQUEST){
            return 0;
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
                if(response.trim() == ''){
                    STOP_REQUEST = true;
                }

                // show bottom contents
                var bottom_content = 'No more Portfolio to show.';
                if(ALLOW_NAVIGATE_BACKEND && HAVE_ADD_PRIVILEGE){
                    bottom_content += '&nbsp; <a href="<?php echo $backend_url; ?>/add/" class="add_record">Add new</a>';
                }
                $('#record_content_bottom').html(bottom_content);
                RUNNING_REQUEST = false;
                PAGE ++;
            },
            'complete' : function(response){
                RUNNING_REQUEST = false;
            }
        });

    }

    function reset_content(){
        $('#record_content').html('');
        PAGE = 0;
        fetch_more_data();
        adjust_load_more_button();
    }

    // main program
    $(document).ready(function(){
        fetch_more_data();
        adjust_load_more_button();

        // delete click
        $('body').on('click', '.delete_record',function(){
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
            if(screen.width >= 1024 && !STOP_REQUEST && !LOADING){
                if($('#record_content_bottom').position().top <= $(window).scrollTop() + $(window).height() ){
                    LOADING = true;
                    fetch_more_data(false);
                    LOADING = false;
                }
            }
        });

        // load more click
        $('#btn_load_more').click(function(event){
            if(!LOADING){
                LOADING = true;
                fetch_more_data(true);
                LOADING = false;
            }
            $(this).hide();
            event.preventDefault();
        });

    });

</script>
