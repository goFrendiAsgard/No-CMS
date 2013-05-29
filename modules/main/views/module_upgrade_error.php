<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
	#message::empty{
        display:none;
    }
</style>
<h4>{{ language:lang_mae_install_failed }}</h4>
<div id="message" class="alert alert-error"><?php
		echo '{{ language:lang_mue_cannot_upgrade }} "<em>'.$module_name.'</em>" - "'.$module_path.'" ';
	    echo anchor('main/module_management','{{ language:lang_mae_back }}');
	    echo br();
	    echo $message;
	?></div>
