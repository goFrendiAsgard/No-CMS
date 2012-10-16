$(document).ready(function(){
	// grid, toggle active/inactive
	$(".navigation_active").click(function(){
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
	// add shadow static content input box
	console.log($('#static_content_field_box'));
	$('#static_content_field_box').append('<div id="static_content_input_box_shadow" style="display: block; ">');
					
    // check
	adjust_component_view();
	$("#field-is_static-true").click(function(){
		adjust_component_view();
	});
	$("#field-is_static-false").click(function(){
		adjust_component_view();
	});
});

function adjust_component_view(){
	var is_static = $("#field-is_static-true").is(':checked');
	var is_not_static = $("#field-is_static-false").is(':checked');
	var static_content = $("textarea#field-static_content").html();
	static_content = static_content.replace(/&lt;/g,'<');
	static_content = static_content.replace(/&gt;/g,'>');
	console.log(static_content);
	$("div#static_content_input_box_shadow").html(static_content);
	if(is_static){
		$("div#static_content_input_box").show();
		$("div#static_content_input_box_shadow").hide();
		$("#field-url").attr('disabled', 'disabled');
		$("#field-url").addClass('input_disable');
	}else if(is_not_static){
		$("div#static_content_input_box").hide();
		$("div#static_content_input_box_shadow").show();
		$("#field-url").removeAttr('disabled');
		$("#field-url").removeClass('input_disable');
	}
}
