function adjust(changing_field, affected_field, ajax_get_restricted_path){
	// define ajax path
	var ajax_path = ajax_get_restricted_path;
	if(ajax_path[ajax_path.length-1] != '/'){
		ajax_path += '/';
	}
	var changing_id = $("#field-"+changing_field+' option:selected').val();
	if(changing_id === undefined){
		changing_id = $("#field-"+changing_field).val();
	}
	$.ajax({
		'url' : ajax_path+changing_id,
		'dataType' : 'json',
		'success' : function(response){
			$('#field_'+affected_field+'_chzn ul.chzn-results li').removeClass('hidden');
			for(var i=0; i<response.length; i++){					
				var current_option = $('select#field-'+affected_field).children('option[value="'+response[i]+'"]');
				var index = $('select#field-'+affected_field+' option').index(current_option);
				$('#field_'+affected_field+'_chzn ul.chzn-results li#field_'+affected_field+'_chzn_o_'+index).addClass('hidden');
				$('#field_'+affected_field+'_chzn ul.chzn-results li#field_'+affected_field+'_chzn_o_'+index).removeClass('result-selected');
				//$('#field_'+affected_field+'_chzn ul.chzn-choices li#field_'+affected_field+'_chzn_c_'+index).remove();
				$('select#field-'+affected_field+' option[value="'+response[i]+'"]').removeAttr('selected');
			}
		}
	});
}