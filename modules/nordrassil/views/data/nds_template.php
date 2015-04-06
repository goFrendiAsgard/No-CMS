<?php
	$asset = new Cms_asset(); 
	foreach($css_files as $file){
		$asset->add_css($file);
	} 
	echo $asset->compile_css();
	
	foreach($js_files as $file){
		$asset->add_js($file);
	}
	echo $asset->compile_js();
	echo '<h4>Template</h4>';
	echo $output;