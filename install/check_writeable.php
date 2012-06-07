<?php
    $return = array("success"=>TRUE, "message"=>"");
    if(!is_writable('../application/config/database.php')){
        $return["success"] = FALSE;
        $return["message"].= "application/config/database.php is not writeable<br />";
    }
    if(!is_writable('../application/config/routes.php')){
        $return["success"] = FALSE;
        $return["message"].= "application/config/routes.php is not writeable<br />";
    }
    if(!is_writable('../application/config/config.php')){
    	$return["success"] = FALSE;
    	$return["message"].= "application/config/config.php is not writeable<br />";
    }
    
    echo json_encode($return);
?>
