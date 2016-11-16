<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// include css
$asset = new Cms_asset();
$asset->add_cms_css('grocery_crud/css/jquery_plugins/chosen/chosen.css');
$asset->add_cms_css('grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css');
echo $asset->compile_css();
?>
<style type="text/css">
    .message:empty{
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
                    $('#success-message').html("{{ language:Here you can edit your user profile. }} {{ language:Click the button once you've finish editing your user profile. }}");
                }else{
                    $('input[name="change_profile"]').hide();
                    $('input[name="change_profile"]').attr('disabled', 'disabled');
                    $('#success-message').html('');
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

                if(message != $('#error-message').html()){
                    $('#error-message').html(message);
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

        $(document).on('scroll', function(){
            adjust_div_notification();
        });
        adjust_div_notification();
        $(window).resize(function(event){
            adjust_div_notification();
        });
    })
</script>
<h3>{{ language:Change Profile }}</h3>
<?php
    echo form_open_multipart('main/change_profile', 'class="ajax-check-form form form-horizontal"');

    echo '<div class="col-sm-12 col-md-8">';
        $input = '';
        if(trim($profile_picture) != ''){
            $input .= '<img style="max-width:256px;" src="{{ BASE_URL }}assets/nocms/images/profile_picture/' . $profile_picture . '" />';
        }
        $input .= '<input type="file" id="profile_picture" name="profile_picture" class="form-control">';
        echo create_labeled_form_input('profile_picture', '{{ language:Profile Picture }}', $input);

        echo create_labeled_form_input('user_name', '{{ language:User Name }}', $user_name);

        $input = form_input('email', $email,
            'id="email" placeholder="{{ language:Email }}" class="form-control"');
        echo create_labeled_form_input('email', '{{ language:Email }}', $input);

        $input = form_input('real_name', $real_name,
            'id="real_name" placeholder="{{ language:Real Name }}" class="form-control"');
        echo create_labeled_form_input('real_name', '{{ language:Real Name }}', $input);

        $input = form_input('birthdate', $birthdate,
            'id="birthdate" placeholder="{{ language:Birthdate }}" class="form-control datepicker-input"');
        echo create_labeled_form_input('birthdate', '{{ language:Birthdate }}', $input);

        $options = array('' => '{{ language:Not Set }}', 'male' => '{{ language:Male }}', 'female' => '{{ language:Female }}');
        $input = '<select name="sex" id="sex" class="form-control chosen-select">';
        foreach($options as $key=>$val){
            $selected = '';
            if($key == $sex){
                $selected = 'selected';
            }
            $input .= '<option value="'.$key.'" '.$selected.'>' . $val . '</option>';
        }
        $input .= '</select>';
        echo create_labeled_form_input('sex', '{{ language:Sex }}', $input);

        $input = '<select name="language" id="language" class="form-control chosen-select">';
        $input .= '<option value="">{{ language:Not Set }}</option>';
        foreach($language_list as $language_option){
            $selected = '';
            if($language_option->code == $language){
                $selected = 'selected';
            }
            $input .= '<option value="'.$language_option->code.'" '.$selected.'>' . $language_option->name . '</option>';
        }
        $input .= '</select>';
        echo create_labeled_form_input('language', '{{ language:Language }}', $input);

        $input = '<select name="theme" id="theme" class="form-control chosen-select">';
        $input .= '<option value="">{{ language:Not Set }}</option>';
        foreach($theme_list as $theme_option){
            $selected = '';
            if($theme_option['path'] == $theme){
                $selected = 'selected';
            }
            $input .= '<option value="'.$theme_option['path'].'" '.$selected.'>' . ucfirst($theme_option['path']) . '</option>';
        }
        $input .= '</select>';
        echo create_labeled_form_input('theme', '{{ language:Theme }}', $input);

        $input = form_textarea('self_description', $self_description,
            'id="self_description" placeholder="{{ language:Self Description }}" class="form-control"');
        echo create_labeled_form_input('self_description', '{{ language:Self Description }}', $input);

        echo $additional_input;

        $input = form_checkbox('change_password','True',FALSE, 'id="change_password"');
        echo create_labeled_form_input('change_password', '{{ language:Change Password }}', $input);

        echo '<div class="password-input">';
            $input = form_password('password', '',
                'id="password" placeholder="Password" class="form-control"');
            echo create_labeled_form_input('password', '{{ language:Password }}', $input);

            $input = form_password('confirm_password', '',
                'id="confirm_password" placeholder="{{ language:Confirm Password }}" class="form-control"');
            echo create_labeled_form_input('confirm_password', '{{ language:Confirm Password }}', $input);
        echo '</div>';

        echo '<div class="form-group col-sm-12 hidden-sm hidden-xs">';
            echo form_submit('change_profile', $change_profile_caption, 'class="btn btn-primary pull-right" style="display:none;"');
        echo '</div>';

    echo '</div>';
    // Submit button and error notification
    echo '<div id="div-notification-container"class="col-md-4">';
        echo '<div id="div-notification" class="col-sm-12">';
            echo '<img id="img_ajax_loader" style="display:none;" src="'.base_url('assets/nocms/images/ajax-loader.gif').'" /><br />';
            echo '<div id="success-message" class="alert alert-success message hidden-xs hidden-sm"></div>';
            echo '<div id="error-message" class="alert alert-danger message"></div>';
            echo form_submit('change_profile', $change_profile_caption, 'class="btn btn-primary btn-lg pull-right" style="display:none;"');
        echo '</div>';
    echo '</div>';

    echo form_close();
?>
