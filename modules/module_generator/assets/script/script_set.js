function get_option_caption(table_name, controller_name, navigation_caption){
	return	table_name+' [controller: '+controller_name+
		', page: '+navigation_caption+']';
}

function add(table){
	var index = module_table.length;
	var controller_name = "", 
		navigation_caption = "", 
		table_name = "";
	// get controller & navigation name
	if(typeof(table) != "undefined"){
		var names = get_controller_and_navigation_name(table);
		controller_name = names["controller_name"];
		navigation_caption = names["navigation_caption"];	
		table_name = table;
	}else{
		controller_name = $("#controller_name_add").val();
		navigation_caption = $("#navigation_caption_add").val();
		table_name = $("#available_table_add>option:selected").val();
	}
	// add to variable module_table
	module_table[index] = {
			"table_name" : table_name,
			"navigation_caption" : navigation_caption,
			"controller_name" : controller_name,
		};
	// add module_data's options
	var attribute = 'index="'+index+'"';
	var option = '<option '+attribute+'>'+
		get_option_caption(table_name, controller_name, navigation_caption)+
		'</option>';	
	$("#module_data").append(option);
}

function edit(){
	var index = $("#index_edit").val();
	var controller_name = $("#controller_name_edit").val();
	var navigation_caption = $("#navigation_caption_edit").val();
	module_table[index]["controller_name"] = controller_name;
	module_table[index]["navigation_caption"] = navigation_caption;
	$('#module_data>option[index="'+index+'"]').html(get_option_caption(table_name, controller_name, navigation_caption));
}

function add_all(){
	$("#available_table_add>option").each(function(){
		var option = $(this);
		table_name = option.val();
		add(table_name);
	});
}

function remove(){
	$("#module_data>option:selected").each(function(){
		var child = $(this);
		var index = parseInt(child.attr("index"));
		var module_table_count = module_table.length;
		// shift module_table up
		for(var i=index+1; i<module_table_count; i++){
			module_table[i-1] = module_table[i];
		}
		// delete the last element of module_table
		delete(module_table[module_table_count-1]);
		module_table.length = module_table_count-1;
		
		// delete from module_data's options
		child.remove();
		// adjust indexes of module_data's options
		$("#module_data>option").each(function(){
			var option_index = parseInt($(this).attr("index"));
			if(option_index>index){
				$(this).attr("index", option_index-1);
			}
		});
	});
	console.log(module_table);
	
}

function swap_module_table(index_1, index_2){
	// swap module_table
	var temp = module_table[index_1];
	module_table[index_1] = module_table[index_2];
	module_table[index_2] = temp;
	// swap indexes of module_data's option
	var selector_1 = '#module_data>option[index="'+index_1+'"]';
	var selector_2 = '#module_data>option[index="'+index_2+'"]';
	var selector_tmp = '#module_data>option[index="tmp"]';
	$(selector_1).attr("index", "tmp");
	$(selector_2).attr("index", index_1);
	$(selector_tmp).attr("index", index_2);
}

function up(){
	$("#module_data>option:selected").each(function(){
		var child = $(this);
		var index = parseInt(child.attr("index"));
		// check if allowed up
		if(index>0){
			swap_module_table(index, index-1);
			child.prev().before(child); 
		}		
	});	
}

function down(){
	$("#module_data>option:selected").each(function(){
		var child = $(this);
		var index = parseInt(child.attr("index"));
		// check if allowed up
		if(index<module_table.length-1){
			swap_module_table(index, index+1);
			child.next().after(child); 
		}		
	});
}

function get_controller_and_navigation_name(table_name){
	// predict controller & navigation name based on table name
	var controller_name = '';
	var navigation_caption = '';
	var index = table_name.indexOf("_");
	if(index<0){index = table_name.indexOf("-");}
	if(index<0){index = table_name.indexOf(".");}
	if(index>0){
		controller_name = table_name.slice(0,index);
		navigation_caption = table_name.slice(index+1);
	}else{
		controller_name = table_name;
		navigation_caption = table_name;
	}
	// give the return
	return {
		"controller_name" : controller_name,
		"navigation_caption" : navigation_caption,
	};
}

function adjust_available_table_add(){
	var table_name = $("#available_table_add>option:selected").val();
	var names = get_controller_and_navigation_name(table_name);
	var controller_name = names["controller_name"];
	var navigation_caption = names["navigation_caption"];	
	
	$("#controller_name_add").val(controller_name);
	$("#navigation_caption_add").val(navigation_caption);
}

function submit(){
	$("#img_ajax_loader").show();
	$.ajax({
		url: BASE_URL+'/module_generator/generate',
		type: 'POST',
		dataType: 'json',
		data: {
			'namespace' : $("#module_namespace").val(),
			'directory' : $("#module_directory").val(),
			'overwrite' : $('#module_overwrite').attr('checked')=='checked'?1:0,
			'structure' : module_table
		},
		success: function(response){
			if(response.success){
				$("#error_message").hide();
				$("#error_message").html('');
				alert('The module has been generated successfully');
			}else{		
				var html = "";
				html += "<ul>";
				for(var i=0; i<response.errors.length; i++){
					var error = response.errors[i];
					html += "<li>"+error+"</li>";
				}
				html += "</ul>";
				$("#error_message").html(html);
				$("#error_message").show();
				alert('Some error ocured when trying to generate module');				
			}
		},
		error: function(response){
			$("#error_message").html('AJAX request failed');
			$("#error_message").show();
			alert('Some error ocured when trying to generate module');
			console.log(response);
		},
		complete: function(){
			$("#img_ajax_loader").hide();
		}
	});
}

// MAIN PROGRAM ================================================================
$(document).ready(function(){
	for(var i=0; i<TABLES.length; i++){
		var table = TABLES[i];
		var table_name = table["table_name"];
		var attribute = 'id="'+table_name+'" name="'+table_name+'" ';
		attribute += 'index="'+i+'" ';
		// the first node is selected
		if(i==0){
			attribute += 'selected="selected"';
		}		
		var option = '<option '+attribute+'>'+table_name+'</option>';
		$('#available_table_add').append(option);
	}
	adjust_available_table_add();
	$("#btn_show_form_add").colorbox({inline:true, width:"50%", href:"#form_add"});
	$("#btn_show_form_edit").colorbox({
			inline:true, width:"50%", href:"#form_edit",
			onOpen:function(){ 
				// change the value of all input
				var selected_option = $("#module_data>option:selected").first();
				if(selected_option.length>0){
					var index = parseInt(selected_option.attr("index"));
					var table_name = module_table[index]["table_name"];
					var controller_name = module_table[index]["controller_name"];					
					var navigation_caption = module_table[index]["navigation_caption"];
					$("#index_edit").val(index);
					$("#table_name_edit").html('<b>'+table_name+'</b>');
					$("#controller_name_edit").val(controller_name);
					$("#navigation_caption_edit").val(navigation_caption);
				} 
			},
		});
	
	$("#btn_add_all").click(function(){
		add_all(); 
		return false;
	});
	$("#btn_add").click(function(){
		add();
		$.colorbox.close();
		return false;
	});
	$("#btn_edit").click(function(){
		edit();
		$.colorbox.close();
		return false;
	});
	$("#btn_remove").click(function(){
		remove(); 
		return false;
	});
	$("#btn_up").click(function(){
		up(); 
		return false;
	});
	$("#btn_down").click(function(){
		down(); 
		return false;
	});
	
	$("#available_table_add").change(function(){
		adjust_available_table_add();
	});	
	
	$("#btn_submit").click(function(){
		submit();
		return false;
	});
});

