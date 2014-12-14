<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

// For every content of option tag, this will replace '&nbsp;' with ' '
function __ommit_nbsp($matches){
    return $matches[1].str_replace('&nbsp;', ' ', $matches[2]).$matches[3];
}
echo preg_replace_callback('/(<option[^<>]*>)(.*?)(<\/option>)/si', '__ommit_nbsp', $output);
?>
