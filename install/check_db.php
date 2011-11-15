<?php
    include('function.php');
    $db_server = get_input("db_server");
    $db_port = get_input("db_port");       
    $db_username = get_input("db_username");
    $db_password = get_input("db_password");
    $db_schema = get_input("db_schema");
    
    $result = array(
        "success"=>true,
        "message"=>""
    );
    
    $db_connection = @mysql_connect($db_server.':'.$db_port,$db_username,$db_password);
    if(!$db_connection){
        $result["success"] = false;
        $result["message"] .= "Cannot connect to database<br />";
    }else{
        $rslt = @mysql_query('SHOW VARIABLES LIKE \'have_innodb\';', $db_connection);
        $row = mysql_fetch_array($rslt);
        $innodb = $row['Value'];
        if(!$innodb){
            $result["success"] = false;
            $result["message"] .= "Your database doesn't support Innodb<br />";
        }
    }
    echo json_encode($result);
?>
