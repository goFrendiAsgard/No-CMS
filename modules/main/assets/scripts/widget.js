$(".widget_active").click(function(){
    var str = $(this).html();
    var $this = $(this);
    $.ajax({
        url: $(this).attr('target'),
        dataType: 'json',
        success: function(response){                    
            if(str == 'Active'){
                str = 'Inactive';
            }else{
                str = 'Active';
            }
            if(response.success){
                console.log(str);
                $this.html(str);
            }
        }
    });
});