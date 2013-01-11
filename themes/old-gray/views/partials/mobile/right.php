<?php require_once BASEPATH."../themes/".$cms['site_theme']."/lib/function.php";?>
<hr />WIDGET<hr />
<?php    
    echo build_widget($cms['widget'], 'sidebar');    
?>
<hr />ADVERTISEMENT<hr />
<?php    
    echo build_widget($cms['widget'], 'advertisement');    
?>