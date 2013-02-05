<?php
echo '<ul>';
foreach($articles as $article){
	echo '<li>';
	echo anchor($cms['module_path'].'/blog/index/'.$article['article_url'],
				$article['title']);
	echo '</li>';
}
echo '</ul>';
