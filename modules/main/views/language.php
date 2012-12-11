<?php
	echo '<br />';
	foreach($language_list as $language){
		echo anchor(site_url($cms['module_path'].'/language/'.$language), $language);
		echo br();
	}
?>