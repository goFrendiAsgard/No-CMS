<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
	div.management-link-container{
		display:block;
		float: left;
		margin: 10px;
		width: 80px;
		height: 75px;
		background-color:#EEEEEE;
	    padding: 5px 5px 5px 5px;
	    margin : 10px;
	    font-size: small;
	    min-height : 25px;
	    border-radius:5px;
	    -moz-border-radius:5px;
	    -moz-box-shadow:    1px 1px 1px 1px #ccc;
	    -webkit-box-shadow: 1px 1px 1px 1px #ccc;
	    box-shadow:         1px 1px 1px 1px #ccc;	
	    text-align: center;	
	}
	div.management-link-container a{
		width: 100%;
		height: 100%;
		display: block;		
	}
</style>
<h3>CMS Management</h3>
<p>Here you can manage user, group, navigation(menu) and privilege</p>
<div class="management-link-container">
	<a href="<?php echo base_url('main/group');?>"><img src="<?php echo base_url('assets/nocms/images/icons/group.png');?>" /><br />Manage Group</a><br />
</div>
<div class="management-link-container">
	<a href="<?php echo base_url('main/user');?>"><img src="<?php echo base_url('assets/nocms/images/icons/user.png');?>" /><br />Manage User</a><br />
</div>
<div class="management-link-container">
	<a href="<?php echo base_url('main/privilege');?>"><img src="<?php echo base_url('assets/nocms/images/icons/privilege.png');?>" /><br />Manage Privilege</a><br />
</div>
<div class="management-link-container">
	<a href="<?php echo base_url('main/navigation');?>"><img src="<?php echo base_url('assets/nocms/images/icons/navigation.png');?>" /><br />Manage Navigation</a><br />
</div>
<div class="management-link-container">
	<a href="<?php echo base_url('main/widget');?>"><img src="<?php echo base_url('assets/nocms/images/icons/widget.png');?>" /><br />Manage Widget</a><br />
</div>
<div class="management-link-container">
	<a href="<?php echo base_url('main/module_management');?>"><img src="<?php echo base_url('assets/nocms/images/icons/module.png');?>" /><br />Add/Remove Module</a><br />
</div>
<div class="management-link-container">
	<a href="<?php echo base_url('main/change_theme');?>"><img src="<?php echo base_url('assets/nocms/images/icons/theme.png');?>" /><br />Change Theme</a><br />
</div>
<div class="management-link-container">
	<a href="<?php echo base_url('main/quicklink');?>"><img src="<?php echo base_url('assets/nocms/images/icons/quicklink.png');?>" /><br />Manage Quicklink</a><br />
</div>
<div class="management-link-container">
	<a href="<?php echo base_url('main/config');?>"><img src="<?php echo base_url('assets/nocms/images/icons/config.png');?>" /><br />Configuration</a><br />
</div>
<?php if($show_wysiwyg){?>
	<div class="management-link-container">
		<a href="<?php echo base_url('wysiwyg');?>"><img src="<?php echo base_url('assets/nocms/images/icons/wysiwyg.png');?>" /><br />Wysiwyg</a><br />
	</div>
<?php }?>
<?php if($show_help){?>
	<div class="management-link-container">
		<a href="<?php echo base_url('help');?>"><img src="<?php echo base_url('assets/nocms/images/icons/help.png');?>" /><br />No-CMS User Guide</a><br />
	</div>
<?php }?>
<?php if($show_module_generator){?>
	<div class="management-link-container">
		<a href="<?php echo base_url('module_generator/index');?>"><img src="<?php echo base_url('assets/nocms/images/icons/module_generator.png');?>" /><br />Module Generator</a><br />
	</div>
<?php }?>