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
            "data" : {"user_name":user_name},
            "dataType" : "json",
            "success" : function(data){
            	if(!data.exists && user_name!='' && password!='' && password==confirm_password){
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
                    message += '{{ language:lang_cp_pw_empty }}<br />';
                }
                if(password != confirm_password){
                    message += '{{ language:lang_cp_pw_no_match }}';
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

<h3>{{ language:lang_reg_title }}</h3>
<?php
    echo form_open('main/register');
    echo form_label('{{ language:lang_cp_user_name }}').br();
    echo form_input('user_name', $user_name).br();
    echo form_label('{{ language:lang_cp_email }}').br();
    echo form_input('email', $email).br();
    echo form_label('{{ language:lang_cp_real_name }}').br();
    echo form_input('real_name', $real_name).br();
    echo form_label('{{ language:lang_cp_new_pw }}').br();
    echo form_password('password').br();
    echo form_label('{{ language:lang_cp_new_pw_again }}').br();
    echo form_password('confirm_password').br();
    echo form_submit('register', $this->No_CMS_Model->cms_lang('lang_reg_submit'), 'class="btn btn-primary"');
    echo form_close();
    echo br();
?>
<img id="img_ajax_loader" style="display:none;" src="<?php echo base_url('assets/nocms/images/ajax-loader.gif');?>" /><br />
<div id="message" class="alert alert-error"></div>
