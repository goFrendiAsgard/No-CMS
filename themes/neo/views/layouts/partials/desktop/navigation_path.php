<?php
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
    echo build_menu_path($navigation_path);
?>
