$(document).ready(function(){
	// grid, toggle active/inactive
	$(".navigation_active").live('click', function(){
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
					$this.html(str);
				}
			}
		});
	});

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
	if(authorization_id == 4){
	    $("div#groups_field_box").show();
	}else{
	    $("div#groups_field_box").hide();
	}
}
