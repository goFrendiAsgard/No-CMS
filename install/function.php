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

function check_db($server, $port, $username, $password){
    $return = array(
        "success"=>true,
        "message"=>""
    );
    
    $connection = @mysql_connect($server.':'.$port,$username,$password);
    if(!$connection){
        $return["success"] = false;
        $return["message"] .= "Cannot connect to database<br />";
    }else{
        $result = @mysql_query('SHOW VARIABLES LIKE \'have_innodb\';', $connection);
        $row = mysql_fetch_array($result);
        $innodb = $row['Value'];
        if(!$innodb){
            $return["success"] = false;
            $return["message"] .= "Your database doesn't support Innodb<br />";
        }
    }
    
    return $return;
}

?>
