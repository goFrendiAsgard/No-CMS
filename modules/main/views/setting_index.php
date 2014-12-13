<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    function show_static_content($list, $key){
        if(key_exists($key, $list) && key_exists('static_content', $list[$key])){
            echo $list[$key]['static_content'];
        }
    };
    // option for tags
    $option_tag = '';
    $selected = 'selected';
    foreach($normal_widget_list as $widget){
        $widget_name = $widget['widget_name'];
        $option_tag .= '<option '.$selected.' value="{{ widget_name:'.$widget_name.' }}">widget : '.$widget_name.'</option>';
        $selected = '';
    }
    foreach($config_list as $config_name=>$value){
        $option_tag .= '<option value="{{ '.$config_name.' }}">configuration : '.$config_name.'</option>';
        $selected = '';
    }
    // option for languages
    $option_language = '';
    foreach($language_list as $language){
        $selected = $language->code == $current_language ? 'selected' : '';
        $option_language .= '<option '.$selected.' value="'.$language->code.'">'.$language->name.'</option>';
    }
    // option for layouts
    $option_layout = '<option selected value="'.$config_list['site_layout'].'">'.$config_list['site_layout'].'</option>';
    foreach($layout_list as $layout){
        if($layout != $config_list['site_layout']){
            $option_layout .= '<option value="'.$layout.'">'.$layout.'</option>';
        }
    }

    $asset = new CMS_Asset();
    $asset->add_cms_css('grocery_crud/css/jquery_plugins/chosen/chosen.css');
    //$asset->add_cms_css('grocery_crud/themes/flexigrid/css/flexigrid.css');
    echo $asset->compile_css();
?>
<style type="text/css">
    .text-area-section{
        resize: none;
        word-wrap: no-wrap;
        white-space: pre-wrap;
        overflow-x: auto;
        width:95%;
        min-width: 385px!important;
        min-height: 75px!important;
        margin-top: 10px!important;
        font-family: Courier;
        font-size: small;
    }
