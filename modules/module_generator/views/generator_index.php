<link rel="stylesheet" href="<?php echo base_url('modules/'.$cms["module_path"].'/assets/style/style.css');?>" />
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
<script type="text/javascript" src="<?php echo base_url('modules/'.$cms["module_path"].'/assets/script/script_index.js');?>"></script>
<?php 
	$checked = $custom_setting? 'checked="checked"' : '';
	$error_message_class = $error? '' : 'no_display';
?>
<h4>Module Generator</h4>
<div id="error_message" class="<?php echo $error_message_class; ?>">
	Cannot Establish Connection
</div>
<form id="form_main" class="form" method="POST" action="<?php echo site_url($cms["module_path"].'/module_generator/index'); ?>">
	<label>Use external database</label><br />
	<input id="custom_setting" name="custom_setting" type="checkbox" <?php echo $checked; ?> /><br />
	<div id="custom_database" class="no_display">
		<label>Hostname</label><br />
		<input id="hostname" name="hostname" type="text" value="<?php echo $hostname; ?>" /><br />
		<label>Username</label><br />
		<input id="username" name="username" type="text" value="<?php echo $username; ?>" /><br />
		<label>Password</label><br />
		<input id="password" name="password" type="password" value="<?php echo $password; ?>" /><br />
		<label>Database Schema</label><br />
		<input id="database" name="database" type="text" value="<?php echo $database; ?>" /><br />
		<label>Driver (Right now only MySQL supported)</label><br />
		<select id="dbdriver" name="dbdriver">
			<option value="mysql" selected>MySQL</option>
		</select>
	</div>
	<br />
	<input type="submit" id="btn_submit" name="submit" value="Start Module Generator" />
	<img id="img_ajax_loader" class="no_display" src="<?php echo base_url('assets/nocms/images/ajax-loader.gif'); ?>" />
</form>