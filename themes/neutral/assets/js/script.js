$(document).ready(function(){
    $(document).on('scroll', function(){
        if ($('body')[0].offsetTop < ($(document).scrollTop()-$('.navbar-fixed-top').height())){
            $('.navbar-fixed-top').css({opacity: 0.95});
        }else{
            $('.navbar-fixed-top').css({opacity: 1});
        }
    });
});
