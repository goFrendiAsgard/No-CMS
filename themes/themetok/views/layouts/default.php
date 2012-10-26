<?php require_once BASEPATH."../themes/".$cms['site_theme']."/lib/function.php";?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $template['title'];?></title>    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
  </head>
  <body>
    <div id="layout-widget" class="span3">                  
        <h4>WIDGET</h4><hr />
        <?php echo build_widget_html($cms['widget'], 'sidebar');?>
        <h4>ADVERTISEMENT</h4><hr />
        <?php echo build_widget_html($cms['widget'], 'advertisement');?>                
    </div><!--/#layout-widget-->
    
    
  </body>
</html>

