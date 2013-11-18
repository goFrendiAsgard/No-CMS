<?php
if(count($articles)==0){
    echo 'Currently there is no article yet';
}else{
    echo '<ul>';
    foreach($articles as $article){
        echo '<li>';
        if($module_path == 'blog'){
            echo anchor(site_url('blog/index/'.$article['article_url']),
                    $article['title']);    
        }else{
            echo anchor(site_url('{{ module_path }}/blog/index/'.$article['article_url']),
                        $article['title']);
        }
        echo '</li>';
    }
    echo '</ul>';
}