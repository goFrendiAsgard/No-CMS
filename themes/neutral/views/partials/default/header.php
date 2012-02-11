<?php require_once BASEPATH."../themes/$site_theme/lib/function.php";?>
<img class="layout_float_left" src ="<?php echo base_url().'assets/nocms/images/No-CMS.png';?>" />
<div class="layout_float_left layout_large_padding">
	<h2><?php echo $site_name;?> - <?php echo $site_slogan;?></h2>
	<?php echo build_quicklink($quicklinks);?>
</div>
<div class="layout_clear"></div>