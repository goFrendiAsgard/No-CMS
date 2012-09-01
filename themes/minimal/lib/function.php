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
    
    
    
	function build_menu($navigations, $path){
    	
    	$div_collapse = '<div class="layout_collapse_icon layout_expand"></div>';
    	$div_nothing = '<div class="layout_nothing_icon"></div>';
    	
        if(count($navigations)==0) return '';//just exit and do nothing 
        
        $html = '';
        
        $html.= '<ul class="dropdown">';
        foreach($navigations as $navigation){
            if($navigation['allowed'] || $navigation['have_allowed_children']){
                if($navigation['active']==1){
                    $html.= '<li>';
                    
                    $expand = '';
                    if(count($navigation['child'])>0){
                    	if($navigation['have_allowed_children']){
                    	    $expand.= $div_collapse;
                    	}else{
                    		$expand.= $div_nothing;
                    	}
                    }else{
                    	$expand.= $div_nothing;
                    }
                    
                    $pageLinkClass = 'layout_page_link';
                    if($navigation['have_allowed_children']){
                    	$pageLinkClass .= ' layout_have_child';
                    }else{
                    	$pageLinkClass .= ' layout_no_child';
                    }
                    if($navigation['allowed']){
                    	$html.= anchor($navigation['url'], 
                    				$navigation['title'].$expand, 
                    				array('class'=>$pageLinkClass));
                    }else{
                    	$html.= '<a class="'.$pageLinkClass.'">'.
                    		$navigation['title'].$expand.'</a>';
                    }
                    
                    
                    $html.= build_menu($navigation['child'], $path);
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
    		$html.= anchor($navigation['url'], $navigation['title']);
    		$html.= '</li>';
    	}
    	$html.= '<li>';
    	$html.= anchor("#layout-menu", "More...", array('class'=>'hidden-desktop'));
    	$html.= '</li>';
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
