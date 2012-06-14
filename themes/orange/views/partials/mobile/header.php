<?php require_once BASEPATH."../themes/".$cms['site_theme']."/lib/function.php";?>
<h3><?php echo $cms['site_name'];?> - <?php echo $cms['site_slogan'];?></h3>
<div>
	<a class="layout_button_menu layout_button" href="#">Menu</a>
    <a class="layout_button_widget layout_button" href="#">Widget</a> 
    <a class="layout_button_content layout_button" href="#">Content</a>
    <?php echo build_quicklink($cms['quicklinks']);?>
</div>
