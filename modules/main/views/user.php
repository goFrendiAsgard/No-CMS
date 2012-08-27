<style type="text/css">
	a[href="<?php echo site_url($cms["module_path"].'/user/delete/1');?>"],
	a[href="<?php echo site_url($cms["module_path"].'/user/delete/'.$cms["user_id"]);?>"]{
		visibility : hidden;
	}
</style>
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<?php echo $output; ?>