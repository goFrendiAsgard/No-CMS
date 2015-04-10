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
            echo '<a href="'.$backend_url.'/add/" class="btn btn-default add_record">Add</a>'.PHP_EOL;
        }
    ?>
</div>
<div id="record_content">
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Notice</p>
<p>Message:  Undefined variable: first_data</p>
<p>Filename: default_generator/front_view.php</p>
<p>Line Number: 46</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/gofrendi/public_html/No-CMS/modules/nordrassil/views/default_generator/front_view.php<br />
			Line: 46<br />
			Function: _error_handler			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/gofrendi/public_html/No-CMS/application/third_party/MX/Loader.php<br />
			Line: 351<br />
			Function: include			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/gofrendi/public_html/No-CMS/application/third_party/MX/Loader.php<br />
			Line: 294<br />
			Function: _ci_load			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/gofrendi/public_html/No-CMS/modules/nordrassil/libraries/NordrassilLib.php<br />
			Line: 90<br />
			Function: view			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/gofrendi/public_html/No-CMS/modules/nordrassil/controllers/default_generator/Default_generator.php<br />
			Line: 276<br />
			Function: read_view			</p>

		
	
		
			<p style="margin-left:10px">
			File: /home/gofrendi/public_html/No-CMS/modules/nordrassil/controllers/default_generator/Default_generator.php<br />
			Line: 200<br />
			Function: create_front_controller_and_view			</p>

		
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: /home/gofrendi/public_html/No-CMS/index.php<br />
			Line: 455<br />
			Function: require_once			</p>

		
	

</div></div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>
<script type="text/javascript">
    var PAGE = 1;
    var URL = '<?php echo site_url($module_path."/browse_city/get_data"); ?>';
    var ALLOW_NAVIGATE_BACKEND = <?php echo $allow_navigate_backend ? "true" : "false"; ?>;
    var BACKEND_URL = '<?php echo $backend_url; ?>';
    var LOADING = false;
    var REQUEST;
    var RUNNING_REQUEST = false;
    var STOP_REQUEST = false;

    function fetch_more_data(async){
        if(typeof(async) == 'undefined'){
            async = true;
        }
        $('#record_content_bottom').html('Load more City &nbsp;<img src="{{ BASE_URL }}assets/nocms/images/ajax-loader.gif" />');
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
                var bottom_content = 'No more City to show.';
                if(ALLOW_NAVIGATE_BACKEND){
                    bottom_content += '&nbsp; <a href="<?php echo $backend_url; ?>/add/" class="add_record">Add new</a>';
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