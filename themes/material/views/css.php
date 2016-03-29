<?php
$asset = new Cms_asset();
$asset->add_themes_css('css/bootstrap.min.css', '{{ used_theme }}');
$asset->add_themes_css('css/style.css', '{{ used_theme }}');
$asset->add_themes_css('css/roboto.min.css', '{{ used_theme }}');
$asset->add_themes_css('css/material.min.css', '{{ used_theme }}');
$asset->add_themes_css('css/ripples.min.css', '{{ used_theme }}');
echo $asset->compile_css();
