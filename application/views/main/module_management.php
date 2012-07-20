<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h3>Module Management</h3>
<table>
<?php
    foreach($modules as $module){
        $str_status = $module['installed']?'installed':'not installed';
        $anchor = !$module['installed']?anchor($module['module_path'].'/install','Install'):
            anchor($module['module_path'].'/install/uninstall','Uninstall');
         
        echo '<tr>';
        echo '<td>'.'<b><i>'.$module['module_path'].'</i></b>'.'</td>';
        echo '<td>'.'is '.$str_status.'</td>';
        echo '<td>'.$anchor.'</td>';
        echo '</tr>';
    }
?>
</table>
