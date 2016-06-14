// addition by gofrendi
function __add_form_control_class(){
    $('.flexigrid input[type!="button"][type!="checkbox"][type!="radio"][type!="submit"], .flexigrid select[class!="multiselect"], .flexigrid textarea').each(function(){
        if(!$(this).hasClass('form-control')){
            $(this).addClass('form-control');
        }
    });
}
function __mutate_delete_icon(){
    $('.flexigrid .delete-icon').each(function(){
        if($(this).html() == ''){
            $(this).addClass('btn btn-default');
            $(this).html('<i class="glyphicon glyphicon-minus-sign"></i>');
        }
    });
}
function __mutate_add_icon(){
    $('.flexigrid .fbutton .add').each(function(){
        if(!$(this).hasClass('btn')){
            $(this).addClass('btn btn-default');
            $(this).prepend('<i class="glyphicon glyphicon-plus-sign"></i>');
        }
    });
}

function __synchronize(real_input_id, data){
    $('#' + real_input_id).val(JSON.stringify(data));
}

function __mutate_input(table_id){
    // datepicker-input
    $('#' + table_id + ' .datepicker-input').datepicker({
            dateFormat: js_date_format,
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            yearRange: "c-100:c+100",
    });
    // date-picker-input-clear
    $('#' + table_id + ' .datepicker-input-clear').click(function(){
        $(this).parent().find('.datepicker-input').val('');
        return false;
    });
    // datetime-input
    $('#' + table_id + ' .datetime-input').datetimepicker({
        timeFormat: 'HH:mm:ss',
        dateFormat: js_date_format,
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true
    });

    $('#' + table_id + ' .datetime-input-clear').button();

    $('#' + table_id + ' .datetime-input-clear').click(function(){
        $(this).parent().find('.datetime-input').val("");
        return false;
    });
    // chzn-select
    $('#' + table_id + ' .chzn-select').chosen({allow_single_deselect: true, width:'180px', search_contains: true});
    // numeric
    $('#' + table_id + ' .numeric').numeric();
    $('#' + table_id + ' .numeric').keydown(function(e){
        if(e.keyCode == 38)
        {
            if(is_numeric($(this).val()))
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
            if(is_numeric($(this).val()))
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
    __add_form_control_class();
}

function get_object_property_as_str(object, key){
    if(typeof(object) != 'undefined' && object.hasOwnProperty(key)){
        return object[key];
    }else{
        return '';
    }
}

function build_single_select_option(value, options){
    var html = '<option value></option>';
    for(var i=0; i<options.length; i++){
        var option = options[i];
        var selected = '';
        if(option['value'] == value){
            selected = 'selected="selected"';
        }
        html += '<option value="'+option['value']+'" '+selected+'>'+option['caption']+'</option>';
    }
    return html;
}

function build_multiple_select_option(value, options){
    var html = '<option value></option>';
    for(var i=0; i<options.length; i++){
        var option = options[i];
        var selected = '';
        if($.inArray(option['value'],value)>-1){
            selected = 'selected="selected"';
        }
        html += '<option value="'+option['value']+'" '+selected+'>'+option['caption']+'</option>';
    }
    return html;
}

function js_datetime_to_php(js_datetime){
    if(typeof(js_datetime)=='undefined' || js_datetime == '' || js_datetime == null){
        return '';
    }
    var datetime_array = js_datetime.split(' ');
    var js_date = datetime_array[0];
    var time = datetime_array[1];
    var php_date = js_date_to_php(js_date);
    return php_date + ' ' + time;
}
function php_datetime_to_js(php_datetime){
    if(typeof(php_datetime)=='undefined' || php_datetime == '' || php_datetime == null){
        return '';
    }
    var datetime_array = php_datetime.split(' ');
    var php_date = datetime_array[0];
    var time = datetime_array[1];
    var js_date = php_date_to_js(php_date);
    return js_date + ' ' + time;
}

function js_date_to_php(js_date){
    if(typeof(js_date)=='undefined' || js_date == '' || js_date == null){
        return '';
    }
    var date = '';
    var month = '';
    var year = '';
    var php_date = '';
    if(DATE_FORMAT == 'uk-date'){
        var date_array = js_date.split('/');
        day = date_array[0];
        month = date_array[1];
        year = date_array[2];
        php_date = year+'-'+month+'-'+day;
    }else if(DATE_FORMAT == 'us-date'){
        var date_array = js_date.split('/');
        day = date_array[1];
        month = date_array[0];
        year = date_array[2];
        php_date = year+'-'+month+'-'+day;
    }else if(DATE_FORMAT == 'sql-date'){
        var date_array = js_date.split('-');
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

function is_numeric(input){
    return (input - 0) == input && input.length > 0;
}

// TODO: gonna be deprecated
function IsNumeric(input){
    return is_numeric(input);
}

$(document).ready(function(){
    __add_form_control_class();

    // make multi select shown as it should be
    $('.ui-helper-clearfix, .ui-helper-clearfix .selected, .ui-helper-clearfix .available').css('width','auto');
    $('.ui-helper-clearfix .selected ul, .ui-helper-clearfix .available ul').css('height','110px');
    // add & delete on detail table
    __mutate_add_icon();
    $('body').on('click', '.fbutton .add', function(){
        __mutate_delete_icon();
    });
    $('.flexigrid tbody').bind("DOMSubtreeModified",function(){
        __mutate_delete_icon();
    });
});
$(document).ajaxComplete(function(){
    __add_form_control_class();
});
$(window).on('load',function(){
    $('.connected-list').css('height', '75px');
});
