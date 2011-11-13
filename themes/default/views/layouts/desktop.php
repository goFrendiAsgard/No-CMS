<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $template['title'];?></title>
        <script type="text/javascript" src ="<?php echo base_url().'themes/default/assets/jquery-1.7.min.js';?>"></script>
        <style type="text/css">
            body{
                padding : 5px;
            }
            div#layout_navigation{
                z-index : 100;
            }
            div#layout_header, div#layout_content, div#layout_footer{
                z-index : 0;
            }
            div#layout_header{
                height : 125px;
                background-color : #CFCFCF;
                padding : 20px;
                font-size : small;
            }
            div#layout_navigation{ 
                position : absolute;
                top : 170px;
                width : 300px;
            }
            div#layout_content{
                margin-left : 300px;
                min-height : 300px;
                padding : 5px;
            }
            div#layout_footer{
                text-align : center;
                top : 550px;
                font-size: x-small;
                background-color : #CFCFCF;
                padding : 10px;
            }
            div#layout_center{
                font-size : small;
                background-color : #E0E0E0;
            }
        </style>
    </head>
    <body>
        <div id="layout_header"><?php echo $template['partials']['header'];?></div>
        <div id="layout_center">
            <div id="layout_navigation"><?php echo $template['partials']['navigation'];?></div>
            <div id="layout_content"><?php echo $template['body'];?></div>
        </div>
        <div id="layout_footer"><?php echo $template['partials']['footer'];?></div>
    </body>
</html>
