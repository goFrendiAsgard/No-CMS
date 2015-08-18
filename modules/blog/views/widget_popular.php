<style type="text/css">
    ul._popular_widget { 
        list-style-type: none; padding-left: 10px;
    }
    ul._popular_widget li {
        padding-top: 5px;
        padding-bottom:5px;
    }
    ul._popular_widget a{
        color: inherit;
    }
    ul._popular_widget a:hover{
        color: inherit;
    }
</style>
<?php
if(count($articles)==0){
    echo 'Currently there is no article yet';
}else{
    echo '<ul class="_popular_widget">';
    foreach($articles as $article){
        echo '<li>';
        // get url
        if($module_path == 'blog'){
            $url = site_url('blog/index/'.$article['article_url']);
        }else{
            $url = site_url('{{ module_path }}/blog/index/'.$article['article_url']);
        }
        // get image
        if(count($article['photos'])>0){
            $photo = $article['photos'][0]['url'];
            $photo = base_url('modules/'.$module_path.'/assets/uploads/thumb_'.$photo);
        }else{
            $photo = base_url('modules/'.$module_path.'/assets/images/text.jpeg');
        }
        echo anchor($url,
                    '<div class="row">'.
                    '<div class="col-md-4" style="min-height:50px; background-repeat: no-repeat;
                        background-attachment: cover; background-position: center; 
                        background-color:black;
                        background-size:cover;
                        background-image:url(\''.$photo.'\')"></div>'.
                    '<div class="col-md-8" style="vertical-align:top;">'.$article['title'].'</div>'.
                    '</div>');
        echo '</li>';
    }
    echo '</ul>';
}