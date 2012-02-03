<?php
echo form_open('blog');
echo form_dropdown('category', $available_category, $category);
echo form_input('search', $search);
echo form_submit('submit', 'Search');
echo form_close();
echo br();

foreach($article as $single_article){
    echo '<h2>'.$single_article['title'].'</h2>'.br();
    echo '('.$single_article['author'].', '.$single_article['date'].')';
    echo '<p>'.$single_article['content'].'</p>';
    
    foreach($single_article['photos'] as $photo){
    	echo '<img src="'.base_url().'assets/uploads/files/'.$photo['url'].'" />';
    }
    echo '<br />';
    
    if($view_readmore){
        echo anchor('blog/index/'.$single_article['id'],'read more');
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
    	echo form_open('blog/add_comment/'.$single_article['id']);
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
