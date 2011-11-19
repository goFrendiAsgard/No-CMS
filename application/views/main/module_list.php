<h3>Module Management</h3>
<?php
    foreach($modules as $module){
        echo br();
        $str_status = $module['installed']?'installed':'not installed';
        $anchor = !$module['installed']?anchor($module['path'].'/install','Install'):
            anchor($module['path'].'/install/uninstall','Uninstall');
        echo 'Module <i>'.$module['path'].'</i> '.$str_status.' '.$anchor.br();        
    }
?>
