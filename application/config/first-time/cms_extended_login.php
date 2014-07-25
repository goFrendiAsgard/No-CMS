<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This function is used as a backup when "normal" login failed. This function should return TRUE, array or FALSE
 * If the return value is TRUE or array, the user_name will be registered into No-CMS's user table and the login will be succeed
 * otherwise nothing happens, and the login will be failed.
 **/
function extended_login($user_name, $password){
    // if the login should be success, you can
    //  - return TRUE
    //  - return array('user_real_name', 'user_email')
    return FALSE;
}