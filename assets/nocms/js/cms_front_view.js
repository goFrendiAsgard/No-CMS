var URL                    = typeof URL == 'undefined'? '' : URL;
var ALLOW_NAVIGATE_BACKEND = typeof ALLOW_NAVIGATE_BACKEND == 'undefined'? false: ALLOW_NAVIGATE_BACKEND;
var HAVE_ADD_PRIVILEGE     = typeof HAVE_ADD_PRIVILEGE == 'undefined'? false: HAVE_ADD_PRIVILEGE;
var BACKEND_URL            = typeof BACKEND_URL == 'undefined'? '': BACKEND_URL;
var LOAD_MESSAGE           = typeof LOAD_MESSAGE == 'undefined'? 'Loading ...': LOAD_MESSAGE;
var REACH_END_MESSAGE      = typeof REACH_END_MESSAGE == 'undefined'? 'No more data to show': REACH_END_MESSAGE;

var SCROLL_WORK            = typeof SCROLL_WORK == 'undefined'? true: SCROLL_WORK;

var PAGE                   = 1;
var LOADING                = false;
var RUNNING_REQUEST        = false;
var STOP_REQUEST           = false;
var REQUEST;

if(typeof prepare_search_post_data != 'function'){
    function prepare_search_post_data(data){
        return data;
    }
}
function fetch_more_data(async){
    if(typeof(async) == 'undefined'){
        async = true;
    }
    $('#record_content_bottom').html(LOAD_MESSAGE);
    // Don't send another request before the first one completed
    if(RUNNING_REQUEST || !SCROLL_WORK){
        return 0;
    }
    // post data
    var DATA = new Object();
    DATA = prepare_search_post_data(DATA);
    DATA['page'] = PAGE;
    if(!('keyword' in DATA) && $('#input_search').length > 0){
        DATA['keyword'] = $('#input_search').val();
    }
    // send the request
    RUNNING_REQUEST = true;
    REQUEST = $.ajax({
        'url'  : URL,
        'type' : 'POST',
        'async': async,
        'data' : DATA,
        'success'  : function(response){
            // show contents
            $('#record_content').append(response);
            // stop request if response is empty
            if(response.trim() == ''){
                STOP_REQUEST = true;
            }

            // show bottom contents
            var bottom_content = REACH_END_MESSAGE;
            if(ALLOW_NAVIGATE_BACKEND && HAVE_ADD_PRIVILEGE){
                bottom_content += '&nbsp; <a href="'+BACKEND_URL+'/add/" class="add_record">Add new</a>';
            }
            $('#record_content_bottom').html(bottom_content);
            RUNNING_REQUEST = false;
            PAGE ++;
        },
        'complete' : function(response){
            RUNNING_REQUEST = false;
        }
    });

}

function adjust_load_more_button(){
    if(SCROLL_WORK){
        if(screen.width >= 1024){
            $('#btn_load_more').hide();
            $('#record_content_bottom').show();
        }else{
            $('#btn_load_more').show();
            $('#record_content_bottom').hide();
        }
    }else{
        $('#btn_load_more').hide();
    }
}

function reset_content(){
    $('#record_content').html('');
    PAGE = 0;
    fetch_more_data(true);
    adjust_load_more_button();
}

// main program
$(document).ready(function(){
    fetch_more_data(true);
    adjust_load_more_button();

    // delete click
    $('body').on('click', '.delete_record',function(event){
        var url = $(this).attr('href');
        var primary_key = $(this).attr('primary_key');
        if (confirm("Do you really want to delete?")) {
            $.ajax({
                url : url,
                dataType : 'json',
                success : function(response){
                    if(response.success){
                        $('div#record_'+primary_key).remove();
                    }
                }
            });
        }
        event.preventDefault();
        return false;
    });

    // input change
    $('#form_search select, #form_search input, #form_search textarea').change(function(event){
        reset_content();
    })

    // button search click
    $('#btn_search').click(function(){
        reset_content();
    });

    // scroll
    $(window).scroll(function(){
        if(screen.width >= 1024 && !STOP_REQUEST && !LOADING && SCROLL_WORK){
            if($('#record_content_bottom').position().top <= $(window).scrollTop() + $(window).height() ){
                LOADING = true;
                fetch_more_data(false);
                LOADING = false;
            }
        }
    });

    // load more click
    $('#btn_load_more').click(function(event){
        if(!LOADING){
            LOADING = true;
            fetch_more_data(true);
            LOADING = false;
        }
        $(this).hide();
        event.preventDefault();
    });

});
