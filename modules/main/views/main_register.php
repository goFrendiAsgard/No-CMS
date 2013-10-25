<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #message:empty{
        display:none;
    }
    .register_input{
        display:none!important;
    }
</style>
<script type="text/javascript">
	var REQUEST_EXISTS = false;
	var REQUEST = "";
    function check_user_exists(){
        var user_name =  $('input[name="<?=$secret_code?>user_name"]').val();
        var email = $('input[name="<?=$secret_code?>email"]').val();
        var password = $('input[name="<?=$secret_code?>password"]').val();        
        var confirm_password = $('input[name="<?=$secret_code?>confirm_password"]').val();
        $("#img_ajax_loader").show();
        if(REQUEST_EXISTS){
        	REQUEST.abort();
        }
        REQUEST_EXISTS = true;
        REQUEST = $.ajax({
            "url" : "check_registration",
            "type" : "POST",
            "data" : {"user_name":user_name, "email":email},
            "dataType" : "json",
            "success" : function(data){
            	if(!data.error && !data.exists && user_name!='' && password!='' && password==confirm_password){
                    $('input[name="register"]').show();
                    $('input[name="register"]').removeAttr('disabled');
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
            }
        });
    }

    $(document).ready(function(){
        check_user_exists();
        $('input').keyup(function(){
            check_user_exists();
        });
    })
</script>
<h3>{{ language:Register }}</h3>
<?php
    echo form_open('main/register');
    echo form_input(array('name'=>'user_name', 'value'=>'', 'class'=>'register_input'));
    echo form_input(array('name'=>'email', 'value'=>'', 'class'=>'register_input'));
    echo form_input(array('name'=>'real_name', 'value'=>'', 'class'=>'register_input'));
    echo form_input(array('name'=>'password', 'value'=>'', 'class'=>'register_input'));
    echo form_input(array('name'=>'confirm_password', 'value'=>'', 'class'=>'register_input'));
    echo form_label('{{ language:User Name }}');
    echo form_input($secret_code.'user_name', $user_name).br();
    echo form_label('{{ language:Email }}');
    echo form_input($secret_code.'email', $email).br();
    echo form_label('{{ language:Real Name }}');
    echo form_input($secret_code.'real_name', $real_name).br();
    echo form_label('{{ language:Password }}');
    echo form_password($secret_code.'password').br();
    echo form_label('{{ language:Confirm Password }}');
    echo form_password($secret_code.'confirm_password').br();
    echo form_submit('register', $register_caption);
    echo form_close();
    echo br();
?>
<img id="img_ajax_loader" style="display:none;" src="<?php echo base_url('assets/nocms/images/ajax-loader.gif');?>" /><br />
<div id="message" class="alert alert-error"></div>
