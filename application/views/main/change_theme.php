<h3>Change Theme</h3>
<table>
<?php
    foreach($themes as $theme){
        $str_status = $theme['used']?'used':'not used';
        $anchor = !$theme['used']?anchor('main/change_theme/'.$theme['path'],'Use'):
            '&nbsp;'; 
        echo '<tr>';
        echo '<td>'.'Theme <b><i>'.$theme['path'].'</i></b>'.'</td>';
        echo '<td>'.'is '.$str_status.'</td>';
        echo '<td>'.$anchor.'</td>';
        echo '</tr>';
    }
?>
</table>