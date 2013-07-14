<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install No-CMS</title>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .input-xlarge{
            height: 28px!important;
        }
        #btn-continue{
            display:none;
        }
        #span-finish-message{
            display:none;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" />
    <script type="text/javascript" src="<?php echo base_url('assets/nocms/js/jquery.tools.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
</head>
<body>
    <div class="row-fluid">
        <div class="navbar navbar-fixed-top">
          <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="#">Install No-CMS on your server</a>
            </div>
          </div>
        </div>
        <div class="span11 well">
            <?php
                if($success){
                    echo '<span id="span-process-message">Installing modules ... &nbsp;</span>';
                    echo '<img id="img-loader" src="'.base_url('modules/installer/assets/ajax-loader.gif').'" />';
                    echo '<span id="span-finish-message">Installation completed &nbsp;</span>';
                    echo '<a href="'.site_url().'" id="btn-continue" class="btn btn-primary btn-large">Continue</a>';
                }else{
                    echo '<div id="div-error-message" class="alert alert-block alert-error">
                            <strong>ERROR: </strong>Installation Failed.
                            <a href="'.site_url('installer').'" class="btn btn-primary btn-large">Go Back</a>
                        </div>';
                }
            ?>


        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            var modules =  ['nordrassil', 'blog'];
            var done = 0;
            for(var i=0; i<modules.length; i++){
                var module = modules[i];
                $.ajax({
                    'url': '<?php echo site_url() ?>/'+module+'/install/activate',
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
</body>