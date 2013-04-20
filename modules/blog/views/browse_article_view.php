<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/nocms/js/colorbox/colorbox.css';?>"></link>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'); ?>" />
<style type="text/css">
    .comment_normal{
        display:none!important;
    }
	#record_content{
		margin-top: 5px;
		margin-bottom: 20px;
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
    div#article-comment{
        margin-top : 30px;
        padding-top : 20px;
        padding-left: 25px;
        border-top : 1px solid gray;
    }
    div.comment-item{
        padding-top : 10px;
        padding-bottom : 10px;
        padding-left : 10px;
    }
    div.comment-header{
        font-size : small;
        font-weight: bold;
    }
    div.row-odd{
        background-color: #DDDDFF;
    }
    div.edit_delete_record_container{
        margin-bottom:45px;
    }
</style>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/colorbox/jquery.colorbox-min.js';?>"></script>

<div id="submenu_screen"><?php echo $submenu_screen; ?></div>
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
            echo '<div>';
            echo $article['content'];
            echo '</div>';

            echo '<div>';
            foreach($article['photos'] as $photo){
                echo '<a class="photo_'.$article['id'].'" href="'.base_url('modules/{{ module_path }}/assets/uploads/'.$photo['url']).'">';
                echo '<img class="photo_thumbnail" src="'.base_url('modules/{{ module_path }}/assets/uploads/'.$photo['url']).'" />';
                echo '</a>';
            }
            echo '</div>';
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
            echo '<div id="article-comment">';
            // comment
            $odd_row = TRUE;
            foreach($article['comments'] as $comment){
                if($odd_row){
                    $row_class = 'row-odd';
                }else{
                    $row_class = 'row-even';
                }
                $odd_row = !$odd_row;
                echo '<div class="comment-item '.$row_class.'">';
                echo '<div class="comment-header">Comment From : '.$comment['name'].', '.$comment['date'].br();
                echo anchor($comment['website'], $comment['website']).'</div>';
                echo $comment['content'];
                echo '</div>';
            }
            echo br();
            // comment form
            if($article['allow_comment']){
                echo '<b>Add Comments </b>'.br().br();
                echo form_open();
                echo form_hidden('article_id', $article['id']);
                echo form_input(array('name'=>'secret_code', 'value'=>$secret_code, 'class'=>'comment_normal'));
                echo form_input(array('name'=>'name', 'value'=>'', 'class'=>'comment_normal'));
                echo form_input(array('name'=>'email', 'value'=>'', 'class'=>'comment_normal'));
                echo form_input(array('name'=>'website', 'value'=>'', 'class'=>'comment_normal'));
                echo form_input(array('name'=>'content', 'value'=>'', 'class'=>'comment_normal'));
                if(!$is_user_login){
                    echo form_label('Name :').br();
                    echo form_input('xname').br();
                    echo form_label('Email :').br();
                    echo form_input('xemail').br();
                }
                echo form_label('Website :').br();
                echo form_input('xwebsite').br();
                echo form_label('Comment :').br();
                echo form_textarea('xcontent').br();
                echo form_submit('submit', 'Comment');
                echo form_close();
            }
            echo '</div>';
        }else if(isset($article) && $article == FALSE){
            echo '<div class="alert alert-error">No article found</div>';
        }
    ?>
</div>
<div id="record_content_bottom" class="alert alert-success">End of Page</div>
<script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js'); ?>"></script>
<script type="text/javascript">
	var PAGE = 0;
	var URL = '<?php echo site_url($module_path."/blog/get_data"); ?>';
	var ALLOW_NAVIGATE_BACKEND = <?php echo $allow_navigate_backend ? "true" : "false"; ?>;
	var BACKEND_URL = '<?php echo $backend_url; ?>';
	var LOADING = false;
	var REQUEST
    var RUNNING_REQUEST = false;
    var SCROLL_WORK = true;
    <?php if(isset($article)){
        echo 'var SCROLL_WORK = false;';
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
			if(!LOADING && SCROLL_WORK){
			    if($(window).scrollTop() == $(document).height() - $(window).height()){
			    	LOADING = true;
			    	fetch_more_data(false);
			    	LOADING = false;
			    }
			}
		});

	});

</script>