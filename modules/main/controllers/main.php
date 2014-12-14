<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The Main Controller of No-CMS
 *
 * @author gofrendi
 */
class Main extends CMS_Controller
{
    private function unique_field_name($field_name)
    {
        return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not with a number
    }

    private function __random_string($length=10)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $size = strlen( $chars );
        $str = '';
        for( $i = 0; $i < $length; $i++ ){
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }
        return $str;
    }

    protected function upload($upload_path, $input_file_name = 'userfile', $submit_name = 'upload')
    {
        $data = array(
            "uploading" => TRUE,
            "success" => FALSE,
            "message" => ""
        );
        if (isset($_POST[$submit_name])) {
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'zip';
            $config['max_size']      = 8 * 1024;
            $config['overwrite']     = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($input_file_name)) {
                $data['uploading'] = TRUE;
                $data['success']   = FALSE;
                $data['message']   = $this->upload->display_errors();
            } else {
                $this->load->library('unzip');
                $upload_data = $this->upload->data();
                $this->unzip->extract($upload_data['full_path']);
                unlink($upload_data['full_path']);
                $data['uploading'] = TRUE;
                $data['success']   = TRUE;
                $data['message']   = '';
            }
        } else {
            $data['uploading'] = FALSE;
            $data['success']   = FALSE;
            $data['message']   = '';
        }
        return $data;
    }

    protected function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    protected function rrmdir($dir) {
        foreach(glob($dir . '/*') as $file) {
            if(is_dir($file)){
                $this->rrmdir($file);
            } else {
                unlink($file);
            }
        }
        unlink($dir.'/.htaccess');
        rmdir($dir);
    }

    public function module_management()
    {
        $this->cms_guard_page('main_module_management');

        if(isset($_FILES['userfile'])){
            // upload new module
            $directory = basename($_FILES['userfile']['name'],'.zip');

            // subsite_auth
            $subsite_auth_file = FCPATH.'modules/'.$directory.'/subsite_auth.php';
            $backup_subsite_auth_file = FCPATH.'modules/'.$directory.'_subsite_auth.php';
            $subsite_backup = FALSE;
            if(file_exists($subsite_auth_file)){
                copy($subsite_auth_file, $backup_subsite_auth_file);
                $subsite_backup = TRUE;
            }
            // config
            $config_dir = FCPATH.'modules/'.$directory.'/config';
            $backup_config_dir = FCPATH.'modules/'.$directory.'_config';
            $config_backup = FALSE;
            if(file_exists($config_dir) && is_dir($config_dir)){
                $this->recurse_copy($config_dir, $backup_config_dir);
                $config_backup = TRUE;
            }
        }


        $data['upload'] = $this->upload(FCPATH.'modules/', 'userfile', 'upload');
        if($data['upload']['success']){
            if($subsite_backup){
                copy($backup_subsite_auth_file, $subsite_auth_file);
                unlink($backup_subsite_auth_file);
            }
            if($config_backup){
                $this->recurse_copy($backup_config_dir, $config_dir);
                $this->rrmdir($backup_config_dir);
            }
        }

        // show the view
        $modules = $this->cms_get_module_list();
        for($i=0; $i<count($modules); $i++){
            $module = $modules[$i];
            $module_path = $module['module_path'];
        }
        $data['modules'] = $modules;
        $data['upload_new_module_caption'] = $this->cms_lang('Upload New Module');
        $this->view('main/main_module_management', $data, 'main_module_management');
    }

    public function change_theme($theme = NULL)
    {
        $this->cms_guard_page('main_change_theme');
        if(isset($_FILES['userfile'])){
            // upload new module
            $directory = basename($_FILES['userfile']['name'],'.zip');

            // subsite_auth
            $subsite_auth_file = FCPATH.'themes'.$directory.'/subsite_auth.php';
            $backup_subsite_auth_file = FCPATH.'themes/'.$directory.'_subsite_auth.php';
            $subsite_backup = FALSE;
            if(file_exists($subsite_auth_file)){
                copy($subsite_auth_file, $backup_subsite_auth_file);
                $subsite_backup = TRUE;
            }
        }
        // upload new theme
        $data['upload'] = $this->upload('./themes/', 'userfile', 'upload');

        if($data['upload']['success']){
            if($subsite_backup){
                copy($backup_subsite_auth_file, $subsite_auth_file);
                unlink($backup_subsite_auth_file);
            }
        }

        // show the view
        if (isset($theme)) {
            $this->cms_set_config('site_theme', $theme);
        }

        $data['themes'] = $this->cms_get_theme_list();
        $data['upload_new_theme_caption'] = $this->cms_lang('Upload New Theme');
        $this->view('main/main_change_theme', $data, 'main_change_theme');
    }

    //this is used for the real static page which doesn't has any URL in navigation management
    public function static_page($navigation_name)
    {
        $this->view('CMS_View', NULL, $navigation_name);
    }

    public function login()
    {
        $this->cms_guard_page('main_login');
        // Is registration allowed
        $allow_register = $this->cms_allow_navigate('main_register');
        //retrieve old_url from userdata if exists
        $this->load->library('session');
        $old_url = $this->session->userdata('cms_old_url');

        //get user input
        $identity = $this->input->post('identity');
        $password = $this->input->post('password');

        //set validation rule
        $this->form_validation->set_rules('identity', 'Identity', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean');

        if ($this->form_validation->run()) {
            if ($this->cms_do_login($identity, $password)) {
                //if old_url exist, redirect to old_url, else redirect to main/index
                if (isset($old_url)) {
                    $this->session->set_userdata('cms_old_url', NULL);
                    // seek for the closest url that exist in navigation table to avoid something like manage_x/index/edit/1/error to be appeared
                    $old_url_part = explode('/', $old_url);
                    while(count($old_url_part)>0){
                        $query = $this->db->select('url')
                            ->from(cms_table_name('main_navigation'))
                            ->like('url', implode('/', $old_url_part))
                            ->get();
                        if($query->num_rows()>0){
                            $row = $query->row();
                            $old_url = $row->url;
                            break;
                        }else{
                            $new_old_url_part = array();
                            for($i=0; $i<count($old_url_part)-1; $i++){
                                $new_old_url_part[] = $old_url_part[$i];
                            }
                            $old_url_part = $new_old_url_part;
                        }
                    }
                    redirect($old_url,'refresh');
                } else {
                    redirect('','refresh');
                }
            } else {

                //view login again
                $data = array(
                    "identity" => $identity,
                    "message" => '{{ language:Error }}: {{ language:Login Failed }}',
                    "providers" => $this->cms_third_party_providers(),
                    "login_caption" => $this->cms_lang("Login"),
                    "register_caption" => $this->cms_lang("Register"),
                    "allow_register"=> $allow_register,
                );
                $this->view('main/main_login', $data, 'main_login');
            }
        } else {
            //view login again
            $data = array(
                "identity" => $identity,
                "message" => '',
                "providers" => $this->cms_third_party_providers(),
                "login_caption" => $this->cms_lang("Login"),
                "register_caption" => $this->cms_lang("Register"),
                "allow_register" => $allow_register,
            );
            $this->view('main/main_login', $data, 'main_login');
        }
    }

    public function activate($activation_code)
    {
        $this->cms_activate_account($activation_code);
        redirect('','refresh');
    }

    public function forgot($activation_code = NULL)
    {
        $this->cms_guard_page('main_forgot');
        if (isset($activation_code)) {
            //get user input
            $password = $this->input->post('password');
            //set validation rule
            $this->form_validation->set_rules('password', 'Password', 'required|xss_clean|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required|xss_clean');

            if ($this->form_validation->run()) {
                if ($this->cms_valid_activation_code($activation_code)) {
                    $this->cms_activate_account($activation_code, $password);
                    redirect('','refresh');
                } else {
                    $main_forgot_url = $this->cms_navigation_url('main_forgot');
                    redirect($main_forgot_url,'refresh');
                }
            } else {
                $data = array(
                    "activation_code" => $activation_code,
                    "change_caption" => $this->cms_lang('Change'),
                );
                $this->view('main/main_forgot_change_password', $data, 'main_forgot');
            }
        } else {
            //get user input
            $identity = $this->input->post('identity');

            //set validation rule
            $this->form_validation->set_rules('identity', 'Identity', 'required|xss_clean');

            if ($this->form_validation->run()) {
                if ($this->cms_generate_activation_code($identity, TRUE, 'FORGOT')) {
                    redirect('','refresh');
                } else {
                    $data = array(
                        "identity" => $identity,
                        "send_activation_code_caption"=> $this->cms_lang('Send activation code to my email'),
                    );
                    $this->view('main/main_forgot_fill_identity', $data, 'main_forgot');
                }
            } else {
                $data = array(
                    "identity" => $identity,
                    "send_activation_code_caption"=> $this->cms_lang('Send activation code to my email'),
                );
                $this->view('main/main_forgot_fill_identity', $data, 'main_forgot');
            }
        }
    }

    public function register()
    {
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
            $previous_secret_code = $this->__random_string();
        }
        //get user input
        $user_name        = $this->input->post($previous_secret_code.'user_name');
        $email            = $this->input->post($previous_secret_code.'email');
        $real_name        = $this->input->post($previous_secret_code.'real_name');
        $password         = $this->input->post($previous_secret_code.'password');
        $confirm_password = $this->input->post($previous_secret_code.'confirm_password');

        //set validation rule
        $this->form_validation->set_rules($previous_secret_code.'user_name', 'User Name', 'required|xss_clean');
        $this->form_validation->set_rules($previous_secret_code.'email', 'E mail', 'required|xss_clean|valid_email');
        $this->form_validation->set_rules($previous_secret_code.'real_name', 'Real Name', 'required|xss_clean');
        $this->form_validation->set_rules($previous_secret_code.'password', 'Password', 'required|xss_clean|matches['.$previous_secret_code.'confirm_password]');
        $this->form_validation->set_rules($previous_secret_code.'confirm_password', 'Password Confirmation', 'required|xss_clean');

        // generate new secret code
        $secret_code = $this->__random_string();
        $this->session->set_userdata('__main_registration_secret_code', $secret_code);
        if ($this->form_validation->run() && !$this->cms_is_user_exists($user_name)) {
            $configs = array();
            if(CMS_SUBSITE == '' && $this->cms_is_module_active('gofrendi.noCMS.multisite') && $this->cms_get_config('cms_add_subsite_on_register') == 'TRUE'){
                $configs['site_name'] = $this->input->post('site_title');
                $configs['site_slogan'] = $this->input->post('site_slogan');
                $this->load->library('image_moo');
                if(isset($_FILES['site_logo'])){
                    $site_logo = $_FILES['site_logo'];
                    if(isset($site_logo['tmp_name']) && $site_logo['tmp_name'] != '' && getimagesize($site_logo['tmp_name']) !== FALSE){
                        $file_name = FCPATH.'assets/nocms/images/custom_logo/'.$user_name.$site_logo['name'];
                        move_uploaded_file($site_logo['tmp_name'], $file_name);
                        $configs['site_logo'] = '{{ base_url }}assets/nocms/images/custom_logo/'.$user_name.$site_logo['name'];
                        $this->image_moo->load($file_name)->resize(800,125)->save($file_name,true);
                    }
                }
                if(isset($_FILES['site_favicon'])){
                    $site_favicon = $_FILES['site_favicon'];
                    if(isset($site_favicon['tmp_name']) && $site_favicon['tmp_name'] != '' && getimagesize($site_favicon['tmp_name']) !== FALSE){
                        $file_name = FCPATH.'assets/nocms/images/custom_favicon/'.$user_name.$site_favicon['name'];
                        move_uploaded_file($site_favicon['tmp_name'], $file_name);
                        $configs['site_favicon'] = '{{ base_url }}assets/nocms/images/custom_favicon/'.$user_name.$site_favicon['name'];
                        $this->image_moo->load($file_name)->resize(64,64)->save($file_name,true);
                    }
                }

            }
            $this->cms_do_register($user_name, $email, $real_name, $password, $configs);
            redirect('','refresh');
        } else {
            $data = array(
                "user_name" => $user_name,
                "email" => $email,
                "real_name" => $real_name,
                "register_caption" => $this->cms_lang('Register'),
                "secret_code" => $secret_code,
                "multisite_active" => $this->cms_is_module_active('gofrendi.noCMS.multisite'),
                "add_subsite_on_register" => $this->cms_get_config('cms_add_subsite_on_register') == 'TRUE',
            );
            $this->view('main/main_register', $data, 'main_register');
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
            }
            $data = array(
                "exists" => $user_name_exists || $email_exists,
                "error" => $error,
                "message" => $message
            );
            $this->cms_show_json($data);
        }
    }

    public function get_layout($theme=''){
        if($this->input->is_ajax_request()){
            if($theme == ''){
                $theme = $this->cms_get_config('site_theme');
            }
            $layout_list = array('');
            $this->load->helper('directory');
            $files = directory_map('themes/'.$theme.'/views/layouts/', 1);
            sort($files);
            foreach($files as $file){
                if(is_dir('themes/'.$theme.'/views/layouts/'.$file)){
                    continue;
                }
                $file = str_ireplace('.php', '', $file);
                $layout_list[] = $file;
            }
            $this->cms_show_json($layout_list);
        }
    }

    public function check_change_profile()
    {
        if ($this->input->is_ajax_request()) {
            $user_name = $this->input->post('user_name');
            $email = $this->input->post('email');
            $user_name_exists    = $this->cms_is_user_exists($user_name) && $user_name != $this->cms_user_name();
            $email_exists        = $this->cms_is_user_exists($email) && $email != $this->cms_user_email();
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
            }
            $data = array(
                "exists" => $user_name_exists || $email_exists,
                "error" => $error,
                "message" => $message
            );
            $this->cms_show_json($data);
        }
    }

    public function change_profile()
    {
        $this->cms_guard_page('main_change_profile');
        $SQL   = "SELECT user_name, email, real_name FROM ".cms_table_name('main_user')." WHERE user_id = " . $this->cms_user_id();
        $query = $this->db->query($SQL);
        $row   = $query->row();

        //get user input
        $user_name        = $this->input->post('user_name');
        $email            = $this->input->post('email');
        $real_name        = $this->input->post('real_name');
        $change_password  = $this->input->post('change_password');
        $password         = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        if (!$change_password) {
            $password = NULL;
        }

        if (!$user_name)
            $user_name = $row->user_name;
        if (!$email)
            $email = $row->email;
        if (!$real_name)
            $real_name = $row->real_name;

        //set validation rule
        $this->form_validation->set_rules('user_name', 'User Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'E mail', 'required|xss_clean|valid_email');
        $this->form_validation->set_rules('real_name', 'Real Name', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'xss_clean|matches[confirm_password]');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'xss_clean');

        if ($this->form_validation->run()) {
            $this->cms_do_change_profile($user_name, $email, $real_name, $password);
            redirect('','refresh');
        } else {
            $data = array(
                "user_name" => $user_name,
                "email" => $email,
                "real_name" => $real_name,
                "change_profile_caption" => $this->cms_lang('Change Profile'),
            );
            $this->view('main/main_change_profile', $data, 'main_change_profile');
        }
    }

    public function logout()
    {
        $this->cms_do_logout();
        redirect('','refresh');
    }

    public function index()
    {
        $this->cms_guard_page('main_index');
        $data = array(
            "submenu_screen" => $this->cms_submenu_screen(NULL)
        );
        $this->view('main/main_index', $data, 'main_index');
    }

    public function management()
    {
        $this->cms_guard_page('main_management');
        $data = array(
            "submenu_screen" => $this->cms_submenu_screen('main_management')
        );
        $this->view('main/main_management', $data, 'main_management');
    }

    public function language($language = NULL)
    {
        $this->cms_guard_page('main_language');
        if (isset($language)) {
            $this->cms_language($language);
            redirect('','refresh');
        } else {
            $data = array(
                "language_list" => $this->cms_language_list(),
                "current_language" => $this->cms_language()
            );
            $this->view('main/main_language', $data, 'main_language');
        }
    }

    // AUTHORIZATION ===========================================================
    public function authorization()
    {
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_authorization'));
        $crud->set_subject('Authorization');

        $crud->columns('authorization_id', 'authorization_name', 'description');
        $crud->display_as('authorization_id', 'Code')->display_as('authorization_name', 'Name')->display_as('description', 'Description');

        $crud->unset_texteditor('description');

        $crud->set_subject('Authorization List');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();
        $crud->required_fields('authorization_name');
        $crud->unique_fields('authorization_name');
        $crud->unset_read();

        $crud->set_language($this->cms_language());

        $output = $crud->render();

        $this->view('grocery_CRUD', $output);
    }

    // USER ====================================================================
    public function user()
    {
        $this->cms_guard_page('main_user_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_user'));
        $crud->set_subject('User');

        $crud->required_fields('user_name','password');
        $crud->unique_fields('user_name');
        $crud->unset_read();

        $crud->columns('user_name', 'email', 'real_name', 'active', 'groups');
        $crud->edit_fields('user_name', 'email', 'real_name', 'active', 'groups');
        $crud->add_fields('user_name', 'email', 'password', 'real_name', 'active', 'groups');
        $crud->field_type('active', 'true_false');

        $crud->display_as('user_name', 'User Name')
            ->display_as('email', 'Email')
            ->display_as('real_name', 'Real Name')
            ->display_as('active', 'Active')
            ->display_as('groups', 'Groups');

        $crud->set_relation_n_n('groups', cms_table_name('main_group_user'), cms_table_name('main_group'), 'user_id', 'group_id', 'group_name');
        $crud->callback_before_insert(array(
            $this,
            'before_insert_user'
        ));
        $crud->callback_before_delete(array(
            $this,
            'before_delete_user'
        ));
        $crud->callback_after_update(array(
            $this,
            'after_update_user'
        ));

        if ($crud->getState() == 'edit') {
            $state_info  = $crud->getStateInfo();
            $primary_key = $state_info->primary_key;
            if ($primary_key == $this->cms_user_id() || $primary_key == 1) {
                $crud->callback_edit_field('active', array(
                    $this,
                    'read_only_user_active'
                ));
            }
        }
        $crud->set_lang_string('delete_error_message', 'You cannot delete super admin user or your own account');
        $crud->set_language($this->cms_language());
        $output = $crud->render();
        $output->undeleted_id = array(1, $this->cms_user_id());

        // prepare css & js, add them to config
        $config = array();
        $asset = new CMS_Asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();
        // show the view
        $this->view('main/main_user', $output, 'main_user_management', $config);
    }

    public function read_only_user_active($value, $row)
    {
        $input   = '<input name="active" value="' . $value . '" type="hidden" />';
        $caption = $value == 0 ? 'Inactive' : 'Active';
        return $input . $caption;
    }

    public function before_insert_user($post_array)
    {
        $post_array['password'] = md5($post_array['password']);
        return $post_array;
    }

    public function before_delete_user($primary_key)
    {
        //The super admin user cannot be deleted, a user cannot delete his/her own account
        if (($primary_key == 1) || ($primary_key == $this->cms_user_id())) {
            return false;
        }
        return true;
    }

    public function after_update_user($post_array, $primary_key)
    {
        // get user activation status
        $user_id = $primary_key;
        $result = $this->db->select('active')
            ->from(cms_table_name('main_user'))
            ->where('user_id', $user_id)
            ->get();
        $row = $result->row();
        $active = $row->active;
        // update subsite
        $this->_cms_set_user_subsite_activation($user_id, $active);
        return TRUE;
    }

    // GROUP ===================================================================
    public function group()
    {
        $this->cms_guard_page('main_group_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_group'));
        $crud->set_subject('User Group');

        $crud->required_fields('group_name');
        $crud->unique_fields('group_name');
        $crud->unset_read();

        $crud->columns('group_name', 'description');
        $crud->edit_fields('group_name', 'description', 'users', 'navigations', 'privileges');
        $crud->add_fields('group_name', 'description', 'users', 'navigations', 'privileges');
        $crud->display_as('group_name', 'Group')
            ->display_as('description', 'Description')
            ->display_as('users', 'Users')
            ->display_as('navigations', 'Navigations')
            ->display_as('privileges', 'Privileges');


        $crud->set_relation_n_n('users', cms_table_name('main_group_user'), cms_table_name('main_user'), 'group_id', 'user_id', 'user_name');
        $crud->set_relation_n_n('navigations', cms_table_name('main_group_navigation'), cms_table_name('main_navigation'), 'group_id', 'navigation_id', 'navigation_name');
        $crud->set_relation_n_n('privileges', cms_table_name('main_group_privilege'), cms_table_name('main_privilege'), 'group_id', 'privilege_id', 'privilege_name');
        $crud->callback_before_delete(array(
            $this,
            'before_delete_group'
        ));

        $crud->unset_texteditor('description');


        $crud->set_lang_string('delete_error_message', $this->cms_lang('You cannot delete admin group or group which is not empty, please empty the group first'));

        $crud->set_language($this->cms_language());

        $output = $crud->render();
        $output->undeleted_id = array(1);
        $query = $this->db->select('group_id')->distinct()
            ->from(cms_table_name('main_group_user'))
            ->get();
        foreach($query->result() as $row){
            $output->undeleted_id[] = $row->group_id;
        }

        // prepare css & js, add them to config
        $config = array();
        $asset = new CMS_Asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();
        // show the view
        $this->view('main/main_group', $output, 'main_group_management', $config);
    }

    public function before_delete_group($primary_key)
    {
        $SQL   = "SELECT user_id FROM ".cms_table_name('main_group_user')." WHERE group_id =" . $primary_key . ";";
        $query = $this->db->query($SQL);
        $count = $query->num_rows();

        /* Can only delete group with no user. Admin group cannot be deleted */
        if ($primary_key == 1 || $count > 0) {
            return false;
        }
        return true;
    }

    // NAVIGATION ==============================================================
    public function navigation($parent_id=NULL)
    {
        $this->cms_guard_page('main_navigation_management');
        $crud = $this->new_crud();

        $state = $crud->getState();
        $state_info = $crud->getStateInfo();

        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_navigation'));
        $crud->set_subject('Navigation (Page)');

        $undeleted_id = array();
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->like('navigation_name','main_','after')
            ->like('url','main/','after')
            ->get();
        foreach($query->result() as $row){
            $undeleted_id[] = $row->navigation_id;
        }

        if($state == 'update' || $state == 'edit' || $state == 'update_validation'){
            $primary_key = $state_info->primary_key;
            if(in_array($primary_key, $undeleted_id)){
                $crud->field_type('navigation_name','readonly');
                $crud->required_fields('title');
            }else{
                $crud->required_fields('navigation_name', 'title');    
            }
        }else{
            $crud->required_fields('navigation_name', 'title');
        }
        $crud->unique_fields('navigation_name', 'title', 'url');
        $crud->unset_read();

        $crud->columns('navigation_name'/*, 'navigation_child', 'title', 'active'*/);
        $crud->edit_fields('navigation_name', 'parent_id', 'title', 'bootstrap_glyph', 'page_title', 'page_keyword', 'description', 'active', 'only_content', 'is_static', 'static_content', 'url','notif_url', 'default_theme', 'default_layout', 'authorization_id', 'groups', 'index');
        $crud->add_fields('navigation_name', 'parent_id', 'title', 'bootstrap_glyph', 'page_title', 'page_keyword', 'description', 'active', 'only_content', 'is_static', 'static_content', 'url','notif_url', 'default_theme', 'default_layout', 'authorization_id', 'groups', 'index');
        
        // get themes to give options for default_theme field
        $themes     = $this->cms_get_theme_list();
        $theme_path = array();
        foreach ($themes as $theme) {
            $theme_path[] = $theme['path'];
        }
        $crud->field_type('default_theme', 'enum', $theme_path);
        $crud->display_as('navigation_name', 'Navigation Code')
            ->display_as('navigation_child', 'Children')
            ->display_as('parent_id', 'Parent')
            ->display_as('title', 'Navigation Title (What visitor see)')
            ->display_as('page_title', 'Page Title')
            ->display_as('page_keyword', 'Page Keyword (Comma Separated)')
            ->display_as('description', 'Description')
            ->display_as('url', 'URL (Where is it point to)')
            ->display_as('notif_url', 'Notification URL')
            ->display_as('active', 'Active')
            ->display_as('is_static', 'Static')
            ->display_as('static_content', 'Static Content')
            ->display_as('authorization_id', 'Authorization')
            ->display_as('groups', 'Groups')
            ->display_as('only_content', 'Only show content')
            ->display_as('default_theme', 'Default Theme')
            ->display_as('default_layout', 'Default Layout');

        $crud->order_by('index', 'asc');

        $crud->unset_texteditor('description');
        $crud->field_type('only_content', 'true_false');
        $crud->field_type('active', 'true_false');
        $crud->field_type('is_static', 'true_false');
        $crud->field_type('bootstrap_glyph','enum',array('glyphicon-adjust', 'glyphicon-align-center', 'glyphicon-align-justify', 'glyphicon-align-left', 'glyphicon-align-right', 'glyphicon-arrow-down', 'glyphicon-arrow-left', 'glyphicon-arrow-right', 'glyphicon-arrow-up', 'glyphicon-asterisk', 'glyphicon-backward', 'glyphicon-ban-circle', 'glyphicon-barcode', 'glyphicon-bell', 'glyphicon-bold', 'glyphicon-book', 'glyphicon-bookmark', 'glyphicon-briefcase', 'glyphicon-bullhorn', 'glyphicon-calendar', 'glyphicon-camera', 'glyphicon-certificate', 'glyphicon-check', 'glyphicon-chevron-down', 'glyphicon-chevron-left', 'glyphicon-chevron-right', 'glyphicon-chevron-up', 'glyphicon-circle-arrow-down', 'glyphicon-circle-arrow-left', 'glyphicon-circle-arrow-right', 'glyphicon-circle-arrow-up', 'glyphicon-cloud', 'glyphicon-cloud-download', 'glyphicon-cloud-upload', 'glyphicon-cog', 'glyphicon-collapse-down', 'glyphicon-collapse-up', 'glyphicon-comment', 'glyphicon-compressed', 'glyphicon-copyright-mark', 'glyphicon-credit-card', 'glyphicon-cutlery', 'glyphicon-dashboard', 'glyphicon-download', 'glyphicon-download-alt', 'glyphicon-earphone', 'glyphicon-edit', 'glyphicon-eject', 'glyphicon-envelope', 'glyphicon-euro', 'glyphicon-exclamation-sign', 'glyphicon-expand', 'glyphicon-export', 'glyphicon-eye-close', 'glyphicon-eye-open', 'glyphicon-facetime-video', 'glyphicon-fast-backward', 'glyphicon-fast-forward', 'glyphicon-file', 'glyphicon-film', 'glyphicon-filter', 'glyphicon-fire', 'glyphicon-flag', 'glyphicon-flash', 'glyphicon-floppy-disk', 'glyphicon-floppy-open', 'glyphicon-floppy-remove', 'glyphicon-floppy-save', 'glyphicon-floppy-saved', 'glyphicon-folder-close', 'glyphicon-folder-open', 'glyphicon-font', 'glyphicon-forward', 'glyphicon-fullscreen', 'glyphicon-gbp', 'glyphicon-gift', 'glyphicon-glass', 'glyphicon-globe', 'glyphicon-hand-down', 'glyphicon-hand-left', 'glyphicon-hand-right', 'glyphicon-hand-up', 'glyphicon-hd-video', 'glyphicon-hdd', 'glyphicon-header', 'glyphicon-headphones', 'glyphicon-heart', 'glyphicon-heart-empty', 'glyphicon-home', 'glyphicon-import', 'glyphicon-inbox', 'glyphicon-indent-left', 'glyphicon-indent-right', 'glyphicon-info-sign', 'glyphicon-italic', 'glyphicon-leaf', 'glyphicon-link', 'glyphicon-list', 'glyphicon-list-alt', 'glyphicon-lock', 'glyphicon-log-in', 'glyphicon-log-out', 'glyphicon-magnet', 'glyphicon-map-marker', 'glyphicon-minus', 'glyphicon-minus-sign', 'glyphicon-move', 'glyphicon-music', 'glyphicon-new-window', 'glyphicon-off', 'glyphicon-ok', 'glyphicon-ok-circle', 'glyphicon-ok-sign', 'glyphicon-open', 'glyphicon-paperclip', 'glyphicon-pause', 'glyphicon-pencil', 'glyphicon-phone', 'glyphicon-phone-alt', 'glyphicon-picture', 'glyphicon-plane', 'glyphicon-play', 'glyphicon-play-circle', 'glyphicon-plus', 'glyphicon-plus-sign', 'glyphicon-print', 'glyphicon-pushpin', 'glyphicon-qrcode', 'glyphicon-question-sign', 'glyphicon-random', 'glyphicon-record', 'glyphicon-refresh', 'glyphicon-registration-mark', 'glyphicon-remove', 'glyphicon-remove-circle', 'glyphicon-remove-sign', 'glyphicon-repeat', 'glyphicon-resize-full', 'glyphicon-resize-horizontal', 'glyphicon-resize-small', 'glyphicon-resize-vertical', 'glyphicon-retweet', 'glyphicon-road', 'glyphicon-save', 'glyphicon-saved', 'glyphicon-screenshot', 'glyphicon-sd-video', 'glyphicon-search', 'glyphicon-send', 'glyphicon-share', 'glyphicon-share-alt', 'glyphicon-shopping-cart', 'glyphicon-signal', 'glyphicon-sort', 'glyphicon-sort-by-alphabet', 'glyphicon-sort-by-alphabet-alt', 'glyphicon-sort-by-attributes', 'glyphicon-sort-by-attributes-alt', 'glyphicon-sort-by-order', 'glyphicon-sort-by-order-alt', 'glyphicon-sound-5-1', 'glyphicon-sound-6-1', 'glyphicon-sound-7-1', 'glyphicon-sound-dolby', 'glyphicon-sound-stereo', 'glyphicon-star', 'glyphicon-star-empty', 'glyphicon-stats', 'glyphicon-step-backward', 'glyphicon-step-forward', 'glyphicon-stop', 'glyphicon-subtitles', 'glyphicon-tag', 'glyphicon-tags', 'glyphicon-tasks', 'glyphicon-text-height', 'glyphicon-text-width', 'glyphicon-th', 'glyphicon-th-large', 'glyphicon-th-list', 'glyphicon-thumbs-down', 'glyphicon-thumbs-up', 'glyphicon-time', 'glyphicon-tint', 'glyphicon-tower', 'glyphicon-transfer', 'glyphicon-trash', 'glyphicon-tree-conifer', 'glyphicon-tree-deciduous', 'glyphicon-unchecked', 'glyphicon-upload', 'glyphicon-usd', 'glyphicon-user', 'glyphicon-volume-down', 'glyphicon-volume-off', 'glyphicon-volume-up', 'glyphicon-warning-sign', 'glyphicon-wrench', 'glyphicon-zoom-in', 'glyphicon-zoom-out'));
        $crud->field_type('index','hidden');

        $crud->set_relation('parent_id', cms_table_name('main_navigation'), 'navigation_name');
        $crud->set_relation('authorization_id', cms_table_name('main_authorization'), 'authorization_name');

        $crud->set_relation_n_n('groups', cms_table_name('main_group_navigation'), cms_table_name('main_group'), 'navigation_id', 'group_id', 'group_name');

        if(isset($parent_id) && intval($parent_id)>0){
            $crud->where(cms_table_name('main_navigation').'.parent_id', $parent_id);
            $state = $crud->getState();
            if($state == 'add'){
                $crud->field_type('parent_id', 'hidden', $parent_id);
            }
        }else{
            $crud->where(array(cms_table_name('main_navigation').'.parent_id' => NULL));
        }

        $crud->callback_column(cms_table_name('main_navigation').'.navigation_name', array(
            $this,
            'column_navigation_name'
        ));

        $crud->callback_before_insert(array(
            $this,
            'before_insert_navigation'
        ));
        $crud->callback_before_delete(array(
            $this,
            'before_delete_navigation'
        ));

        $crud->set_language($this->cms_language());

        $output = $crud->render();

        $navigation_path = array();
        if(isset($parent_id) && intval($parent_id)>0){
            $this->db->select('navigation_name')
                ->from(cms_table_name('main_navigation'))
                ->where('navigation_id', $parent_id);
            $query = $this->db->get();
            if($query->num_rows()>0){
                $row = $query->row();
                $navigation_name = $row->navigation_name;
                $navigation_path = $this->cms_get_navigation_path($navigation_name);
            }
        }
        $output->navigation_path = $navigation_path;

        
        $output->undeleted_id = $undeleted_id;        

        // prepare css & js, add them to config
        $config = array();
        $asset = new CMS_Asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();
        // show the view
        $this->view('main/main_navigation', $output, 'main_navigation_management', $config);
    }


    public function before_insert_navigation($post_array)
    {
        //get parent's navigation_id
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_id', is_int($post_array['parent_id'])? $post_array['parent_id']: NULL)
            ->get();
        $row   = $query->row();

        $parent_id = isset($row->navigation_id) ? $row->navigation_id : NULL;

        //index = max index+1
        $query = $this->db->select_max('index')
            ->from(cms_table_name('main_navigation'))
            ->where('parent_id', $parent_id)
            ->get();
        $row   = $query->row();
        $index = $row->index;
        if (!isset($index)){
            $index = 1;
        }else{
            $index = $index+1;
        }

        $post_array['index'] = $index;

        if (!isset($post_array['authorization_id']) || $post_array['authorization_id'] == '') {
            $post_array['authorization_id'] = 1;
        }

        return $post_array;
    }

    public function before_delete_navigation($primary_key)
    {
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_id', $primary_key)
            ->like('navigation_name','main_','after')
            ->like('url','main/','after')
            ->get();
        if($query->num_rows() == 0){
            $this->db->delete(cms_table_name('main_quicklink'), array(
                'navigation_id' => $primary_key
            ));
            return true;
        }else{
            return false;
        }
    }

    public function column_navigation_name($value, $row)
    {
        if(!isset($_SESSION)){
            session_start();
        }

        $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('parent_id', $row->navigation_id);
        $query = $this->db->get();
        $child_count = $query->num_rows();
        // determine need_child class
        if($child_count>0){
            $can_be_expanded = TRUE;
            $need_child = ' need-child';
        }else{
            $can_be_expanded = FALSE;
            $need_child = '';
        }

        $html  = '<a name="'.$row->navigation_id.'"></a>';
        $html .= '<span>' .$value . '<br />(' . $row->title . ')</span>';
        $html .= '<input type="hidden" class="navigation_id' . $need_child . '" value="'.$row->navigation_id.'" /><br />';
        // active or not
        $target = site_url($this->cms_module_path() . '/toggle_navigation_active/' . $row->navigation_id);
        if ($row->active == 0) {
            $html .= '<a href="#" target="' . $target . '" class="navigation_active"><i class="glyphicon glyphicon-eye-open"></i> <span>Inactive</span></a>';
        } else {
            $html .= '<a href="#" target="' . $target . '" class="navigation_active"><i class="glyphicon glyphicon-eye-open"></i> <span>Active</span></a>';
        }
        // expand
        if($can_be_expanded){
            $html .= ' | <a href="#" class="expand-collapse-children" target="'.$row->navigation_id.'"><i class="glyphicon glyphicon-chevron-up"></i> Collapse</a>';
        }
        // add children
        $html .= ' | <a href="'.site_url($this->cms_module_path().'/navigation/'.$row->navigation_id).'/add">'.
            '<i class="glyphicon glyphicon-plus"></i> '.$this->cms_lang('Add Child')
            .'</a>';

        if(isset($_SESSION['__mark_move_navigation_id'])){
            $mark_move_navigation_id = $_SESSION['__mark_move_navigation_id'];
            if($row->navigation_id == $mark_move_navigation_id){
                // cancel link
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/navigation_move_cancel').'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            }else{
                // paste before, paste after, paste inside
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/navigation_move_before/'.$row->navigation_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/navigation_move_after/'.$row->navigation_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/navigation_move_into/'.$row->navigation_id).'"><i class="glyphicon glyphicon-import"></i> Put Into</a>';
            }
        }else{
            $html .= ' | <a href="'.site_url($this->cms_module_path().'/navigation_mark_move/'.$row->navigation_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }

    public function navigation_mark_move($navigation_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION['__mark_move_navigation_id'] = $navigation_id;
        redirect($this->cms_module_path().'/navigation#'.$navigation_id,'refresh');
    }

    public function navigation_move_cancel(){
        if(!isset($_SESSION)){
            session_start();
        }
        $navigation_id = $_SESSION['__mark_move_navigation_id'];
        unset($_SESSION['__mark_move_navigation_id']);
        redirect($this->cms_module_path().'/navigation#'.$navigation_id,'refresh');
    }

    public function navigation_move_before($dst_navigation_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $src_navigation_id = $_SESSION['__mark_move_navigation_id'];
        $this->cms_do_move_navigation_before($src_navigation_id, $dst_navigation_id);
        unset($_SESSION['__mark_move_navigation_id']);
        redirect($this->cms_module_path().'/navigation#'.$src_navigation_id,'refresh');
    }
    public function navigation_move_after($dst_navigation_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $src_navigation_id = $_SESSION['__mark_move_navigation_id'];
        $this->cms_do_move_navigation_after($src_navigation_id, $dst_navigation_id);
        unset($_SESSION['__mark_move_navigation_id']);
        redirect($this->cms_module_path().'/navigation#'.$src_navigation_id,'refresh');
    }
    public function navigation_move_into($dst_navigation_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $src_navigation_id = $_SESSION['__mark_move_navigation_id'];
        $this->cms_do_move_navigation_into($src_navigation_id, $dst_navigation_id);
        unset($_SESSION['__mark_move_navigation_id']);
        redirect($this->cms_module_path().'/navigation#'.$src_navigation_id,'refresh');
    }

    public function toggle_navigation_active($navigation_id)
    {
        if ($this->input->is_ajax_request()) {
            $this->db->select('active')->from(cms_table_name('main_navigation'))->where('navigation_id', $navigation_id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $row       = $query->row();
                $new_value = ($row->active == 0) ? 1 : 0;
                $this->db->update(cms_table_name('main_navigation'), array(
                    'active' => $new_value
                ), array(
                    'navigation_id' => $navigation_id
                ));
                $this->cms_show_json(array(
                    'success' => true
                ));
            } else {
                $this->cms_show_json(array(
                    'success' => false
                ));
            }
        }
    }

    // QUICKLINK ===============================================================
    public function quicklink()
    {
        $this->cms_guard_page('main_quicklink_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_quicklink'));
        $crud->set_subject('Quick Link');

        $crud->required_fields('navigation_id');
        $crud->unique_fields('navigation_id');
        $crud->unset_read();

        $crud->columns('navigation_id');
        $crud->edit_fields('navigation_id', 'index');
        $crud->add_fields('navigation_id', 'index');

        $crud->display_as('navigation_id', 'Navigation Code');

        $crud->order_by('index', 'asc');

        $crud->set_relation('navigation_id', cms_table_name('main_navigation'), 'navigation_name');
        $crud->field_type('index','hidden');

        $crud->callback_before_insert(array(
            $this,
            'before_insert_quicklink'
        ));

        $crud->callback_column($this->unique_field_name('navigation_id'), array(
            $this,
            'column_quicklink_navigation_id'
        ));

        $crud->set_language($this->cms_language());

        $output = $crud->render();

        // prepare css & js, add them to config
        $config = array();
        $asset = new CMS_Asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();
        // show the view
        $this->view('main_quicklink', $output, 'main_quicklink_management', $config);
    }

    public function before_insert_quicklink($post_array)
    {
        $query = $this->db->select_max('index')
            ->from(cms_table_name('main_quicklink'))
            ->get();
        $row   = $query->row();
        $index = $row->index;
        if (!isset($index)){
            $index = 1;
        }else{
            $index = $index+1;
        }

        $post_array['index'] = $index;

        return $post_array;
    }

    public function column_quicklink_navigation_id($value, $row)
    {
        $html  = '<a name="'.$row->quicklink_id.'"></a>';
        $html .= '<span>' .$value . '</span>';
        $html .= '<input type="hidden" class="quicklink_id" value="'.$row->quicklink_id.'" />';

        if(isset($_SESSION['__mark_move_quicklink_id'])){
            $mark_move_quicklink_id = $_SESSION['__mark_move_quicklink_id'];
            if($row->quicklink_id == $mark_move_quicklink_id){
                // cancel link
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/quicklink_move_cancel').'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            }else{
                // paste before, paste after, paste inside
                $html .= '<br /><a href="'.site_url($this->cms_module_path().'/quicklink_move_before/'.$row->quicklink_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/quicklink_move_after/'.$row->quicklink_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
            }
        }else{
            $html .= ' | <a href="'.site_url($this->cms_module_path().'/quicklink_mark_move/'.$row->quicklink_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }

    public function quicklink_mark_move($quicklink_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION['__mark_move_quicklink_id'] = $quicklink_id;
        redirect($this->cms_module_path().'/quicklink#'.$quicklink_id,'refresh');
    }

    public function quicklink_move_cancel(){
        if(!isset($_SESSION)){
            session_start();
        }
        $quicklink_id = $_SESSION['__mark_move_quicklink_id'];
        unset($_SESSION['__mark_move_quicklink_id']);
        redirect($this->cms_module_path().'/quicklink#'.$quicklink_id,'refresh');
    }

    public function quicklink_move_before($dst_quicklink_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $src_quicklink_id = $_SESSION['__mark_move_quicklink_id'];
        $this->cms_do_move_quicklink_before($src_quicklink_id, $dst_quicklink_id);
        unset($_SESSION['__mark_move_quicklink_id']);
        redirect($this->cms_module_path().'/quicklink#'.$src_quicklink_id,'refresh');
    }
    public function quicklink_move_after($dst_quicklink_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $src_quicklink_id = $_SESSION['__mark_move_quicklink_id'];
        $this->cms_do_move_quicklink_after($src_quicklink_id, $dst_quicklink_id);
        unset($_SESSION['__mark_move_quicklink_id']);
        redirect($this->cms_module_path().'/quicklink#'.$src_quicklink_id,'refresh');
    }

    // PRIVILEGE ===============================================================
    public function privilege()
    {
        $this->cms_guard_page('main_privilege_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_privilege'));
        $crud->set_subject('Privilege');

        $crud->required_fields('privilege_name');
        $crud->unique_fields('privilege_name');
        $crud->unset_read();

        $crud->columns('privilege_name','title','description');
        $crud->edit_fields('privilege_name','title','description','authorization_id','groups');
        $crud->add_fields('privilege_name','title','description','authorization_id','groups');

        $crud->set_relation('authorization_id', cms_table_name('main_authorization'), 'authorization_name'); //, 'groups');

        $crud->set_relation_n_n('groups', cms_table_name('main_group_privilege'), cms_table_name('main_group'), 'privilege_id', 'group_id', 'group_name');

        $crud->display_as('authorization_id', 'Authorization')
            ->display_as('groups', 'Groups')
            ->display_as('privilege_name', 'Privilege Code')
            ->display_as('title', 'Title')
            ->display_as('description', 'Description');

        $crud->unset_texteditor('description');

        $crud->set_language($this->cms_language());

        $output = $crud->render();

        // prepare css & js, add them to config
        $config = array();
        $asset = new CMS_Asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();
        // show the view
        $this->view('main/main_privilege', $output, 'main_privilege_management', $config);
    }

    // WIDGET ==================================================================
    public function widget()
    {
        $this->cms_guard_page('main_widget_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_widget'));
        $crud->set_subject('Widget');

        $state = $crud->getState();
        $state_info = $crud->getStateInfo();

        $undeleted_id = array();
        $query = $this->db->select('widget_id')
            ->from(cms_table_name('main_widget'))
            ->like('widget_name','section_','after')
            ->get();
        foreach($query->result() as $row){
            $undeleted_id[] = $row->widget_id;
        }

        if($state == 'update' || $state == 'edit' || $state == 'update_validation'){
            $primary_key = $state_info->primary_key;
            if(in_array($primary_key, $undeleted_id)){
                $crud->field_type('widget_name','readonly');
            }else{
                $crud->required_fields('widget_name');   
            }
        }else{
            $crud->required_fields('widget_name');
        }

        
        $crud->unique_fields('widget_name');
        $crud->unset_read();

        $crud->columns('widget_name');
        $crud->edit_fields('widget_name', 'title', 'active', 'description', 'is_static', 'static_content', 'url', 'slug', 'authorization_id', 'groups', 'index');
        $crud->add_fields('widget_name', 'title', 'active', 'description', 'is_static', 'static_content', 'url', 'slug', 'authorization_id', 'groups', 'index');
        $crud->field_type('active', 'true_false');
        $crud->field_type('is_static', 'true_false');
        $crud->field_type('index', 'hidden');

        $crud->display_as('widget_name', 'Widget Code')
            ->display_as('title', 'Title (What visitor see)')
            ->display_as('active', 'Active')
            ->display_as('description', 'Description')
            ->display_as('url', 'URL (Where is it point to)')
            ->display_as('index', 'Order')
            ->display_as('is_static', 'Static')
            ->display_as('static_content', 'Static Content')
            ->display_as('slug', 'Slug')
            ->display_as('authorization_id', 'Authorization')
            ->display_as('groups', 'Groups');

        $crud->order_by('index, slug', 'asc');

        $crud->unset_texteditor('static_content');
        $crud->unset_texteditor('description');

        $crud->set_relation('authorization_id', cms_table_name('main_authorization'), 'authorization_name');

        $crud->set_relation_n_n('groups', cms_table_name('main_group_widget'), cms_table_name('main_group'), 'widget_id', 'group_id', 'group_name');

        $crud->callback_before_insert(array(
            $this,
            'before_insert_widget'
        ));

        $crud->callback_before_delete(array(
            $this,
            'before_delete_navigation'
        ));

        $crud->callback_column('widget_name', array(
            $this,
            'column_widget_name'
        ));

        $crud->set_language($this->cms_language());

        $output = $crud->render();

        $output->undeleted_id = $undeleted_id;

        // prepare css & js, add them to config
        $config = array();
        $asset = new CMS_Asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();
        // show the view
        $this->view('main/main_widget', $output, 'main_widget_management', $config);
    }

    public function before_insert_widget($post_array)
    {
        $query = $this->db->select_max('index')
            ->from(cms_table_name('main_widget'))
            ->get();
        $row   = $query->row();
        $index = $row->index;
        if (!isset($index)){
            $index = 1;
        }else{
            $index = $index+1;
        }

        $post_array['index'] = $index;

        if (!isset($post_array['authorization_id']) || $post_array['authorization_id'] == '') {
            $post_array['authorization_id'] = 1;
        }

        return $post_array;
    }

    public function toggle_widget_active($widget_id)
    {
        if ($this->input->is_ajax_request()) {
            $this->db->select('active')->from(cms_table_name('main_widget'))->where('widget_id', $widget_id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $row       = $query->row();
                $new_value = ($row->active == 0) ? 1 : 0;
                $this->db->update(cms_table_name('main_widget'), array(
                    'active' => $new_value
                ), array(
                    'widget_id' => $widget_id
                ));
                $this->cms_show_json(array(
                    'success' => true
                ));
            } else {
                $this->cms_show_json(array(
                    'success' => false
                ));
            }
        }
    }

    public function before_delete_widget($primary_key)
    {
        $query = $this->db->select('widget_id')
            ->from(cms_table_name('main_widget'))
            ->where('widget_id', $primary_key)
            ->like('widget_name','section_','after')
            ->get();
        if($query->num_rows() == 0){
            return true;
        }else{
            return false;
        }
    }

    public function column_widget_name($value, $row)
    {
        if(!isset($_SESSION)){
            session_start();
        }

        $html  = '<a name="'.$row->widget_id.'"></a>';
        $html .= '<span>' .$value . ' (' . $row->title . ')</span>';
        $html .= '<input type="hidden" class="widget_id" value="'.$row->widget_id.'" /><br />';
        if(isset($row->slug) && $row->slug != NULL && $row->slug != ''){
            $html .= '<span style="font-size:smaller;">' . $row->slug . '</span><br />';
        }
        // active or not
        $target = site_url($this->cms_module_path() . '/toggle_widget_active/' . $row->widget_id);
        if ($row->active == 0) {
            $html .= '<a href="#" target="' . $target . '" class="widget_active"><i class="glyphicon glyphicon-eye-open"></i> <span>Inactive</span></a>';
        } else {
            $html .= '<a href="#" target="' . $target . '" class="widget_active"><i class="glyphicon glyphicon-eye-open"></i> <span>Active</span></a>';
        }

        if(isset($_SESSION['__mark_move_widget_id'])){
            $mark_move_widget_id = $_SESSION['__mark_move_widget_id'];
            if($row->widget_id == $mark_move_widget_id){
                // cancel link
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/widget_move_cancel').'"><i class="glyphicon glyphicon-repeat"></i> Undo</a>';
            }else{
                // paste before, paste after, paste inside
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/widget_move_before/'.$row->widget_id).'"><i class="glyphicon glyphicon-open"></i> Put Before</a>';
                $html .= ' | <a href="'.site_url($this->cms_module_path().'/widget_move_after/'.$row->widget_id).'"><i class="glyphicon glyphicon-save"></i> Put After</a>';
            }
        }else{
            $html .= ' | <a href="'.site_url($this->cms_module_path().'/widget_mark_move/'.$row->widget_id).'"><i class="glyphicon glyphicon-share-alt"></i> Move</a>';
        }

        return $html;
    }

    public function widget_mark_move($widget_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION['__mark_move_widget_id'] = $widget_id;
        redirect($this->cms_module_path().'/widget#'.$widget_id,'refresh');
    }

    public function widget_move_cancel(){
        if(!isset($_SESSION)){
            session_start();
        }
        $widget_id = $_SESSION['__mark_move_widget_id'];
        unset($_SESSION['__mark_move_widget_id']);
        redirect($this->cms_module_path().'/widget#'.$widget_id,'refresh');
    }

    public function widget_move_before($dst_widget_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $src_widget_id = $_SESSION['__mark_move_widget_id'];
        $this->cms_do_move_widget_before($src_widget_id, $dst_widget_id);
        unset($_SESSION['__mark_move_widget_id']);
        redirect($this->cms_module_path().'/widget#'.$src_widget_id,'refresh');
    }
    public function widget_move_after($dst_widget_id){
        if(!isset($_SESSION)){
            session_start();
        }
        $src_widget_id = $_SESSION['__mark_move_widget_id'];
        $this->cms_do_move_widget_after($src_widget_id, $dst_widget_id);
        unset($_SESSION['__mark_move_widget_id']);
        redirect($this->cms_module_path().'/widget#'.$src_widget_id,'refresh');
    }

    // CONFIG ==================================================================
    public function config()
    {
        $this->cms_guard_page('main_config_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_config'));
        $crud->set_subject($this->cms_lang('Configuration'));

        $crud->unique_fields('config_name');
        $crud->unset_read();
        $crud->unset_delete();

        $crud->columns('config_name', 'value');
        $crud->edit_fields('config_name', 'value', 'description');
        $crud->add_fields('config_name', 'value', 'description');

        $crud->display_as('config_name', 'Configuration Key')
            ->display_as('value', 'Configuration Value')
            ->display_as('description', 'Description');

        $crud->unset_texteditor('description');
        $crud->unset_texteditor('value');

        $operation = $crud->getState();
        if ( $operation == 'edit' || $operation == 'update' || $operation == 'update_validation') {
            $crud->field_type('config_name', 'readonly');
            $crud->field_type('description', 'readonly');
        }else if( $operation == 'add' || $operation == 'insert' || $operation == 'insert_validation'){
            //$crud->set_rules('config_name', 'Configuration Key', 'required');
            $crud->required_fields('config_name');
        }

        $crud->callback_after_insert(array(
            $this,
            'after_insert_config'
        ));
        $crud->callback_after_update(array(
            $this,
            'after_update_config'
        ));
        $crud->callback_before_delete(array(
            $this,
            'before_delete_config'
        ));

        $crud->set_language($this->cms_language());

        $output = $crud->render();

        // prepare css & js, add them to config
        $config = array();
        $asset = new CMS_Asset();
        foreach($output->css_files as $file){
            $asset->add_css($file);
        }
        $config['css'] = $asset->compile_css();

        foreach($output->js_files as $file){
            $asset->add_js($file);
        }
        $config['js'] = $asset->compile_js();
        // show the view
        $this->view('main/main_config', $output, 'main_config_management', $config);
    }

    public function after_insert_config($post_array, $primary_key){
        // adjust configuration file entry
        cms_config($post_array['config_name'], $post_array['value']);
        return TRUE;
    }

    public function after_update_config($post_array, $primary_key){
        // adjust configuration file entry
        $query = $this->db->select('config_name')->from(cms_table_name('main_config'))->where('config_id', $primary_key)->get();
        if($query->num_rows()>0){
            $row = $query->row();
            $config_name = $row->config_name;
            cms_config($config_name, $post_array['value']);
        }
        return TRUE;
    }

    public function before_delete_config($primary_key){
        $query = $this->db->select('config_name')->from(cms_table_name('main_config'))->where('config_id', $primary_key)->get();
        if($query->num_rows()>0){
            $row = $query->row();
            $config_name = $row->config_name;
            // delete configuration file entry
            cms_config($config_name, '', TRUE);
        }
        return TRUE;
    }

    public function json_is_login(){
        $result = array('is_login'=> $this->cms_user_id()>0);
        $this->cms_show_json($result);
    }

    public function ck_adjust_script(){
        $base_url = base_url();
        $save_base_url = str_replace('/', '\\/', $base_url);
        $ck_editor_adjust_script = '
            $(document).ready(function(){
                if (typeof(CKEDITOR) != "undefined"){
                    function __adjust_ck_editor(){
                        for (instance in CKEDITOR.instances) {
                            /* ck_instance */
                            ck_instance = CKEDITOR.instances[instance];
                            var name = CKEDITOR.instances[instance].name;
                            var $ck_textarea = $("#cke_"+name+" textarea");
                            var $ck_iframe = $("#cke_"+name+" iframe");
                            var data = ck_instance.getData();
                            if($ck_textarea.length > 0){
                                content = data.replace(
                                    /(src=".*?)('.$save_base_url.')(.*?")/gi,
                                    "$1{{ base_url }}$3"
                                );
                                ck_instance.setData(content);
                            }else if ($ck_iframe.length > 0){
                                content = data.replace(
                                    /(src=".*?)({{ base_url }})(.*?")/gi,
                                    "$1'.$base_url.'$3"
                                );
                                ck_instance.setData(content);
                            }
                            ck_instance.updateElement();
                        }
                    }

                    /* when instance ready & form submit, adjust ck editor */
                    CKEDITOR.on("instanceReady", function(){
                        __adjust_ck_editor();
                        for (instance in CKEDITOR.instances) {
                            /* ck_instance */
                            ck_instance = CKEDITOR.instances[instance];
                            ck_instance.on("mode", function(){
                                __adjust_ck_editor();
                            });
                        }
                    });
                    
                    /* when form submit, adjust ck editor */
                    $(document).ajaxSend(function(event, xhr, settings) {
                        if(settings.url == $("#crudForm").attr("action")){
                            for (instance in CKEDITOR.instances) {
                                /* ck_instance */
                                ck_instance = CKEDITOR.instances[instance];
                                var name = CKEDITOR.instances[instance].name;
                                var $original_textarea = $("textarea#"+name);
                                var data = ck_instance.getData();
                                content = data.replace(
                                    /(src=".*?)('.$save_base_url.')(.*?")/gi,
                                    "$1{{ base_url }}$3"
                                );
                                ck_instance.setData(content);
                            }
                        }
                    });

                    $(document).ajaxComplete(function(event, xhr, settings){
                        if(settings.url == $("#crudForm").attr("action")){
                            __adjust_ck_editor();
                        }
                    });
                }
            });
        ';
        $content = str_replace(array(PHP_EOL, '    '), array('',''), $ck_editor_adjust_script);
        $this->output->set_content_type('application/javascript')
            ->set_header('Cache-Control:max-age=2592000')
            ->set_header('Expires:Fri, 30 Oct 2037 11:32:29 GMT')
            ->set_output($content);
    }

    public function widget_online_user(){
        $this->view('main/main_widget_online_user');
    }
    public function widget_online_user_ajax(){
        $query = $this->db->select('user_name')
            ->from(cms_table_name('main_user'))
            ->where('login', 1)
            ->where('last_active >=', microtime(true)-70)
            ->get();
        $user_name_list = array();
        foreach($query->result() as $row){
            $user_name_list[] = $row->user_name;
        }
        if(count($user_name_list)>0){
            echo implode(', ', $user_name_list);
        }else{
            echo 'No user online';
        }
    }

    public function widget_logout()
    {
        $data = array(
            "user_name" => $this->cms_user_name(),
            "welcome_lang" => $this->cms_lang('Welcome'),
            "logout_lang" => $this->cms_lang('Logout')
        );
        $this->view('main/main_widget_logout', $data);
    }

    public function widget_login()
    {
        $this->login();
    }

    public function widget_left_nav($first = TRUE, $navigations = NULL){
        if(!isset($navigations)){
            $navigations = $this->cms_navigations();
        }

        if(count($navigations) == 0) return '';
        if($first){
            $result = '<style type="text/css">
                .dropdown-submenu{
                    position:relative;
                }

                .dropdown-submenu > .dropdown-menu
                {
                    top:0;
                    left:100%;
                    margin-top:-6px;
                    margin-left:-1px;
                    -webkit-border-radius:0 6px 6px 6px;
                    -moz-border-radius:0 6px 6px 6px;
                    border-radius:0 6px 6px 6px;
                }

                .dropdown-submenu:hover > .dropdown-menu{
                    display:block;
                }

                .dropdown-submenu > a:after{
                    display:block;
                    content:" ";
                    float:right;
                    width:0;
                    height:0;
                    border-color:transparent;
                    border-style:solid;
                    border-width:5px 0 5px 5px;
                    border-left-color:#cccccc;
                    margin-top:5px;
                    margin-right:-10px;
                }

                .dropdown-submenu:hover > a:after{
                    border-left-color:#ffffff;
                }

                .dropdown-submenu .pull-left{
                    float:none;
                }

                .dropdown-submenu.pull-left > .dropdown-menu{
                    left:-100%;
                    margin-left:10px;
                    -webkit-border-radius:6px 0 6px 6px;
                    -moz-border-radius:6px 0 6px 6px;
                    border-radius:6px 0 6px 6px;
                }
                #_first-left-dropdown{
                    display:block;
                    margin:0px;
                    border:none;
                }
                @media (max-width: 750px){
                    #_first-left-dropdown{
                        position:static;
                    }
                }
            }
            </style>';
        }else{
            $result = '';
        }
        $result .= '<ul  class="dropdown-menu nav nav-pills nav-stacked" '.($first?'id="_first-left-dropdown"':'').'>';
        foreach($navigations as $navigation){
            if(($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']){
                // create badge if needed
                $badge = '';
                if($quicklink['notif_url'] != ''){
                    $badge_id = '__cms_notif_left_navigation_'.$quicklink['navigation_id'];
                    $badge = '&nbsp;<span id="'.$badge_id.'" class="badge"></span>';
                    $badge.= '<script type="text/javascript">
                            $(document).ready(function(){
                                setInterval(function(){
                                    $.ajax({
                                        dataType:"json",
                                        url: "'.addslashes($quicklink['notif_url']).'",
                                        success: function(response){
                                            if(response.success){
                                                $("#'.$badge_id.'").html(response.notif);
                                            }
                                        }
                                    });
                                }, 50000);
                            });
                        </script>
                    ';
                }
                // set active class
                $active = '';
                if($this->cms_ci_session('__cms_navigation_name') == $quicklink['navigation_name']){
                    $active = 'active';
                }
                // make text
                $icon = '<span class="glyphicon '.$navigation['bootstrap_glyph'].'"></span>&nbsp;';
                if($navigation['allowed'] && $navigation['active']){
                    $text = '<a class="dropdown-toggle" href="'.$navigation['url'].'">'.$icon.$navigation['title'].$badge.'</a>';
                }else{
                    $text = $icon.$navigation['title'].$badge;
                }

                if(count($navigation['child'])>0 && $navigation['have_allowed_children']){
                    $result .= '<li class="dropdown-submenu '.$active.'">'.$text.$this->widget_left_nav(FALSE, $navigation['child']).'</li>';
                }else{
                    $result .= '<li class="'.$active.'">'.$text.'</li>';
                }
            }
        }
        $result .= '</ul>';
        // show up
        if($first){
            $this->cms_show_html($result);
        }else{
            return $result;
        }
    }

    public function widget_top_nav($caption = 'Complete Menu', $first = TRUE, $no_complete_menu=FALSE, $no_quicklink=FALSE, $inverse = FALSE, $navigations = NULL){
        $result = '';
        $caption = $this->cms_lang($caption);

        if(!$no_complete_menu){
            if(!isset($navigations)){
                $navigations = $this->cms_navigations();
            }
            if(count($navigations) == 0) return '';


            $result .= '<ul class="dropdown-menu">';
            foreach($navigations as $navigation){
                if(($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']){
                    $navigation['bootstrap_glyph'] = $navigation['bootstrap_glyph'] == ''? 'icon-white': $navigation['bootstrap_glyph'];
                    // make text
                    $icon = '<span class="glyphicon '.$navigation['bootstrap_glyph'].'"></span>&nbsp;';
                    $badge = '';
                    if($navigation['notif_url'] != ''){
                        $badge_id = '__cms_notif_top_nav_'.$navigation['navigation_id'];
                        $badge = '&nbsp;<span id="'.$badge_id.'" class="badge"></span>';
                        $badge.= '<script type="text/javascript">
                                $(document).ready(function(){
                                    function __top_nav_get_badge_'.$badge_id.'(){
                                        $.ajax({
                                            dataType:"json",
                                            url: "'.addslashes($navigation['notif_url']).'",
                                            success: function(response){
                                                if(response.success){
                                                    $("#'.$badge_id.'").html(response.notif);
                                                }
                                            }
                                        });
                                    }
                                    __top_nav_get_badge_'.$badge_id.'();
                                    setInterval(function(){
                                        __top_nav_get_badge_'.$badge_id.'();
                                    }, 50000);
                                });
                            </script>
                        ';
                    }
                    if($navigation['allowed'] && $navigation['active']){
                        $text = '<a href="'.$navigation['url'].'">'.$icon.
                            $navigation['title'].$badge.'</a>';
                    }else{
                        $text = '<a href="#">'.$icon.
                            $navigation['title'].$badge.'</a>';
                    }

                    if(count($navigation['child'])>0 && $navigation['have_allowed_children']){
                        $result .= '<li class="dropdown-submenu">'.
                            $text.$this->widget_top_nav($caption, FALSE, $no_complete_menu, $no_quicklink, $inverse, $navigation['child']).'</li>';
                    }else{
                        $result .= '<li>'.$text.'</li>';
                    }
                }
            }
            $result .= '</ul>';
        }

        // show up
        if($first){
            if(!$no_complete_menu){
                //  hidden-sm hidden-xs
                $result = '<li class="dropdown">'.
                    '<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$caption.' <span class="caret"></span></a>'.
                    $result.'</li>';
            }
            if(!$no_quicklink){
                $result .= $this->build_quicklink();
            }
            $result =
            '<style type="text/css">
                @media (min-width: 750px){
                    .dropdown-submenu{
                        position:relative;
                    }

                    .dropdown-submenu > .dropdown-menu
                    {
                        top:0;
                        left:100%;
                        margin-top:-6px;
                        margin-left:-1px;
                        -webkit-border-radius:0 6px 6px 6px;
                        -moz-border-radius:0 6px 6px 6px;
                        border-radius:0 6px 6px 6px;
                    }

                    .dropdown-submenu:hover > .dropdown-menu{
                        display:block;
                    }

                    .dropdown-submenu > a:after{
                        display:block;
                        content:" ";
                        float:right;
                        width:0;
                        height:0;
                        border-color:transparent;
                        border-style:solid;
                        border-width:5px 0 5px 5px;
                        border-left-color:#cccccc;
                        margin-top:5px;
                        margin-right:-10px;
                    }

                    .dropdown-submenu:hover > a:after{
                        border-left-color:#ffffff;
                    }

                    .dropdown-submenu .pull-left{
                        float:none;
                    }

                    .dropdown-submenu.pull-left > .dropdown-menu{
                        left:-100%;
                        margin-left:10px;
                        -webkit-border-radius:6px 0 6px 6px;
                        -moz-border-radius:6px 0 6px 6px;
                        border-radius:6px 0 6px 6px;
                    }
                    .dropdown .caret{
                        display:inline-block!important;
                    }
                }
            </style>
            <div class="navbar '.($inverse? 'navbar-inverse' : 'navbar-default').' navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{ site_url }}"><img src ="{{ site_favicon }}" style="max-height:20px; max-width:20px;" /></a>
                    </div>
                    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
                        <ul class="navbar-nav nav">'.$result.'</ul>
                        <ul class="navbar-nav nav navbar-right">
                            <li class="dropdown" id="__right_navbar">{{ widget_name:navigation_right_partial }}</li>
                        </ul>
                    </nav><!--/.nav-collapse -->
                </div>
            </div>
            <script type="text/javascript">
                // function to adjust navbar size so that it will always fit to the screen

                var _NAVBAR_LI_ORIGINAL_PADDING = $(".navbar-nav > li > a").css("padding-right");
                var _NAVBAR_LI_ORIGINAL_FONTSIZE = $(".navbar-nav > li").css("font-size");
                function __adjust_navbar(){
                    var li_count = $(".navbar-nav > li").length;
                    $(".navbar-nav > li > a").css("padding-left", _NAVBAR_LI_ORIGINAL_PADDING);
                    $(".navbar-nav > li").css("font-size", _NAVBAR_LI_ORIGINAL_FONTSIZE);
                    if($(document).width()>=750){
                        var need_transform = true;
                        while(need_transform){
                            need_transform = false;
                            for(var i=0; i<li_count; i++){
                                var top = $(".navbar-nav > li")[i].offsetTop;
                                if(top>$(".navbar-brand")[0].offsetTop){
                                    need_transform = true;
                                }
                            }
                            if(need_transform){
                                // decrease the padding
                                var currentPadding = $(".navbar-nav > li > a").css("padding-right");
                                var currentPaddingNum = parseFloat(currentPadding, 10);
                                if(currentPaddingNum>10){
                                    newPadding = currentPaddingNum-1;
                                    $(".navbar-nav > li > a").css("padding-right", newPadding);
                                    $(".navbar-nav > li > a").css("padding-left", newPadding);
                                }else{
                                    // decrease the font
                                    var currentFontSize = $(".navbar-nav > li").css("font-size");
                                    var currentFontSizeNum = parseFloat(currentFontSize, 10);
                                    var newFontSize = currentFontSizeNum * 0.95;
                                    $(".navbar-nav > li").css("font-size", newFontSize);
                                }
                            }
                        }
                    }
                }

                // MAIN PROGRAM
                $(document).ready(function(){
                    // override bootstrap default behavior on dropdown click
                    $("a.dropdown-toggle span.anchor-text").on("click touchstart", function(event){
                        if(event.stopPropagation){
                            event.stopPropagation();
                        }
                        event.cancelBubble=true;
                        window.location = $(this).parent().attr("href");
                    });
                    // adjust navbar
                    __adjust_navbar();
                    $(window).resize(function() {
                        __adjust_navbar();
                    });
                    $(document).ajaxComplete(function(){
                        __adjust_navbar();
                    });
                    // adjust right navbar
                    font_color = $("nav li a").css("color");
                    $("#__right_navbar").css("color", font_color);
                    padding_top = $("nav li a").css("padding-top");
                    padding_bottom = $("nav li a").css("padding-bottom");
                    $("#__right_navbar").css("padding-top", padding_top);
                    $("#__right_navbar").css("padding-bottom", padding_bottom);
                });
            </script>';
            $this->cms_show_html($result);
        }else{
            return $result;
        }
    }

    public function widget_top_nav_no_quicklink($caption = 'Complete Menu'){
        $this->widget_top_nav($caption, TRUE, FALSE, TRUE, FALSE, NULL);
    }

    public function widget_quicklink(){
        $this->widget_top_nav('', TRUE, TRUE, FALSE, FALSE, NULL);
    }

    public function widget_top_nav_inverse($caption = 'Complete Menu'){
        $this->widget_top_nav($caption, TRUE, FALSE, TRUE, TRUE, NULL);
    }

    public function widget_top_nav_no_quicklink_inverse($caption = 'Complete Menu'){
        $this->widget_top_nav($caption, TRUE, FALSE, TRUE, TRUE, NULL);
    }

    public function widget_quicklink_inverse(){
        $this->widget_top_nav('', TRUE, TRUE, FALSE, TRUE, NULL);
    }


    private function build_quicklink($quicklinks = NULL,$first = TRUE){
        if(!isset($quicklinks)){
            $quicklinks = $this->cms_quicklinks();
        }
        if(count($quicklinks) == 0) return '';

        $current_navigation_name = $this->cms_ci_session('__cms_navigation_name');
        $current_navigation_path = $this->cms_get_navigation_path($current_navigation_name);
        $html = '';

        foreach($quicklinks as $quicklink){
            // if navigation is not active then skip it
            if(!$quicklink['active']){
                continue;
            }
            // create icon if needed
            $icon = '';
            if($first){
                $icon_class = $quicklink['bootstrap_glyph'].' icon-white';
            }else{
                $icon_class = $quicklink['bootstrap_glyph'];
            }
            if($quicklink['bootstrap_glyph'] != '' || !$first){
                $icon_class = $icon_class==''? 'icon-white': $icon_class;
                $icon = '<span class="glyphicon '.$icon_class.'"></span>&nbsp;';
            }
            // create badge if needed
            $badge = '';
            if($quicklink['notif_url'] != ''){
                $badge_id = '__cms_notif_quicklink_'.$quicklink['navigation_id'];
                $badge = '&nbsp;<span id="'.$badge_id.'" class="badge"></span>';
                $badge.= '<script type="text/javascript">
                        $(document).ready(function(){
                            function __quicklink_get_badge_'.$badge_id.'(){
                                $.ajax({
                                    dataType:"json",
                                    url: "'.addslashes($quicklink['notif_url']).'",
                                    success: function(response){
                                        if(response.success){
                                            $("#'.$badge_id.'").html(response.notif);
                                        }
                                    }
                                });
                            }
                            __quicklink_get_badge_'.$badge_id.'();
                            setInterval(function(){
                                __quicklink_get_badge_'.$badge_id.'();
                            }, 50000);
                        });
                    </script>
                ';
            }
            // set active class
            $active = '';
            if($current_navigation_name == $quicklink['navigation_name']){
                $active = 'active';
            }else{
                foreach($current_navigation_path as $navigation_parent){
                    if($quicklink['navigation_name'] == $navigation_parent['navigation_name']){
                        $active = 'active';
                        break;
                    }
                }
            }
            // create li based on child availability
            if(count($quicklink['child'])==0){
                $html.= '<li class="'.$active.'">';
                $html.= anchor($quicklink['url'], '<span>'.$icon.$quicklink['title'].$badge.'</span>');
                $html.= '</li>';
            }else{
                if($first){
                    $html.= '<li class="dropdown '.$active.'">';
                    $html.= '<a class="dropdown-toggle" data-toggle="dropdown" href="'.$quicklink['url'].'">'.
                        '<span class="anchor-text">'.$icon.$quicklink['title'].$badge.'</span>'.
                        '&nbsp;<span class="caret"></span></a>'; // hidden-sm hidden-xs
                    $html.= $this->build_quicklink($quicklink['child'],FALSE);
                    $html.= '</li>';
                }else{
                    $html.= '<li class="dropdown-submenu '.$active.'">';
                    $html.= '<a href="'.$quicklink['url'].'">'.
                        '<span>'.$icon.$quicklink['title'].$badge.'</span></a>';
                    $html.= $this->build_quicklink($quicklink['child'],FALSE);
                    $html.= '</li>';
                }
            }
        }

        if(!$first){
            $html = '<ul class="dropdown-menu">'.$html.'</ul>';
        }
        return $html;
    }
}
