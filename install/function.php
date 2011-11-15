<?php

function get_input($key){
    $result = isset($_POST[$key])?$_POST[$key]:"";
    return $result;
}

function get_secure_input($key){
    return addslashes(get_input($key));
}

function replace($str,$search,$replace){
    if(count($search)==count($replace)){
        for($i=0; $i<count($search); $i++){
            $str = str_replace($search, $replace, $str);
        }
    }
    return $str;
}

?>
