<?php require_once BASEPATH."../themes/".$cms['site_theme']."/lib/function.php";?>
<h4>WIDGET</h4><hr />
<?php    
    echo build_widget_html($cms['widget'], 'sidebar');    
?>
<h4>ADVERTISEMENT</h4><hr />
<?php    
    echo build_widget_html($cms['widget'], 'advertisement');    
?>