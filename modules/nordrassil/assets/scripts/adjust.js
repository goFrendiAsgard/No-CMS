var RESTRICTED_INDEX = {};
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
			RESTRICTED_INDEX[affected_field] = response;
			function onchange(){
				restricted_index = RESTRICTED_INDEX[affected_field];
				$('#field_'+affected_field+'_chosen ul.chosen-results li').removeClass('hidden');
				for(var i=0; i<restricted_index.length; i++){
					var current_option = $('select#field-'+affected_field).children('option[value="'+restricted_index[i]+'"]');
					var index = $('select#field-'+affected_field+' option').index(current_option);
					var $option = $('#field_'+affected_field+'_chosen ul.chosen-results li[data-option-array-index="'+index+'"]');
					$option.addClass('hidden');
					$option.removeClass('result-selected');
					$('select#field-'+affected_field+' option[value="'+restricted_index[i]+'"]').removeAttr('selected');
				}
			}
			$('#field_'+affected_field+'_chosen').click(onchange);
			$('#field_'+affected_field+'_chosen').keyup(onchange);
		}
	});
}