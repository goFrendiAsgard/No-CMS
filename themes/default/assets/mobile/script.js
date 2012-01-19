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
    $(".layout_nav li a.layout_expand").click(function(){
        $(this).parent(".layout_nav li").children(".layout_nav").toggle();
        if($(this).html()=="[+]"){$(this).html("[-]");}
        else{$(this).html("[+]");}
        return false;
    });

    $(".layout_button_menu").click(function(){
        $("#layout_widget").hide();
        $("#layout_navigation").show(); 
        small_content();
        return false;
    });

    $(".layout_button_widget").click(function(){
        $("#layout_navigation").hide();
        $("#layout_widget").show();  
        small_content();
        return false;
    });
    $(".layout_button_content").click(function(){
        $("#layout_navigation").hide();
        $("#layout_widget").hide(); 
        full_content();
        return false;
    });

    $(".layout_nav li a:not(.layout_expand)").click(function(){                    
        $("#layout_navigation").hide();
        $("#layout_widget").hide();
    });
    
    full_content();

});

function small_content(){
    var containerWidth = $("#layout_center").width();
    $("#layout_content").width(containerWidth-260-21);
}

function full_content(){
    var containerWidth = $("#layout_center").width();
    $("#layout_content").width(containerWidth-5);
}

