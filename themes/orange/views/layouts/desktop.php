<!DOCTYPE html>
<?php require_once BASEPATH."../themes/$site_theme/lib/function.php";?>
<html>
    <head>
        <title><?php echo $template['title'];?></title>
        <script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()."themes/$site_theme/assets/desktop/style.css";?>"></link>
        <script type="text/javascript" src="<?php echo base_url()."themes/$site_theme/assets/desktop/script.js";?>"></script>
    </head>
    <body>       
        <div id="layout_header">
        	<?php echo $template['partials']['header'];?>
        	<?php echo build_quicklink($quicklinks);?>
        </div>
        <div id="layout_center">
            <div id="layout_navigation"><?php echo $template['partials']['navigation'];?></div>
            <div id="layout_widget">WIDGET<hr /><?php echo $template['partials']['widget'] ?></div>
            <div id="layout_content">
                <div id="layout_nav_path">You are here : <?php echo $template['partials']['navigation_path']?></div>
                <br />
                <?php echo $template['body'];?>
            </div>
            <div class="layout_clear"></div>
        </div>
        <div id="layout_footer"><?php echo $template['partials']['footer'];?></div> 
    </body>
</html>