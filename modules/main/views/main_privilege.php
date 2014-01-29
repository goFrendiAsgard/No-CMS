<?php 
	$asset = new CMS_Asset();
	foreach($css_files as $file){
		$asset->add_css($file);
	} 
	echo $asset->compile_css();
	
	foreach($js_files as $file){
		$asset->add_js($file);
	}
	$asset->add_module_js('scripts/privilege.js','main');
	echo $asset->compile_js();
	echo $output;