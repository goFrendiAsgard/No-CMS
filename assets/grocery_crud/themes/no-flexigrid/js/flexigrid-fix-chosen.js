$(".form-input-box .chosen-select, .form-input-box .chosen-multiple-select").each(function(){
    if($(this).width() == 0){
        $(this).width(500);            
    }
});
$(".chosen-select,.chosen-multiple-select").each(function(){
    if($(this).width() == 0){
        $(this).width(240);            
    }
});