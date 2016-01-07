<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #message:empty{
        display:none;
    }
    #btn-register, .register_input{
        display:none;
    }
</style>
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
                    console.log($('input[name="register"]'));
                }else{
                    $('input[name="register"]').hide();
                    $('input[name="register"]').attr('disabled', 'disabled');
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
                    message += '{{ language:Confirm password doesn\'t match }}';
                }

                if(message != $('#message').html()){
                    $('#message').html(message);
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
    });
</script>
<h3>{{ language:Register }}</h3>
<?php
    echo form_open_multipart('main/register', 'id="form-register" class="ajax-check-form form form-horizontal"');
    echo form_input(array('name'=>'user_name', 'value'=>'', 'class'=>'register_input'));
    echo form_input(array('name'=>'email', 'value'=>'', 'class'=>'register_input'));
    echo form_input(array('name'=>'real_name', 'value'=>'', 'class'=>'register_input'));
    echo form_input(array('name'=>'password', 'value'=>'', 'class'=>'register_input'));
    echo form_input(array('name'=>'confirm_password', 'value'=>'', 'class'=>'register_input'));

    echo '<div class="form-group">';
    echo form_label('{{ language:User Name }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_input($secret_code.'user_name', $user_name,
        'id="'.$secret_code.'user_name" placeholder="User Name" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Email }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_input($secret_code.'email', $email,
        'id="'.$secret_code.'email" placeholder="Email" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Real Name }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_input($secret_code.'real_name', $real_name,
        'id="'.$secret_code.'real_name" placeholder="Real Name" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Password }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_password($secret_code.'password', '',
        'id="'.$secret_code.'password" placeholder="Password" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Confirm Password }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_password($secret_code.'confirm_password', '',
        'id="'.$secret_code.'confirm_password" placeholder="Password (again)" class="form-control"');
    echo '</div>';
    echo '</div>';

    // additional input from hook
    echo $additional_input;

    echo '<div class="form-group"><div class="col-sm-offset-4 col-sm-8">';
    echo '<img id="img_ajax_loader" style="display:none;" src="'.base_url('assets/nocms/images/ajax-loader.gif').'" /><br />';
    echo '<div id="message" class="alert alert-danger"></div>';
    echo form_submit('register', $register_caption, 'id="btn-register" class="btn btn-primary" style="display:none;"');
    echo '</div></div>';


    echo form_close();
?>
