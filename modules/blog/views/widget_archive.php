<style type="text/css">
    ul._archive_widget a{
        color: inherit;
    }
    ul._archive_widget a:hover{
        color: inherit;
    }
</style>
<?php
if($module_path == 'blog'){
    $module_url = 'blog';
}else{
    $module_url = '{{ module_path }}/blog';
}
echo '<ul class="_archive_widget">';
echo '<li>'.anchor(site_url($module_url), 'All').'</li>';
foreach($archives as $key=>$value){
    echo '<li>';
    // key
    if($key == ''){
        $url = $module_url;
    }else if($archive_route_exists){
        $url = $module_url.'/archive/'.$key;
    }else{
        $url = $module_url.'/index?archive='.$key;
    }
    echo anchor(site_url($url), $value);
    echo '</li>';
}
echo '</ul>';