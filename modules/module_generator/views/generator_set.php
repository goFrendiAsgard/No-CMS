<link rel="stylesheet" href="<?php echo base_url('modules/'.$cms["module_path"].'/assets/style/style.css');?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/nocms/js/colorbox/colorbox.css';?>"></link>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/colorbox/jquery.colorbox-min.js';?>"></script>
<script type="text/javascript">
	var TABLES = <?php echo json_encode($tables);?>;
	var module_table = new Array();
	var BASE_URL = "<?php echo site_url($cms["module_path"]);?>"; 
</script>
<script type="text/javascript" src="<?php echo base_url('modules/'.$cms["module_path"].'/assets/script/script_set.js');?>"></script>

<h4>Module Generator</h4>
<div id="error_message" class="no_display">
</div>
<div id="form_main" class="form">
	<label>Module Namespace</label>
	<input id="module_namespace" name="module_namespace" type="text" />
	<label>Module Directory</label>
	<input id="module_directory" name="module_directory" type="text" />
	<label>Force overwrite</label>
	<input id="module_overwrite" name="module_overwrite" type="checkbox" />
	<label>Data</label>
	<select id="module_data" name="module_data" multiple="multiple">
	</select>
	<br />
	<input type="button" id="btn_up" value="Move Up" />
	<input type="button" id="btn_down" value="Move Down" />	
	<input type="button" id="btn_add_all" value="Add All" />
	<input type="button" id="btn_show_form_add" value="Add Table" />	
	<input type="button" id="btn_show_form_edit" value="Edit" />
	<input type="button" id="btn_remove" value="Remove" />
	<br /><br />	
	<input type="button" id="btn_submit" name="submit" value="Generate !!!" />
	<img id="img_ajax_loader" class="no_display" src="<?php echo base_url('assets/nocms/images/ajax-loader.gif'); ?>" />
</div>
<div class="no_display">
	<div id="form_add" class="form">
		<label>Controller Name</label>
		<input id="controller_name_add" name="controller_name" type="text" />
		<label>Navigation Caption</label>
		<input id="navigation_caption_add" name="navigation_caption" type="text" />		
		<label>Table</label><br />
		<select id="available_table_add" name="module_data">
			<!--  the content will be taken from php -->
		</select>
		<br />
		<input type="button" id="btn_add" value="Add to Module" />
	</div>
	<div id="form_edit" class="form">
		<input id="index_edit" type="hidden" />
		<label>Table Name</label>		
		<label id="table_name_edit"></label>
		<label>Controller Name</label>		
		<input id="controller_name_edit" name="controller_name" type="text" />
		<label>Navigation Caption</label>
		<input id="navigation_caption_edit" name="navigation_caption" type="text" />
		<br />
		<input type="button" id="btn_edit" value="Edit" />
	</div>
</div>