<?php
    //include the widget
    function build_widget($widgets, $slug = NULL){
        $str = '';
        foreach($widgets as $widget){
            if((isset($slug) && ($widget["slug"]==$slug)) || !isset($slug)){
                $str.= '<div id="layout_widget_container_'.$widget['widget_name'].'">';
                $str.= '<h4>'.$widget['title'].'</h4>';
                $str.= '<div class="widget_content"></div>';
                if($widget['is_static']){
                    $path=base_url().'index.php/main/show_static_widget/'.$widget['widget_id'].'?_only_content=true';
                }else{
                    $path=base_url().'index.php/'.$widget['url'].'?_only_content=true';
                }
                $str.= '
                    <script type="text/javascript">
                        $(document).ready(function(){

                            $.ajax({
                                url : "'.$path.'",
                                type: "POST",
                                data: {_only_content:true},
                                success : function(response){
                                    $("#layout_widget_container_'.$widget['widget_name'].' .widget_content").replaceWith(response);

                                }
                            });  

                        });

                    </script>
                ';
                $str.= '<br />';
                $str.= '</div>';
            }
        }
        return $str;
    }
    
    
    
    function build_menu($navigations, $path, $invisible = FALSE){
        if(count($navigations)==0) return '';//just exit and do nothing
        
        //check if there is navigation_array that match with array
        $class_invisible = $invisible? "invisible" : "";
        foreach($navigations as $navigation) {
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
        foreach($navigations as $navigation){
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
    
    
    
    function build_menu_path($path){
        $str = "";
        for($i=0; $i<count($path); $i++){
            $current_path = $path[$i];
            $str .= anchor($current_path['url'], $current_path['title']);
            if($i<count($path)-1){
                $str .= " >> ";
            }
        }
        return $str;
    }
?>
