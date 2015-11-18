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
        height: <?php echo $slide_height; ?>px;
        max-height:<?php echo $slide_height; ?>px;
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
                    $(this).css('background-image', 'url("' + $(this).attr('real-src')+'")');
                }
            });
            // add shadow
            var id_list = ['.carousel-caption', '.carousel-caption h1', '.carousel-caption h2',
                '.carousel-caption h3', '.carousel-caption h4', 'carousel-caption p', '.carousel-caption div',
                '.carousel-caption span'];
            for(var i=0; i<id_list.length; i++){
                var selector = id_list[i];
                if($(selector).length>0){
                    var color = _rgb2hex($(selector).css('color'));
                    if(typeof(color) != 'undefined'){                    
                        var shadow_color = _getContrastYIQ(color);
                        $(selector).css('text-shadow', '1px 1px '+shadow_color+', 1px 1px 20px '+shadow_color+', -1px -1px  20px'+shadow_color);
                    }
                }
            }
        }
    }

    function _rgb2hex(rgb) {
        if (  rgb.search("rgb") == -1 ) {
            return rgb;
        } else {
            rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
            function hex(x) {
                return ("0" + parseInt(x).toString(16)).slice(-2);
            }
            return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]); 
        }
    }
    function _getContrastYIQ(hexcolor){
        var r = parseInt(hexcolor.substr(1,2),16);
        var g = parseInt(hexcolor.substr(3,2),16);
        var b = parseInt(hexcolor.substr(4,2),16);
        var yiq = ((r*299)+(g*587)+(b*114))/1000;
        return (yiq >= 128) ? '#000000' : '#FFFFFF';
    }


    $(window).scroll(function(){
        var windowTop = $(window).scrollTop();
        $('.carousel-inner .item-image').css('background-position', '0 ' + windowTop + 'px');
    });
</script>
