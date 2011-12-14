<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $template['title'];?></title>
        <script type="text/javascript" src ="<?php echo base_url().'assets/jquery.js';?>"></script>
        <style type="text/css">
            body{
                padding : 5px;
            }
            div#layout_header, div#layout_content, div#layout_footer{
                margin : 0px;
            }
            div#layout_header{
                background-color : #CFCFCF;
                padding : 5px;
                font-size : small;
            }
            div#layout_navigation{ 
                min-height : 300px;
            }
            div#layout_content{
                min-height : 300px;
                padding : 5px;
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
                margin : 0px;
            }
            .layout_nav li{
                background-color : #DFDFDF;
                border : 1px solid white;
                padding : 3px;
                margin : 3px;
            }
            #layout_header a, #layout_footer a, #layout_center a{
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
                    $(this).parent(".layout_nav li").children(".layout_nav").toggle();
                    if($(this).html()=="[+]"){$(this).html("[-]");}
                    else{$(this).html("[+]");}
                    return false;
                });
                
                $(".layout_button_menu").click(function(){
                    $("#layout_content").hide();
                    $("#layout_widget").hide();
                    $("#layout_navigation").show();                    
                    return false;
                });
                
                $(".layout_button_widget").click(function(){
                    $("#layout_content").hide();
                    $("#layout_navigation").hide();
                    $("#layout_widget").show();                    
                    return false;
                });
                
                $(".layout_button_content").click(function(){
                    $("#layout_navigation").hide();
                    $("#layout_widget").hide();
                    $("#layout_content").show();                    
                    return false;
                });
                
                $(".layout_nav li a:not(.layout_expand)").click(function(){                    
                    $("#layout_navigation").hide();
                    $("#layout_widget").hide();
                    $("#layout_content").show();
                    return false;
                });

            });
        </script>
    </head>
    <body>
        <div id="layout_header"><?php echo $template['partials']['header'];?></div>
        <div id="layout_center">
            <div id="layout_navigation" class="invisible">
                <div>
                    <a class="layout_button_widget" href="#">Show Widget</a> ||
                    <a class="layout_button_content" href="#">Show Content</a>
                </div>
                <?php echo $template['partials']['navigation'];?>
            </div>
            <div id="layout_content">                
                <div id="layout_nav_path">
                    <a class="layout_button_menu" href="#">Show Menu</a> ||
                    <a class="layout_button_widget" href="#">Show Widget</a> || 
                    You are here : <?php echo $template['partials']['navigation_path'];?>
                </div>
                <br />
                <?php echo $template['body'];?>
            </div>
            <div id="layout_widget" class="invisible">
                <div>
                    <a class="layout_button_menu" href="#">Show Menu</a> ||
                    <a class="layout_button_content" href="#">Show Content</a>
                </div>
                <?php echo $template['partials']['widget'] ?>
            </div>
        </div>
        <div id="layout_footer"><?php echo $template['partials']['footer'];?></div>
    </body>
</html>
