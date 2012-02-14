<?php require_once BASEPATH."../themes/$site_theme/lib/function.php";?>
<head><link rel="icon" href="<?php echo $site_favicon;?>"></head>
<body>
	<img class="layout_float_left" src ="<?php echo $site_logo;?>" />
	<div class="layout_float_left layout_large_padding">
		<h2><?php echo $site_name;?> - <?php echo $site_slogan;?></h2>
		<?php echo build_quicklink($quicklinks);?>
	</div>
	<div class="layout_clear"></div>
</body>