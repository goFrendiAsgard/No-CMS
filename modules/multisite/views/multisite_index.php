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
        <input type="text" name="search" value="" id="input_search" class="input-medium search-query form-control" placeholder="keyword" />    
    </div>
    <input type="submit" name="submit" value="Search" id="btn_search" class="btn btn-primary" />
    <?php
        if($allow_navigate_backend){
            echo '<a href="'.$backend_url.'/" class="btn btn-default add_record">Add</a>'.PHP_EOL;
        }
    ?>
</div>
<div id="record_content" class="row"></div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>
<script type="text/javascript">
    var PAGE = 0;
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
    function adjust_thumbnail(){
        __adjust_component(".thumbnail img");
        __adjust_component(".thumbnail div.caption");
        __adjust_component(".record_container_thumbnail")
    }

    function fetch_more_data(async){
        if(typeof(async) == 'undefined'){
            async = true;
        }
        $('#content-bottom').html('Load more Subsite ...');
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
                adjust_thumbnail();
                if(response == ''){
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
            if(!LOADING & !STOP_REQUEST){
                if($(window).scrollTop() == $(document).height() - $(window).height()){
                    LOADING = true;
                    fetch_more_data(false);
                    LOADING = false;
                }
            }
        });

        // resize
        $(window).resize(function(){
            adjust_thumbnail();
        });        

    });
</script>