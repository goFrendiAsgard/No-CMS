<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $template['title'];?></title>
        <script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url()."themes/$site_theme/assets/default_backend/style.css";?>"></link>
        <script type="text/javascript" src="<?php echo base_url()."themes/$site_theme/assets/default_backend/script.js";?>"></script>
    </head>
    <body>       
        
        <div id="layout_header"><?php echo $template['partials']['header'];?></div>
        
        <div id="layout_center">
            <div id="layout_left"><?php echo $template['partials']['left'];?></div>
            <div id="layout_right">WIDGET<hr /><?php echo $template['partials']['right'] ?></div>
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