</style>
<?php if($changed){?>
<div class="alert alert-info">Changes applied</div>
<?php } ?>
<div id="div-body" class="tabbable"> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">Configurations</a></li>
        <li><a href="#tab2" data-toggle="tab">Images</a></li>
        <li><a href="#tab3" data-toggle="tab">Sections</a></li>
        <li><a href="#tab4" data-toggle="tab">Third Party Authentication</a></li>
    </ul>
    <form enctype="multipart/form-data" class="form form-horizontal" method="post">
        <div class="tab-content">

            <div class="tab-pane" id="tab1">
                <h3>Site Configurations</h3>
                <hr /><h4>General</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="site_layout">Default Layout</label>
                    <div class="controls col-md-8">
                        <select id="site_language" name="site_layout" class="form-control"><?php echo $option_layout; ?></select>
                        <p class="help-block">Default layout used</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="site_language">Default Language</label>
                    <div class="controls col-md-8">
                        <select id="site_language" name="site_language" class="form-control"><?php echo $option_language; ?></select>
                        <p class="help-block">Default language used</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="site_name">Site Name</label>
                    <div class="controls col-md-8">
                        <input type="text" id="site_name" name="site_name" value="<?php echo $config_list['site_name'] ?>" class="form-control">
                        <p class="help-block">Site name (e.g: No-CMS, My Company website, etc)</p>
                    </div>
                </div>
                <div class="form-group">
                      <label class="control-label col-md-4" for="site_slogan">Site Slogan</label>
                      <div class="controls col-md-8">
                          <input type="text" id="site_slogan" name="site_slogan" value="<?php echo $config_list['site_slogan'] ?>" class="form-control">
                          <p class="help-block">Your site slogan (e.g: "There is no place like home", "Song song and song", etc)</p>
                      </div>
                </div>
                <div class="form-group">
                      <label class="control-label col-md-4" for="site_footer">Site Footer</label>
                      <div class="controls col-md-8">
                          <input type="text" id="site_footer" name="site_footer" value="<?php echo $config_list['site_footer'] ?>" class="form-control">
                          <p class="help-block">Site footer &amp; attribution (e.g: "Powered by No-CMS © 2013", etc)</p>
                      </div>
                </div>

                <div class="form-group">
                      <label class="control-label col-md-4" for="default_controller">Default Controller</label>
                      <div class="controls col-md-8">
                          <input type="text" id="default_controller" name="default_controller" value="<?php echo $default_controller ?>" class="form-control">
                          <p class="help-block">Default Controller, default value: main. Leave it as is, unless you are familiar with CodeIgniter routing and know it well.</p>
                      </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4" for="cms_signup_activation">User Activation</label>
                    <div class="controls col-md-8">
                        <select id="cms_signup_activation" name="cms_signup_activation" class="form-control">
                        <?php
                            $option_list = array('automatic'=>'Automatic', 'by_mail'=>'By Email', 'manual'=>'Manual');
                            foreach($option_list as $key=>$value){
                                $selected = $config_list['cms_signup_activation'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">User Activation (Automatic, By Email, or Manual)</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="cms_internet_connectivity">Internet Connectivity</label>
                    <div class="controls col-md-8">
                        <select type="text" id="cms_internet_connectivity" name="cms_internet_connectivity" class="form-control">
                        <?php
                            $option_list = array('UNKNOWN'=>'Unknown Connectivity', 'ONLINE'=>'Always Online (Use this for real website)', 'OFFLINE'=>'Always Offline (Only use this if you are sure)');
                            foreach($option_list as $key=>$value){
                                $selected = $config_list['cms_internet_connectivity'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Internet Connectivity (If this set to online, No-CMS will use jquery cdn instead of the local one)</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="cms_google_analytic_property_id">Google Analytics Property Id</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_google_analytic_property_id" name="cms_google_analytic_property_id" value="<?php echo $config_list['cms_google_analytic_property_id'] ?>" class="form-control">
                        <p class="help-block">Google Analytics Property Id (e.g: UA-30285787-1)</p>
                    </div>
                </div>
                <?php if(CMS_SUBSITE == '' && $multisite_active){ ?>
                        <hr /><h4>Multisite and Registration</h4>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="cms_add_subsite_on_register">Automatically add subsite on register</label>
                            <div class="controls col-md-8">
                                <select id="cms_add_subsite_on_register" name="cms_add_subsite_on_register" class="form-control">
                                <?php
                                    $option_list = array('TRUE'=>'Yes', 'FALSE'=>'No');
                                    foreach($option_list as $key=>$value){
                                        $selected = $config_list['cms_add_subsite_on_register'] == $key ? 'selected' : '';
                                        echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                                    }
                                ?>
                                </select>
                                <p class="help-block">Automatic Add subsite when user register (only works if multisite module is installed)</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="cms_subsite_use_subdomain">Use subdomain for automatically added subsite</label>
                            <div class="controls col-md-8">
                                <select id="cms_subsite_use_subdomain" name="cms_subsite_use_subdomain" class="form-control">
                                <?php
                                    $option_list = array('TRUE'=>'Yes', 'FALSE'=>'No');
                                    foreach($option_list as $key=>$value){
                                        $selected = $config_list['cms_subsite_use_subdomain'] == $key ? 'selected' : '';
                                        echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                                    }
                                ?>
                                </select>
                                <p class="help-block">You should has "wildcard" DNS in order to make this works</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="cms_subsite_home_content">Default Subsite Homepage Content</label>
                            <div class="controls col-md-8">
                                <textarea class="text-area-section" id="cms_subsite_home_content" name="cms_subsite_home_content" class="form-control"><?php echo $config_list['cms_subsite_home_content'] ?></textarea>
                                <p class="help-block">Default Subsite Homepage Content</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="cms_subsite_configs">Default Configurations for New Subsite</label>
                            <div class="controls col-md-8">
                                <textarea class="text-area-section" id="cms_subsite_configs" name="cms_subsite_configs" class="form-control"><?php echo $config_list['cms_subsite_configs'] ?></textarea>
                                <p class="help-block">Default configuration for new subsite (JSON Format). I.e:<br />
                                {"site_footer" : "Powered by me", "site_name" : "Another cool website"}
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="cms_subsite_modules">Default Modules for New Subsite</label>
                            <div class="controls col-md-8">
                                <textarea class="text-area-section" id="cms_subsite_modules" name="cms_subsite_modules" class="form-control"><?php echo $config_list['cms_subsite_modules'] ?></textarea>
                                <p class="help-block">Default modules for new subsite (Comma Separated)</p>
                            </div>
                        </div>                        
                <?php } ?>
                <hr /><h4>Email Setting</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="cms_email_protocol">Email Protocol</label>
                    <div class="controls col-md-8">
                        <select type="text" id="cms_email_protocol" name="cms_email_protocol" class="form-control">
                        <?php
                            $option_list = array('mail'=>'Mail', 'sendmail'=>'Sendmail', 'smtp'=>'SMTP');
                            foreach($option_list as $key=>$value){
                                $selected = $config_list['cms_email_protocol'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Email Protocol</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="cms_email_reply_address">Email Reply Address</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_reply_address" name="cms_email_reply_address" value="<?php echo $config_list['cms_email_reply_address'] ?>" class="form-control">
                        <p class="help-block">Reply address for all generated email</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="cms_email_reply_name">Email Reply Name</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_reply_name" name="cms_email_reply_name" value="<?php echo $config_list['cms_email_reply_name'] ?>" class="form-control">
                        <p class="help-block">Reply name for all generated email</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="cms_email_forgot_subject">Forgot Password Mail Subject</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_forgot_subject" name="cms_email_forgot_subject" value="<?php echo $config_list['cms_email_forgot_subject'] ?>" class="form-control">
                        <p class="help-block">Forgot password mail subject</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="cms_email_forgot_message">Forgot Password Mail Message</label>
                    <div class="controls col-md-8">
                        <textarea class="text-area-section" id="cms_email_forgot_message" name="cms_email_forgot_message" class="form-control"><?php echo $config_list['cms_email_forgot_message'] ?></textarea>
                        <p class="help-block">Forgot password mail message</p>
                    </div>
                </div>
                <div class="form-group cms_signup_activation_dependend">
                    <label class="control-label col-md-4" for="cms_email_signup_subject">User Activation Mail Subject</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_signup_subject" name="cms_email_signup_subject" value="<?php echo $config_list['cms_email_signup_subject'] ?>" class="form-control">
                        <p class="help-block">User activation mail subject</p>
                    </div>
                </div>
                <div class="form-group cms_signup_activation_dependend">
                    <label class="control-label col-md-4" for="cms_email_signup_message">User Activation Mail Message</label>
                    <div class="controls col-md-8">
                        <textarea class="text-area-section" id="cms_email_signup_message" name="cms_email_signup_message" class="form-control"><?php echo $config_list['cms_email_signup_message'] ?></textarea>
                        <p class="help-block">User activation mail message</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="cms_email_useragent">Email User Agent</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_useragent" name="cms_email_useragent" value="<?php echo $config_list['cms_email_useragent'] ?>" class="form-control">
                        <p class="help-block">Email User Agent</p>
                    </div>
                </div>
                <div class="form-group cms_email_protocol_dependend_smtp_hide">
                    <label class="control-label col-md-4" for="cms_email_mailpath">Mail Path</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_mailpath" name="cms_email_mailpath" value="<?php echo $config_list['cms_email_mailpath'] ?>" class="form-control">
                        <p class="help-block">Mail Path</p>
                    </div>
                </div>
                <div class="form-group cms_email_protocol_dependend_smtp_show">
                    <label class="control-label col-md-4" for="cms_email_smtp_host">SMTP Host</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_smtp_host" name="cms_email_smtp_host" value="<?php echo $config_list['cms_email_smtp_host'] ?>" class="form-control">
                        <p class="help-block">SMTP Host (e.g: ssl://smtp.googlemail.com)</p>
                    </div>
                </div>
                <div class="form-group cms_email_protocol_dependend_smtp_show">
                    <label class="control-label col-md-4" for="cms_email_smtp_user">SMTP User</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_smtp_user" name="cms_email_smtp_user" value="<?php echo $config_list['cms_email_smtp_user'] ?>" class="form-control">
                        <p class="help-block">SMTP User (e.g: your.gmail.account@gmail.com)</p>
                    </div>
                </div>
                <div class="form-group cms_email_protocol_dependend_smtp_show">
                    <label class="control-label col-md-4" for="cms_email_smtp_pass">SMTP Password</label>
                    <div class="controls col-md-8">
                        <input type="password" id="cms_email_smtp_pass" name="cms_email_smtp_pass" value="<?php echo $config_list['cms_email_smtp_pass'] ?>" class="form-control">
                        <p class="help-block">SMTP Password</p>
                    </div>
                </div>
                <div class="form-group cms_email_protocol_dependend_smtp_show">
                    <label class="control-label col-md-4" for="cms_email_smtp_port">SMTP Port</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_smtp_port" name="cms_email_smtp_port" value="<?php echo $config_list['cms_email_smtp_port'] ?>" class="form-control">
                        <p class="help-block">SMTP Port (e.g: 465)</p>
                    </div>
                </div>
                <div class="form-group cms_email_protocol_dependend_smtp_show">
                    <label class="control-label col-md-4" for="cms_email_smtp_timeout">SMTP Timeout</label>
                    <div class="controls col-md-8">
                        <input type="text" id="cms_email_smtp_timeout" name="cms_email_smtp_timeout" value="<?php echo $config_list['cms_email_smtp_timeout'] ?>" class="form-control">
                        <p class="help-block">SMTP Timeout (e.g: 30)</p>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab2">
                <h3>Images</h3>
                <div class="form-group">
                    <label class="control-label col-md-4" for="site_logo">Site Logo</label>
                    <div class="controls col-md-8">
                        <img src="<?php echo $config_list['site_logo'] ?>"><br>
                        <input type="file" id="site_logo" name="site_logo" class="form-control">
                        <p class="help-block">Image used as site Logo</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="site_favicon">Site Favicon</label>
                    <div class="controls col-md-8">
                        <img src="<?php echo $config_list['site_favicon'] ?>"><br>
                        <input type="file" id="site_favicon" name="site_favicon" class="form-control">
                        <p class="help-block">Image used as favicon</p>
                    </div>
                </div>
            </div>

            <div class="tab-pane active" id="tab3">
                <h3>Sections</h3>
                <div class="form-group">
                    <label class="control-label col-md-4" for="section_top_fix">Custom Style</label>
                    <div class="controls col-md-8">
                        <textarea id="section_custom_style" name="section_custom_style" class="text-area-section"><?php show_static_content($section_widget_list, 'section_custom_style'); ?></textarea>
                        <p class="help-block">Custom CSS (You can use this to customize your theme etc)</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="section_top_fix">Custom Script</label>
                    <div class="controls col-md-8">
                        <textarea id="section_custom_script" name="section_custom_script" class="text-area-section"><?php show_static_content($section_widget_list, 'section_custom_script'); ?></textarea>
                        <p class="help-block">Custom Javascript</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="section_top_fix">Top Section</label>
                    <div class="controls col-md-8">
                        <div class="div-normal-widget">
                         <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                        </div>
                        <textarea id="section_top_fix" name="section_top_fix" class="text-area-section"><?php echo show_static_content($section_widget_list, 'section_top_fix'); ?></textarea>
                        <p class="help-block">HTML &amp; tags of top section</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="section_top_fix">Navigation Bar's Right Partial</label>
                    <div class="controls col-md-8">
                        <div class="div-normal-widget">
                         <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                        </div>
                        <textarea id="navigation_right_partial" name="navigation_right_partial" class="text-area-section"><?php show_static_content($section_widget_list, 'navigation_right_partial'); ?></textarea>
                        <p class="help-block">HTML &amp; tags of navigation bar's right partial (don't put too much thing here)</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="section_banner">Banner Section</label>
                    <div class="controls col-md-8">
                        <div class="div-normal-widget">
                         <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                        </div>
                        <textarea id="section_banner" name="section_banner" class="text-area-section"><?php show_static_content($section_widget_list, 'section_banner'); ?></textarea>
                        <p class="help-block">HTML &amp; tags of banner section</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="section_left">Left Section</label>
                    <div class="controls col-md-8">
                        <div class="div-normal-widget">
                         <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                        </div>
                        <textarea id="section_left" name="section_left" class="text-area-section"><?php show_static_content($section_widget_list, 'section_left'); ?></textarea>
                        <p class="help-block">HTML &amp; tags of left Section</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="section_right">Right Section</label>
                    <div class="controls col-md-8">
                        <div class="div-normal-widget">
                         <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                        </div>
                        <textarea id="section_right" name="section_right" class="text-area-section"><?php show_static_content($section_widget_list, 'section_right'); ?></textarea>
                        <p class="help-block">HTML &amp; tags of right section</p>
                    </div>
                </div>
                <div class="form-group" style="height:260px;">
                    <label class="control-label col-md-4" for="section_bottom">Bottom Section</label>
                    <div class="controls col-md-8">
                        <div class="div-normal-widget">
                         <select class="chosen-select"><?php echo $option_tag; ?></select> <a class="btn-tag-add btn btn-primary" href="#">Add Tag</a>
                        </div>
                        <textarea id="section_bottom" name="section_bottom" class="text-area-section"><?php show_static_content($section_widget_list, 'section_bottom'); ?></textarea>
                        <p class="help-block">HTML &amp; tags of bottom section</p>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="tab4">
                <h3>Third Party Authentication</h3>

                <hr /><h4>Facebook</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_enable_facebook">Enable Facebook</label>
                    <div class="controls col-md-8">
                        <select id="auth_enable_facebook" name="auth_enable_facebook" class="form-control">
                        <?php
                            $option_list = array(1=>'Yes', 0=>'No');
                            foreach($option_list as $key=>$value){
                                $selected = $third_party_config['auth_enable_facebook'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Enable facebook login</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_facebook_app_id">App Id</label>
                    <div class="controls col-md-8">
                        <input id="auth_facebook_app_id" name="auth_facebook_app_id" class="form-control" value="<?=$third_party_config['auth_facebook_app_id']?>" />
                        <p class="help-block">App Id</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_facebook_app_secret">App Secret</label>
                    <div class="controls col-md-8">
                        <input id="auth_facebook_app_secret" name="auth_facebook_app_secret" class="form-control" value="<?=$third_party_config['auth_facebook_app_secret']?>" />
                        <p class="help-block">APP Secret</p>
                    </div>
                </div>

                <hr /><h4>Twitter</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_enable_twitter">Enable Twitter</label>
                    <div class="controls col-md-8">
                        <select id="auth_enable_twitter" name="auth_enable_twitter" class="form-control">
                        <?php
                            $option_list = array(1=>'Yes', 0=>'No');
                            foreach($option_list as $key=>$value){
                                $selected = $third_party_config['auth_enable_twitter'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Enable twitter login</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_twitter_app_key">App Key</label>
                    <div class="controls col-md-8">
                        <input id="auth_twitter_app_key" name="auth_twitter_app_key" class="form-control" value="<?=$third_party_config['auth_twitter_app_key']?>" />
                        <p class="help-block">App Key</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_twitter_app_secret">App Secret</label>
                    <div class="controls col-md-8">
                        <input id="auth_twitter_app_secret" name="auth_twitter_app_secret" class="form-control" value="<?=$third_party_config['auth_twitter_app_secret']?>" />
                        <p class="help-block">APP Secret</p>
                    </div>
                </div>


                <hr /><h4>Google</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_enable_google">Enable Google</label>
                    <div class="controls col-md-8">
                        <select id="auth_enable_google" name="auth_enable_google" class="form-control">
                        <?php
                            $option_list = array(1=>'Yes', 0=>'No');
                            foreach($option_list as $key=>$value){
                                $selected = $third_party_config['auth_enable_google'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Enable google login</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_google_app_id">App Id</label>
                    <div class="controls col-md-8">
                        <input id="auth_google_app_id" name="auth_google_app_id" class="form-control" value="<?=$third_party_config['auth_google_app_id']?>" />
                        <p class="help-block">App Id</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_google_app_secret">App Secret</label>
                    <div class="controls col-md-8">
                        <input id="auth_google_app_secret" name="auth_google_app_secret" class="form-control" value="<?=$third_party_config['auth_google_app_secret']?>" />
                        <p class="help-block">APP Secret</p>
                    </div>
                </div>

                <hr /><h4>Yahoo</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_enable_yahoo">Enable Yahoo</label>
                    <div class="controls col-md-8">
                        <select id="auth_enable_yahoo" name="auth_enable_yahoo" class="form-control">
                        <?php
                            $option_list = array(1=>'Yes', 0=>'No');
                            foreach($option_list as $key=>$value){
                                $selected = $third_party_config['auth_enable_yahoo'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Enable yahoo login</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_yahoo_app_id">App Id</label>
                    <div class="controls col-md-8">
                        <input id="auth_yahoo_app_id" name="auth_yahoo_app_id" class="form-control" value="<?=$third_party_config['auth_yahoo_app_id']?>" />
                        <p class="help-block">App Id</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_yahoo_app_secret">App Secret</label>
                    <div class="controls col-md-8">
                        <input id="auth_yahoo_app_secret" name="auth_yahoo_app_secret" class="form-control" value="<?=$third_party_config['auth_yahoo_app_secret']?>" />
                        <p class="help-block">APP Secret</p>
                    </div>
                </div>


                <hr /><h4>Linkedin</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_enable_linkedin">Enable Linkedin</label>
                    <div class="controls col-md-8">
                        <select id="auth_enable_linkedin" name="auth_enable_linkedin" class="form-control">
                        <?php
                            $option_list = array(1=>'Yes', 0=>'No');
                            foreach($option_list as $key=>$value){
                                $selected = $third_party_config['auth_enable_linkedin'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Enable linkedin login</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_linkedin_app_key">App Key</label>
                    <div class="controls col-md-8">
                        <input id="auth_linkedin_app_key" name="auth_linkedin_app_key" class="form-control" value="<?=$third_party_config['auth_linkedin_app_key']?>" />
                        <p class="help-block">App Key</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_linkedin_app_secret">App Secret</label>
                    <div class="controls col-md-8">
                        <input id="auth_linkedin_app_secret" name="auth_linkedin_app_secret" class="form-control" value="<?=$third_party_config['auth_linkedin_app_secret']?>" />
                        <p class="help-block">APP Secret</p>
                    </div>
                </div>


                <hr /><h4>Myspace</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_enable_myspace">Enable Myspace</label>
                    <div class="controls col-md-8">
                        <select id="auth_enable_myspace" name="auth_enable_myspace" class="form-control">
                        <?php
                            $option_list = array(1=>'Yes', 0=>'No');
                            foreach($option_list as $key=>$value){
                                $selected = $third_party_config['auth_enable_myspace'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Enable myspace login</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_myspace_app_key">App Key</label>
                    <div class="controls col-md-8">
                        <input id="auth_myspace_app_key" name="auth_myspace_app_key" class="form-control" value="<?=$third_party_config['auth_myspace_app_key']?>" />
                        <p class="help-block">App Key</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_myspace_app_secret">App Secret</label>
                    <div class="controls col-md-8">
                        <input id="auth_myspace_app_secret" name="auth_myspace_app_secret" class="form-control" value="<?=$third_party_config['auth_myspace_app_secret']?>" />
                        <p class="help-block">APP Secret</p>
                    </div>
                </div>


                <hr /><h4>Windows Live</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_enable_windows_live">Enable Windows Live</label>
                    <div class="controls col-md-8">
                        <select id="auth_enable_windows_live" name="auth_enable_windows_live" class="form-control">
                        <?php
                            $option_list = array(1=>'Yes', 0=>'No');
                            foreach($option_list as $key=>$value){
                                $selected = $third_party_config['auth_enable_windows_live'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Enable windows live login</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_windows_live_app_id">App Id</label>
                    <div class="controls col-md-8">
                        <input id="auth_windows_live_app_id" name="auth_windows_live_app_id" class="form-control" value="<?=$third_party_config['auth_windows_live_app_id']?>" />
                        <p class="help-block">App Id</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_windows_live_app_secret">App Secret</label>
                    <div class="controls col-md-8">
                        <input id="auth_windows_live_app_secret" name="auth_windows_live_app_secret" class="form-control" value="<?=$third_party_config['auth_windows_live_app_secret']?>" />
                        <p class="help-block">APP Secret</p>
                    </div>
                </div>


                <hr /><h4>Open Id</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_enable_open_id">Enable Open Id</label>
                    <div class="controls col-md-8">
                        <select id="auth_enable_open_id" name="auth_enable_open_id" class="form-control">
                        <?php
                            $option_list = array(1=>'Yes', 0=>'No');
                            foreach($option_list as $key=>$value){
                                $selected = $third_party_config['auth_enable_open_id'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Enable open id login</p>
                    </div>
                </div>

                <hr /><h4>AOL</h4>
                <div class="form-group">
                    <label class="control-label col-md-4" for="auth_enable_aol">Enable AOL</label>
                    <div class="controls col-md-8">
                        <select id="auth_enable_aol" name="auth_enable_aol" class="form-control">
                        <?php
                            $option_list = array(1=>'Yes', 0=>'No');
                            foreach($option_list as $key=>$value){
                                $selected = $third_party_config['auth_enable_aol'] == $key ? 'selected' : '';
                                echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                            }
                        ?>
                        </select>
                        <p class="help-block">Enable AOL login</p>
                    </div>
                </div>

            </div>

        </div>
        <input type="submit" class="btn btn-primary btn-lg" value="Apply Changes">
    </form>
</div>
<script type="text/javascript" src="{{ base_url }}assets/nocms/js/jquery.autosize.js"></script>
<?php
    $asset->add_cms_js("grocery_crud/js/jquery_plugins/jquery.chosen.min.js");
    $asset->add_cms_js("grocery_crud/js/jquery_plugins/config/jquery.chosen.config.js");
    echo $asset->compile_js();
?>
<script type="text/javascript">
    // magic to do insertAtCaret
    $.fn.extend({
        insertAtCaret: function(myValue){
            return this.each(function(i) {
                  if (document.selection) {
                      //For browsers like Internet Explorer
                      this.focus();
                      var sel = document.selection.createRange();
                      sel.text = myValue;
                      this.focus();
                  }
                  else if (this.selectionStart || this.selectionStart == '0') {
                      //For browsers like Firefox and Webkit based
                      var startPos = this.selectionStart;
                      var endPos = this.selectionEnd;
                      var scrollTop = this.scrollTop;
                      this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
                      this.focus();
                      this.selectionStart = startPos + myValue.length;
                      this.selectionEnd = startPos + myValue.length;
                      this.scrollTop = scrollTop;
                  } else {
                      this.value += myValue;
                      this.focus();
                  }
              });
        }
    });

    function _adjust_input_visibility(){
        var dependend_component = $('.cms_signup_activation_dependend');
        if($('#cms_signup_activation').val() == 'by_mail'){
            dependend_component.show();
        }else{
            dependend_component.hide();
        }

        var dependend_component_show = $('.cms_email_protocol_dependend_smtp_show');
        var dependend_component_hide = $('.cms_email_protocol_dependend_smtp_hide');
        if($('#cms_email_protocol').val() == 'smtp'){
            dependend_component_show.show();
            dependend_component_hide.hide();
        }else{
            dependend_component_show.hide();
            dependend_component_hide.show();
        }
    }

    $(document).ready(function(){
        // when calling chosen, the select should be visible, that's why I need to do this:
        $('#tab3').removeClass('active');
        $('#tab1').addClass('active');
        // make text area autosize
        $('.text-area-section').autosize();

        // add widget or whatever to the section at current caret
        $('.btn-tag-add').click(function(){
            var select_component = $(this).parent().children('select');
            var text_area_component = $(this).parent().parent().children('.text-area-section');
            var selected_item = select_component.val();
            text_area_component.insertAtCaret(selected_item);
            return false;
        });

        // adjust input visibility
        _adjust_input_visibility();
        $('#cms_signup_activation, #cms_email_protocol').change(_adjust_input_visibility);
    });
</script>
