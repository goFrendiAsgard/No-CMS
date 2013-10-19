<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #message:empty{
        display:none;
    }
</style>
<script type="text/javascript">
	var REQUEST_EXISTS = false;
	var REQUEST = "";
    function check_user_exists(){
        var user_name =  $('input[name="user_name"]').val();
        var email = $('input[name="email"]').val();
        var password = $('input[name="password"]').val();        
        var confirm_password = $('input[name="confirm_password"]').val();
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
    echo form_label('{{ language:User Name }}').br();
    echo form_input('user_name', $user_name).br();
    echo form_label('{{ language:Email }}').br();
    echo form_input('email', $email).br();
    echo form_label('{{ language:Real Name }}').br();
    echo form_input('real_name', $real_name).br();
    echo form_label('{{ language:Password }}').br();
    echo form_password('password').br();
    echo form_label('{{ language:Confirm Password }}').br();
    echo form_password('confirm_password').br();
    echo form_submit('register', $register_caption);
    echo form_close();
    echo br();
?>
<img id="img_ajax_loader" style="display:none;" src="<?php echo base_url('assets/nocms/images/ajax-loader.gif');?>" /><br />
<div id="message" class="alert alert-error"></div>
