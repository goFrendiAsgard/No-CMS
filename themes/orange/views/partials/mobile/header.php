<?php require_once BASEPATH."../themes/$site_theme/lib/function.php";?>
<h3><?php echo $site_name;?> - <?php echo $site_slogan;?></h3>
<div>
	<a class="layout_button_menu layout_button" href="#">Menu</a>
    <a class="layout_button_widget layout_button" href="#">Widget</a> 
    <a class="layout_button_content layout_button" href="#">Content</a>
    <?php echo build_quicklink($quicklinks);?>
</div>
