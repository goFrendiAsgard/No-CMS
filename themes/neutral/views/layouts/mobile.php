<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $template['title'];?></title>
        <link rel="icon" href="<?php echo $site_favicon;?>">
        <meta name="HandheldFriendly" content="true" />
        <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no" />
        <script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()."themes/$site_theme/assets/mobile/style.css";?>"></link>
        <script type="text/javascript" src="<?php echo base_url()."themes/$site_theme/assets/mobile/script.js";?>"></script>
    </head>
    <body>
        <div id="layout_header"><?php echo $template['partials']['header'];?></div>
        
        <div id="layout_center">             
            <div id="layout_left" class="invisible"><?php echo $template['partials']['left'];?></div>
            <div id="layout_right" class="invisible"><?php echo $template['partials']['right'] ?></div>
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
