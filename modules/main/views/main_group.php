<style type="text/css">
	<?php foreach($undeleted_id as $id){ ?>
    tr[rowid="<?=$id?>"] a.delete-row{
        display:none;
    }
    <?php } ?>
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