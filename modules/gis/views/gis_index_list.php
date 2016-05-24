<h4>gis</h4>
<p>Maps: </p>
<?php
	echo '<ul>';
	foreach($map_list as $map){
		echo '<li>';
		echo anchor('{{ module_path }}/index/'.$map["map_id"], $map["map_name"]);
		echo br();
		echo $map["map_desc"];
		echo '</li>';
	}
	echo '</ul>';
?>
