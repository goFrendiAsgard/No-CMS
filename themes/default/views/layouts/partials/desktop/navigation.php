<style type="text/css">
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
    }
    li.layout_nav_hot{
        background-color : #AAAAAA;
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
        
        //
        $(".layout_nav li a.layout_expand").click(function(){
            $(this).parent(".layout_nav li").children(".layout_nav").toggle();
            if($(this).html()=="[+]"){$(this).html("[-]");}
            else{$(this).html("[+]");}
            return false;
        });
        
    });
</script>

<?php build_menu($navigations, $navigation_path); ?>

<?php
    function build_menu($navigation_array, $path, $invisible = FALSE){
        if(count($navigation_array)==0) return 0;//just exit and do nothing
        
        //check if there is navigation_array that match with array
        $class_invisible = $invisible? "invisible" : "";
        foreach($navigation_array as $navigation) {
            if($class_invisible == "") break;
            foreach($path as $current_path){
                if($navigation['navigation_name']==$current_path){
                    $class_invisible = "";
                    break;
                }
            }
        }
        $last_path = count($path)>0?$path[count($path)-1]:"";
        
        echo '<ul class="layout_nav '.$class_invisible.'">';
        foreach($navigation_array as $navigation){
            $layout_nav_hot = ($last_path == $navigation['navigation_name'])?'layout_nav_hot':'';
            
            echo '<li class ="'.$layout_nav_hot.'">';
            if(count($navigation['child'])>0) echo '<a href="#" class="layout_expand">[+]</a> ';
            
            echo anchor($navigation['url'], $navigation['title']);
            if(isset($navigation['description'])){
                echo '<div class="layout_nav_description invisible">Description : '.
                        $navigation['description'].'</div>';
            }
            build_menu($navigation['child'], $path, TRUE);
            echo '</li>';
        }
        echo '</ul>';
    }
?>
