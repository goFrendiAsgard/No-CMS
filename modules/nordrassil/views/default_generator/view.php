&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

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

?&gt;
<?php
	if($make_frontpage){
		echo '<a class="btn btn-primary" href="{{ module_path }}front/'.$controller_name.'/index">Show Front Page</a>';
	}
?>