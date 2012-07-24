<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $template['title'];?></title>    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link rel="icon" href="<?php echo $cms['site_favicon'];?>">

    <!-- Le styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."themes/".$cms['site_theme']."/assets/default/style-all.min.css";?>"></link>
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo $cms['site_favicon'];?>">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url()."themes/".$cms['site_theme']."/assets/default/";?>ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url()."themes/".$cms['site_theme']."/assets/default/";?>ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url()."themes/".$cms['site_theme']."/assets/default/";?>ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url()."themes/".$cms['site_theme']."/assets/default/";?>ico/apple-touch-icon-57-precomposed.png">
    <script type="text/javascript" src ="<?php echo base_url().'assets/nocms/js/jquery.js';?>"></script>
    <?php
    	echo build_widget_js($cms['widget'], 'sidebar');
    	echo build_widget_js($cms['widget'], 'advertisement');
    ?>
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
      	<?php echo $template['partials']['header'];?>        
      </div>
    </div>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3 visible-desktop">
          <div class="well sidebar-nav">
          	<?php echo $template['partials']['left'];?>            
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span7">
            <?php echo $template['body'];?>
        </div><!--/span-->
        <div class="span2 hidden-phone">
            <?php echo $template['partials']['right'] ?>
        </div><!--/span--> 
      </div><!--/row-->

      <hr>

      <footer>
        <?php echo $template['partials']['footer'];?>
      </footer>

    </div><!--/.fluid-container-->

    
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url()."themes/".$cms['site_theme']."/assets/default/";?>script-all.min.js"></script>
    <!-- Let it be peace between bootstrap and flexigrid -->
  </body>
</html>

