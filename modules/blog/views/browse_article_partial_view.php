<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
foreach($articles as $article){
    if($article_route_exists){
        $article_url = $module_path=='blog'? 'blog/': '{{ module_path }}/blog/';
        $article_url .= $article['article_url'].'.html';
    }else{
        $article_url = $module_path=='blog'? 'blog/index/': '{{ module_path }}/blog/index/';
        $article_url .= $article['article_url'];
    }

    // title & author
    echo '<div id="record_'.$article['id'].'">';
    echo anchor($article_url, '<h2>'.$article['title'].'</h2>');
    echo '('.$article['author'].', '.$article['date'].')';

    // photos
    if(count($article['photos'])>0){
        echo '<div id="small_photo_'.$article['id'].'" class="small_photo well">';
        $index = 0;
        foreach($article['photos'] as $photo){
            if($index == $blog_max_slide_image){
                echo '<a href="'.site_url($article_url).'">';
                echo '<div class="photo_more well"><span class="col-xs-12" style="padding-left:10px; padding-right:10px;"><i class="glyphicon glyphicon-plus"></i> {{ language:Read More }}</span></div>';
                echo '</a>';
                break;
            }
            $is_first = $index == 0 ? 1 : 0;
            $is_last = $index == ($blog_max_slide_image-1) ? 1 : 0;
            echo '<a data-toggle="modal" data-target="#photo-modal" class="photo_link" article_id="'.$article['id'].'" index="'.$index.'" photo_id="'.$photo['id'].'" img="'.base_url('modules/{{ module_path }}/assets/uploads/'.$photo['url']).'" is_first="'.$is_first.'" is_last="'.$is_last.'" href="#">';
            echo '<div class="photo_thumbnail" style="background-image:url('.base_url('modules/{{ module_path }}/assets/uploads/thumb_'.$photo['url']).');"></div>';
            echo '<div id="photo_caption_'.$photo['id'].'" class="photo_caption">'.$photo['caption'].'</div>';
            echo '</a>';
            $index ++;
        }
        echo '</div>';
    }

    // content
    echo '<div>';
    echo $article['content'];
    echo '<div style="clear:both;"></div>';
    echo '</div>';

    // categories
    if(count($article['categories'])>0){
        if($module_path == 'blog'){
            $module_url = 'blog';
        }else{
            $module_url = $module_path.'/blog';
        }
        echo '<div style="margin-bottom:20px;">';
        echo '<b>Categories</b> :&nbsp;';
        foreach($article['categories'] as $category){
            if($category_route_exists){
                $url = $module_url.'/category/'.$category['name'];
            }else{
                $url = $module_url.'/index?category='.$category['name'];
            }
            echo '<a href="'.site_url($url).'"><span class="label label-primary">'.$category['name'].'</span></a> ';
        }
        echo '</div>';
    }

    $comment_count = $article['comment_count'];
    $comment_count_caption = '';
    switch($comment_count){
        case 0  : $comment_count_caption = '';break;
        case 1  : $comment_count_caption = ' (1 comment)'; break;
        default : $comment_count_caption = ' ('.$comment_count.' comments)';
    }

    echo '<div class="edit_delete_record_container">';
    echo anchor($article_url,
                '<i class="glyphicon glyphicon-plus"></i> {{ language:Read More }}'.$comment_count_caption, array("class"=>"btn btn-primary"));
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
