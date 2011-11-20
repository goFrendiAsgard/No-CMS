<?php
    $return = array("success"=>TRUE, "messages"=>"");
    if(!is_writable('../application/config/database.php')){
        $return["success"] = FALSE;
        $return["messages"].= "application/config/database.php is not writeable<br />";
    }
    if(!is_writable('../application/config/routes.php')){
        $return["success"] = FALSE;
        $return["messages"].= "application/config/database.php is not writeable<br />";
    }
    
    echo json_encode($return);
?>
