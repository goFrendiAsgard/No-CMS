<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h4>Uninstallation Failed</h4>
<?php
	echo 'Cannot uninstall "<em>'.$module_name.'</em>" on "'.$module_path.'" ';
    echo anchor('main/module_management','Back');
    echo br();
    if($undefinedName_error){
    	echo '<p>';
    	echo 'Module name is undefined';
    	echo '</p>';
    }
    if($alreadyUninstalled_error){
    	echo '<p>';
    	echo 'The module has been uninstalled';
    	echo '</p>';
    }
    if($dependencies_error){
	    echo '<p>';
	    echo 'There are other modules depended on this module';
	    echo br();
	   	echo 'Please uninstall these modules first :';
	    echo '<ul>';
	    for($i=0; $i<count($dependencies); $i++){
	    	$dependency = $dependencies[$i];
	    	echo '<li>'.$dependency["module_name"]." on <b>modules/".$dependency["module_path"].'</b></li>';
	    }    
	    echo '</ul>';
	    echo '</p>';
    }
?>