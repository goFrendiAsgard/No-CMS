<?php
	$asset = new CMS_Asset(); 
	foreach($css_files as $file){
		$asset->add_css($file);
	} 
	echo $asset->compile_css();
	
	foreach($js_files as $file){
		$asset->add_js($file);
	}
	echo $asset->compile_js();
	if(isset($article_id)){
		echo anchor(site_url($cms['module_path'].'/article/edit/'.$article_id),'Back to article');
		echo br();
	}	
	echo $output;
?>