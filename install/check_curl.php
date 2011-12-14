<?php

$return = array();
if(function_exists('curl_version') == "Enabled"){
    $return["success"] = true;
    $return["message"] = "";
}else{
    $return["success"] = false;
    $return["message"] = "CURL is not enabled, please install CURL first";
}
echo json_encode($return);
?>
