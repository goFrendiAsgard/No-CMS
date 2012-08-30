<?php
	$asset = new CMS_Asset(); 
	foreach($css_files as $file){
		$asset->add_css($file);
	} 
	foreach($js_files as $file){		
		$asset->add_js($file);
	}
	echo $asset->compile_css();
	echo $asset->compile_js();
	echo $output;

?>