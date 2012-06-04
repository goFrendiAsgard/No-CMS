<?php require_once BASEPATH."../themes/$site_theme/lib/function.php";?>
	<img class="layout_float_left" src ="<?php echo $site_logo;?>" />
    <div class="layout_float_left layout_large_left_padding">
    	<h1><?php echo $site_name;?></h1>
        <h2><?php echo $site_slogan;?></h2>
        <?php echo build_quicklink($quicklinks);?>
    </div>
    <div class="layout_clear"></div>