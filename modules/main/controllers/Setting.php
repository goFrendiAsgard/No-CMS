<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends CMS_Controller{

    protected $theme = 'neutral';

    private function update_static_content($widget_name, $content){
        $parsed_content = $this->cms_parse_keyword($content);
        $no_change = FALSE;
        $seek_no_longer = FALSE;
        // see if there are changes
        $widgets = $this->cms_widgets(NULL, $widget_name);
        foreach($widgets as $slug => $widget_list){
            foreach($widget_list as $widget){
                if($widget['widget_name'] == $widget_name){
                    if($widget['content'] == $parsed_content){
                        $no_change = TRUE;
                    }
                    $seek_no_longer = TRUE;
                    break;
                }
            }
            if($seek_no_longer){
                break;
            }
        }
        // don't change if there is no changes
        if(!$no_change){
            $this->db->update(cms_table_name('main_widget'), array('static_content'=>$content), array('widget_name'=>$widget_name));
        }
    }

    public function index(){

        // third party authentication setting
        $third_party_variables = array(
            'auth_enable_facebook', 'auth_facebook_app_id', 'auth_facebook_app_secret', 'auth_enable_twitter',
            'auth_twitter_app_key', 'auth_twitter_app_secret', 'auth_enable_google', 'auth_google_app_id',
            'auth_google_app_secret', 'auth_enable_yahoo', 'auth_yahoo_app_id', 'auth_yahoo_app_secret',
            'auth_enable_linkedin', 'auth_linkedin_app_key', 'auth_linkedin_app_secret', 'auth_enable_myspace',
            'auth_myspace_app_key', 'auth_myspace_app_secret', 'auth_enable_foursquare', 'auth_foursquare_app_id',
            'auth_foursquare_app_secret', 'auth_enable_windows_live', 'auth_windows_live_app_id',
            'auth_windows_live_app_secret', 'auth_enable_open_id', 'auth_enable_aol');

        if(CMS_SUBSITE == ''){
            $hybridauth_config_file = APPPATH.'/config/main/hybridauthlib.php';
        }else{
            $hybridauth_config_file = APPPATH.'/config/site-'.CMS_SUBSITE.'/hybridauthlib.php';
        }

        // save the uploaded files
        if(isset($_FILES['site_logo'])){
            try{
                $site_logo = $_FILES['site_logo'];
                if(isset($site_logo['tmp_name']) && $site_logo['tmp_name'] != '' && getimagesize($site_logo['tmp_name']) !== FALSE){
                    $file_name = FCPATH.'assets/nocms/images/custom_logo/'.CMS_SUBSITE.$site_logo['name'];
                    move_uploaded_file($site_logo['tmp_name'], $file_name);
                    @chmod($file_name, 644);
                    $this->cms_resize_image($file_name, 800, 125);
                    @chmod($file_name, 644);
                    $this->cms_set_config('site_logo', '{{ base_url }}assets/nocms/images/custom_logo/'.CMS_SUBSITE.$site_logo['name']);
                }
            }catch(Exception $e){
                // do nothing
            }
        }
        if(isset($_FILES['site_favicon'])){
            try{
                $site_favicon = $_FILES['site_favicon'];
                if(isset($site_favicon['tmp_name']) && $site_favicon['tmp_name'] != '' && getimagesize($site_favicon['tmp_name']) !== FALSE){
                    $file_name = FCPATH.'assets/nocms/images/custom_favicon/'.CMS_SUBSITE.$site_favicon['name'];
                    move_uploaded_file($site_favicon['tmp_name'], $file_name);
                    @chmod($file_name, 644);
                    $this->cms_resize_image($file_name, 64, 64);
                    @chmod($file_name, 644);
                    $this->cms_set_config('site_favicon', '{{ base_url }}assets/nocms/images/custom_favicon/'.CMS_SUBSITE.$site_favicon['name']);
                }
            }catch(Exception $e){
                // do nothing
            }
        }
        if($this->input->post('remove_meta_image') == 1){
            $this->cms_set_config('meta_image', '');
        }else if(isset($_FILES['meta_image'])){
            try{
                $meta_image = $_FILES['meta_image'];
                if(isset($meta_image['tmp_name']) && $meta_image['tmp_name'] != '' && getimagesize($meta_image['tmp_name']) !== FALSE){
                    $file_name = FCPATH.'assets/nocms/images/custom_meta_image/'.CMS_SUBSITE.$meta_image['name'];
                    move_uploaded_file($meta_image['tmp_name'], $file_name);
                    @chmod($file_name, 644);
                    $this->cms_set_config('meta_image', '{{ base_url }}assets/nocms/images/custom_meta_image/'.CMS_SUBSITE.$meta_image['name']);
                }
            }catch(Exception $e){
                // do nothing
            }
        }


        if($this->input->post('remove_background_image') == 1){
            $this->cms_set_config('site_background_image', '');
        }else if(isset($_FILES['site_background_image'])){
            try{
                $site_background_image = $_FILES['site_background_image'];
                if(isset($site_background_image['tmp_name']) && $site_background_image['tmp_name'] != '' && getimagesize($site_background_image['tmp_name']) !== FALSE){
                    $file_name = FCPATH.'assets/nocms/images/custom_background/'.CMS_SUBSITE.$site_background_image['name'];
                    move_uploaded_file($site_background_image['tmp_name'], $file_name);
                    @chmod($file_name, 644);
                    $this->cms_set_config('site_background_image', '{{ base_url }}assets/nocms/images/custom_background/'.CMS_SUBSITE.$site_background_image['name']);
                }
            }catch(Exception $e){
                // do nothing
            }
        }

        if(count($_POST)>0){
            // save the section widgets
            $this->update_static_content('section_custom_style', $this->input->post('section_custom_style'));
            $this->update_static_content('section_custom_script', $this->input->post('section_custom_script'));
            $this->update_static_content('section_top_fix', $this->input->post('section_top_fix'));
            $this->update_static_content('section_banner', $this->input->post('section_banner'));
            $this->update_static_content('section_left', $this->input->post('section_left'));
            $this->update_static_content('section_right', $this->input->post('section_right'));
            $this->update_static_content('section_bottom', $this->input->post('section_bottom'));
            $this->update_static_content('navigation_right_partial', $this->input->post('navigation_right_partial'));
            // save configurations
            $configuration_list = array(
                'site_name', 'site_layout', 'site_slogan', 'site_footer', 'site_language',
                'site_background_color', 'site_background_position', 'site_background_size',
                'site_background_repeat', 'site_background_origin', 'site_background_clip',
                'site_background_attachment', 'site_background_blur', 'site_text_color',
                'cms_signup_activation', 'cms_email_protocol',
                'cms_email_reply_address', 'cms_email_reply_name', 'cms_email_forgot_subject',
                'cms_email_forgot_message', 'cms_email_signup_subject', 'cms_email_signup_message',
                'cms_email_useragent', 'cms_email_mailpath', 'cms_email_smtp_host', 'cms_email_smtp_user',
                'cms_email_smtp_pass', 'cms_email_smtp_port', 'cms_email_smtp_timeout',
                'cms_google_analytic_property_id','cms_internet_connectivity','cms_subsite_configs','cms_subsite_modules',
                'meta_twitter_card', 'meta_keyword', 'meta_description', 'meta_author', 'meta_type', 'meta_fb_admin',
                'meta_twitter_publisher_handler', 'meta_twitter_author_handler',
            );
            // only for non-subsite
            if(CMS_SUBSITE == ''){
                $configuration_list[] = 'site_show_benchmark';
                $configuration_list[] = 'site_developer_addr';
            }
            if(CMS_SUBSITE == '' && $this->cms_is_module_active('gofrendi.noCMS.multisite')){
                $configuration_list[] = 'cms_add_subsite_on_register';
                $configuration_list[] = 'cms_subsite_use_subdomain';
                $configuration_list[] = 'cms_subsite_home_content';
                $configuration_list[] = 'cms_subsite_configs';
                $configuration_list[] = 'cms_subsite_modules';
            }
            foreach($configuration_list as $configuration){
                $value = $this->input->post($configuration);
                if($configuration == 'cms_email_smtp_pass'){
                    if($value == '[PASSWORD SET]'){
                        continue;
                    }
                    $value = cms_encode($value);
                }
                // Don't update configuration if there is no change
                if($this->cms_get_config($configuration, TRUE) == $value){
                    continue;
                }
                $this->cms_set_config($configuration, $value);
            }
            // save language
            $this->cms_language($this->input->post('site_language'));
            // save default_controller
            $this->cms_set_default_controller($this->input->post('default_controller'));
            // save third party authentication
            $str = file_get_contents($hybridauth_config_file);
            foreach($third_party_variables as $var){
                $value = $this->input->post($var);
                // for auth_enable type, just put a boolean value, else add quotes
                if(substr($var, 0,11) == 'auth_enable'){
                    $value = $value == 0? 'FALSE' : 'TRUE';
                }else{
                    $value = "'".addslashes($value)."'";
                }
                $pattern = '/(\$'.$var.' *= *)(.*?)(;)/si';
                $replacement = '${1}'.$value.'${3}';
                $str = preg_replace($pattern, $replacement, $str);
            }
            @chmod($hybridauth_config_file, 0777);
            file_put_contents($hybridauth_config_file, $str);
            @chmod($hybridauth_config_file, 0755);
        }
        // widgets
        $query = $this->db->select('widget_id, widget_name, static_content')->from(cms_table_name('main_widget'))->get();
        $widget_list = $query->result_array();
        $normal_widget_list = array();
        $section_widget_list = array();
        foreach($widget_list as $widget){
            if(substr($widget['widget_name'],0,8) == 'section_' || $widget['widget_name'] == 'navigation_right_partial'){
                $section_widget_list[$widget['widget_name']] = $widget;
            }else{
                $normal_widget_list[] = $widget;
            }
        }
        // languages
        $language_list = $this->cms_language_list();

        // config
        $query = $this->db->select('config_name, value')->from(cms_table_name('main_config'))->get();
        $config_list = array();
        foreach($query->result_array() as $row){
            $value = $row['value'];
            if($row['config_name'] == 'cms_email_smtp_pass'){
                //$value = cms_decode($value);
                if($value != ''){
                    $value = '[PASSWORD SET]';
                }
            }
            $config_list[$row['config_name']] = $value;
        }

        // layout
        $layout_list = $this->cms_get_layout();

        // get third_party_configurations
        include($hybridauth_config_file);
        $third_party_config = array();
        foreach($third_party_variables as $var){
            eval('$third_party_config["'.$var.'"] = $'.$var.';');
        }

        // update route
        if($this->cms_is_module_active('gofrendi.noCMS.multisite')){
            $module_path = $this->cms_module_path('gofrendi.noCMS.multisite');
            if(strtoupper($this->cms_get_config('cms_add_subsite_on_register')) == 'TRUE'){
                $this->cms_add_route('main/register', $module_path.'/multisite/register');
            }else{
                $this->cms_remove_route('main/register');
            }
        }

        $default_controller = $this->cms_get_default_controller();

        // send to the view
        $data['normal_widget_list'] = $normal_widget_list;
        $data['section_widget_list'] = $section_widget_list;
        $data['language_list'] = $language_list;
        $data['config_list'] = $config_list;
        $data['layout_list'] = $layout_list;
        $data['current_language'] = $this->cms_get_config('site_language', True);
        $data['default_controller'] = $default_controller;
        $data['multisite_active'] = $this->cms_is_module_active('gofrendi.noCMS.multisite');
        $data['third_party_config'] = $third_party_config;
        $data['changed'] = count($_POST)>0;
        $data['selected_tab_id'] = isset($_POST['selected_tab_id'])? $_POST['selected_tab_id'] : 'tab-general';
        $this->cms_invalidate_cache();
        $this->view('setting_index', $data, 'main_setting');
    }
}
