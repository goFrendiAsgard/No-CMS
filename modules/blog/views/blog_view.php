<?php
echo form_open('blog');
echo form_dropdown('category', $available_category, $category);
echo form_input('search', $search);
echo form_submit('submit', 'Search');
echo form_close();

foreach($article as $single_article){
    echo '<h3>'.$single_article['title'].'</h3>';
    echo '<p>'.$single_article['content'].'</p>';
}
?>
