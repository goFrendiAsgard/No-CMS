<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/nocms/js/colorbox/colorbox.css';?>"></link>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/colorbox/jquery.colorbox-min.js';?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#wysiwyg_show").colorbox({
			width:"95%",
			height:"95%",
			href: "<?php echo site_url("{{ module_path }}/main?_only_content=true");?>",
		});
	});
</script>
<h3>WYSIWYG Editor</h3>
<p>
Click the button to start WYSIWYG editor.<br />
<i>(You might need to refresh the page after using WYSIWYG editor to see the real changes)</i>
</p>
<input id="wysiwyg_show" value="Show WYSIWYG Editor" type="button" />