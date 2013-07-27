<?php
	$record_index = 0;
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/grocery_crud/css/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/grocery_crud/css/jquery_plugins/chosen/chosen.css'); ?>" />
<style type="text/css">
	/* set width of every detail input*/
	#md_table_physical_book .md_field_physical_book_col{
		width:auto!important;
		min-width:50px!important;
		max-width:150px!important;
	}
	#md_table_physical_book .datepicker-input{
		width:auto!important;
		min-width:50px!important;
		max-width:100px!important;
	}
	#md_table_physical_book .chzn-container,
	#md_table_physical_book .chzn-drop{
		width:auto!important;
		min-width:100px!important;
		max-width:250px!important;
	}
</style>

<table id="md_table_physical_book" class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Code</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<!-- the data presentation be here -->
	</tbody>
</table>
<input id="md_field_physical_book_add" class="btn" type="button" value="Add Physical Book" />
<br />
<!-- This is the real input. If you want to catch the data, please json_decode this input's value -->
<input id="md_real_field_physical_book_col" name="md_real_field_physical_book_col" type="hidden" />

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
	var RECORD_INDEX_physical_book = <?php echo $record_index; ?>;
	var DATA_physical_book = {update:new Array(), insert:new Array(), delete:new Array()};
	var old_data = <?php echo json_encode($result); ?>;
	for(var i=0; i<old_data.length; i++){
		var row = old_data[i];
		var record_index = i;
		var primary_key = row['id'];
		var data = row;
		delete data['id'];
		DATA_physical_book.update.push({
			'record_index' : record_index,
			'primary_key' : primary_key,
			'data' : data,
		});
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ADD ROW FUNCTION
	//
	// * When "Add Physical Book" clicked, this function is called without parameter.
	// * When page loaded for the first time, this function is called with value parameter
	//
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	function add_table_row_physical_book(value){

		var component = '<tr id="md_field_physical_book_tr_'+RECORD_INDEX_physical_book+'" class="md_field_physical_book_tr">';
		
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        //    FIELD "code"
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		var field_value = ''
		if(typeof(value) != 'undefined' && value.hasOwnProperty('code')){
			field_value = value.code;
		}
		component += '<td>';
		component += '<input id="md_field_physical_book_col_code_'+RECORD_INDEX_physical_book+'" record_index="'+RECORD_INDEX_physical_book+'" class="md_field_physical_book_col" column_name="code" type="text" value="'+field_value+'"/>';
		component += '</td>';



		/////////////////////////////////////////////////////////////////////////////////////////////////////
		// Delete Button
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		component += '<td><input class="md_field_physical_book_delete btn" record_index="'+RECORD_INDEX_physical_book+'" primary_key="" type="button" value="Delete Physical Book" /></td>';
		component += '</tr>';

		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // Add component to table
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('#md_table_physical_book tbody').append(component);
		mutate_input();

	} // end of ADD ROW FUNCTION



	/////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Main event handling program
    //
    // * Initialization
    // * md_field_physical_book_add.click (Add row)
    // * md_field_physical_book_delete.click (Delete row)
    // * md_field_physical_book_col.change (Edit cell)
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
	$(document).ready(function(){

		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // INITIALIZATION
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		synchronize_physical_book();
		for(var i=0; i<DATA_physical_book.update.length; i++){
			add_table_row_physical_book(DATA_physical_book.update[i].data);
			RECORD_INDEX_physical_book++;
		}


		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_physical_book_add.click (Add row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('#md_field_physical_book_add').click(function(){
			// new data
			var data = new Object();
			
			data.code = '';
			// insert data to the DATA_physical_book
			DATA_physical_book.insert.push({
				'record_index' : RECORD_INDEX_physical_book,
				'primary_key' : '',
				'data' : data,
			});

			// add table's row
			add_table_row_physical_book(data);
			// add  by 1
			RECORD_INDEX_physical_book++;

			// synchronize to the md_real_field_physical_book_col
			synchronize_physical_book();
		});


		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_physical_book_delete.click (Delete row)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('.md_field_physical_book_delete').live('click', function(){
			var record_index = $(this).attr('record_index');
			// remove the component
			$('#md_field_physical_book_tr_'+record_index).remove();

			var record_index_found = false;
			for(var i=0; i<DATA_physical_book.insert.length; i++){
				if(DATA_physical_book.insert[i].record_index == record_index){
					record_index_found = true;
					// delete element from insert
					DATA_physical_book.insert.splice(i,1);
					break;
				}
			}
			if(!record_index_found){
				for(var i=0; i<DATA_physical_book.update.length; i++){
					if(DATA_physical_book.update[i].record_index == record_index){
						record_index_found = true;
						var primary_key = DATA_physical_book.update[i].primary_key
						// delete element from update
						DATA_physical_book.update.splice(i,1);
						// add it to delete
						DATA_physical_book.delete.push({
							'record_index':record_index,
							'primary_key':primary_key
						});
						break;
					}
				}
			}
			synchronize_physical_book();
		});


		/////////////////////////////////////////////////////////////////////////////////////////////////////
        // md_field_physical_book_col.change (Edit cell)
        /////////////////////////////////////////////////////////////////////////////////////////////////////
		$('.md_field_physical_book_col').live('change', function(){
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
			for(var i=0; i<DATA_physical_book.insert.length; i++){
				if(DATA_physical_book.insert[i].record_index == record_index){
					record_index_found = true;
					// insert value
					eval('DATA_physical_book.insert['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
					break;
				}
			}
			if(!record_index_found){
				for(var i=0; i<DATA_physical_book.update.length; i++){
					if(DATA_physical_book.update[i].record_index == record_index){
						record_index_found = true;
						// edit value
						eval('DATA_physical_book.update['+i+'].data.'+column_name+' = '+JSON.stringify(value)+';');
						break;
					}
				}
			}
			synchronize_physical_book();
		});


	});

	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// reset field on save
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	$(document).ajaxSuccess(function(event, xhr, settings) {
        response = $.parseJSON(xhr.responseText);
        if (settings.url == "{{ module_site_url }}manage_book/index/insert" &&
            response.success == true
        ) {
            DATA_citizen = {update:new Array(), insert:new Array(), delete:new Array()};
            $('#md_table_physical_book tr').not(':first').remove();
                synchronize_citizen();
        }
    });


	/////////////////////////////////////////////////////////////////////////////////////////////////////////
    // General Functions
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

	// synchronize data to md_real_field_physical_book_col.
	function synchronize_physical_book(){
		$('#md_real_field_physical_book_col').val(JSON.stringify(DATA_physical_book));
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
		$('#md_table_physical_book .datepicker-input').datepicker({
				dateFormat: js_date_format,
				showButtonPanel: true,
				changeMonth: true,
				changeYear: true
		});
		// date-picker-input-clear
		$('#md_table_physical_book .datepicker-input-clear').click(function(){
			$(this).parent().find('.datepicker-input').val('');
			return false;
		});
		// chzn-select
		$("#md_table_physical_book .chzn-select").chosen({allow_single_deselect: true});
		// numeric
		$('#md_table_physical_book .numeric').numeric();
		$('#md_table_physical_book .numeric').keydown(function(e){
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