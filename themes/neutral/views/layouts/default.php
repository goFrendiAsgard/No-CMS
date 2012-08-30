<?php require_once BASEPATH."../themes/".$cms['site_theme']."/lib/function.php";?>
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
    <?php 
	    $asset = new CMS_Asset();
	    $asset->add_css(base_url("themes/".$cms['site_theme']."/assets/default/style.css"));
	    $asset->add_cms_css('bootstrap/css/bootstrap-all.min.css');	
	    echo $asset->compile_css();
	?>
	<?php 
	    $asset = new CMS_Asset();
	    $asset->add_cms_js('nocms/js/jquery.js');
	    $asset->add_cms_js("bootstrap/js/bootstrap-all.min.js");
	    $asset->add_string_js(build_widget_js($cms['widget'], 'sidebar'));
	    $asset->add_string_js(build_widget_js($cms['widget'], 'advertisement'));
	    $asset->add_js(base_url("themes/".$cms['site_theme']."/assets/default/script.js"));
	    echo $asset->compile_js(TRUE);
	?>
	
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
    
  </head>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
      	<div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">
          	<img src ="<?php echo $cms['site_logo'];?>" style="max-height:30px; max-width:30px;" />          	
          </a>          
          <div class="nav-collapse">
            <?php echo build_quicklink($cms['quicklinks']);?>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->       
      </div><!--/.navbar-innder -->
    </div><!--/.nav .navbar-fixed-top -->
    
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
		        </div><!--/#layout-content-->       
		        <div id="layout-widget" class="span3">
		            <h4>WIDGET</h4><hr />
                    <?php echo build_widget_html($cms['widget'], 'sidebar');?>
                    <h4>ADVERTISEMENT</h4><hr />
                    <?php echo build_widget_html($cms['widget'], 'advertisement');?>	            
		        </div><!--/#layout-widget-->
	        </div><!--/#layout-content-widget-->
        </div><!--/#layout-banner-content-widget-->  
        
        <div id="layout-sidebar" class="span3">
          <div class="well sidebar-nav">
          	<a name="layout-menu"></a>          	
          	<?php echo build_menu($cms['navigations'], $cms['navigation_path']);?>     
          </div><!--/.well -->
        </div><!--/#layout-sidebar-->
              
      </div><!--/row-->
      <hr>
      <footer>
        <?php echo $cms['site_footer'];?>
      </footer>
    </div><!--/.fluid-container-->

    
    <!-- Placed at the end of the document so the pages load faster -->
    
    
  </body>
</html>

