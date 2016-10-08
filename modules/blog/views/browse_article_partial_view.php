<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$record_template = isset($record_template)? $record_template: $default_record_template;

$config = array(
        'record_template' => $record_template,
        'backend_url'     => $backend_url,
        'primary_key'     => 'id',
        'allow_edit'      => $allow_navigate_backend,
        'allow_delete'    => $allow_navigate_backend,
    );
foreach($articles as $article){
    $article = (object) $article; // convert article to object

    // preprocess $article->url
    if($article_route_exists){
        $article_url = $module_path=='blog'? 'blog/': '{{ module_path }}/blog/';
        $article_url .= $article->article_url.'.html';
    }else{
        $article_url = $module_path=='blog'? 'blog/index/': '{{ module_path }}/blog/index/';
        $article_url .= $article->article_url;
    }
    $article->article_url = site_url($article_url);

    // preprocess $article->photos
    $article_photos = '';
    if(count($article->photos)>0){
        $article_photos .= '<div id="small_photo_'.$article->id.'" class="small_photo well">';
        $index = 0;
        foreach($article->photos as $photo){
            if($index == $blog_max_slide_image){
                $article_photos .= '<a href="'.$article->article_url.'">';
                $article_photos .= '<div class="photo_more well"><span class="col-xs-12" style="padding-left:10px; padding-right:10px;"><i class="glyphicon glyphicon-plus"></i> {{ language:Read More }}</span></div>';
                $article_photos .= '</a>';
                break;
            }
            $is_first = $index == 0 ? 1 : 0;
            $is_last = $index == ($blog_max_slide_image-1) ? 1 : 0;
            $article_photos .= '<a data-toggle="modal" data-target="#photo-modal" class="photo_link" article_id="'.$article->id.'" index="'.$index.'" photo_id="'.$photo['id'].'" img="'.base_url('modules/{{ module_path }}/assets/uploads/'.$photo['url']).'" is_first="'.$is_first.'" is_last="'.$is_last.'" href="#">';
            $article_photos .= '<div class="photo_thumbnail" style="background-image:url('.base_url('modules/{{ module_path }}/assets/uploads/thumb_'.$photo['url']).');"></div>';
            $article_photos .= '<div id="photo_caption_'.$photo['id'].'" class="photo_caption">'.$photo['caption'].'</div>';
            $article_photos .= '</a>';
            $index ++;
        }
        $article_photos .= '</div>';
    }
    $article->photos = $article_photos;

    // preprocess $article->categories
    $article_categories = '';
    if(count($article->categories)>0){
        if($module_path == 'blog'){
            $module_url = 'blog';
        }else{
            $module_url = $module_path.'/blog';
        }
        $article_categories .= '<div style="margin-bottom:20px;">';
        $article_categories .= '<b>Categories</b> :&nbsp;';
        foreach($article->categories as $category){
            if($category_route_exists){
                $url = $module_url.'/category/'.$category['name'];
            }else{
                $url = $module_url.'/index?category='.$category['name'];
            }
            $article_categories .= '<a href="'.site_url($url).'"><span class="label label-primary">'.$category['name'].'</span></a> ';
        }
        $article_categories .= '</div>';
    }
    $article->categories = $article_categories;

    // $article->comment_count_caption
    $comment_count = $article->comment_count;
    $article->comment_count_caption = '';
    switch($comment_count){
        case 0  : $article->comment_count_caption = '';break;
        case 1  : $article->comment_count_caption = ' (1 comment)'; break;
        default : $article->comment_count_caption = ' ('.$comment_count.' comments)';
    }

    // $article->backend_url
    $article_backend_url = '';
    if($allow_navigate_backend){
        if($is_super_admin || $is_blog_editor || $is_blog_author || ($is_blog_contributor && $article->author_user_id == $user_id)){
            $article_backend_url .= '&nbsp;';
            $article_backend_url .= '<a href="'.$backend_url.'/edit/'.$article->id.'" class="btn btn-default edit_record" primary_key = "'.$article->id.'">Edit</a>';
            $article_backend_url .= '&nbsp;';
            $article_backend_url .= '<a href="'.$backend_url.'/delete/'.$article->id.'" class="btn btn-danger delete_record" primary_key = "'.$article->id.'">Delete</a>';
        }
    }
    $article->backend_url = $article_backend_url;

    // parse record
    echo parse_record($article, $config);
}
