<?php
    echo anchor('main/module_management','Back');
    echo br();
    echo 'Cannot uninstall <em>'.$module_name.'</em>, because some module depend on it';
    echo br();
    echo 'Please uninstall these modules first';
    echo ul($dependencies);
?>
