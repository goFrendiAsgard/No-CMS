<style type="text/css">
    ul._newest_widget { 
        list-style-type: none; padding-left: 10px;
    }
    ul._newest_widget li {
        padding-top: 5px;
        padding-bottom:5px;
    }
    ul._newest_widget a{
        color: inherit;
    }
    ul._newest_widget a:hover{
        color: inherit;
    }
</style>
<?php
if(count($articles)==0){
    echo 'Currently there is no article yet';
}else{
    echo '<ul class="_newest_widget">';
    foreach($articles as $article){
        if($article_route_exists){
            $article_url = $module_path=='blog'? 'blog/': '{{ module_path }}/blog/';
            $article_url .= $article['article_url'].'.html';
        }else{
            $article_url = $module_path=='blog'? 'blog/index/': '{{ module_path }}/blog/index/';
            $article_url .= $article['article_url'];
        }
        echo '<li>';
        // get image
        if(count($article['photos'])>0){
            $photo = $article['photos'][0]['url'];
            $photo = base_url('modules/'.$module_path.'/assets/uploads/'.$photo);
        }else{
            $photo = base_url('modules/'.$module_path.'/assets/images/text.jpeg');
        }
        echo anchor($article_url,
                    '<div class="row">'.
                        '<div class="col-md-4" style="height:150px; overflow:hidden"><img src="'.$photo.'" class="img-responsive" alt="cover"></div>'.
                        '<div class="col-md-8" style="vertical-align:top;">'.
                            $article['title'].'<br />'.
                            '<span class="label label-primary">'.
                                $article['date'].' &nbsp;<i class="glyphicon glyphicon-calendar"></i>'.
                            '</span>'.
                        '</div>'.
                    '</div>');
        echo '</li>';
    }
    echo '</ul>';
}
