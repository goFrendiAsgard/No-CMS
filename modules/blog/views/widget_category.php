<?php
echo '<ul>';
foreach($categories as $key=>$value){
    echo '<li>';
    if($key == ''){
        $url = $cms['module_path'].'/blog/index';
    }else{
        $url = $cms['module_path'].'/blog/index?category='.$key;
    }
    echo anchor(site_url($url), $value);
    echo '</li>';
}
echo '</ul>';