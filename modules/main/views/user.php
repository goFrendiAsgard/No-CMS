<style type="text/css">
	a[href="<?php echo site_url($cms["module_path"].'/user/delete/1');?>"],
	a[href="<?php echo site_url($cms["module_path"].'/user/delete/'.$cms["user_id"]);?>"]{
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
	echo $asset->compile_js(TRUE);	
	echo $output;
?>