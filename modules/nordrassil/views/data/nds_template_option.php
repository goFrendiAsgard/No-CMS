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
	echo '<h4>Template Option</h4>';
	if(isset($template_id)){
		echo anchor(site_url('nordrassil/data/nds/template/edit/'.$template_id),'Back to template','class="btn btn-primary"');
	}	
	echo $output;
?>