<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// include css
$asset = new Cms_asset();
$asset->add_cms_css('grocery_crud/css/jquery_plugins/chosen/chosen.css');
$asset->add_cms_css('grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css');
echo $asset->compile_css();
?>
<style type="text/css">
	#message:empty{
		display:none;
	}
</style>

<?php
// include php
$asset->add_cms_js('grocery_crud/js/jquery_plugins/jquery.chosen.min.js');
$asset->add_cms_js('grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js');
echo $asset->compile_js();
?>

<script type="text/javascript">
	var REQUEST_EXISTS = false;
	var REQUEST = "";
    function check_change_profile(){
        var email =  $('input[name="email"]').val();
        var password = $('input[name="password"]').val();
        var confirm_password = $('input[name="confirm_password"]').val();
        var change_password_checked = $('input[name="change_password"]:checked').length > 0;
        $("#img_ajax_loader").show();
        if(REQUEST_EXISTS){
        	REQUEST.abort();
        }
        REQUEST_EXISTS = true;
        // build request data
        var request_data = {"email":email};
        $('.ajax-check-form input, .ajax-check-form select, .ajax-check-form textarea').each(function(){
            if(($(this).attr('type') == 'checkbox' && $(this).attr('checked')) || $(this).attr('type') != 'checkbox'){
                request_data[$(this).attr('name')] = $(this).val();
            }
        });
        REQUEST = $.ajax({
            "url" : "check_change_profile",
            "type" : "POST",
            "data" : request_data,
            "dataType" : "json",
            "success" : function(data){
                if(!data.error && !data.exists &&
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
                    setTimeout(check_change_profile, 10000);
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
        check_change_profile();
        $('input, select, textarea').keyup(function(){
            check_change_profile();
        });
        $('input, select, textarea').change(function(){
        	check_change_profile();
        });
        $('input[name="change_password"]').change(function(){toggle_password_input();});
        // turn select into chosen
        $('.chosen-select').chosen({allow_single_deselect:true, width:"100%", search_contains: true});
        // turn input into datepicker
    	$('.datepicker-input').datepicker({
    			dateFormat: 'yy-mm-dd',
    			showButtonPanel: true,
    			changeMonth: true,
    			changeYear: true,
				yearRange: "-150:+0"
    	});

    	$('.datepicker-input-clear').button();

    	$('.datepicker-input-clear').click(function(){
    		$(this).parent().find('.datepicker-input').val("");
    		return false;
    	});
    })
</script>
<h3>{{ language:Change Profile }}</h3>
<?php
    echo form_open_multipart('main/change_profile', 'class="ajax-check-form form form-horizontal"');

    echo '<div class="form-group">';
    echo form_label('{{ language:Profile Picture }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    if(trim($profile_picture) != ''){
        echo '<img style="max-width:256px;" src="{{ BASE_URL }}assets/nocms/images/profile_picture/' . $profile_picture . '" />';
    }
    echo '<input type="file" id="profile_picture" name="profile_picture" class="form-control">';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:User Name }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo $user_name;
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
    echo form_label('{{ language:Birthdate }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_input('birthdate', $birthdate,
        'id="birthdate" placeholder="Birthdate" class="form-control datepicker-input"');
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Sex }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    $options = array('' => '{{ language:Not Set }}', 'male' => '{{ language:Male }}', 'female' => '{{ language:Female }}');
    echo '<select name="sex" id="sex" class="form-control chosen-select">';
    foreach($options as $key=>$val){
        $selected = '';
        if($key == $sex){
            $selected = 'selected';
        }
        echo '<option value="'.$key.'" '.$selected.'>' . $val . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Language }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo '<select name="language" id="language" class="form-control chosen-select">';
    echo '<option value="">{{ language:Not Set }}</option>';
    foreach($language_list as $language_option){
        $selected = '';
        if($language_option->code == $language){
            $selected = 'selected';
        }
        echo '<option value="'.$language_option->code.'" '.$selected.'>' . $language_option->name . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Theme }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo '<select name="theme" id="theme" class="form-control chosen-select">';
    echo '<option value="">{{ language:Not Set }}</option>';
    foreach($theme_list as $theme_option){
        $selected = '';
        if($theme_option['path'] == $theme){
            $selected = 'selected';
        }
        echo '<option value="'.$theme_option['path'].'" '.$selected.'>' . ucfirst($theme_option['path']) . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '</div>';

    echo '<div class="form-group">';
    echo form_label('{{ language:Self Description }}', ' for="" class="control-label col-sm-4');
    echo '<div class="col-sm-8">';
    echo form_textarea('self_description', $self_description,
        'id="self_description" placeholder="Self Description" class="form-control"');
    echo '</div>';
    echo '</div>';

    echo $additional_input;

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
