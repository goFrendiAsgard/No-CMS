<h1>This is example module</h1> 
<p>Here you will see various view call</p>

<?php 
	if(isset($anchors)){
		echo '<ul>';
		foreach ($anchors as $anchor){
			echo '<li>';
			echo anchor($anchor['url'], $anchor['title']);
			echo br();
			echo $anchor['description'];
			echo '</li>';
		}
		echo '</ul>';
	}else{
		echo 'There is no data sent, so the view cannot show anything :(';
	}
?>


