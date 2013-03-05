<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/nocms/js/colorbox/colorbox.css';?>"></link>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'); ?>" />
<style type="text/css">
	#record_content{
		margin-top: 5px;
		margin-bottom: 10px;
	}
    #record_content:empty{
        display:none;
    }
	.record_container{
		margin:10px;
	}
	.edit_delete_record_container{
		margin-top: 10px;
	}
    #record_content_bottom{
        margin-top: 5px;
        display:none;
    }
    img.photo_thumbnail{
        width: auto;
        height: 75px;
        margin: 5px;
    }
</style>
<select id="input_category" class="select-category">
    <?php
        foreach($categories as $key=>$value){
            $selected = '';
            if($key == $chosen_category){
                $selected = 'selected = "selected"';
            }
            echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
    ?>
</select>
<input type="text" name="search" value="<?php echo $keyword; ?>" id="input_search" class="input-medium search-query">
<input type="submit" name="submit" value="Search" id="btn_search" class="btn btn-primary">
<?php
	if($allow_navigate_backend){
		echo '<a href="'.$backend_url.'/add/" class="btn add_record">Add</a>'.PHP_EOL;
	}
?>
<div id="record_content">
    <?php
        if(isset($article) && $article !== FALSE){
            echo '<h2>'.$article['title'].'</h2>'.br();
            echo '('.$article['author'].', '.$article['date'].')';
            echo '<p>'.$article['content'].'</p>';
            foreach($article['photos'] as $photo){
                echo '<a class="photo_'.$article['id'].'" href="'.base_url('modules/'.$cms['module_path'].'/assets/uploads/'.$photo['url']).'">';
                echo '<img class="photo_thumbnail" src="'.base_url('modules/'.$cms['module_path'].'/assets/uploads/'.$photo['url']).'" />';
                echo '</a>';
            }
            // edit and delete button
            if($allow_navigate_backend){
                echo '<div class="edit_delete_record_container">';
                echo '<a href="'.$backend_url.'/edit/'.$article['id'].'" class="btn edit_record" primary_key = "'.$article['id'].'">Edit</a>';
                echo '&nbsp;';
                echo '<a href="'.$backend_url.'/delete/'.$article['id'].'" class="btn delete_record" primary_key = "'.$article['id'].'">Delete</a>';
                echo '</div>';
            }
            echo '<script type="text/javascript">
            $(".photo_'.$article['id'].'").colorbox({rel:"photo_'.$article['id'].'", transition:"none", width:"75%", height:"75%", slideshow:true});
            </script>';
            echo '<hr />';
            // comment
            foreach($article['comments'] as $comment){
                echo '<b>Comment From : '.$comment['name'].', '.$comment['date'].'</b>'.br();
                echo anchor($comment['website'], $comment['website']).br();
                echo $comment['content'].br();
                echo '<hr />';
                echo br();
            }
            // comment form
            if($article['allow_comment']){
                echo '<b>Add Comments </b>'.br().br();
                echo form_open();
                echo form_hidden('article_id', $article['id']);
                echo form_label('Name :').br();
                echo form_input('name').br();
                echo form_label('Email :').br();
                echo form_input('email').br();
                echo form_label('Website :').br();
                echo form_input('website').br();
                echo form_label('Comment :').br();
                echo form_textarea('content').br();
                echo form_submit('submit', 'Comment');
                echo form_close();
            }
        }else if(isset($article) && $article == FALSE){
            echo '<div class="alert alert-error">No article found</div>';
        }
    ?>
</div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/colorbox/jquery.colorbox-min.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js'); ?>"></script>
<script type="text/javascript">
	var PAGE = 0;
	var URL = '<?php echo site_url($module_path."/browse_article/get_data"); ?>';
	var ALLOW_NAVIGATE_BACKEND = <?php echo $allow_navigate_backend ? "true" : "false"; ?>;
	var BACKEND_URL = '<?php echo $backend_url; ?>';
	var LOADING = false;
	var REQUEST
    var RUNNING_REQUEST = false;
    <?php if(isset($article)){
        echo 'var SCROLL_WORK = false;';
    }else{
        echo 'var SCROLL_WORK = true;';
    }
    ?>

	function fetch_more_data(async){
		if(typeof(async) == 'undefined'){
			async = true;
		}
		$('#record_content_bottom').html('Load more Article ...');
		var keyword = $('#input_search').val();
		var category = $('#input_category').val();
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
			    'category' : category,
				'keyword' : keyword,
				'page' : PAGE,
			},
			'success'  : function(response){
				// show contents
				$('#record_content').append(response);

				// show bottom contents
				var bottom_content = 'No more Article to show.';
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
	    SCROLL_WORK = true;
	    $('#record_content_bottom').show();
		$('#record_content').html('');
		PAGE = 0;
		fetch_more_data();
	}

	// main program
	$(document).ready(function(){
	    if(SCROLL_WORK){
            reset_content();
            $('#record_content_bottom').show();
        }
		fetch_more_data();
		$(".select-category").chosen();

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
		// input category change
        $('#input_category').change(function(){
            reset_content();
        });

		// scroll
		$(window).scroll(function(){
			if(!LOADING){
			    if($(window).scrollTop() == $(document).height() - $(window).height()){
			    	LOADING = true;
			    	fetch_more_data(false);
			    	LOADING = false;
			    }
			}
		});

	});

</script>