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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."themes/".$cms['site_theme']."/assets/default/style.css";?>"></link>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()."assets/bootstrap/css/bootstrap-all.min.css";?>"></link>
	
	
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
              
        <div id="layout-banner-content-widget" class="span9">
        	<div id="layout-banner" class="hero-unit row-fluid hidden-phone span12">
	        	<div class="span2">
	        		<img src ="<?php echo $cms['site_logo'];?>"" /> 
	        	</div>        	
	            <div class="span10">
		            <h1><?php echo $cms['site_name'];?></h1>
		            <p><?php echo $cms['site_slogan'];?></p>
	            </div>
	            <div class="clear"></div>            
	        </div><!-- /.layout-banner -->
        	<div id="layout-content-widget" class="span12">
	        	<div id="layout-content" class="span9">
		            <?php echo $template['body'];?>
		            <div class="clear"></div>
		        </div><!--/span-->       
		        <div id="layout-widget" class="span3">
		            <?php echo $template['partials']['right'] ?>	            
		        </div><!--/span-->
	        </div>
        </div><!--/content-->  
        
        <div id="layout-sidebar" class="span3">
          <div class="well sidebar-nav">
          	<a name="layout-menu"></a>
          	<?php echo $template['partials']['left'];?>     
          </div><!--/.well -->
        </div><!--/#layout-sidebar-->
              
      </div><!--/row-->
      <hr>
      <footer>
        <?php echo $template['partials']['footer'];?>
      </footer>
    </div><!--/.fluid-container-->

    
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url()."assets/bootstrap/js/bootstrap-all.min.js";?>"></script>
    <script src="<?php echo base_url()."themes/".$cms['site_theme']."/assets/default/";?>script.js"></script>
  </body>
</html>

