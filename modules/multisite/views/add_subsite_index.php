<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<head>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 5px;
        }
        #div-error-warning-message{
            margin-top:10px;
            position:static;
        }
        #btn-install, #img-loader, #div-error-message, #div-warning-message, #div-success-message{
            display:none;
        }
        .btn-change-tab{
            padding-right:10px;
        }
        #div-body{
            margin-left:10px;
            margin-right:10px;
        }
        .tab-content{
            overflow:inherit!important;
        }
        .help-block, .controls ol{
            font-size:small;
        }
        .a-change-tab, .a-change-tab:visited{
            color:#b94a48!important;
            text-decoration: none;
            font-weight:bold;
        }
        .a-change-tab:hover{
            color:#b94a48!important;
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="div-body" class="tabbable"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><i class="glyphicon glyphicon-cog"></i> Site Configurations</a></li>
            <li><a href="#tab2" data-toggle="tab"><i class="glyphicon glyphicon-eye-open"></i> Appearance</a></li>
        </ul>

        <form class="form-horizontal" action="<?php echo site_url('multisite/add_subsite/install'); ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">

            <div class="tab-content">

                <div class="tab-pane active" id="tab1">
                    <h3>Site Configurations</h3>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="subsite">Subsite</label>
                        <div class="controls col-md-8">
                            <input type="text" id="subsite" name="subsite" value="" class="input form-control" placeholder="Subsite">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="aliases">Custom Domain / Alias</label>
                        <div class="controls col-md-8">
                            <input type="text" id="aliases" name="aliases" value="" class="input form-control" placeholder="Aliases, comma separated (e.g: somedomain.com, other.com)">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="logo">Logo</label>
                        <div class="controls col-md-8">
                            <input type="file" id="logo" name="logo" value="" class="input form-control" placeholder="logo">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="description">Description</label>
                        <div class="controls col-md-8">
                            <textarea id="description" name="description" class="input form-control" placeholder="description"></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab2">
                    <h3>Appearance</h3>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="homepage_layout">Homepage Layout</label>
                        <div class="controls col-md-8">
                            <select id="homepage_layout" name="homepage_layout" class="input form-control" placeholder="Homepage Layout">
                                <?php
                                    foreach ($layout_list as $homepage_layout) {
                                        $selected = $homepage_layout == 'slide' ? 'selected' : '';
                                        echo '<option value="'.$homepage_layout.'" '.$selected.'>'.$homepage_layout.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="default_layout">Default Layout</label>
                        <div class="controls col-md-8">
                            <select id="default_layout" name="default_layout" class="input form-control" placeholder="Default Layout">
                                <?php
                                    foreach ($layout_list as $default_layout) {
                                        $selected = $default_layout == 'default' ? 'selected' : '';
                                        echo '<option value="'.$default_layout.'" '.$selected.'>'.$default_layout.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="theme">Theme</label>
                        <div class="controls col-md-8">
                            <select id="theme" name="theme" class="input form-control" placeholder="Theme">
                                <?php
                                    foreach ($theme_list as $theme) {
                                        $selected = $theme == 'neutral'? 'selected' : '';
                                        echo '<option value="'.$theme.'" '.$selected.'>'.$theme.'</option>';
                                    }
                                ?>
                            </select>
                            <p class="help-block">Theme used for the new site</p>
                            <div>
                                <?php
                                    foreach ($theme_list as $theme) {
                                        echo '<img style="width:100%; display:none;" class="img-theme" id="img-theme-'.str_replace(' ', '_', $theme).'" real-src="{{ base_url }}themes/'.$theme.'/preview.png" />';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="template">Template</label>
                        <div class="controls col-md-8">
                            <select id="template" name="template" class="input form-control" placeholder="template">
                                <?php
                                    foreach ($template_list as $template) {
                                        echo '<option value="'.$template['name'].'">'.$template['name'].'</option>';
                                    }
                                ?>
                            </select>
                            <p class="help-block">Template used for the new site</p>
                            <div>
                                <?php
                                    foreach ($template_list as $template) {
                                        $template_name = str_replace(' ', '_', $template['name']);
                                        echo '<img style="width:100%; display:none;" class="img-template" id="img-template-'.$template_name.'" real-src="{{ module_base_url }}assets/uploads/'.$template['icon'].'" />';
                                        echo '<p style="display:none;" class="desc-template" id="desc-template-'.$template_name.'">'.$template['description'].'</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-4" for="use_subdomain">Use Subdomain</label>
                <div class="controls col-md-8">
                    <input type="checkbox" id="use_subdomain" name="use_subdomain" class="input" value="true">
                    <p class="help-block">
                        Use subdomain (e.g: subdomain.maindomain.com). This require some DNS setting. Leave it unchecked if you aren't sure.
                    </p>
                    <p>
                        <button id="btn-install" class="btn btn-primary btn-lg" name="Install" disabled="disabled" value="INSTALL NOW">INSTALL NOW</button>
                    </p>
                </div>
            </div>

            <div id="div-error-warning-message">
                <div id="div-error-message" class="alert alert-danger">
                    <strong>ERRORS:</strong>
                    <ul id="ul-error-message"></ul>
                </div>
                <div id="div-warning-message" class="alert alert-warning">
                    <strong>WARNINGS:</strong>
                    <ul id="ul-warning-message"></ul>
                </div>
                <div id="div-success-message" class="alert alert-success">
                    <strong>GREAT !!!</strong>, you can now install <span id="span-subsite"></span> without worrying anything.
                </div>

            </div>

        </form>
    </div>
    <script type="text/javascript">
        var REQUEST;
        var RUNNING_REQUEST = false;
        var SUCCESS = false;

        $(document).ready(function(){
            // check things
            check();
            adjust_theme();
            adjust_template();
        });
        $("input, select").change(function(){
            check();
        });
        $("input:not(#db_name), select").keyup(function(){
            check();
        });

        function adjust_theme(){
            var value = $('#theme option:selected').val().replace(/ /g, '_');
            $('.img-theme').hide();
            $('#img-theme-'+value).attr('src', $('#img-theme-'+value).attr('real-src')).show();
        }
        $('#theme').change(function(event){adjust_theme();});

        function adjust_template(){
            var value = $('#template option:selected').val().replace(/ /g, '_');
            console.log(value);
            $('.img-template').hide();
            $('.desc-template').hide();
            $('#img-template-'+value).attr('src', $('#img-template-'+value).attr('real-src')).show();
            $('#desc-template-'+value).show();
        }
        $('#template').change(function(event){adjust_template();});

        // next or previous step
        $(".btn-change-tab").click(function(){
            var href = $(this).attr('href');
            $("ul.nav-tabs li").removeClass('active');
            $("div.tab-pane").removeClass('active');
            $("ul.nav-tabs li a[href='"+href+"']").parent().addClass('active');
            $("div.tab-pane[id='"+href.substr(1)+"']").addClass('active');
            return false;
        });

        // from error message
        $('body').on('click', '.a-change-tab', function(){
            var tab = $(this).attr('tab');
            var component = $(this).attr('component');
            $("ul.nav-tabs li").removeClass('active');
            $("div.tab-pane").removeClass('active');
            $("ul.nav-tabs li a[href='"+tab+"']").parent().addClass('active');
            $("div.tab-pane[id='"+tab.substr(1)+"']").addClass('active');
            if(typeof(component) != 'undefined' && component != ''){
                $('#'+component).focus();
            }
            return false;
        });

        $("#btn-install").click(function(){
            $(this).hide();
            $("#img-loader").show();
        });


        function check(){
            if(RUNNING_REQUEST){
                REQUEST.abort();
            }
            RUNNING_REQUEST = true;
            REQUEST = $.ajax({
                type : "POST",
                url : "<?php echo site_url('{{ module_path }}/add_subsite/check'); ?>",
                dataType: "json",
                async : true,
                data : {
                    subsite : $("#subsite").val(),
                },
                success : function(response){
                    SUCCESS = response.success;
                    var warning_list = response.warning_list;
                    var error_list = response.error_list;
                    // show error
                    $('#ul-error-message').html('');
                    if(error_list.length>0){
                        for(var i=0; i<error_list.length; i++){
                            var error = error_list[i];
                            $('#ul-error-message').append('<li>'+error+'</li>');
                        }
                        $('#div-error-message').show();
                    }else{
                        $('#div-error-message').hide();
                    }
                    // show warning
                    $('#ul-warning-message').html('');
                    if(warning_list.length>0){
                        for(var i=0; i<warning_list.length; i++){
                            var warning = warning_list[i];
                            $('#ul-warning-message').append('<li>'+warning+'</li>');
                        }
                        $('#div-warning-message').show();
                    }else{
                        $('#div-warning-message').hide();
                    }
                    if(error_list.length==0 && warning_list.length==0){
                        $('#div-success-message').show();
                    }else{
                        $('#div-success-message').hide();
                    }
                    // show/hide button
                    if(SUCCESS){
                        var subsite = $("#subsite").val();
                        var url = '';
                        if($('#use_subdomain').prop('checked')){
                            site_url = '{{ SITE_URL }}';
                            url = site_url.replace('://', '://'+subsite+'.');
                        }else{
                            url = '{{ SITE_URL }}site-'+subsite;
                        }
                        $('#span-subsite').html('<b>'+subsite + '</b> subsite ('+url+')');
                        $('#btn-install').show();
                        $("#btn-install").removeAttr('disabled');
                    }else{
                        $('#btn-install').hide();
                        $("#btn-install").attr('disabled','disabled');
                    }

                },
                error: function(xhr, textStatus, errorThrown){
                    if(textStatus != 'abort'){
                        setTimeout(check, 1000);
                    }
                }
            });
        }
    </script>
</body>
