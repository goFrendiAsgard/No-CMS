<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    .message:empty{
        display:none;
    }
    #btn-register, .register_input{
        display:none;
    }
</style>
<h3>{{ language:Register }}</h3>
<?php
    echo '<div class="col-md-8">';
        echo form_open_multipart('main/register', 'id="form-register" class="ajax-check-form form form-horizontal"');
        echo form_input(array('name'=>'user_name', 'value'=>'', 'class'=>'register_input'));
        echo form_input(array('name'=>'email', 'value'=>'', 'class'=>'register_input'));
        echo form_input(array('name'=>'real_name', 'value'=>'', 'class'=>'register_input'));
        echo form_input(array('name'=>'password', 'value'=>'', 'class'=>'register_input'));
        echo form_input(array('name'=>'confirm_password', 'value'=>'', 'class'=>'register_input'));

        echo build_register_input($secret_code, $user_name, $email, $real_name);

        // additional input from hook
        if(trim($additional_input) != ''){
            echo '<hr />';
            echo $additional_input;
        }

        echo '<div class="form-group col-sm-12 hidden-sm-hidden-xs">';
            echo form_submit('register', $register_caption, 'id="btn-register" class="btn btn-primary pull-right" style="display:none;"');
        echo '</div>';
    echo '</div>';

    // Submit button and error notification
    echo '<div id="div-notification-container"class="col-md-4">';
        echo '<div id="div-notification" class="col-sm-12">';
            echo '<img id="img_ajax_loader" style="display:none;" src="'.base_url('assets/nocms/images/ajax-loader.gif').'" /><br />';
            echo '<div id="success-message" class="alert alert-success message hidden-xs hidden-sm"></div>';
            echo '<div id="error-message" class="alert alert-danger message"></div>';
            echo form_submit('register', $register_caption, 'id="btn-register" class="btn btn-primary btn-lg pull-right" style="display:none;"');
        echo '</div>';
    echo '</div>';

    echo form_close();
?>
<script type="text/javascript">
    var REQUEST_EXISTS = false;
    var REQUEST = "";
    function check_register(){
        var user_name =  $('input[name="<?php echo $secret_code; ?>user_name"]').val();
        var email = $('input[name="<?php echo $secret_code; ?>email"]').val();
        var password = $('input[name="<?php echo $secret_code; ?>password"]').val();
        var confirm_password = $('input[name="<?php echo $secret_code; ?>confirm_password"]').val();
        $("#img_ajax_loader").show();
        if(REQUEST_EXISTS){
            REQUEST.abort();
        }
        REQUEST_EXISTS = true;
        // build request data
        var request_data = {};
        $('.ajax-check-form input, .ajax-check-form select, .ajax-check-form textarea').each(function(){
            if(($(this).attr('type') == 'checkbox' && $(this).attr('checked')) || $(this).attr('type') != 'checkbox'){
                request_data[$(this).attr('name')] = $(this).val();
            }
        });
        request_data["user_name"] = user_name;
        request_data["email"] = email;
        REQUEST = $.ajax({
            "url" : "check_registration",
            "type" : "POST",
            "data" : request_data,
            "dataType" : "json",
            "success" : function(data){
                if(!data.error && !data.exists && user_name!='' && password!='' && password==confirm_password){
                    $('input[name="register"]').show();
                    $('input[name="register"]').removeAttr('disabled');
                    $('#success-message').html('{{ language:Here you can register. Click the button once the data is complete }}');
                }else{
                    $('input[name="register"]').hide();
                    $('input[name="register"]').attr('disabled', 'disabled');
                    $('#success-message').html('');
                }

                // get message from server + local check
                var message = '';
                if(data.message!=''){
                    message += data.message+'<br />';
                }
                if(password == ''){
                    message += '{{ language:Password is empty }}<br />';
                }
                if(password != confirm_password){
                    message += "{{ language:Confirm password doesn't match }}";
                }

                if(message != $('#error-message').html()){
                    $('#error-message').html(message);
                }
                REQUEST_EXISTS = false;
                $("#img_ajax_loader").hide();
            },
            error: function(xhr, textStatus, errorThrown){
                if(textStatus != 'abort'){
                    setTimeout(check_register, 10000);
                }
            }
        });
    }

    function capitaliseFirstLetter(string)
    {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
    function adjust_div_notification(){
        if($(window).width() >= 992){
            var navbar_top = 0;
            var navbar_relative_top = 0;
            var navbar_height = 0;
            var component_container_top = $('#div-notification-container').offset().top;
            var component_container_width = $('#div-notification-container').width();
            if($('.navbar-fixed-top').length > 0){
                navbar_top = $('.navbar-fixed-top').offset().top;
                navbar_relative_top = $('.navbar-fixed-top').position().top;
                navbar_height = $('.navbar-fixed-top').height();
            }
            if(component_container_top < navbar_top + navbar_height){
                var new_component_top = navbar_height + navbar_relative_top;
                $('#div-notification').css({position:"fixed", top:new_component_top});
            }else{
                $('#div-notification').removeAttr('style');
            }
            // ensure that div-notification has exactly the same width as div-notification-container
            $('#div-notification').width(component_container_width);
        }else{
            $('#div-notification').removeAttr('style');
        }
    }

    $(document).ready(function(){

        check_register();

        $('input, select, textarea').keyup(function(){
            check_register();
        });
        $('input, select, textarea').change(function(){
            check_register();
        });

        $('#<?php echo $secret_code; ?>user_name').keyup(function(){
            var value = $('#<?php echo $secret_code; ?>user_name').val();
            $('#site_title').val(capitaliseFirstLetter(value));
            $('#site_slogan').val('Website ' + capitaliseFirstLetter(value));
        });

        $(document).on('scroll', function(){
            adjust_div_notification();
        });
        adjust_div_notification();
        $(window).resize(function(event){
            adjust_div_notification();
        });

    });
</script>
