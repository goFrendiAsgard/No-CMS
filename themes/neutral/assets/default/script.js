$(document).ready(function(){
	
    //view description
    $(".layout_nav li").mouseenter(function(){
        $(this).children(".layout_nav_description").show();
    });
    $(".layout_nav li a").mouseenter(function(){
        $(this).parent(".layout_nav li").children(".layout_nav_description").show();
    });

    //hide description
    $(".layout_nav li").mouseout(function(){
        $(this).children(".layout_nav_description").hide();
    });
    $(".layout_nav li a").mouseout(function(){
        $(this).parent(".layout_nav li").children(".layout_nav_description").hide();
    });

    //expand and collapse
    $(".layout_nav li .layout_expand").click(function(){
        $(this).parent('a').parent(".layout_nav li").children(".layout_nav").slideToggle('slow');
        $(this).toggleClass('layout_collapse_icon');
        $(this).toggleClass('layout_expand_icon');          
        return false;
    });
    
    $(window).resize(function() {
    	adjust_content_width();
	});
    
    adjust_content_width();

});

function adjust_content_width(){
	$('#layout_content').width(
			$('#layout_center').width() -
			$('#layout_right').width() -
			$('#layout_left').width() -
			100
	);
}


