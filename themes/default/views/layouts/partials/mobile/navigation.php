<?php
    function build_menu($navigation_array, $path, $invisible = FALSE){
        if(count($navigation_array)==0) return '';//just exit and do nothing
        
        //check if there is navigation_array that match with array
        $class_invisible = $invisible? "invisible" : "";
        foreach($navigation_array as $navigation) {
            if($class_invisible == "") break;
            foreach($path as $current_path){
                if($navigation['navigation_name']==$current_path['navigation_name']){
                    $class_invisible = "";
                    break;
                }
            }
        }
        $last_path = count($path)>0?$path[count($path)-1]['navigation_name']:"";
        
        $str = '';
        
        $str.= '<ul class="layout_nav '.$class_invisible.'">';
        foreach($navigation_array as $navigation){
            $layout_nav_hot = ($last_path == $navigation['navigation_name'])?'layout_nav_hot':'';
            
            $str.= '<li class ="'.$layout_nav_hot.'">';
            if(count($navigation['child'])>0) $str.= '<a href="#" class="layout_expand">[+]</a> ';
            
            $str.= anchor($navigation['url'], $navigation['title']);
            if(isset($navigation['description'])){
                $str.= '<div class="layout_nav_description invisible">Description : '.
                        $navigation['description'].'</div>';
            }
            $str.= build_menu($navigation['child'], $path, TRUE);
            $str.= '</li>';
        }
        $str.= '</ul>';
        
        return $str;
    }
    echo build_menu($navigations, $navigation_path);
?>
