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
    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" />
</head>
<body>
    <div class="navbar navbar-fixed-top navbar-default">
        <div class="navbar-header"><a class="navbar-brand" href="#">Installing No-CMS</a></div>
    </div>
    <div class="col-sm-12 well">
        <?php
            if($success){
                echo '<span id="span-process-message">Installation complete. &nbsp;</span>';
                echo '<a href="'.site_url().'" id="btn-continue" class="btn btn-primary btn-lg">Continue</a>';
            }else{
                echo '<div id="div-error-message" class="alert alert-block alert-danger">
                        <strong>ERROR: </strong>Installation Failed.
                        <a href="'.site_url('installer').'" class="btn btn-primary btn-lg">Go Back</a>
                    </div>';
            }
        ?>
    </div>
    <script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/'.JQUERY_FILE_NAME); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
</body>
