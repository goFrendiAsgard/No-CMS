<?php
    //include the widget
    function build_widget($widgets, $slug = NULL){
        $html = '';
        $js = '';
        
        foreach($widgets as $widget){
            if((isset($slug) && ($widget["slug"]==$slug)) || !isset($slug)){
                $html.= '<div id="layout_widget_container_'.$widget['widget_name'].'">';
                $html.= '<h5>'.$widget['title'].'</h5>';
                $html.= '<div class="widget_content"></div>';
                $html.= '<br />';
                $html.= '<br />';
                $path=base_url().'index.php/main/show_widget/'.$widget['widget_id'].'?_only_content=true';
                $js.= '
                        $.ajax({
                            url : "'.$path.'",
                            type: "POST",
                            data: {_only_content:true},
                            success : function(response){
                                $("#layout_widget_container_'.$widget['widget_name'].' .widget_content").replaceWith(response);

                            }
                        }); 
                ';
                $html.= '</div>';
            }
        }
        $js = '
            <script type="text/javascript">
                $(document).ready(function(){
                    '.$js.'
                });
            </script>
        ';
        return $js.$html;
    }
    
    
    
    function build_menu($navigations, $path, $invisible = FALSE){
        if(count($navigations)==0) return '';//just exit and do nothing
        
        //check if there is navigation_array that match with array
        $class_invisible = $invisible? "not_shown" : "";
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
        
        $html = '';
        
        $html.= '<ul class="layout_nav nav nav-list '.$class_invisible.'">';
        foreach($navigations as $navigation){
            if($navigation['allowed'] || $navigation['have_allowed_children']){
                $layout_nav_hot = ($last_path == $navigation['navigation_name'])?'active':'';
                if($navigation['active']==1){
                    $html.= '<li class ="'.$layout_nav_hot.'">';
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
                            $html.= '<a href="#" class="layout_expand">[-]</a> ';
                        }else{
                            $html.= '<a href="#" class="layout_expand">[+]</a> ';
                        }                
                    }

                    $pageLinkClass = 'layout_page_link';
                    if(count($navigation['child'])>0){
                        $pageLinkClass .= ' layout_have_child';
                    }else{
                        $pageLinkClass .= ' layout_no_child';
                    }
                    if($navigation['allowed']){
                        $html.= anchor($navigation['url'], $navigation['title'], array('class'=>$pageLinkClass));
                    }else{
                        $html.= $navigation['title'];
                    }
                    if(isset($navigation['description'])){ 
                        $html.= '<div class="layout_nav_description not_shown">Description : '.
                                $navigation['description'].'</div>';
                    }

                    $html.= build_menu($navigation['child'], $path, TRUE);
                    $html.= '</li>';
                }
            }
        }
        $html.= '</ul>';
        
        return $html;
    }
    
    function build_quicklink($navigations){
    	if(count($navigations)==0) return '';//just exit and do nothing
    	
    	$html = '<ul class="nav">';
    	foreach($navigations as $navigation){
    		$html.= '<li>';
    		$html.= anchor($navigation['url'], $navigation['title'], array('class'=>'layout_quicklink layout_button'));
    		$html.= '</li>';
    	}    
    	$html .= '</ul>';	
    	return $html;
    	
    }
    
    function build_menu_path($path){
        $html = "";
        for($i=0; $i<count($path); $i++){
            $current_path = $path[$i];
            $html .= anchor($current_path['url'], $current_path['title']);
            if($i<count($path)-1){
                $html .= " >> ";
            }
        }
        return $html;
    }
?>
