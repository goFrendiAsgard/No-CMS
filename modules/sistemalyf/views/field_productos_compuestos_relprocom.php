<?php
	$record_index = 0;
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/grocery_crud/css/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'); ?>" />
<style type="text/css">
	/* set width of every detail input*/
	#md_table_relprocom .md_field_relprocom_col{
		width:auto!important;
		min-width:50px!important;
		max-width:150px!important;
	}
	#md_table_relprocom .datepicker-input{
		width:auto!important;
		min-width:50px!important;
		max-width:100px!important;
	}
	#md_table_relprocom .chzn-container,
	#md_table_relprocom .chzn-drop{
		width:auto!important;
		min-width:100px!important;
		max-width:250px!important;
	}
</style>

<table id="md_table_relprocom" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Relpro</th>
			<th>Cantidad</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<!-- the data presentation be here -->
	</tbody>
</table>
<input id="md_field_relprocom_add" class="btn" type="button" value="Add Rel Pro Compuestos" />
<br />
<!-- This is the real input. If you want to catch the data, please json_decode this input's value -->
<input id="md_real_field_relprocom_col" name="md_real_field_relprocom_col" type="hidden" />

<script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/jquery.chosen.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/jquery.ui.datetime.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/jquery.numeric.min.js'); ?>"></script>
<script type="text/javascript">

	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// DATA INITIALIZATION
	//
	// * Prepare some global variables
	//
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	var DATE_FORMAT = '<?php echo $date_format ?>';
	var OPTIONS = <?php echo json_encode($options); ?>;
	var RECORD_INDEX_relprocom = <?php echo $record_index; ?>;
	var DATA_relprocom = {update:new Array(), insert:new Array(), delete:new Array()};
	var old_data = <?php echo json_encode($result); ?>;
	for(var i=0; i<old_data.length; i++){
		var row = old_data[i];
		var record_index = i;
		var primary_key = row['priority'];
		var data = row;
		delete data['priority'];
		DATA_relprocom.update.push({
			'record_index' : record_index,
			'primary_key' : primary_key,
			'data' : data,
		});
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ADD ROW FUNCTION
	//
	// * When "Add Rel Pro Compuestos" clicked, this function is called without parameter.
	// * When page loaded for the first time, this function is called with value parameter
	//
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	function add_table_row_relprocom(value){

		var component = '<tr id="md_field_relprocom_tr_'+RECORD_INDEX_relprocom+'" class="md_field_relprocom_tr">';
		
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "relpro"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		var field_value = ''
		if(typeof(value) != 'undefined' && value.hasOwnProperty('relpro')){
			field_value = value.relpro;
		}
		component += '<td>';
		component += '<select id="md_field_relprocom_col_relpro_'+RECORD_INDEX_relprocom+'" record_index="'+RECORD_INDEX_relprocom+'" class="md_field_relprocom_col numeric chzn-select" column_name="relpro" >';
		var options = OPTIONS.relpro;
		component += '<option value></option>';
		for(var i=0; i<options.length; i++){
			var option = options[i];
			var selected = '';
			if(option['value'] == field_value){
				selected = 'selected="selected"';
			}
			component += '<option value="'+option['value']+'" '+selected+'>'+option['caption']+'</option>';
		}
		component += '</select>';
		component += '</td>';


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "cantidad"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		var field_value = ''
		if(typeof(value) != 'undefined' && value.hasOwnProperty('cantidad')){
			field_value = value.cantidad;
		}
		component += '<td>';
		component += '<input id="md_field_relprocom_col_cantidad_'+RECORD_INDEX_relprocom+'" record_index="'+RECORD_INDEX_relprocom+'" class="md_field_relprocom_col" column_name="cantidad" type="text" value="'+field_value+'"/>';
		component += '</td>';



		/////////////////////////////////////////////////////////////////////////////////////////////////////
		// Delete Button
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		component += '<td><input class="md_field_relprocom_delete btn" record_index="'+RECORD_INDEX_relprocom+'" primary_key="" type="button" value="Delete Rel Pro Compuestos" /></td>';
		component += '</tr>';

		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // Add component to table
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('#md_table_relprocom tbody').append(component);
		mutate_input();

	} // end of ADD ROW FUNCTION



	/////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Main event handling program
    //
    // * Initialization
    // * md_field_relprocom_add.click (Add row)
    // * md_field_relprocom_delete.click (Delete row)
    // * md_field_relprocom_col.change (Edit cell)
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
	$(document).ready(function(){

		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // INITIALIZATION
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		synchronize_relprocom();
		for(var i=0; i<DATA_relprocom.update.length; i++){
			add_table_row_relprocom(DATA_relprocom.update[i].data);
			RECORD_INDEX_relprocom++;
		}


		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_relprocom_add.click (Add row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('#md_field_relprocom_add').click(function(){
			// new data
			var data = new Object();
			
			data.relpro = '';
			data.cantidad = '';
			// insert data to the DATA_relprocom
			DATA_relprocom.insert.push({
				'record_index' : RECORD_INDEX_relprocom,
				'primary_key' : '',
				'data' : data,
			});

			// add table's row
			add_table_row_relprocom(data);
			// add  by 1
			RECORD_INDEX_relprocom++;

			// synchronize to the md_real_field_relprocom_col
			synchronize_relprocom();
		});


		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_relprocom_delete.click (Delete row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('.md_field_relprocom_delete').live('click', function(){
			var record_index = $(this).attr('record_index');
			// remove the component
			$('#md_field_relprocom_tr_'+record_index).remove();

			var record_index_found = false;
			for(var i=0; i<DATA_relprocom.insert.length; i++){
				if(DATA_relprocom.insert[i].record_index == record_index){
					record_index_found = true;
					// delete element from insert
					DATA_relprocom.insert.splice(i,1);
					break;
				}
			}
			if(!record_index_found){
				for(var i=0; i<DATA_relprocom.update.length; i++){
					if(DATA_relprocom.update[i].record_index == record_index){
						record_index_found = true;
						var primary_key = DATA_relprocom.update[i].primary_key
						// delete element from update
						DATA_relprocom.update.splice(i,1);
						// add it to delete
						DATA_relprocom.delete.push({
							'record_index':record_index,
							'primary_key':primary_key
						});
						break;
					}
				}
			}
			synchronize_relprocom();
		});


		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_relprocom_col.change (Edit cell)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('.md_field_relprocom_col').live('change', function(){
			var value = $(this).val();
			var column_name = $(this).attr('column_name');
			var record_index = $(this).attr('record_index');
			var record_index_found = false;
			// date picker
			if($(this).hasClass('datepicker-input')){
				value = js_date_to_php(value);
			}
			if(typeof(value)=='undefined'){
				value = '';
			}
			for(var i=0; i<DATA_relprocom.insert.length; i++){
				if(DATA_relprocom.insert[i].record_index == record_index){
					record_index_found = true;
					// insert value
					eval('DATA_relprocom.insert['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
					break;
				}
			}
			if(!record_index_found){
				for(var i=0; i<DATA_relprocom.update.length; i++){
					if(DATA_relprocom.update[i].record_index == record_index){
						record_index_found = true;
						// edit value
						eval('DATA_relprocom.update['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
						break;
					}
				}
			}
			synchronize_relprocom();
		});


	});

	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// reset field on save
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$(document).ajaxSuccess(function(event, xhr, settings) {
        response = $.parseJSON(xhr.responseText);
        if (settings.url == "{{ module_site_url }}manage_productos_compuestos/index/insert" &&
            response.success == true
        ) {
            DATA_citizen = {update:new Array(), insert:new Array(), delete:new Array()};
            $('#md_table_relprocom tr').not(':first').remove();
                synchronize_citizen();
        }
    });


	/////////////////////////////////////////////////////////////////////////////////////////////////////////
    // General Functions
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

	// synchronize data to md_real_field_relprocom_col.
	function synchronize_relprocom(){
		$('#md_real_field_relprocom_col').val(JSON.stringify(DATA_relprocom));
	}

	function js_datetime_to_php(js_datetime){
		var datetime_array = js_datetime.split(' ');
		var js_date = datetime_array[0];
		var time = datetime_array[1];
		var php_date = js_date_to_php(js_date);
		return php_date + ' ' + time;
	}
	function php_datetime_to_js(php_datetime){
		var datetime_array = php_datetime.split(' ');
		var php_date = datetime_array[0];
		var time = datetime_array[1];
		var js_date = php_date_to_js(php_date);
		return js_date + ' ' + time;
	}

	function js_date_to_php(js_date){
		if(typeof(js_date)=='undefined' || js_date == ''){
			return '';
		}
		var date = '';
		var month = '';
		var year = '';
		var php_date = '';
		if(DATE_FORMAT == 'uk-date'){
			var date_array = js_date.split('/')
			day = date_array[0];
			month = date_array[1];
			year = date_array[2];
			php_date = year+'-'+month+'-'+day;
		}else if(DATE_FORMAT == 'us-date'){
			var date_array = js_date.split('/')
			day = date_array[1];
			month = date_array[0];
			year = date_array[2];
			php_date = year+'-'+month+'-'+day;
		}else if(DATE_FORMAT == 'sql-date'){
			var date_array = js_date.split('-')
			day = date_array[2];
			month = date_array[1];
			year = date_array[0];
			php_date = year+'-'+month+'-'+day;
		}
		return php_date;
	}


	function php_date_to_js(php_date){
		if(typeof(php_date)=='undefined' || php_date == ''){
			return '';
		}
		var date_array = php_date.split('-');
		var year = date_array[0];
		var month = date_array[1];
		var day = date_array[2];
		if(DATE_FORMAT == 'uk-date'){
			return day+'/'+month+'/'+year;
		}else if(DATE_FORMAT == 'us-date'){
			return month+'/'+date+'/'+year;
		}else if(DATE_FORMAT == 'sql-date'){
			return year+'-'+month+'-'+day;
		}else{
			return '';
		}
	}

	function IsNumeric(input){
		return (input - 0) == input && input.length > 0;
	}

	function mutate_input(){
		// datepikcer-input
		$('#md_table_relprocom .datepicker-input').datepicker({
				dateFormat: js_date_format,
				showButtonPanel: true,
				changeMonth: true,
				changeYear: true
		});
		// date-picker-input-clear
		$('#md_table_relprocom .datepicker-input-clear').click(function(){
			$(this).parent().find('.datepicker-input').val('');
			return false;
		});
		// chzn-select
		$("#md_table_relprocom .chzn-select").chosen({allow_single_deselect: true});
		// numeric
		$('#md_table_relprocom .numeric').numeric();
		$('#md_table_relprocom .numeric').keydown(function(e){
			if(e.keyCode == 38)
			{
				if(IsNumeric($(this).val()))
				{
					var new_number = parseInt($(this).val()) + 1;
					$(this).val(new_number);
				}else if($(this).val().length == 0)
				{
					var new_number = 1;
					$(this).val(new_number);
				}
			}
			else if(e.keyCode == 40)
			{
				if(IsNumeric($(this).val()))
				{
					var new_number = parseInt($(this).val()) - 1;
					$(this).val(new_number);
				}else if($(this).val().length == 0)
				{
					var new_number = -1;
					$(this).val(new_number);
				}
			}
			$(this).trigger('change');
		});

	}

</script>