<!DOCTYPE html>
<?php require_once BASEPATH."../themes/$site_theme/lib/function.php";?>
<html>
    <head>
        <title><?php echo $template['title'];?></title>
        <meta name="HandheldFriendly" content="true" />
        <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" />
        <script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()."themes/$site_theme/assets/mobile/style.css";?>"></link>
        <script type="text/javascript" src="<?php echo base_url()."themes/$site_theme/assets/mobile/script.js";?>"></script>
    </head>
    <body>
        <div id="layout_header">
            <?php echo $template['partials']['header'];?>
            <div>
                <a class="layout_button_menu layout_button" href="#">Menu</a>
                <a class="layout_button_widget layout_button" href="#">Widget</a> 
                <a class="layout_button_content layout_button" href="#">Content</a>
            </div>
        </div>
        <div id="layout_center">             
            <div id="layout_navigation" class="invisible">
                <?php echo $template['partials']['navigation'];?>
            </div>
            <div id="layout_widget" class="invisible">
                <?php echo $template['partials']['widget'] ?>
            </div>
            <div id="layout_content">                
                <div id="layout_nav_path">                    
                    You are here : <?php echo $template['partials']['navigation_path'];?>
                </div>
                <br />
                <?php echo $template['body'];?>
            </div> 
            <div class="layout_clear"></div>
        </div>
        <div id="layout_footer"><?php echo $template['partials']['footer'];?></div>
    </body>
</html>
