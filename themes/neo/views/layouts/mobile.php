<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $template['title'];?></title>
        <script type="text/javascript" src ="<?php echo base_url().'assets/jquery.js';?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'themes/neo/assets/mobile/style.css';?>"></link>
        <script type="text/javascript" src="<?php echo base_url().'themes/neo/assets/mobile/script.js';?>"></script>
    </head>
    <body>
        <div id="layout_header"><?php echo $template['partials']['header'];?></div>
        <div id="layout_center">
            <div id="layout_navigation" class="invisible">
                <div>
                    <a class="layout_button_widget" href="#">Show Widget</a> ||
                    <a class="layout_button_content" href="#">Show Content</a>
                </div>
                <?php echo $template['partials']['navigation'];?>
            </div>
            <div id="layout_content">                
                <div id="layout_nav_path">
                    <a class="layout_button_menu" href="#">Show Menu</a> ||
                    <a class="layout_button_widget" href="#">Show Widget</a> || 
                    You are here : <?php echo $template['partials']['navigation_path'];?>
                </div>
                <br />
                <?php echo $template['body'];?>
            </div>
            <div id="layout_widget" class="invisible">
                <div>
                    <a class="layout_button_menu" href="#">Show Menu</a> ||
                    <a class="layout_button_content" href="#">Show Content</a>
                </div>
                <?php echo $template['partials']['widget'] ?>
            </div>
        </div>
        <div id="layout_footer"><?php echo $template['partials']['footer'];?></div>
    </body>
</html>
