<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$li_indicator_list = array();
$div_item_list = array();
for($i=0; $i<count($slide_list); $i++){
    $slide = $slide_list[$i];
    if($i==0){
        $class = 'active';
    }else{
        $class = '';
    }
    $li_indicator_list[] = '<li data-target="#slideshow-widget" data-slide-to="'.$i.'" class="'.$class.'"></li>';
    $div_item_list[] = '<div class="item '.$class.'" style="background-image:url('.base_url('modules/{{ module_path }}/assets/images/slides/'.$slide['image_url']).'); background-repeat:no-repeat; background-position:center center; min-height:350px;">'.
            '<div class="carousel-caption">'.$slide['content'].'</div>'.
            '</div>';
}

?>
<style type="text/css">
    .carousel-indicators{
        background-color:#222222;
        opacity: 0.5;
        filter:alpha(opacity=50);
        padding: 10px;
        border-radius: 20px;
    }
    .carousel-indicators:hover{
        opacity: 0.9;
        filter:alpha(opacity=90);
    }
    .carousel-indicators li{
        cursor: pointer;
    }
</style>
<div class="carousel slide hidden-phone" id="slideshow-widget">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php foreach($li_indicator_list as $li_indicator){ echo $li_indicator;} ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <?php foreach($div_item_list as $div_item){ echo $div_item;} ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#slideshow-widget" data-slide="prev">
        <span class="icon-prev">&lsaquo;</span>
    </a>
    <a class="right carousel-control" href="#slideshow-widget" data-slide="next">
        <span class="icon-next">&rsaquo;</span>
    </a>
</div>
<script type ="text/javascript">
    $('#slideshow-widget').carousel('cycle');
</script>
