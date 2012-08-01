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
<?php
echo '<base href="'.base_url().'" />';
echo '<div>';
echo form_open($cms['module_path'], array("class"=>"form-search"));
echo form_dropdown('category', $available_category, $category);
echo form_input('search', $search);
echo form_submit('submit', 'Search');
echo form_close();
echo '</div>';

foreach($article as $single_article){
    echo '<h2>'.$single_article['title'].'</h2>'.br();
    echo '('.$single_article['author'].', '.$single_article['date'].')';
    echo '<p>'.$single_article['content'].'</p>';
    
    foreach($single_article['photos'] as $photo){
    	echo '<a class="photo_'.$single_article['id'].'" href="'.base_url().'assets/uploads/files/'.$photo['url'].'">';
    	echo '<img class="photo_thumbnail" src="'.base_url().'assets/uploads/files/'.$photo['url'].'" />';
    	echo '</a>';
    }
    echo '<script type="text/javascript">
    	$(".photo_'.$single_article['id'].'").colorbox({rel:"photo_'.$single_article['id'].'", transition:"none", width:"75%", height:"75%", slideshow:true});
    	</script>';
    echo '<br />';
    
    if($view_readmore){
        echo anchor($cms['module_path'].'/blog/index/'.$single_article['article_url'],'read more');
    }
    if($single_article['allow_comment']){
    	echo '<hr />';
    	
    	foreach($single_article['comments'] as $comment){
    		echo '<b>Comment From : '.$comment['name'].', '.$comment['date'].'</b>'.br();
    		echo anchor($comment['website'], $comment['website']).br();
    		echo $comment['content'].br();
    		echo '<hr />';
    		echo br();
    	}
    	
    	echo '<b>Add Comments </b>'.br().br();
    	echo form_open($cms['module_path'].'/blog/add_comment/'.$single_article['id']);
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
?>
