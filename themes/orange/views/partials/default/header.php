<?php require_once BASEPATH . "../themes/$site_theme/lib/function.php"; ?>
    <img class="layout_float_left layout_margin_5" src ="<?php echo $site_logo; ?>" />
    
    <div class="layout_float_left">
        <h1><?php echo $site_name; ?></h1>
        <h2><?php echo $site_slogan; ?></h2>
    </div>
    <div class="layout_clear"></div>
    <?php echo build_quicklink($quicklinks); ?>