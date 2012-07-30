<?php require_once BASEPATH."../themes/".$cms['site_theme']."/lib/function.php";?>

        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">
          	<img src ="<?php echo $cms['site_logo'];?>" style="max-height:30px; max-width:30px;" />  
          	<span class="visible-desktop"><?php echo $cms['site_name']. ' - '.$cms['site_slogan'];?></span>        	
          </a>          
          <div class="nav-collapse">
            <?php echo build_quicklink($cms['quicklinks']);?>
          </div><!--/.nav-collapse -->
        </div>
