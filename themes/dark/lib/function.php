<?php
    //include the widget
    function build_widget_html($widgets, $slug = NULL){
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
    
	function build_left_nav($navigations, $first = TRUE){
		if(count($navigations) == 0) return '';
		if($first){
			$style = 'display: block; position: static; border:none; margin:0px; background-color:light-gray; width:100%';	
		}else{
			$style = 'background-color:light-gray;';
		}
		$result = '<ul  class="dropdown-menu nav nav-pills nav-stacked" style="'.$style.'">';
		foreach($navigations as $navigation){
			if(($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']){
				// make text
				if($navigation['allowed'] && $navigation['active']){
					$text = '<a class="dropdown-toggle" href="'.site_url($navigation['url']).'">'.$navigation['title'].'</a>';
				}else{
					$text = $navigation['title'];
				}
				
				if(count($navigation['child'])>0 && $navigation['have_allowed_children']){
					$result .= '<li class="dropdown-submenu">'.$text.build_left_nav($navigation['child'], FALSE).'</li>';
				}else{
					$result .= '<li>'.$text.'</li>';
				}				
			}	
		}
		$result .= '</ul>';
		return $result;
	}
	
	function build_btn_nav($navigations, $caption = 'More', $first = TRUE){
		if(count($navigations) == 0) return '';
		$result = '<ul  class="dropdown-menu">';
		foreach($navigations as $navigation){
			if(($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']){
				// make text
				if($navigation['allowed'] && $navigation['active']){
					$text = '<a href="'.site_url($navigation['url']).'">'.$navigation['title'].'</a>';
				}else{
					$text = '<a href="#">'.$navigation['title'].'</a>';
				}
				
				if(count($navigation['child'])>0 && $navigation['have_allowed_children']){
					$result .= '<li class="dropdown-submenu">'.$text.build_btn_nav($navigation['child'], $caption, FALSE).'</li>';
				}else{
					$result .= '<li>'.$text.'</li>';
				}				
			}	
		}
		$result .= '</ul>';
		if($first){
			$result = '<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$caption.
				' <span class="caret"></span></a>'.
				$result;
			
		}
		return $result;
	}
	
	
    
    function build_quicklink($quicklinks, $navigations = array()){
    	if(count($quicklinks)==0) return '';//just exit and do nothing
    	
    	$html = '<ul class="nav" id="main-menu-left">';
		
		if(count($navigations)>0){
			$html.= '<li class="dropdown">';
			$html.= build_btn_nav($navigations, 'Complete Menu');
			$html.= '</li>';
		}
		    	
    	foreach($quicklinks as $quicklink){
    		$html.= '<li>';
    		$html.= anchor($quicklink['url'], $quicklink['title']);
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
