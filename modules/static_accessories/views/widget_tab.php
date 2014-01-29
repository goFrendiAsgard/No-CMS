<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$li_nav_tab_list = array();
$div_tab_pane_list = array();
for($i=0; $i<count($tab_list); $i++){
    $tab = $tab_list[$i];
    if($i==0){
        $class="active";
    }else{
        $class="";
    }
    $li_nav_tab_list[] = '<li class="'.$class.'"><a href="#tab_'.$i.'" data-toggle="tab">'.$tab['caption'].'</a></li>';
    $div_tab_pane_list[] = '<div class="tab-pane '.$class.'" id="tab_'.$i.'">'.$tab['content'].'</div>';
}

?>
<div class="tabbable" id="tab-widget">
    <ul class="nav nav-tabs">
        <?php foreach($li_nav_tab_list as $li_nav_tab){echo $li_nav_tab;} ?>
    </ul>
    <div class="tab-content">
         <?php foreach($div_tab_pane_list as $div_tab_pane){echo $div_tab_pane;} ?>
    </div>    
</div>
