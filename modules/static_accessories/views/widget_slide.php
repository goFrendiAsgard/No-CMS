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
    $div_item_list[] = '<div class="item '.$class.'" style="height:'.$slide_height.'; background-color:#AAAAAA; padding:5px;">'.
            '<img style="max-height:100%; max-width:100%; display: block; margin-left: auto; margin-right: auto;" 
            src ="'.base_url('modules/{{ module_path }}/assets/images/slides/'.$slide['image_url']).'" />'.
            '<div class="carousel-caption">'.$slide['content'].'</div>'.
            '</div>';
}

?>
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
    $(document).ready(function(){
        $('#slideshow-widget').carousel('cycle');
    });    
</script>
