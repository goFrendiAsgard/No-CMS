<?php
    include('function.php');
    $db_server = get_input("db_server");
    $db_port = get_input("db_port");       
    $db_username = get_input("db_username");
    $db_password = get_input("db_password");
    $db_schema = get_input("db_schema");
    
    echo json_encode(check_db($db_server, $db_port, $db_username, $db_password));
?>
