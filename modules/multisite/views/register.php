<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    .message:empty{
        display:none;
    }
    #btn-register, .register_input{
        display:none;
    }
    .bordered-image{
        border:1px solid;
        width:100%!important;
        height:auto!important;
    }
</style>

<div id="div-body" class="tabbable"> <!-- Only required for left/right tabs -->
    <ul id="form-tabs" class="nav nav-tabs">
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
            echo '<div class="tab-content col-md-8">';

                echo form_input(array('name'=>'user_name', 'value'=>'', 'class'=>'register_input'));
                echo form_input(array('name'=>'email', 'value'=>'', 'class'=>'register_input'));
                echo form_input(array('name'=>'real_name', 'value'=>'', 'class'=>'register_input'));
                echo form_input(array('name'=>'password', 'value'=>'', 'class'=>'register_input'));
                echo form_input(array('name'=>'confirm_password', 'value'=>'', 'class'=>'register_input'));

                echo '<div class="tab-pane active" id="tab1">';
                    echo '<h4>User Information</h4>';
                    echo build_register_input($secret_code, $user_name, $email, $real_name);

                    echo '<div class="form-group col-sm-12" style="text-align:right;">';
                        echo '<a href="#tab2" data-toggle="tab" class="btn btn-primary btn-change-tab">{{ language:Next }}</a>';
                        echo '<span class="hidden-sm-hidden-xs">';
                            echo '&nbsp;';
                            echo form_submit('register', $register_caption, 'id="btn-register" class="btn btn-primary" style="display:none;"');
                        echo '</span>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="tab-pane" id="tab2">';
                    echo '<h4>Site Information</h4>';

                    echo create_labeled_form_input('site_title', '{{ language:Site Title }}',
                        form_input('site_title', '', 'id="{{ id }}" placeholder="Site Title" class="form-control"')
                    );

                    echo create_labeled_form_input('site_slogan', '{{ language:Site Logo }}',
                        form_input('site_slogan', '', 'id="{{ id }}" placeholder="Site Slogan" class="form-control"')
                    );

                    echo create_labeled_form_input('site_logo', '{{ language:Site Logo }}',
                        '<input type="file" name="site_logo" id="{{ id }}" class="form-control" />'
                    );

                    echo create_labeled_form_input('site_favicon', '{{ language:Site Favicon }}',
                        '<input type="file" name="site_favicon" id="{{ id }}" class="form-control" />'
                    );

                    echo '<div class="form-group col-sm-12" style="text-align:right;">';
                        echo '<a href="#tab1" data-toggle="tab" class="btn btn-primary btn-change-tab">{{ language:Previous }}</a>&nbsp;';
                        echo '<a href="#tab3" data-toggle="tab" class="btn btn-primary btn-change-tab">{{ language:Next }}</a>';
                        echo '<span class="hidden-sm-hidden-xs">';
                            echo '&nbsp;';
                            echo form_submit('register', $register_caption, 'id="btn-register" class="btn btn-primary" style="display:none;"');
                        echo '</span>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="tab-pane" id="tab3">';

                    $input = '';
                    $counter = 0;
                    foreach($layout_list as $homepage_layout){
                        $checked = $homepage_layout == 'slide'? 'checked' : '';
                        $input .= '<div class="col-sm-3">';
                            $input .= '<img class="bordered-image" src="{{ base_url }}assets/nocms/images/layouts/'.$homepage_layout.'.png" /><br />';
                            $input .= '<div style="width:100%; min-height:30px; margin-bottom:10px;">';
                                $input .= '<input name="homepage_layout" type="radio" value="'.$homepage_layout.'" '.$checked.'> <span style="font-size:smaller;">'.$homepage_layout.'</span>';
                            $input .= '</div>';
                        $input .= '</div>';
                        $counter++;
                        if($counter%4 == 0){
                            $input .= '<div class="col-sm-12" style="clear:both;"></div>';
                        }
                    }
                    echo '<h4>{{ language:Homepage Layout }}</h4>';
                    echo '<div class="col-sm-12">'.$input.'</div>';

                    $input = '';
                    $counter = 0;
                    foreach($layout_list as $default_layout){
                        $checked = $default_layout == 'default'? 'checked' : '';
                        $input .= '<div class="col-sm-3">';
                            $input .= '<img class="bordered-image" src="{{ base_url }}assets/nocms/images/layouts/'.$default_layout.'.png" /><br />';
                            $input .= '<div style="width:100%; min-height:30px; margin-bottom:10px;">';
                                $input .= '<input name="default_layout" type="radio" value="'.$default_layout.'" '.$checked.'> <span style="font-size:smaller;">'.$default_layout.'</span>';
                            $input .= '</div>';
                        $input .= '</div>';
                        $counter++;
                        if($counter%4 == 0){
                            $input .= '<div class="col-sm-12" style="clear:both;"></div>';
                        }
                    }
                    echo '<div style="clear:both;"></div>';;
                    echo '<h4>{{ language:Default Layout }}</h4>';
                    echo '<div class="col-sm-12">'.$input.'</div>';

                    $input = '';
                    $counter = 0;
                    foreach($theme_list as $theme){
                        $checked = $theme == 'neutral'? 'checked' : '';
                        $input .= '<div class="col-sm-3">';
                            $input .= '<img class="bordered-image" src="{{ base_url }}themes/'.$theme.'/preview.png" /><br />';
                            $input .= '<div style="width:100%: min-height:30px; margin-bottom:10px;">';
                                $input .= '<input name="theme" type="radio" value="'.$theme.'" '.$checked.'> <span style="font-size:small;">'.$theme.'</span>';
                            $input .= '</div>';
                        $input .= '</div>';
                        $counter++;
                        if($counter%4 == 0){
                            $input .= '<div class="col-sm-12" style="clear:both;"></div>';
                        }
                    }
                    echo '<div style="clear:both;"></div>';;
                    echo '<h4>{{ language:Theme }}</h4>';
                    echo '<div class="col-sm-12">'.$input.'</div>';

                    $input = '';
                    $counter = 0;
                    foreach($template_list as $template){
                        $template_name = str_replace(' ','_',$template['name']);
                        $checked = $counter==0? 'checked' : '';
                        $input .= '<div class="col-sm-4">';
                            $input .= '<img class="bordered-image" src="{{ module_base_url }}assets/uploads/'.$template['icon'].'" /><br />';
                            $input .= '<div style="width:100%; min-height:30px; margin-bottom:10px;">';
                                $input .= '<input name="template" type="radio" value="'.$template_name.'" '.$checked.'> <span style="font-size:small;">'.$template_name.'</span>';
                                $input .= '<p style="font-size:smaller; text-align:justify;">'.$template['description'].'</p>';
                            $input .= '</div>';
                        $input .= '</div>';
                        $counter++;
                        if($counter%3 == 0){
                            $input .= '<div class="col-sm-12" style="clear:both;"></div>';
                        }
                    }
                    echo '<div style="clear:both;"></div>';;
                    echo '<h4>{{ language:Template }}</h4>';
                    echo '<div class="col-sm-12">'.$input.'</div>';

                    echo '<div class="form-group col-sm-12" style="text-align:right;">';
                        echo '<a href="#tab2" data-toggle="tab" class="btn btn-primary btn-change-tab">{{ language:Previous }}</a>';
                        if(trim($additional_input) != ''){
                            echo '&nbsp;<a href="#tab4" data-toggle="tab" class="btn btn-primary btn-change-tab">{{ language:Next }}</a>';
                        }

                        echo '<span class="hidden-sm-hidden-xs">';
                            echo '&nbsp;';
                            echo form_submit('register', $register_caption, 'id="btn-register" class="btn btn-primary" style="display:none;"');
                        echo '</span>';
                    echo '</div>';

                echo '</div>';

                // from hook
                if(trim($additional_input) != ''){
                    echo '<div class="tab-pane" id="tab4">';
                        echo '<h4>Others</h4>';
                        echo $additional_input;
                        echo '<div class="form-group col-sm-12" style="text-align:right;">';
                            echo '<a href="#tab3" data-toggle="tab" class="btn btn-primary btn-change-tab">{{ language:Previous }}</a>';

                            echo '<span class="hidden-sm-hidden-xs">';
                                echo '&nbsp;';
                                echo form_submit('register', $register_caption, 'id="btn-register" class="btn btn-primary" style="display:none;"');
                            echo '</span>';

                        echo '</div>';
                    echo '</div>';
                }

            echo '</div>';

            // Submit button and error notification
            echo '<div id="div-notification-container"class="col-md-4">';
                echo '<div id="div-notification" class="col-sm-12">';
                    echo '<img id="img_ajax_loader" style="display:none;" src="'.base_url('assets/nocms/images/ajax-loader.gif').'" /><br />';
                    echo '<div id="success-message" class="alert alert-success message hidden-xs hidden-sm"></div>';
                    echo '<div id="error-message" class="alert alert-danger message"></div>';
                    echo form_submit('register', $register_caption, 'id="btn-register" class="btn btn-primary btn-lg pull-right" style="display:none;"');
                echo '</div>';
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
                    $('#success-message').html('{{ language:Here you can register. Click the button once the data is complete }}');
                }else{
                    $('input[name="register"]').hide();
                    $('input[name="register"]').attr('disabled', 'disabled');
                    $('#success-message').html('');
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

                if(message != $('#error-message').html()){
                    $('#error-message').html(message);
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

        $(document).on('scroll', function(){
            adjust_div_notification();
        });
        adjust_div_notification();
        $(window).resize(function(event){
            adjust_div_notification();
        });

        $(".btn-change-tab").click(function(){
            var href = $(this).attr('href');
            $("ul#form-tabs li").removeClass('active');
            $("div.tab-pane").removeClass('active');
            $("ul#form-tabs li a[href='"+href+"']").parent().addClass('active');
            $("div.tab-pane[id='"+href.substr(1)+"']").addClass('active');
            return false;
        });

    });
</script>
