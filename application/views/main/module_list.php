<h3>Module Management</h3>
<table>
<?php
    foreach($modules as $module){
        $str_status = $module['installed']?'installed':'not installed';
        $anchor = !$module['installed']?anchor($module['path'].'/install','Install'):
            anchor($module['path'].'/install/uninstall','Uninstall');
        //echo 'Module <i>'.$module['path'].'</i> '.$str_status.' '.$anchor.br(); 
        echo '<tr>';
        echo '<td>'.'<b><i>'.$module['path'].'</i></b>'.'</td>';
        echo '<td>'.'is '.$str_status.'</td>';
        echo '<td>'.$anchor.'</td>';
        echo '</tr>';
    }
?>
</table>
