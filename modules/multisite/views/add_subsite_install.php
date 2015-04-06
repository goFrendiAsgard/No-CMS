<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="col-sm-12 well">
    <?php
        if($success){
            echo '<span id="span-process-message">Installation Complete ... &nbsp;</span>';
            echo '<a href="{{ site_url }}{{ module_path }}" id="btn-continue" class="btn btn-primary btn-lg">Continue</a>';
        }else{
            echo '<div id="div-error-message" class="alert alert-block alert-danger">
                    <strong>ERROR: </strong>Installation Failed.
                    <a href="{{ site_url }}{{ module_path }}" class="btn btn-primary btn-lg">Go Back</a>
                </div>';
        }
    ?>
</div>