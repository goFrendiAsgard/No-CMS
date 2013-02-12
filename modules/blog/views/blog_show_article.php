<?php
foreach($articles as $article){
	echo anchor(site_url($cms['module_path'].'/blog/index/'.$article['article_url']),
				'<h2>'.$article['title'].'</h2>');
	echo '('.$article['author'].', '.$article['date'].')';
	echo '<p>'.$article['content'].'</p>';
	
	foreach($article['photos'] as $photo){
		echo '<a class="photo_'.$article['id'].'" href="'.base_url('modules/'.$cms['module_path'].'/assets/uploads/'.$photo['url']).'">';
		echo '<img class="photo_thumbnail" src="'.base_url('modules/'.$cms['module_path'].'/assets/uploads/thumb_'.$photo['url']).'" />';
		echo '</a>';
	}
	echo '<script type="text/javascript">
	$(".photo_'.$article['id'].'").colorbox({rel:"photo_'.$article['id'].'", transition:"none", width:"75%", height:"75%", slideshow:true});
	</script>';
	
	echo '<div class="edit_delete_record_container">';
	echo anchor($cms['module_path'].'/blog/index/'.$article['article_url'],
				'read more', array("class"=>"btn btn-primary"));
	if($allow_navigate_backend){
		echo '&nbsp;';
		echo '<a href="'.$backend_url.'/edit/'.$article['id'].'" class="btn edit_record" primary_key = "'.$article['id'].'">Edit</a>';
		echo '&nbsp;';
		echo '<a href="'.$backend_url.'/delete/'.$article['id'].'" class="btn delete_record" primary_key = "'.$article['id'].'">Delete</a>';
		
	}
	echo '</div>';
}
