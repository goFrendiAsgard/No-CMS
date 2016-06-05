<style type="text/css">
    ul._category_widget a{
        color: inherit;
    }
    ul._category_widget a:hover{
        color: inherit;
    }
    ul._category_widget{
        list-style-type: none;
        padding-left: 0;
    }
    ul._category_widget li{
        display:inline-block;
        margin-right:10px;
    }
</style>
<?php
$max_category = 20;
if($module_path == 'blog'){
    $module_url = 'blog';
}else{
    $module_url = $module_path.'/blog';
}
echo '<ul class="_category_widget">';
$counter = 0;
foreach($categories as $key=>$value){
    $class= '';
    $style = '';
    if(count($categories) > $max_category){
        if($counter == $max_category){
            echo '<li><a href="#" class="_more_category _toggle-more" style="font-size:large">{{ language:more }}...</a></li>';
        }
        if($counter >= $max_category){
            $class = '_toggle-more';
            $style = 'display:none;';
        }
    }
    echo '<li class="'.$class.'" style="'.$style.'">';
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
    $counter ++;
}
if(count($categories) > $max_category){
    echo '<li><a href="#" class="_more_category _toggle-more" style="font-size:large; display:none;">{{ language:less }}</a></li>';
}
echo '</ul>';
?>
<script type="text/javascript">
    $('._more_category').click(function(event){
        $('._toggle-more').toggle();
        event.preventDefault();
    })
</script>
