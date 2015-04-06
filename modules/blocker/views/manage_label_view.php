<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$asset = new Cms_asset(); 
foreach($css_files as $file){
    $asset->add_css($file);
} 
echo $asset->compile_css();

foreach($js_files as $file){
    $asset->add_js($file);
}
echo $asset->compile_js();

// For every content of option tag, this will replace '&nbsp;' with ' '
function __ommit_nbsp($matches){
    return $matches[1].str_replace('&nbsp;', ' ', $matches[2]).$matches[3];
}
?>
<style type="text/css">
    .flexigrid .full-width{
        width:95%!important;
    }
</style>
<?php
echo preg_replace_callback('/(<option[^<>]*>)(.*?)(<\/option>)/si', '__ommit_nbsp', $output);
?>
