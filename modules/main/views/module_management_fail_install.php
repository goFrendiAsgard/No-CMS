<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
	#message:not(:empty){
        background-color:#FFCACA;
	    padding: 5px 5px 5px 5px;
	    margin : 10px;
	    font-size: small;
	    min-height : 25px;
	    border-radius:5px;
	    -moz-border-radius:5px;
	    -moz-box-shadow:    1px 1px 5px 6px #ccc;
	    -webkit-box-shadow: 1px 1px 5px 6px #ccc;
	    box-shadow:         1px 1px 5px 6px #ccc;      
        max-width : 400px;
    }
</style>
<h4>Installation Failed</h4>
<div id="message">
	<?php
		echo 'Cannot install "<em>'.$module_name.'</em>" on "'.$module_path.'" ';
	    echo anchor('main/module_management','Back');
	    echo br();
	    if($undefinedName_error){
	    	echo '<p>';
	    	echo 'Module name is undefined';
	    	echo '</p>';
	    }
	    if($alreadyInstalled_error){
	    	echo '<p>';
	    	echo 'The module has been installed';
	    	echo '</p>';
	    }
	    if($dependencies_error){
		    echo '<p>';
		    echo 'There are unsatisfied dependencies';
		    echo br();
		    echo 'Please install these modules first :';
		    echo ul($dependencies);
		    echo '</p>';
	    }
	?>
</div>
