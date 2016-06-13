$(document).ready(function(){	
	adjust_component_view();
	$("#field-authorization_id").change(function(){
	    adjust_component_view();
	});
});

function adjust_component_view(){
	// authorization
	var authorization_id = $('select#field-authorization_id option:selected').val();
	if(authorization_id >= 4){
	    $("div#group_privilege_field_box").show();
		// field_box
		$('div#group_privilege_field_box, div#authorization_id_field_box').removeClass('col-md-12').addClass('col-md-6');
		// label
		$('label#group_privilege_display_as_box, label#authorization_id_display_as_box').removeClass('col-md-2').addClass('col-md-4');
		// input
		$('#group_privilege_input_box, #authorization_id_input_box').removeClass('col-md-10').addClass('col-md-8');
	}else{
	    $("div#group_privilege_field_box").hide();
		// field_box
		$('div#group_privilege_field_box, div#authorization_id_field_box').removeClass('col-md-6').addClass('col-md-12');
		// label
		$('label#group_privilege_display_as_box, label#authorization_id_display_as_box').removeClass('col-md-4').addClass('col-md-2');
		// input
		$('#group_privilege_input_box, #authorization_id_input_box').removeClass('col-md-8').addClass('col-md-10');
	}
}
