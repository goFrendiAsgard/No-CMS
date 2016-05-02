<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    #message:empty{
        display:none;
    }
    #btn-register, .register_input{
        display:none;
    }
</style>

<div id="div-body" class="tabbable"> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> User Information</a></li>
        <li><a href="#tab2" data-toggle="tab"><i class="glyphicon glyphicon-cog"></i> Site Configurations</a></li>
        <li><a href="#tab3" data-toggle="tab"><i class="glyphicon glyphicon-eye-open"></i> Appearance</a></li>
        <?php
            if(trim($additional_input) != ''){
                echo '<li><a href="#tab4" data-toggle="tab"><i class="glyphicon glyphicon-th-list"></i> Others</a></li>';
            }
        ?>
    </ul>

    <?php
        echo form_open_multipart('main/register', 'id="form-register" class="ajax-check-form form form-horizontal"');
            echo '<div class="tab-content">';

                echo form_input(array('name'=>'user_name', 'value'=>'', 'class'=>'register_input'));
                echo form_input(array('name'=>'email', 'value'=>'', 'class'=>'register_input'));
                echo form_input(array('name'=>'real_name', 'value'=>'', 'class'=>'register_input'));
                echo form_input(array('name'=>'password', 'value'=>'', 'class'=>'register_input'));
                echo form_input(array('name'=>'confirm_password', 'value'=>'', 'class'=>'register_input'));

                echo '<div class="tab-pane active" id="tab1">';
                    echo '<h4>User Information</h4>';

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
                echo '</div>';

                echo '<div class="tab-pane" id="tab2">';
                    echo '<h4>Site Information</h4>';

                    echo '<div class="form-group">';
                    echo form_label('{{ language:Site Title }}', ' for="" class="control-label col-sm-4');
                    echo '<div class="col-sm-8">';
                    echo form_input('site_title', '',
                        'id="site_title" placeholder="Site Title" class="form-control"');
                    echo '</div>';
                    echo '</div>';

                    echo '<div class="form-group">';
                    echo form_label('{{ language:Site Slogan }}', ' for="" class="control-label col-sm-4');
                    echo '<div class="col-sm-8">';
                    echo form_input('site_slogan', '',
                        'id="site_slogan" placeholder="Site Slogan" class="form-control"');
                    echo '</div>';
                    echo '</div>';

                    echo '<div class="form-group">';
                    echo form_label('{{ language:Site Logo }}', ' for="" class="control-label col-sm-4');
                    echo '<div class="col-sm-8">';
                    echo '<input type="file" name="site_logo" id="site_logo" class="form-control" />';
                    echo '</div>';
                    echo '</div>';

                    echo '<div class="form-group">';
                    echo form_label('{{ language:Site Favicon }}', ' for="" class="control-label col-sm-4');
                    echo '<div class="col-sm-8">';
                    echo '<input type="file" name="site_favicon" id="site_favicon" class="form-control" />';
                    echo '</div>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="tab-pane" id="tab3">';
                    echo '<h4>Site Appearance</h4>';

                    echo'<div class="form-group">';
                    echo'<label class="control-label col-md-4" for="homepage_layout">Homepage Layout</label>';
                    echo'<div class="controls col-md-8">';
                    echo'<select id="homepage_layout" name="homepage_layout" class="input form-control" placeholder="Homepage Layout">';
                    foreach($layout_list as $homepage_layout){
                        $selected = $homepage_layout == 'slide'? 'selected' : '';
                        echo '<option value="'.$homepage_layout.'" '.$selected.'>'.$homepage_layout.'</option>';
                    }
                    echo'</select>';
                    echo'</div>';
                    echo'</div>';

                    echo'<div class="form-group">';
                    echo'<label class="control-label col-md-4" for="default_layout">Default Layout</label>';
                    echo'<div class="controls col-md-8">';
                    echo'<select id="default_layout" name="default_layout" class="input form-control" placeholder="Default Layout">';
                    foreach($layout_list as $default_layout){
                        $selected = $default_layout == 'default'? 'selected' : '';
                        echo '<option value="'.$default_layout.'" '.$selected.'>'.$default_layout.'</option>';
                    }
                    echo'</select>';
                    echo'</div>';
                    echo'</div>';

                    echo'<div class="form-group">';
                    echo'<label class="control-label col-md-4" for="theme">Theme</label>';
                    echo'<div class="controls col-md-8">';
                    echo'<select id="theme" name="theme" class="input form-control" placeholder="Theme">';
                    foreach($theme_list as $theme){
                        $selected = $theme == 'neutral'? 'selected' : '';
                        echo '<option value="'.$theme.'" '.$selected.'>'.$theme.'</option>';
                    }
                    echo'</select>';
                    echo'<p class="help-block">Theme used for the new site</p>';
                    echo'<div>';
                    foreach($theme_list as $theme){
                        echo '<img style="width:100%; display:none;" class="img-theme" id="img-theme-'.str_replace(' ','_',$theme).'" real-src="{{ base_url }}themes/'.$theme.'/preview.png" />';
                    }
                    echo'</div>';
                    echo'</div>';
                    echo'</div>';

                    echo'<div class="form-group">';
                    echo'<label class="control-label col-md-4" for="template">Template</label>';
                    echo'<div class="controls col-md-8">';
                    echo'<select id="template" name="template" class="input form-control" placeholder="template">';
                    foreach($template_list as $template){
                        echo '<option value="'.$template['name'].'">'.$template['name'].'</option>';
                    }
                    echo'</select>';
                    echo'<p class="help-block">Template used for the new site</p>';
                    echo'<div>';
                    foreach($template_list as $template){
                        $template_name = str_replace(' ','_',$template['name']);
                        echo '<img style="width:100%; display:none;" class="img-template" id="img-template-'.$template_name.'" real-src="{{ module_base_url }}assets/uploads/'.$template['icon'].'" />';
                        echo '<p style="display:none;" class="desc-template" id="desc-template-'.$template_name.'">'.$template['description'].'</p>';
                    }
                    echo'</div>';
                    echo'</div>';
                    echo'</div>';
                echo '</div>';

                // from hook
                if(trim($additional_input) != ''){
                    echo '<div class="tab-pane" id="tab4">';
                        echo '<h4>Others</h4>';
                        echo $additional_input;
                    echo '</div>';
                }

                echo '<div class="form-group"><div class="col-sm-offset-4 col-sm-8">';
                echo '<img id="img_ajax_loader" style="display:none;" src="'.base_url('assets/nocms/images/ajax-loader.gif').'" /><br />';
                echo '<div id="message" class="alert alert-danger"></div>';
                echo form_submit('register', $register_caption, 'id="btn-register" class="btn btn-primary" style="display:none;"');
                echo '</div></div>';


            echo '</div>';
        echo form_close();
    ?>
</div>
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
            "url" : "<?php echo site_url('{{ module_path }}/multisite/check_registration'); ?>",
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

    function adjust_theme(){
        var value = $('#theme option:selected').val().replace(/ /g, '_');
        $('.img-theme').hide();
        $('#img-theme-'+value).attr('src', $('#img-theme-'+value).attr('real-src')).show();
    }
    $('#theme').change(function(event){adjust_theme();});

    function adjust_template(){
        var value = $('#template option:selected').val().replace(/ /g, '_');
        $('.img-template').hide();
        $('.desc-template').hide();
        $('#img-template-'+value).attr('src', $('#img-template-'+value).attr('real-src')).show();
        $('#desc-template-'+value).show();
    }
    $('#template').change(function(event){adjust_template();});

    $(document).ready(function(){

        check_register();
        adjust_theme();
        adjust_template();

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
