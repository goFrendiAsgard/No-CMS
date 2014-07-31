$(document).ready(function(){
	$("#field-authorization_id").change(function(){
	    adjust_component_view();
	});
});

function adjust_component_view(){
	// authorization
	var authorization_id = $('select#field-authorization_id option:selected').val();
	if(authorization_id >= 4){
	    $("div#groups_field_box").show();
	}else{
	    $("div#groups_field_box").hide();
	}
}
