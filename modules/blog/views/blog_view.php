<?php
echo form_open('blog');
echo form_dropdown('category', $available_category, $category);
echo form_input('search', $search);
echo form_submit('submit', 'Search');
echo form_close();

foreach($article as $single_article){
    echo '<h3>'.$single_article['title'].'</h3>';
    echo $single_article['author'].', '.$single_article['date'];
    echo '<p>'.$single_article['content'].'</p>';
    if($view_readmore){
        echo anchor('blog/index/'.$single_article['id'],'read more');
    }
}
?>
