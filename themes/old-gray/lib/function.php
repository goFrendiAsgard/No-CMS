<?php
    //include the widget
    function build_widget($widgets, $slug = NULL){
    	// slug set, but unavailable
        if(isset($slug) && !isset($widgets[$slug])) return "";
        // slug not set
        if(!isset($slug)){
        	$tmp_widgets = array();
        	foreach($widgets as $widget_slug){
        		foreach($widget_slug as $widget){
        			$tmp_widgets[] = $widget;
        		}
        	}
        	$widgets = $tmp_widgets;
        }else if(isset($slug)){        
        	$widgets = $widgets[$slug];
        }
        
        $html = "";
        foreach($widgets as $widget){
        		
                $html.= '<div id="layout_widget_container_'.$widget['widget_name'].'" class="layout_widget_container">';
                $html.= '<h5>'.$widget['title'].'</h5>';
                $html.= '<div class="widget_content">'.$widget['content'].'</div>';
                $html.= '<br />';
                $html.= '<br />';
                $html.= '</div>';
        }
        return $html;
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
        
        $html = '';
        
        $html.= '<ul class="layout_nav '.$class_invisible.'">';
        foreach($navigations as $navigation){
            if($navigation['allowed'] || $navigation['have_allowed_children']){
                $layout_nav_hot = ($last_path == $navigation['navigation_name'])?'layout_nav_hot':'';
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

                        if($navigation['have_allowed_children']){
                            if($in_path){
                                $html.= '<a href="#" class="layout_expand">[-]</a> ';
                            }else{
                                $html.= '<a href="#" class="layout_expand">[+]</a> ';
                            } 
                        }               
                    }

                    $pageLinkClass = 'layout_page_link';
                    if($navigation['have_allowed_children']){
                        $pageLinkClass .= ' layout_have_child';
                    }else{
                        $pageLinkClass .= ' layout_no_child';
                    }
                    if($navigation['allowed']){
                        $html.= anchor($navigation['url'], $navigation['title'], array('class'=>$pageLinkClass));
                    }else{
                        $html.= $navigation['title'];
                    }
                    $html.= '<div class="layout_clear"></div>';
                    if(isset($navigation['description'])){
                        $html.= '<div class="layout_nav_description invisible">Description : '.
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
    	
    	$html = '';
    	foreach($navigations as $navigation){
    		$html.= anchor($navigation['url'], $navigation['title'], array('class'=>'layout_quicklink layout_button'));
    		$html .='&nbsp;';
    	}    	
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
