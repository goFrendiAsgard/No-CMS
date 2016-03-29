<?php
$asset = new Cms_asset();
$asset->add_cms_js('bootstrap/js/bootstrap.min.js');
$asset->add_themes_js('js/script.js', '{{ used_theme }}');
echo $asset->compile_js();
