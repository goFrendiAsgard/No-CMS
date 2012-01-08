<?php
    echo anchor('main/module_management','Back');
    echo br();
    echo 'Cannot install <em>'.$module_name.'</em>, because there are unsatisfied dependencies';
    echo br();
    echo 'Please install these modules first';
    echo ul($dependencies);
?>
