<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Multisite extends CMS_Secure_Controller {

    private function randomize_string($value){
        $time = date('Y:m:d H:i:s');
        return substr(md5($value.$time),0,6);
    }

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('index');
        $URL_MAP[$module_path.'/multisite'] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        $URL_MAP[$module_path.'/multisite/get_data'] = $navigation_name;
        $URL_MAP[$module_path.'/get_data'] = $navigation_name;
        return $URL_MAP;
    }

    protected function make_associative_array($array){
        $new_array = array();
        foreach($array as $element){
            $new_array[$element] = $element;
        }
        return $new_array;
    }

    public function index(){
        $data = array(
            'allow_navigate_backend' => CMS_SUBSITE == '' && $this->cms_allow_navigate($this->n('add_subsite')),
            'backend_url' => site_url($this->cms_module_path().'/add_subsite/index'),
            'module_path' => $this->cms_module_path(),
            'first_data'  => Modules::run($this->cms_module_path().'/multisite/get_data', 0, '')
        );
        $this->view($this->cms_module_path().'/multisite_index',$data,
            $this->n('index'));
    }

    public function delete($subsite){
        $is_admin = $this->cms_user_id() == 1 || in_array(1, $this->cms_user_group_id());
        if($is_admin){
            $this->load->model($this->cms_module_path().'/subsite_model');
            $this->subsite_model->delete($subsite);
        }
        redirect( $this->cms_module_path() == 'multisite'?
                site_url($this->cms_module_path()) :
                site_url($this->cms_module_path().'/multisite'));
    }

    public function edit($site_name){
        $this->cms_guard_page($this->n('index'));
        $this->load->model($this->cms_module_path().'/subsite_model');
        $is_super_admin = $this->cms_user_id() == 1 || in_array(1, $this->cms_user_group_id());
        // don't edit if not allowed
        if(!$is_super_admin && !$this->cms_have_privilege('modify_subsite')){
            $not_allowed = TRUE;
            $query = $this->db->select('user_id')
                ->from($this->cms_complete_table_name('subsite', 'gofrendi.noCMS.multisite'))
                ->where('name', $site_name)
                ->get();
            if($query->num_rows()>0){
                $row = $query->row();
                if($row->user_id == $this->cms_user_id()){
                    $not_allowed = FALSE;
                }
            }
            if($not_allowed){
                $module_path = $this->cms_module_path();
                if($module_path == 'multisite'){
                    redirect($module_path);
                }else{
                    redirect($module_path.'/multisite');
                }
            }
        }
        // get module and theme list
        $module_list = $this->subsite_model->module_list();
        $theme_list = $this->subsite_model->theme_list();
        // if btn_save clicked
        $save = false;
        if($this->input->post('btn_save')){
            $description = $this->input->post('description');
            $name = $this->input->post('name');
            $use_subdomain = $this->input->post('use_subdomain') == 'True'? 1 : 0;

            // upload the logo
            $upload_path = FCPATH.'modules/'.$this->cms_module_path().'/assets/uploads/';
            $file_name = NULL;
            if(isset($_FILES['logo']) && isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != ''){
                $tmp_name = $_FILES['logo']['tmp_name'];
                $file_name = $_FILES['logo']['name'];
                $file_name = $this->randomize_string($file_name).$file_name;
                move_uploaded_file($tmp_name, $upload_path.$file_name);

                $new_logo_file = FCPATH.'assets/nocms/images/custom_logo/'.$site_name.$_FILES['logo']['name'];
                $new_logo_config = '{{ base_url }}assets/nocms/images/custom_logo/'.$site_name.$_FILES['logo']['name'];

                $this->cms_resize_image($upload_path.$file_name, 800, 125, $new_logo_file);
                $this->db->update($this->subsite_model->get_subsite_config_table_name($site_name),
                    array('value'=>$new_logo_config),
                    array('config_name'=>'site_logo'));
                // update cms_config
                __cms_config('site_logo', $new_logo_config, FALSE,
                    APPPATH.'config/site-'.$site_name.'/cms_config.php', 'cms_config');
            }
            $logo = $file_name;

            $data = array(
                    'description'=>$description,
                    'use_subdomain'=>$use_subdomain,
                );
            if($logo !== NULL){
                $data['logo'] = $logo;
            }
            if($is_super_admin){
                $activated_module_list = $this->input->post('modules');
                $activated_theme_list = $this->input->post('themes');
                $aliases = $this->input->post('aliases');
                $activated_module_list = $activated_module_list===NULL? array() : $activated_module_list;
                $activated_theme_list = $activated_theme_list===NULL? array() : $activated_theme_list;
                $modules = $activated_module_list == NULL? '' : implode(',', $activated_module_list);
                $themes = $activated_theme_list == NULL? '' : implode(',', $activated_theme_list);
                $active = $this->input->post('active') == 'True'? 1 : 0;
                $data['modules'] = $modules;
                $data['themes'] = $themes;
                $data['aliases'] = $aliases;
                $data['active'] = $active;
            }
            $this->db->update($this->cms_complete_table_name('subsite', 'gofrendi.noCMS.multisite'), $data, array('name'=>$site_name));
            $save = true;
        }
        // get data
        $subsite = $this->subsite_model->get_one_data($site_name);
        // adjusted module_list
        $adjusted_module_list = array();
        foreach($module_list as $module){
            $adjusted_module_list[$module['module_name']] = $module['module_path'];
        }
        $adjusted_theme_list = array();
        foreach($theme_list as $theme){
            $adjusted_theme_list[$theme['name']] = $theme['name'];
        }
        $data = array(
            'edit_url' => $this->cms_module_path() == 'multisite'?
                site_url($this->cms_module_path().'/edit/'.$site_name) :
                site_url($this->cms_module_path().'/multisite/edit/'.$site_name),
            'description' => $subsite->description,
            'name' => $subsite->name,
            'logo' => $subsite->logo,
            'use_subdomain' => $subsite->use_subdomain,
            'modules' => $subsite->modules,
            'themes' => $subsite->themes,
            'aliases'=> $subsite->aliases,
            'module_list' => $adjusted_module_list,
            'theme_list' => $adjusted_theme_list,
            'is_super_admin' => $is_super_admin,
            'save' => $save,
            'active' => $subsite->active,
        );
        // show
        //$config = array('privileges'=>array('modify_subsite'));
        $config = array();
        $this->view($this->cms_module_path().'/multisite_edit', $data,
            $this->n('index'), $config);
    }

    public function get_data($page=0, $keyword=''){
        // get page and keyword parameter
        // get page and keyword parameter
        $post_keyword   = $this->input->post('keyword');
        $post_page      = $this->input->post('page');
        if($keyword == '' && $post_keyword != NULL) $keyword = $post_keyword;
        if($page == 0 && $post_page != NULL) $page = $post_page;

        // get data from model
        $this->load->model($this->cms_module_path().'/subsite_model');
        $result = $this->subsite_model->get_data($keyword, $page);

        // get site_url and is_admin
        $is_admin = $this->cms_user_id() == 1 || in_array(1, $this->cms_user_group_id());

        $data = array(
            'site_url' => site_url(),
            'result'=>$result,
            'allow_navigate_backend' => CMS_SUBSITE == '' && $this->cms_have_privilege('modify_subsite'),
            'is_admin' => $is_admin,
            'delete_url' => $this->cms_module_path() == 'multisite'? site_url($this->cms_module_path().'/delete') :site_url($this->cms_module_path().'/multisite/delete'),
            'edit_url' => $this->cms_module_path() == 'multisite'? site_url($this->cms_module_path().'/edit') :site_url($this->cms_module_path().'/multisite/edit'),
        );
        $config = array('only_content'=>TRUE);
        $this->view($this->cms_module_path().'/browse_subsite_partial_view',$data,
           $this->n('browse_subsite'), $config);
    }

    public function ajax_user_multisite(){
        $user_id = $this->cms_user_id();
        $query = $this->db->select('name')
            ->from($this->cms_complete_table_name('subsite', 'gofrendi.noCMS.multisite'))
            ->where('user_id', $user_id)
            ->get();
        $subsite_list = array();
        foreach($query->result() as $row){
            $subsite_list[] = $row->name;
        }
        echo json_encode($subsite_list);
    }

    public function register(){
        $this->cms_guard_page('main_register');

        // the honey_pot, every fake input should be empty
        $honey_pot_pass = (strlen($this->input->post('user_name', ''))==0) &&
            (strlen($this->input->post('email', ''))==0) &&
            (strlen($this->input->post('real_name', ''))==0) &&
            (strlen($this->input->post('password', ''))==0) &&
            (strlen($this->input->post('confirm_password'))==0);
        if(!$honey_pot_pass){
            show_404();
            die();
        }

        $previous_secret_code = $this->session->userdata('__main_registration_secret_code');
        if($previous_secret_code === NULL){
            $previous_secret_code = $this->cms_random_string();
        }

        $activation = $this->cms_get_config('cms_signup_activation');

        $module_path = $this->cms_module_path('gofrendi.noCMS.multisite');
        $subsite_table_name = $this->cms_complete_table_name('subsite', 'gofrendi.noCMS.multisite');
        $this->load->model($module_path.'/subsite_model');

        //get user input
        $user_name        = $this->input->post($previous_secret_code.'user_name');
        $email            = $this->input->post($previous_secret_code.'email');
        $real_name        = $this->input->post($previous_secret_code.'real_name');
        $password         = $this->input->post($previous_secret_code.'password');
        $confirm_password = $this->input->post($previous_secret_code.'confirm_password');

        //set validation rule
        $this->form_validation->set_rules($previous_secret_code.'user_name', 'User Name', 'required');
        $this->form_validation->set_rules($previous_secret_code.'email', 'E mail', 'required|valid_email');
        $this->form_validation->set_rules($previous_secret_code.'real_name', 'Real Name', 'required');
        $this->form_validation->set_rules($previous_secret_code.'password', 'Password', 'required|matches['.$previous_secret_code.'confirm_password]');
        $this->form_validation->set_rules($previous_secret_code.'confirm_password', 'Password Confirmation', 'required');

        // use_subdomain
        $cms_subsite_use_subdomain = $this->cms_get_config('cms_subsite_use_subdomain');

        // generate new secret code
        $secret_code = $this->cms_random_string();
        $this->session->set_userdata('__main_registration_secret_code', $secret_code);
        if ($this->form_validation->run() && !$this->cms_is_user_exists($user_name) &&
        !$this->cms_is_user_exists($email) && preg_match('/@.+\./', $email) &&
        $user_name != '' && $email != '') {
            $configs = array();
            if(CMS_SUBSITE == '' && $this->cms_is_module_active('gofrendi.noCMS.multisite') && $this->cms_get_config('cms_add_subsite_on_register') == 'TRUE'){
                $configs['site_name'] = $this->input->post('site_title');
                $configs['site_slogan'] = $this->input->post('site_slogan');
                $this->load->library('image_moo');
                if(isset($_FILES['site_logo'])){
                    $site_logo = $_FILES['site_logo'];
                    if(isset($site_logo['tmp_name']) && $site_logo['tmp_name'] != '' && getimagesize($site_logo['tmp_name']) !== FALSE){
                        try{
                            $file_name = FCPATH.'assets/nocms/images/custom_logo/'.$user_name.$site_logo['name'];
                            move_uploaded_file($site_logo['tmp_name'], $file_name);
                            $this->cms_resize_image($file_name, 800, 125);
                            $configs['site_logo'] = '{{ base_url }}assets/nocms/images/custom_logo/'.$user_name.$site_logo['name'];
                        }catch(Exception $e){
                            // do nothing
                        }
                    }
                }
                if(isset($_FILES['site_favicon'])){
                    $site_favicon = $_FILES['site_favicon'];
                    if(isset($site_favicon['tmp_name']) && $site_favicon['tmp_name'] != '' && getimagesize($site_favicon['tmp_name']) !== FALSE){
                        try{
                            $file_name = FCPATH.'assets/nocms/images/custom_favicon/'.$user_name.$site_favicon['name'];
                            move_uploaded_file($site_favicon['tmp_name'], $file_name);
                            $this->cms_resize_image($file_name, 64, 64);
                            $configs['site_favicon'] = '{{ base_url }}assets/nocms/images/custom_favicon/'.$user_name.$site_favicon['name'];
                        }catch(Exception $e){
                            // do nothing
                        }
                    }
                }

            }
            $new_user_id = $this->cms_do_register($user_name, $email, $real_name, $password, $configs);
            $this->cms_call_hook('cms_after_register', array($new_user_id, $this->input->post()));
            // create subsite
            $current_user_id = $this->db->select('user_id')
                ->from($this->cms_user_table_name())
                ->where('user_name', $user_name)
                ->get()->row()->user_id;

            $this->load->model('installer/install_model');

            // get these from old setting
            $this->install_model->db_table_prefix              = cms_table_prefix();
            $this->install_model->is_subsite                   = TRUE;
            $this->install_model->subsite                      = $user_name;
            $this->install_model->subsite_aliases              = '';
            $this->install_model->set_subsite();
            $this->install_model->hide_index                   = TRUE;
            $this->install_model->gzip_compression             = FALSE;

            $this->load->model($this->cms_module_path().'/subsite_model');
            $template = $this->subsite_model->get_single_template($this->input->post('template'));
            $homepage_layout = $this->input->post('homepage_layout');
            $default_layout = $this->input->post('default_layout');
            $theme = $this->input->post('theme');

            // get template configs
            $template_configs = $template != NULL? $template->configuration: $this->cms_get_config('cms_subsite_configs');
            $template_configs = @json_decode($configs, TRUE);
            if(!$template_configs){
                $template_configs = array();
            }
            foreach($template_configs as $key=>$val){
                $configs[$key] = $val;
            }
            // add site theme and layout
            if($theme != ''){
                $configs['site_theme'] = $theme;
            }
            if($default_layout != ''){
                $configs['site_layout'] = $default_layout;
            }

            // get modules
            $modules = $template != NULL? $template->modules: $this->cms_get_config('cms_subsite_modules');
            $modules = explode(',', $modules);
            $new_modules = array();
            foreach($modules as $module){
                $module = trim($module);
                if(!in_array($module, $new_modules)){
                    $new_modules[] = $module;
                }
            }
            $modules = $new_modules;
            // check installation
            $check_installation = $this->install_model->check_installation();
            $success = $check_installation['success'];
            $module_installed = FALSE;
            if($success){
                $this->install_model->configs = $configs;
                $this->install_model->modules = $modules;
                $config = array(
                        'subsite_home_content'=> $template != NULL? $template->homepage: $this->cms_get_config('cms_subsite_home_content', TRUE),
                        'subsite_homepage_layout' => $homepage_layout,
                        'subsite_user_id' => $current_user_id,
                    );
                log_message('debug', 'Start installing '.$this->install_model->subsite.' subsite');
                $this->install_model->build_configuration($config);
                log_message('debug', 'Configuration built for '.$this->install_model->subsite.' subsite');
                $this->install_model->build_database($config);
                log_message('debug', 'Database built for '.$this->install_model->subsite.' subsite');
                $module_installed = $this->install_model->install_modules();
                log_message('debug', 'Module installed for '.$this->install_model->subsite.' subsite');
            }

            $data = array(
                'name'=> $this->install_model->subsite,
                'description'=>$user_name.' website',
                'use_subdomain'=>$cms_subsite_use_subdomain=='TRUE'?1:0,
                'user_id'=>$current_user_id,
                'active'=>$activation == 'automatic'
            );
            $this->db->insert($subsite_table_name, $data);

            // get the new subsite
            $t_user = $this->cms_user_table_name();
            $t_subsite = $subsite_table_name;
            $query = $this->db->select('name,use_subdomain')
                ->from($t_subsite)
                ->join($t_user, $t_user.'.user_id='.$t_subsite.'.user_id')
                ->where('user_name', $user_name)
                ->order_by($t_subsite.'.id', 'desc')
                ->get();
            if($query->num_rows()>0){
                $row = $query->row();
                $subsite = $row->name;
                // get directory
                $site_url = site_url();
                $site_url = substr($site_url, 0, strlen($site_url)-1);
                $site_url_part = explode('/', $site_url);
                if(count($site_url_part)>3){
                    $directory_part = array_slice($site_url_part, 3);
                    $directory = '/'.implode('/', $directory_part);
                }else{
                    $directory = '';
                }
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
                $ssl = $protocol == 'https://';
                $port = $_SERVER['SERVER_PORT'];
                $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
                if($row->use_subdomain){
                    $url = $protocol.$subsite.'.'.$_SERVER['SERVER_NAME'].$port.$directory;
                }else{
                    $url = $protocol.$_SERVER['SERVER_NAME'].$port.$directory.'/site-'.$subsite;
                }
                $this->cms_do_login($user_name, $password);
                redirect($url,'refresh');
            }
            redirect('','refresh');
        } else {
            $additional_input = $this->cms_call_hook('cms_registration_additional_input');
            $additional_input = implode(' ', $additional_input);
            $data = array(
                'user_name' => $user_name,
                'email' => $email,
                'real_name' => $real_name,
                'register_caption' => $this->cms_lang('Register'),
                'secret_code' => $secret_code,
                'multisite_active' => $this->cms_is_module_active('gofrendi.noCMS.multisite'),
                'add_subsite_on_register' => $this->cms_get_config('cms_add_subsite_on_register') == 'TRUE',
                'theme_list'    => $this->subsite_model->public_theme_list(),
                'layout_list'   => $this->cms_get_layout(),
                'template_list' => $this->subsite_model->template_list(),
                'additional_input' => $additional_input,
            );

            $this->view('multisite/register', $data, 'main_register');
        }

    }

    public function check_registration()
    {
        if ($this->input->is_ajax_request()) {
            $user_name = $this->input->post('user_name');
            $email = $this->input->post('email');
            $user_name_exists    = $this->cms_is_user_exists($user_name);
            $email_exists        = $this->cms_is_user_exists($email);
            $valid_email = preg_match('/@.+\./', $email);
            $message   = "";
            $error = FALSE;
            if ($user_name == "") {
                $message = $this->cms_lang("Username is empty");
                $error = TRUE;
            } else if ($user_name_exists) {
                $message = $this->cms_lang("Username already exists");
                $error = TRUE;
            } else if (!$valid_email){
                $message = $this->cms_lang("Invalid email address");
                $error = TRUE;
            } else if ($email_exists){
                $message = $this->cms_lang("Email already used");
                $error = TRUE;
            } else{
                $subsite = strtolower($user_name);
                $sanitized_subsite = '';
                for($i=0; $i<strlen($subsite); $i++){
                    $letter = substr($subsite, $i, 1);
                    if(is_numeric($letter) || strpos('abcdefghijklmnopqrstuvwxyz_', $letter) !== FALSE){
                        $sanitized_subsite .= $letter;
                    }
                }
                $subsite = $sanitized_subsite;
                if($subsite == ''){
                    $message = $this->cms_lang("Subsite is empty or username have no alphabet character");
                    $error = TRUE;
                }
                if(!$error){
                    // is there any subsite with similar name
                    // $module_path = $this->cms_module_path('gofrendi.noCMS.multisite');
                    // $this->cms_override_module_path($module_path);
                    $t_subsite = $this->cms_complete_table_name('subsite', 'gofrendi.noCMS.multisite');
                    $query = $this->db->select('name')
                        ->from($t_subsite)
                        ->where('name', $subsite)
                        ->get();
                    if($query->num_rows()>0){
                        $message = $this->cms_lang("Subsite already used, choose other username");
                        $error = TRUE;
                    }
                    // $this->cms_reset_overridden_module_path();
                }
            }

            if(!$error){
                $return_set = $this->cms_call_hook('cms_validate_register', array($this->input->post()));
                foreach($return_set as $return){
                    if(is_array($return)){
                        if(array_key_exists('error', $return)){
                            $error = $return['error'];
                            if(array_key_exists('message', $return)){
                                $message = $return['message'];
                            }
                            break;
                        }
                    }
                }
            }

            $data = array(
                "exists" => $user_name_exists || $email_exists,
                "error" => $error,
                "message" => $message
            );
            $this->cms_show_json($data);
        }
    }
}
