<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
foreach($articles as $article){
    $article_url = $module_path=='blog'? 'blog/index/': '{{ module_path }}/blog/index/';
    $article_url .= $article['article_url'];

    echo '<div id="record_'.$article['id'].'">';
    echo anchor($article_url, '<h2>'.$article['title'].'</h2>');
    echo '('.$article['author'].', '.$article['date'].')';
    echo '<div>';
    echo $article['content'];
    echo '</div>';

    foreach($article['photos'] as $photo){
        echo '<a class="photo_'.$article['id'].'" href="'.base_url('modules/{{ module_path }}/assets/uploads/'.$photo['url']).'">';
        echo '<img class="photo_thumbnail" src="'.base_url('modules/{{ module_path }}/assets/uploads/thumb_'.$photo['url']).'" />';
        echo '</a>';
    }
    echo '<script type="text/javascript">
    $(".photo_'.$article['id'].'").colorbox({rel:"photo_'.$article['id'].'", transition:"none", width:"75%", height:"75%", slideshow:true});
    </script>';

    $comment_count = $article['comment_count'];
    $comment_count_caption = '';
    switch($comment_count){
        case 0  : $comment_count_caption = '';break;
        case 1  : $comment_count_caption = ' (1 comment)'; break;
        default : $comment_count_caption = ' ('.$comment_count.' comments)';
    }

    echo '<div class="edit_delete_record_container">';    
    echo anchor($article_url,
                'read more'.$comment_count_caption, array("class"=>"btn btn-primary"));
    if($allow_navigate_backend){
        if($is_super_admin || $article['author_user_id'] == $user_id){
            echo '&nbsp;';
            echo '<a href="'.$backend_url.'/edit/'.$article['id'].'" class="btn btn-default edit_record" primary_key = "'.$article['id'].'">Edit</a>';
            echo '&nbsp;';
            echo '<a href="'.$backend_url.'/delete/'.$article['id'].'" class="btn btn-danger delete_record" primary_key = "'.$article['id'].'">Delete</a>';
        }
    }
    echo '</div>';
    echo '</div>';
}
