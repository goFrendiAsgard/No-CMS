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
<script type="text/javascript">
    $(document).ready(function(){
        $('.field-sorting').removeClass('field-sorting');
    });
    $(document).ajaxComplete(function(){
        $('.field-sorting').removeClass('field-sorting');
    });
</script>
