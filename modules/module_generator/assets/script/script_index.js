$(document).ready(function(){
	// start condition
	if($("#custom_setting").attr("checked")=="checked"){
		$("#custom_database").show();
	}
	
	// custom_setting change event
	$("#custom_setting").change(function(){
		$("#custom_database").toggle();
	});
	
});