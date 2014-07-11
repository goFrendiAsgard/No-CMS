<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
    <?php if($module_installed){ ?>
        #span-process-message{
            display:none;
        }
        #img-loader{
            display:none;
        }
    <?php } else { ?>
        #btn-continue{
            display:none;
        }
        #span-finish-message{
            display:none;
        }
    <?php } ?>
</style>
<div class="col-sm-12 well">
    <?php
        if($success){
            echo '<span id="span-process-message">Installing modules ... &nbsp;</span>';
            echo '<img id="img-loader" src="'.base_url('modules/installer/assets/ajax-loader.gif').'" />';
            echo '<span id="span-finish-message">Installation completed &nbsp;</span>';
            echo '<a href="{{ site_url }}{{ module_path }}" id="btn-continue" class="btn btn-primary btn-lg">Continue</a>';
        }else{
            echo '<div id="div-error-message" class="alert alert-block alert-danger">
                    <strong>ERROR: </strong>Installation Failed.
                    <a href="{{ site_url }}{{ module_path }}" class="btn btn-primary btn-lg">Go Back</a>
                </div>';
        }
    ?>
</div>
<?php if($success && !$module_installed){ ?>
    <script type="text/javascript">
        $(document).ready(function(){
            var modules =  ['blog', 'static_accessories', 'contact_us'];
            var done = 0;
            for(var i=0; i<modules.length; i++){
                var module = modules[i];
                $.ajax({
                    'url': '<?php echo site_url() ?>/'+module+'/install/activate/?__cms_subsite=<?php echo $subsite; ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'async': true,
                    'data':{
                            'silent' : true,
                            'identity': '<?php echo $admin_user_name;?>',
                            'password': '<?php echo $admin_password;?>'
                        },
                    'success': function(response){
                            if(!response['success']){
                                console.log('error installing '+response['module_path']);
                            }
                        },
                    'error': function(response){
                            console.log('error send request');
                        },
                    'complete' : function(){
                            done ++;
                            if(done == modules.length){
                                $('#btn-continue').show();
                                $('#img-loader').hide();
                                $('#span-process-message').hide();
                                $('#span-finish-message').show();

                            }
                        }
                });
            }
        });
    </script>
<?php } ?>