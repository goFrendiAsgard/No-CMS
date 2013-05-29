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
        var change_password_checked = $('input[name="change_password"]').attr("checked")=='checked';
        $("#img_ajax_loader").show();
        if(REQUEST_EXISTS){
        	REQUEST.abort();
        }
        REQUEST_EXISTS = true;
        REQUEST = $.ajax({
            "url" : "check_change_profile",
            "type" : "POST",
            "data" : {"user_name":user_name},
            "dataType" : "json",
            "success" : function(data){
                if(!data.exists && user_name!='' &&
                ((!change_password_checked) || (change_password_checked && password!='' && password==confirm_password)) ){
                    $('input[name="change_profile"]').show();
                    $('input[name="change_profile"]').removeAttr('disabled');
                }else{
                    $('input[name="change_profile"]').hide();
                    $('input[name="change_profile"]').attr('disabled', 'disabled');
                }

                // get message from server + local check
                var message = '';
                if(data.message!=''){
                    message += data.message+'<br />';
                }
                if(change_password_checked){
	                if(password == '' && change_password_checked){
	                    message += '{{ language:lang_cp_pw_empty }}<br />';
	                }
	                if(password != confirm_password){
	                    message += '{{ language:lang_cp_pw_no_match }}';
	                }
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
        $('input').change(function(){
        	check_user_exists();
        });
    })
</script>

<h3>{{ language:Change Profile }}</h3>
<?php
    echo form_open('main/change_profile');
    echo form_label('{{ language:lang_cp_user_name }}');
    echo form_input('user_name', $user_name).br();
    echo form_label('{{ language:lang_cp_email }}');
    echo form_input('email', $email).br();
    echo form_label('{{ language:lang_cp_real_name }}');
    echo form_input('real_name', $real_name).br().br();
	echo form_checkbox('change_password','True',FALSE).' {{ language:lang_cp_do_change_pw }}'.br().br();
    echo form_label('{{ language:lang_cp_new_pw }}');
    echo form_password('password').br();
    echo form_label('{{ language:lang_cp_new_pw_again }}');
    echo form_password('confirm_password').br();
    echo form_submit('change_profile', $this->No_CMS_Model->cms_lang('lang_cp_submit'), 'class="btn btn-primary"');
    echo form_close();
?>
<img id="img_ajax_loader" style="display:none;" src="<?php echo base_url('assets/nocms/images/ajax-loader.gif');?>" /><br />
<div id="message" class="alert alert-error"></div>
