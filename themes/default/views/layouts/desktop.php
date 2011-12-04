<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $template['title'];?></title>
        <script type="text/javascript" src ="<?php echo base_url().'assets/jquery.js';?>"></script>
        <style type="text/css">
            body{
                padding : 5px;
            }
            div#layout_navigation, div#layout_widget{
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
            div#layout_navigation {
                float:left;
                width:300px;
                padding : 10px;
            }
            div#layout_widget {
                float:right;
                width:200px;
                padding : 10px;
            }
            div#layout_content {
                margin-left : 300px;
                margin-right:220px;
                padding : 20px;
            }
            div#layout_footer{
                text-align : center;
                font-size: x-small;
                background-color : #CFCFCF;
                padding : 10px;
            }
            div#layout_center{
                font-size : small;
                background-color : #E0E0E0;
                margin-top : 0px;
                
                padding : 5px;
                position:relative;	/* This fixes the IE7 overflow hidden bug */
                clear:both;
            }
            .invisible{
                display : none;
            }
            .layout_nav_description{
                position:absolute;
                background-color:#AAAA88;
                padding: 5px 5px 5px 5px;
                margin : 10px;
                font-size: small;
                min-height : 25px;
            }
            .layout_nav{
                list-style-type: none;
                padding : 0px;
            }
            .layout_nav li{
                background-color : #DFDFDF;
                border : 1px solid white;
                padding : 3px;
                margin : 3px;
            }
            .layout_nav a{
                font-family : serif;
                color : black;
            }
            li.layout_nav_hot{
                background-color : #AAAAAA;
            }
            #layout_nav_path a{
                font-family : serif;
                color : black;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function(){

                //view description
                $(".layout_nav li").mouseenter(function(){
                    $(this).children(".layout_nav_description").show();
                });
                $(".layout_nav li a").mouseenter(function(){
                    $(this).parent(".layout_nav li").children(".layout_nav_description").show();
                });

                //hide description
                $(".layout_nav li").mouseout(function(){
                    $(this).children(".layout_nav_description").hide();
                });
                $(".layout_nav li a").mouseout(function(){
                    $(this).parent(".layout_nav li").children(".layout_nav_description").hide();
                });

                //expand and collapse
                $(".layout_nav li a.layout_expand").click(function(){
                    //expand or collapse
                    $(this).parent(".layout_nav li").children(".layout_nav").toggle();
                    if($(this).html()=="[+]"){$(this).html("[-]");}
                    else{$(this).html("[+]");}
                    
                    calibrateHeight();   
                    
                    return false;
                });
                
                calibrateHeight();

            });
            
            $(document).ajaxComplete(function(){
                calibrateHeight();
            });
            
            function calibrateHeight(){
                var navigationHeight = $("div#layout_navigation").height();
                var contentHeight = $("div#layout_content").height();
                var widgetHeight = $("div#layout_widget").height();
                var centerHeight = 0;
                if((navigationHeight>=contentHeight) && (navigationHeight>=widgetHeight)){
                    centerHeight = navigationHeight;
                }else if((widgetHeight>=contentHeight) && (widgetHeight>=navigationHeight)){
                    centerHeight = widgetHeight;
                }else{
                    centerHeight = contentHeight;
                }
                
                $("div#layout_center").height(centerHeight+20);
            }
        </script>
    </head>
    <body>
        <div id="layout_header"><?php echo $template['partials']['header'];?></div>
        <div id="layout_center">
            <div id="layout_navigation"><?php echo $template['partials']['navigation'];?></div>
            <div id="layout_widget">WIDGET<hr /><?php echo $template['partials']['widget'] ?></div>
            <div id="layout_content">
                <div id="layout_nav_path">You are here : <?php echo $navigation_path;?></div>
                <br />
                <?php echo $template['body'];?>
            </div>
        </div>
        <div id="layout_footer"><?php echo $template['partials']['footer'];?></div>       
    </body>
</html>
