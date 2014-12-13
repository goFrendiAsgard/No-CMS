<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends CMS_Controller{

    protected $theme = 'neutral';

    private function update_static_content($widget_name, $content){
        $this->db->update(cms_table_name('main_widget'), array('static_content'=>$content), array('widget_name'=>$widget_name));
    }

    public function index(){
        $this->theme = $this->cms_get_config('site_theme');

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
            $hybridauth_config_file = APPPATH.'/config/hybridauthlib.php';
        }else{
            $hybridauth_config_file = APPPATH.'/config/site-'.CMS_SUBSITE.'/hybridauthlib.php';
        }

        $this->load->library('image_moo');
        // save the uploaded files
        if(isset($_FILES['site_logo'])){
            $site_logo = $_FILES['site_logo'];
            if(isset($site_logo['tmp_name']) && $site_logo['tmp_name'] != '' && getimagesize($site_logo['tmp_name']) !== FALSE){
                $file_name = FCPATH.'assets/nocms/images/custom_logo/'.CMS_SUBSITE.$site_logo['name'];
                move_uploaded_file($site_logo['tmp_name'], $file_name);
                $this->cms_set_config('site_logo', '{{ base_url }}assets/nocms/images/custom_logo/'.CMS_SUBSITE.$site_logo['name']);
                $this->image_moo->load($file_name)->resize(800,125)->save($file_name,true);
            }
        }
        if(isset($_FILES['site_favicon'])){
            $site_favicon = $_FILES['site_favicon'];
            if(isset($site_favicon['tmp_name']) && $site_favicon['tmp_name'] != '' && getimagesize($site_favicon['tmp_name']) !== FALSE){
                $file_name = FCPATH.'assets/nocms/images/custom_favicon/'.CMS_SUBSITE.$site_favicon['name'];
                move_uploaded_file($site_favicon['tmp_name'], $file_name);
                $this->cms_set_config('site_favicon', '{{ base_url }}assets/nocms/images/custom_favicon/'.CMS_SUBSITE.$site_favicon['name']);
                $this->image_moo->load($file_name)->resize(64,64)->save($file_name,true);
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
                'cms_signup_activation', 'cms_email_protocol',
                'cms_email_reply_address', 'cms_email_reply_name', 'cms_email_forgot_subject',
                'cms_email_forgot_message', 'cms_email_signup_subject', 'cms_email_signup_message',
                'cms_email_useragent', 'cms_email_mailpath', 'cms_email_smtp_host', 'cms_email_smtp_user',
                'cms_email_smtp_pass', 'cms_email_smtp_port', 'cms_email_smtp_timeout',
                'cms_google_analytic_property_id','cms_internet_connectivity','cms_subsite_configs','cms_subsite_modules',
            );
            // only for non-subsite
            if(CMS_SUBSITE == '' && $this->cms_is_module_active('gofrendi.noCMS.multisite')){
                $configuration_list[] = 'cms_add_subsite_on_register';
                $configuration_list[] = 'cms_subsite_use_subdomain';
                $configuration_list[] = 'cms_subsite_home_content';
                $configuration_list[] = 'cms_subsite_configs';
                $configuration_list[] = 'cms_subsite_modules';
            }
            foreach($configuration_list as $configuration){
                $this->cms_set_config($configuration, $this->input->post($configuration));
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
            $config_list[$row['config_name']] = $row['value'];
        }

        // layout
        $layout_list = array();
        $site_theme = $config_list['site_theme'];
        $this->load->helper('directory');
        $files = directory_map('themes/'.$site_theme.'/views/layouts/', 1);
        sort($files);
        foreach($files as $file){
            if(is_dir('themes/'.$site_theme.'/views/layouts/'.$file)){
                continue;
            }
            $file = str_ireplace('.php', '', $file);
            if($file == $config_list['site_layout']){
                continue;
            }
            $layout_list[] = $file;
        }
        
        // get third_party_configurations
        include($hybridauth_config_file);
        $third_party_config = array();
        foreach($third_party_variables as $var){
            eval('$third_party_config["'.$var.'"] = $'.$var.';');
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
        $this->view('setting_index', $data, 'main_setting');
    }
}
