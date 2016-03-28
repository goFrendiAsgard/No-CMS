<?php
$asset = new CMS_Asset();
$asset->add_themes_css('css/bootstrap.min.css', '{{ used_theme }}');
$asset->add_themes_css('css/style.css', '{{ used_theme }}');
echo $asset->compile_css();
