<?php
if($module_path == 'blog'){
    $module_url = 'blog';
}else{
    $module_url = '{{ module_path }}/blog';
}
echo '<ul>';
foreach($categories as $key=>$value){
    echo '<li>';    
    // key
    if($key == ''){
        $url = $module_url.'/index';
    }else{
        $url = $module_url.'/index?category='.$key;
    }
    echo anchor(site_url($url), $value);
    echo '</li>';
}
echo '</ul>';