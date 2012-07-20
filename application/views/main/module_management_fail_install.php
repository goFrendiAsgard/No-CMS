<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h4>Installation Failed</h4>
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
