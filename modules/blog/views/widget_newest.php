<?php
if(count($articles)==0){
    echo 'Currently there is no article yet';
}else{
    echo '<ul>';
    foreach($articles as $article){
        echo '<li>';
        echo anchor(site_url('{{ module_path }}/blog/index/'.$article['article_url']),
                    $article['title']);
        echo '</li>';
    }
    echo '</ul>';
}