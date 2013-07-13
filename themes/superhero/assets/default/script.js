$(document).ready(function(){
    // if section-left is empty, remove it
    if($.trim($('#__section-left').html()) == ''){
        $('#__section-left').remove();
        $('#__section-content').removeClass('span9');
        $('#__section-content').addClass('span12');
    }
    // if section-right is empty, remove it
    if($.trim($('#__section-right').html()) == ''){
        $('#__section-right').remove();
        $('#__section-left-and-content').removeClass('span9');
        $('#__section-left-and-content').addClass('span12');
    }
    // if section-banner is empty, remove it
    if($.trim($('__section-banner').html()) == ''){
        $('__section-banner').remove();
    }
});
