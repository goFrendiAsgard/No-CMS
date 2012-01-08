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
            if(count($navigation['child'])>0){
                $in_path = false;
                if($last_path != $navigation['navigation_name']){
                    foreach($path as $current_path){
                        if($current_path['navigation_name']==$navigation['navigation_name']){
                            $in_path = true;
                            break;
                        }
                    }
                }
                
                if($in_path){
                    $str.= '<a href="#" class="layout_expand">[-]</a> ';
                }else{
                    $str.= '<a href="#" class="layout_expand">[+]</a> ';
                }                
            }
            
            $pageLinkClass = 'layout_page_link';
            if(count($navigation['child'])>0){
                $pageLinkClass .= ' layout_have_child';
            }else{
                $pageLinkClass .= ' layout_no_child';
            }            
            if($navigation['is_static']){
                $str.= anchor(base_url().'index.php/main/show_static_page/'.$navigation['navigation_id'], $navigation['title'], array('class'=>$pageLinkClass));
            }else{
                $str.= anchor($navigation['url'], $navigation['title'], array('class'=>$pageLinkClass));
            }
            $str.= '<div class="layout_clear"></div>';
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
