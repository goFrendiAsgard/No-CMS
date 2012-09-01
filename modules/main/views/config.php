<?php
	$identifier = '';
	for($i=1; $i<=30; $i++){
		$identifier.= 'a[href="'.site_url($cms["module_path"].'/config/delete/'.$i).'"]';
		if($i<30){
			$identifier.= ',';
		}
	}
?>
<style type="text/css">
	<?php echo $identifier;?>
	{
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