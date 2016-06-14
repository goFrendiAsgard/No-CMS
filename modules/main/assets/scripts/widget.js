$(document).ready(function(){
	$('body').on('click', '.widget_active', function(event){
        event.preventDefault();
        var $this = $(this);
	    var str = $this.children('span').html();
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
	                $this.children('span').html(str);
	            }
	        }
	    });
	});

	// remove sorting
    $('.field-sorting').removeClass('field-sorting');

	// check
    adjust_component_view();
    $("#field-is_static-true").click(function(){
        adjust_component_view();
    });
    $("#field-is_static-false").click(function(){
        adjust_component_view();
    });
    $("#field-authorization_id").change(function(){
        adjust_component_view();
    });
});

function adjust_component_view(){
    // static
    var is_static = $("#field-is_static-true").is(':checked');
    if(is_static){
        $("div#static_content_field_box").show();
        $("div#url_field_box").hide();
    }else{
        $("div#static_content_field_box").hide();
        $("div#url_field_box").show();
    }
    // authorization
    var authorization_id = $('select#field-authorization_id option:selected').val();
	if(authorization_id >= 4){
	    $("div#group_widget_field_box").show();
		// field_box
		$('div#group_widget_field_box, div#authorization_id_field_box').removeClass('col-md-12').addClass('col-md-6');
		// label
		$('label#group_widget_display_as_box, label#authorization_id_display_as_box').removeClass('col-md-2').addClass('col-md-4');
		// input
		$('#group_widget_input_box, #authorization_id_input_box').removeClass('col-md-10').addClass('col-md-8');
	}else{
	    $("div#group_widget_field_box").hide();
		// field_box
		$('div#group_widget_field_box, div#authorization_id_field_box').removeClass('col-md-6').addClass('col-md-12');
		// label
		$('label#group_widget_display_as_box, label#authorization_id_display_as_box').removeClass('col-md-4').addClass('col-md-2');
		// input
		$('#group_widget_input_box, #authorization_id_input_box').removeClass('col-md-8').addClass('col-md-10');
	}
}

$(document).ajaxComplete(function(){
    $('.field-sorting').removeClass('field-sorting');
});
