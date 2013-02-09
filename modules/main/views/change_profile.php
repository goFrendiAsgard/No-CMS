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
	                    message += 'Password is empty<br />';
	                }
	                if(password != confirm_password){
	                    message += 'Confirm password doesn\'t match';
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

<?php
    echo form_open('main/change_profile');
    echo form_label('User Name');
    echo form_input('user_name', $user_name).br();
    echo form_label('E mail');
    echo form_input('email', $email).br();
    echo form_label('Real Name');
    echo form_input('real_name', $real_name).br().br();
	echo form_checkbox('change_password','True',FALSE).' Change Password'.br().br();
    echo form_label('Password');
    echo form_password('password').br();
    echo form_label('Confirm Password');
    echo form_password('confirm_password').br();    
    echo form_submit('change_profile', 'Change Profile');
    echo form_close();
?>
<img id="img_ajax_loader" style="display:none;" src="<?php echo base_url('assets/nocms/images/ajax-loader.gif');?>" /><br />
<div id="message" class="alert alert-error"></div>
