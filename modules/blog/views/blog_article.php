<style type="text/css">
	img.photo_thumbnail_grid{
		width: 25px;
		height: auto;
		margin: 5px;
	}
	
	img.photo_thumbnail_form{
		width: auto;
		height: 100px;
		margin: 5px;
	}
</style>
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
	echo $output;
?>