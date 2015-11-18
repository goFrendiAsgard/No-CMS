<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style type="text/css">
    a.image-thumbnail img{
        max-width:200px;
    }
</style>
<?php if($state == 'list' || $state == 'success'){ ?>
    <h3>Configuration</h3>
    <form method="post" class="form">
        <label>Slideshow Height (left blank to make it dynamic) </label>
        <input name="slideshow-height" value="<?php echo $slide_height; ?>" />
        <label>px</label>
        <button name="apply" class="btn btn-primary">Save Configuration</button>
    </form>
    <h3>Slideshow</h3>
<?php
}

$asset = new Cms_asset(); 
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
