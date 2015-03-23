<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$li_indicator_list = array();
$div_item_list = array();
$edit_link = '';
if($show_edit){
    $edit_link = '<div>'.
        '<a class="btn btn-primary" href="{{ MODULE_SITE_URL }}manage_slide"><i class="glyphicon glyphicon-pencil">&nbsp;</i>Edit Slideshow</a>'.
        '</div>';
}
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
            '<div class="item-image" real-src="'.base_url('modules/'.$module_path.'/assets/images/slides/'.$slide['image_url']).'" alt=""></div>'.
            '<div class="container"><div class="carousel-caption">'.
                $slide['content'].$edit_link.
            '</div></div>'.
            '</div>';
}
?>
<style type="text/css">
    <?php if($slide_height+0 == $slide_height){?>
    div.carousel-inner div.item{
        height: <?=$slide_height?>px;
        max-height:<?=$slide_height?>px;
    }
    <?php } ?>
    div.carousel-inner{
        opacity:0.85;
    }
    .carousel-inner .item-image{
        margin:auto;
        background-color:black;
        height:100%;
        background-size:cover;
        /*background-attachment: fixed;*/
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
            $('.item-image').each(function(){
                if($(this).attr('src') !== ''){
                    //$(this).attr('src', $(this).attr('real-src'));
                    $(this).css('background-image', 'url("' + $(this).attr('real-src')+'")');
                }
            });    
        }
    }

    $(window).scroll(function(){
        var carouselTop = $('div.carousel-inner').offset().top;
        var windowTop = $(window).scrollTop();
        var height = $('div.carousel-inner').height();
        if(windowTop >= carouselTop){
            var newTop = windowTop - carouselTop;
            var newOpacity = 1-0.6*(newTop/height);
            var blur = Math.round(newTop/height * 5);
        }else{
            var newTop = 0;
            var newOpacity = 1;
            var blur = 0;
        }
        $('.carousel-inner .item-image').css('background-position', '0 ' + newTop + 'px');
        $('.carousel-inner .item-image').css('filter', 'blur(' + blur + 'px)');
        $('.carousel-inner .item-image').css('-webkit-filter', 'blur(' + blur + 'px)');
        $('.carousel-inner>.item').css('opacity', newOpacity);
    });
</script>
