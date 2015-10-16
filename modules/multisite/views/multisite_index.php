<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #record_content{
        margin-top: 5px;
        margin-bottom: 10px;
    }
    .record_container{
        margin:10px;
        overflow-x:hidden;
    }
    .edit_delete_record_container{
        margin-top: 10px;
    }
</style>
<div class="form form-inline">
    <div class="form-group">
        <input type="text" name="search" value="" id="input_search" class="input-medium search-query form-control" placeholder="keyword" />
    </div>&nbsp;
    <input type="submit" name="submit" value="Search" id="btn_search" class="btn btn-primary" />&nbsp;
    <?php
        if($allow_navigate_backend){
            echo '<a href="'.$backend_url.'/" class="btn btn-default add_record">Add</a>'.PHP_EOL;
        }
    ?>
</div>
<div id="record_content" class="row"><?php echo $first_data; ?></div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>
<script type="text/javascript">
    var PAGE = 1;
    var URL = '<?php echo site_url($module_path."/multisite/get_data"); ?>';
    var ALLOW_NAVIGATE_BACKEND = <?php echo $allow_navigate_backend ? "true" : "false"; ?>;
    var BACKEND_URL = '<?php echo $backend_url; ?>';
    var LOADING = false;
    var REQUEST;
    var RUNNING_REQUEST = false;
    var STOP_REQUEST = false;

    function __adjust_component(identifier){
        var max_height = 0;
        $(identifier).each(function(){
            $(this).css("margin-bottom", 0);
            if($(this).height()>max_height){
                max_height = $(this).height();
            }
        });
        $(identifier).each(function(){
            var margin_bottom = 0;
            if($(this).height()<max_height){
                margin_bottom = max_height - $(this).height();
            }
            margin_bottom += 10;
            $(this).css("margin-bottom", margin_bottom);
        });
    }
    function _adjust_thumbnail(){
        __adjust_component(".record_container img");
        __adjust_component(".record_container div.caption h3");
        __adjust_component(".record_container div.caption .description");
        __adjust_component(".record_container div.caption");
        __adjust_component(".record_container");
    }

    function fetch_more_data(async){
        if(typeof(async) == 'undefined'){
            async = true;
        }
        $('#content_bottom').html('Load more sites &nbsp;<img src="{{ BASE_URL }}assets/nocms/images/ajax-loader.gif" />');

        var keyword = $('#input_search').val();
        // don't overflow network with request
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
                // change loading status
                if(response.trim() == ''){
                    LOADING = true;
                }
                // show contents
                $('#record_content').append(response);
                _adjust_thumbnail();
                if(response.trim() == ''){
                    STOP_REQUEST = true;
                }
                // show bottom contents
                var bottom_content = 'No more Subsite to show.';
                if(ALLOW_NAVIGATE_BACKEND){
                    bottom_content += '&nbsp; <a href="<?php echo $backend_url; ?>" class="add_record">Add new</a>';
                }
                $('#record_content_bottom').html(bottom_content);
                RUNNING_REQUEST = false;
                PAGE ++;
            },
            'complete' : function(response){
                RUNNING_REQUEST = false;
            }
        });
        _adjust_thumbnail();

    }

    function reset_content(){
        $('#record_content').html('');
        LOADING = false;
        PAGE = 0;
        fetch_more_data();
    }

    // main program
    $(document).ready(function(){
        fetch_more_data();

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
            if(!LOADING && !STOP_REQUEST){
                if($('#record_content_bottom').position().top <= $(window).scrollTop() + $(window).height() ){
                    LOADING = true;
                    fetch_more_data(true);
                    LOADING = false;
                }
            }
        });

        // resize
        $(window).resize(function(){
            _adjust_thumbnail();
        });

    });
    $('.record_container').load(function(){
        _adjust_thumbnail();
    });
</script>
