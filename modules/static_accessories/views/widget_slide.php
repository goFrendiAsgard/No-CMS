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
    $div_item_list[] = 
            '<div class="item '.$class.'">'.
            '<img class="item-image" real-src="'.base_url('modules/'.$module_path.'/assets/images/slides/'.$slide['image_url']).'" alt="">'.
            '<div class="container"><div class="carousel-caption">'.$slide['content'].'</div></div>'.
            '</div>';
}
?>
<style type="text/css">
    div.carousel-inner div.item{
        height: <?=$slide_height?>;
        max-height:<?=$slide_height?>;
    }
    img.item-image{
        margin:auto;
    }
    #slideshow-widget{
        margin-bottom:20px;
    }
</style>
<div class="carousel slide hidden-sm hidden-xs" id="slideshow-widget">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php foreach($li_indicator_list as $li_indicator){ echo $li_indicator;} ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <?php foreach($div_item_list as $div_item){ echo $div_item;} ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#slideshow-widget" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
    <a class="right carousel-control" href="#slideshow-widget" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
</div>
<script type ="text/javascript">
    $(document).ready(function(){
        $('#slideshow-widget').carousel('cycle');
        __load_slide();        
    });

    $(window).resize(function(){__load_slide();});

    function __load_slide(){
        var body_width = $('body').width();
        if(body_width>=978){
            $('img.item-image').each(function(){
                if($(this).attr('src') !== ''){
                    $(this).attr('src', $(this).attr('real-src'));
                }
            });    
        }
    }

</script>
