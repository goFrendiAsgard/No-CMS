function adjust(changing_field, affected_field, ajax_get_path){
	// define ajax path
	var ajax_path = ajax_get_path;
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
			var old_value = $('select#field-'+affected_field).val();
			var old_value_exists = false;
			$('select#field-'+affected_field).find('option').remove();
			$('select#field-'+affected_field).append('<option value=""></option>');
			for(i=0; i<response.length; i++){
				// old value exists?
				var selected = '';
				if(old_value == response[i].value){
					old_value_exists = true;
					selected = ' selected';
				}
				// look for old value
				$('select#field-'+affected_field).append('<option'+selected+' value="' + response[i].value + '">' + response[i].caption + '</option>');
			}
			if(old_value_exists){
				$('select#field-'+affected_field).val(old_value);
			}else{
				$('select#field-'+affected_field).val('');
			}
			$('select#field-'+affected_field).trigger("chosen:updated");
		}
	});
}
