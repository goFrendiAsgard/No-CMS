// addition by gofrendi
function __add_form_control_class(){
    $('.flexigrid input[type!="button"][type!="checkbox"], .flexigrid select, .flexigrid textarea').each(function(){
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
$(document).ready(function(){
    __add_form_control_class();

    // make multi select shown as it should be
    $('.ui-helper-clearfix, .ui-helper-clearfix .selected, .ui-helper-clearfix .available').css('width','auto');
    $('.ui-helper-clearfix .selected ul, .ui-helper-clearfix .available ul').css('height','110px');
    // add & delete on detail table
    __mutate_add_icon();
    $('.fbutton .add').live('click', function(){
        __mutate_delete_icon();
    });
    $('.flexigrid tbody').bind("DOMSubtreeModified",function(){
        __mutate_delete_icon();
    });
});
$(document).ajaxComplete(function(){
    __add_form_control_class();
});

function js_datetime_to_php(js_datetime){
    if(typeof(js_datetime)=='undefined' || js_datetime == ''){
        return '';
    }
    var datetime_array = js_datetime.split(' ');
    var js_date = datetime_array[0];
    var time = datetime_array[1];
    var php_date = js_date_to_php(js_date);
    return php_date + ' ' + time;
}
function php_datetime_to_js(php_datetime){
    if(typeof(php_datetime)=='undefined' || php_datetime == ''){
        return '';
    }
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

function IsNumeric(input){
    return (input - 0) == input && input.length > 0;
}
