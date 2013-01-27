<style type="text/css">
	a[href="<?php echo site_url($cms["module_path"].'/group/delete/1');?>"]{
		visibility : hidden;
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