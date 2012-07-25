<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/nocms/js/colorbox/colorbox.css';?>"></link>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/nocms/js/jquery.js"></script>
<script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/colorbox/jquery.colorbox-min.js';?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#show").colorbox({
			width:"90%",
			height:"90%",
			href: "<?php echo site_url($cms["module_path"]."/main?_only_content=true");?>",
		});
	});
</script>
<a id="show">Show WYSIWYG Editor</a>