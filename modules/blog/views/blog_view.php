<?php if(!$only_show_article){?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/nocms/js/colorbox/colorbox.css';?>"></link>
	<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
	<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/colorbox/jquery.colorbox-min.js';?>"></script>
	<style type="text/css">
		img.photo_thumbnail{
			width: auto;
			height: 75px;
			margin: 5px;
		}
	</style>
	<?php if(!$single_article){?>
		<script type="text/javascript">
			var PAGE = 0;
			var LOADING = false;
			$(window).scroll(function(){
				if(!LOADING){					
				    if($(window).scrollTop() == $(document).height() - $(window).height())
				    {
				    	LOADING = true;
				    	PAGE ++;
				        $("#blog_content").append('<span id="blog_content_'+PAGE+'"></span>');
				        $('div#loadmoreajaxloader').show();
				        
				        $.ajax({
					        async: false,
					        type: "POST",
					        data: {						        
						        "page" : PAGE,
						        "only_article" : true,
						        "search" : $("#input_search").val(),
						        "category" : $("#input_category").val(),
						    },
					        url: "<?php echo site_url($cms['module_path'].'/blog/index?_only_content=true'); ?>",
					        success: function(html)
					        {
								if(html)
					            {
					                $("#blog_content_"+PAGE).after(html);
					                $('div#loadmoreajaxloader').hide();
					            }else
					            {
					                $('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
					            }
					        }
				        });
				        LOADING = false;
				    }
				    
				}
			});
			
		</script>
	<?php }?>
<?php }

// MAIN PROGRAM ================================================================

if(!$only_show_article){
	show_search_form($cms, $available_category, $category, $search);
	if($allow_edit){
		echo '<p>';
		echo anchor($cms['module_path'].'/blog/article/add/',
				'Add New Article', array("class"=>"btn"));
		echo '</p>';
	}
}

if(!$single_article){
	if(!$only_show_article){
		echo '<div id="blog_content">';
	}
	
	// blog content
	if($articles !== FALSE){
		foreach($articles as $article){
			show_article($cms, $article, false, $allow_edit);
		}
	}
	
	if(!$only_show_article){
	    echo '</div>';
		echo '<div id="loadmoreajaxloader" style="display:none;">
			<center><img src="'.base_url('assets/nocms/images/ajax-loader.gif').'" /></center></div>';
	}
}else{
	if($article !== FALSE){
		show_article($cms, $article, true, $allow_edit);
	}
}

// FUNCTIONS ===================================================================

function show_article($cms, $article, $single=true, $allow_edit = false){
	if($single){
		echo '<h2>'.$article['title'].'</h2>'.br();
	}else{
		echo anchor($cms['module_path'].'/blog/index/'.$article['article_url'],
				'<h2>'.$article['title'].'</h2>');
	}
	echo '('.$article['author'].', '.$article['date'].')';
	echo '<p>'.$article['content'].'</p>';
	
	foreach($article['photos'] as $photo){
		echo '<a class="photo_'.$article['id'].'" href="'.base_url('modules/'.$cms['module_path'].'/assets/uploads/'.$photo['url']).'">';
		echo '<img class="photo_thumbnail" src="'.base_url('modules/'.$cms['module_path'].'/assets/uploads/'.$photo['url']).'" />';
		echo '</a>';
	}
	echo '<script type="text/javascript">
	$(".photo_'.$article['id'].'").colorbox({rel:"photo_'.$article['id'].'", transition:"none", width:"75%", height:"75%", slideshow:true});
	</script>';
	
	echo br();
	if(!$single){		
		echo anchor($cms['module_path'].'/blog/index/'.$article['article_url'],
				'read more', array("class"=>"btn btn-primary"));
		echo '&nbsp;';
	}
	if($allow_edit){
		echo anchor($cms['module_path'].'/blog/article/edit/'.$article['id'],
				'edit', array("class"=>"btn"));
	}
	echo '<hr />';
	if($single)	show_comment($cms, $article);
	
}

function show_comment($cms, $article){
	
	
	foreach($article['comments'] as $comment){
		echo '<b>Comment From : '.$comment['name'].', '.$comment['date'].'</b>'.br();
		echo anchor($comment['website'], $comment['website']).br();
		echo $comment['content'].br();
		echo '<hr />';
		echo br();
	}
	
	if($article['allow_comment']){
		echo '<b>Add Comments </b>'.br().br();
		echo form_open($cms['module_path'].'/blog/add_comment/'.$article['id']);
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
	
}

function show_search_form($cms, $available_category, $category, $search){
	echo '<base href="'.base_url().'" />';
	echo '<div>';
	echo form_open($cms['module_path'].'/blog/index', array("class"=>"form-search"));
	echo form_dropdown('category', $available_category, $category, 'id="input_category" class="input-medium"');
	echo '&nbsp;';
	echo form_input('search', $search, 'id="input_search" class="input-medium search-query"');
	echo '&nbsp;';
	echo form_submit('submit', 'Search', 'class="btn btn-primary"');
	echo form_close();
	echo '</div>';
}