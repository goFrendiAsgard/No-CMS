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
        var email =  $('input[name="email"]').val();
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
            "data" : {"user_name":user_name, "email":email},
            "dataType" : "json",
            "success" : function(data){
                if(!data.error && !data.exists && user_name!='' &&
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
	                    message += '{{ language:Password is empty }}<br />';
	                }
	                if(password != confirm_password){
	                    message += '{{ language:Confirm password doesn\'t match }}';
	                }
                }

                if(message != $('#message').html()){
                    $('#message').html(message);
                }
                REQUEST_EXISTS = false;
                $("#img_ajax_loader").hide();
            },
            error: function(xhr, textStatus, errorThrown){
                if(textStatus != 'abort'){
                    setTimeout(check_user_exists, 10000);    
                }
            }
        });
    }

    function toggle_password_input(){
        if($('input[name="change_password"]').prop('checked')){
            $('.password-input').show();
        }else{
            $('.password-input').hide();
        }
    }

    $(document).ready(function(){
        toggle_password_input();
        check_user_exists();
        $('input').keyup(function(){
            check_user_exists();
        });
        $('input').change(function(){
        	check_user_exists();
        });
        $('input[name="change_password"]').change(function(){toggle_password_input();});
    })
</script>
<h3>{{ language:Change Profile }}</h3>
<?php
    echo form_open('main/change_profile', 'class="form form-horizontal"');

    echo '<div class="form-group">';
    echo form_label('{{ language:User Name }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_input('user_name', $user_name, 
        'id="user_name" placeholder="User Name" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Email }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_input('email', $email, 
        'id="email" placeholder="Email" class="form-control"');   
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Real Name }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_input('real_name', $real_name, 
        'id="real_name" placeholder="Real Name" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';    
    echo '<div class="col-sm-offset-4 col-sm-8">';
    echo form_checkbox('change_password','True',FALSE);
    echo form_label('{{ language:Change Password }}', ' for="" class="control-label');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group password-input">';
    echo form_label('{{ language:Password }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_password('password', '', 
        'id="password" placeholder="Password" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group password-input">';
    echo form_label('{{ language:Confirm Password }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_password('confirm_password', '', 
        'id="confirm_password" placeholder="Password (again)" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group"><div class="col-sm-offset-4 col-sm-8">';
    echo '<img id="img_ajax_loader" style="display:none;" src="'.base_url('assets/nocms/images/ajax-loader.gif').'" /><br />';
    echo '<div id="message" class="alert alert-danger"></div>';
    echo form_submit('change_profile', $change_profile_caption, 'class="btn btn-primary" style="display:none;"');
    echo '</div></div>';

    echo form_close();
?>
