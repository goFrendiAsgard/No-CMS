<?php
	$record_index = 0;
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/grocery_crud/css/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS); ?>" />
<style type="text/css">
	/* set width of every detail input*/
	#md_table_citizen .md_field_citizen_col{
		width:auto!important;
		min-width:50px!important;
		max-width:150px!important;
	}
	#md_table_citizen .datepicker-input{
		width:auto!important;
		min-width:50px!important;
		max-width:100px!important;
	}
	#md_table_citizen .chzn-container,
	#md_table_citizen .chzn-drop{
		width:auto!important;
		min-width:100px!important;
		max-width:250px!important;
	}
</style>

<table id="md_table_citizen" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Name</th>
			<th>Birthdate</th>
			<th>Job</th>
			<th>IQ</th>
			<th>Capita</th>
			<th>Hobby</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<!-- the data presentation be here -->
	</tbody>
</table>
<input id="md_field_citizen_add" class="btn" type="button" value="Add Citizen" />
<br />
<!-- This is the real input. If you want to catch the data, please json_decode this input's value -->
<input id="md_real_field_citizen_col" name="md_real_field_citizen_col" type="hidden" />

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
	var RECORD_INDEX_citizen = <?php echo $record_index; ?>;
	var DATA_citizen = {update:new Array(), insert:new Array(), delete:new Array()};
	var old_data = <?php echo json_encode($result); ?>;
	for(var i=0; i<old_data.length; i++){
		var row = old_data[i];
		var record_index = i;
		var primary_key = row['citizen_id'];
		var data = row;
		delete data['citizen_id'];
		DATA_citizen.update.push({
			'record_index' : record_index,
			'primary_key' : primary_key,
			'data' : data,
		});
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ADD ROW FUNCTION
	//
	// * When "Add Citizen" clicked, this function is called without parameter.
	// * When page loaded for the first time, this function is called with value parameter
	//
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	function add_table_row_citizen(value){

		var component = '<tr id="md_field_citizen_tr_'+RECORD_INDEX_citizen+'" class="md_field_citizen_tr">';
		
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "name"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		var field_value = ''
		if(typeof(value) != 'undefined' && value.hasOwnProperty('name')){
			field_value = value.name;
		}
		component += '<td>';
		component += '<input id="md_field_citizen_col_name_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col" column_name="name" type="text" value="'+field_value+'"/>';
		component += '</td>';


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "birthdate"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		var field_value = ''
		if(typeof(value) != 'undefined' && value.hasOwnProperty('birthdate')){
			field_value = php_date_to_js(value.birthdate);
		}
		component += '<td>';
		component += '<input id="md_field_citizen_col_birthdate_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col datepicker-input" column_name="birthdate" type="text" value="'+field_value+'"/>';
		component += '<a href="#" class="datepicker-input-clear btn">Clear</a>';
		component += '</td>';


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "job_id"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		var field_value = ''
		if(typeof(value) != 'undefined' && value.hasOwnProperty('job_id')){
			field_value = value.job_id;
		}
		component += '<td>';
		component += '<select id="md_field_citizen_col_job_id_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col numeric chzn-select" column_name="job_id" >';
		var options = OPTIONS.job_id;
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
        //    FIELD "IQ"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		var field_value = ''
		if(typeof(value) != 'undefined' && value.hasOwnProperty('IQ')){
			field_value = value.IQ;
		}
		component += '<td>';
		component += '<input id="md_field_citizen_col_IQ_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col numeric" column_name="IQ" type="text" value="'+field_value+'"/>';
		component += '</td>';


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "Capita"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		var field_value = ''
		if(typeof(value) != 'undefined' && value.hasOwnProperty('Capita')){
			field_value = value.Capita;
		}
		component += '<td>';
		component += '<input id="md_field_citizen_col_Capita_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col" column_name="Capita" type="text" value="'+field_value+'"/>';
		component += '</td>';


        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "hobby"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		var field_value = ''
		if(typeof(value) != 'undefined' && value.hasOwnProperty('hobby')){
			field_value = value.hobby;
		}
		component += '<td>';
		component += '<select id="md_field_citizen_col_hobby_'+RECORD_INDEX_citizen+'" record_index="'+RECORD_INDEX_citizen+'" class="md_field_citizen_col chzn-select" column_name="hobby"  multiple = "multiple">';
		var options = OPTIONS.hobby;
		component += '<option value></option>';
		for(var i=0; i<options.length; i++){
			var option = options[i];
			var selected = '';
			if($.inArray(option['value'],field_value)>-1){
				selected = 'selected="selected"';
			}
			component += '<option value="'+option['value']+'" '+selected+'>'+option['caption']+'</option>';
		}
		component += '</select>';
		component += '</td>';



		/////////////////////////////////////////////////////////////////////////////////////////////////////
		// Delete Button
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		component += '<td><input class="md_field_citizen_delete btn" record_index="'+RECORD_INDEX_citizen+'" primary_key="" type="button" value="Delete Citizen" /></td>';
		component += '</tr>';

		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // Add component to table
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('#md_table_citizen tbody').append(component);
		mutate_input();

	} // end of ADD ROW FUNCTION



	/////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Main event handling program
    //
    // * Initialization
    // * md_field_citizen_add.click (Add row)
    // * md_field_citizen_delete.click (Delete row)
    // * md_field_citizen_col.change (Edit cell)
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
	$(document).ready(function(){

		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // INITIALIZATION
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		synchronize_citizen();
		for(var i=0; i<old_data.length; i++){
			add_table_row_citizen(old_data[i]);
			RECORD_INDEX_citizen++;
		}


		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_citizen_add.click (Add row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('#md_field_citizen_add').click(function(){
			// new data
			var data = new Object();
			
			data.name = '';
			data.birthdate = '';
			data.job_id = '';
			data.IQ = '';
			data.Capita = '';
			data.hobby = '';
			// insert data to the DATA_citizen
			DATA_citizen.insert.push({
				'record_index' : RECORD_INDEX_citizen,
				'primary_key' : '',
				'data' : data,
			});

			// add table's row
			add_table_row_citizen(data);
			// add  by 1
			RECORD_INDEX_citizen++;

			// synchronize to the md_real_field_citizen_col
			synchronize_citizen();
		});


		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_citizen_delete.click (Delete row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('.md_field_citizen_delete').live('click', function(){
			var record_index = $(this).attr('record_index');
			// remove the component
			$('#md_field_citizen_tr_'+record_index).remove();

			var record_index_found = false;
			for(var i=0; i<DATA_citizen.insert.length; i++){
				if(DATA_citizen.insert[i].record_index == record_index){
					record_index_found = true;
					// delete element from insert
					DATA_citizen.insert.splice(i,1);
					break;
				}
			}
			if(!record_index_found){
				for(var i=0; i<DATA_citizen.update.length; i++){
					if(DATA_citizen.update[i].record_index == record_index){
						record_index_found = true;
						var primary_key = DATA_citizen.update[i].primary_key
						// delete element from update
						DATA_citizen.update.splice(i,1);
						// add it to delete
						DATA_citizen.delete.push({
							'record_index':record_index,
							'primary_key':primary_key
						});
						break;
					}
				}
			}
			synchronize_citizen();
		});


		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_citizen_col.change (Edit cell)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('.md_field_citizen_col').live('change', function(){
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
			for(var i=0; i<DATA_citizen.insert.length; i++){
				if(DATA_citizen.insert[i].record_index == record_index){
					record_index_found = true;
					// insert value
					eval('DATA_citizen.insert['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
					break;
				}
			}
			if(!record_index_found){
				for(var i=0; i<DATA_citizen.update.length; i++){
					if(DATA_citizen.update[i].record_index == record_index){
						record_index_found = true;
						// edit value
						eval('DATA_citizen.update['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
						break;
					}
				}
			}
			synchronize_citizen();
		});


	});




	/////////////////////////////////////////////////////////////////////////////////////////////////////////
    // General Functions
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

	// synchronize data to md_real_field_citizen_col.
	function synchronize_citizen(){
		$('#md_real_field_citizen_col').val(JSON.stringify(DATA_citizen));
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
		$('#md_table_citizen .datepicker-input').datepicker({
				dateFormat: js_date_format,
				showButtonPanel: true,
				changeMonth: true,
				changeYear: true
		});
		// date-picker-input-clear
		$('#md_table_citizen .datepicker-input-clear').click(function(){
			$(this).parent().find('.datepicker-input').val('');
			return false;
		});
		// chzn-select
		$("#md_table_citizen .chzn-select").chosen({allow_single_deselect: true});
		// numeric
		$('#md_table_citizen .numeric').numeric();
		$('#md_table_citizen .numeric').keydown(function(e){
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