<?php
    //a good guy from forum write it for us, thx :D
    function get_content($url) {
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_HEADER, 0);		
	ob_start();
	curl_exec ($ch);
	curl_close ($ch);
	$string = ob_get_contents();
	ob_end_clean();  
	return $string;   
    }
   
    //include the widget
    function build_widget($widget_array){
        foreach($widget_array as $widget){
            
            echo '<div id="layout_widget_container_'.$widget['widget_name'].'">';
            echo '<h4>'.$widget['title'].'</h4>';
            if($widget['is_static']){
                $path=base_url().'index.php/main/show_static_widget/'.$widget['widget_id'].'?_only_content=true';
            }else{
                $path=base_url().'index.php/'.$widget['url'].'?_only_content=true';
            }
            echo get_content($path);            
            echo '<br />';
            echo'</div>';
        }
    }
    build_widget($widget);
?>
