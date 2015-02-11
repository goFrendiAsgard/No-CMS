<style type="text/css">
    ul._newest_widget { 
        list-style-type: disc; padding-left: 20px;
    }
</style>
<?php
if(count($articles)==0){
    echo 'Currently there is no article yet';
}else{
    echo '<ul class="_newest_widget">';
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