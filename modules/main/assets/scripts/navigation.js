$(document).ready(function(){
	// grid, toggle active/inactive
	$('body').on('click', '.navigation_active', function(event){
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
	// expand or collapse
	$('body').on('click', '.expand-collapse-children', function(event){
	    event.preventDefault();
	    var target = $(this).attr('target');
        $('#child-'+target).toggle();
        if($('#child-'+target).is(':visible')){
            $(this).html('<i class="glyphicon glyphicon-chevron-up"></i> Collapse');
        }else{
            $(this).html('<i class="glyphicon glyphicon-chevron-down"></i> Expand');
        }
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
	if(authorization_id >= 4){
	    $("div#group_navigation_field_box").show();
		// field_box
		$('div#group_navigation_field_box, div#authorization_id_field_box').removeClass('col-md-12').addClass('col-md-6');
		// label
		$('label#group_navigation_display_as_box, label#authorization_id_display_as_box').removeClass('col-md-2').addClass('col-md-4');
		// input
		$('#group_navigation_input_box, #authorization_id_input_box').removeClass('col-md-10').addClass('col-md-8');
	}else{
	    $("div#group_navigation_field_box").hide();
		// field_box
		$('div#group_navigation_field_box, div#authorization_id_field_box').removeClass('col-md-6').addClass('col-md-12');
		// label
		$('label#group_navigation_display_as_box, label#authorization_id_display_as_box').removeClass('col-md-4').addClass('col-md-2');
		// input
		$('#group_navigation_input_box, #authorization_id_input_box').removeClass('col-md-8').addClass('col-md-10');
	}
}
