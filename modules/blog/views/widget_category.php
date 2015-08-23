<style type="text/css">
    ul._category_widget a{
        color: inherit;
    }
    ul._category_widget a:hover{
        color: inherit;
    }
</style>
<?php
if($module_path == 'blog'){
    $module_url = 'blog';
}else{
    $module_url = $module_path.'/blog';
}
echo '<ul class="_category_widget">';
foreach($categories as $key=>$value){
    echo '<li>';    
    // key
    if($key == ''){
        $url = $module_url;
    }else if($category_route_exists){
        $url = $module_url.'/category/'.$key;
    }else{
        $url = $module_url.'/index?category='.$key;
    }
    echo anchor(site_url($url), $value);
    echo '</li>';
}
echo '</ul>';