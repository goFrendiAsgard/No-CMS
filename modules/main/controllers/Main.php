<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

/**
 * The Main Controller of No-CMS.
 *
 * @author gofrendi
 */
class Main extends CMS_Controller
{
    protected function upload($upload_path, $input_file_name = 'userfile', $submit_name = 'upload')
    {
        $data = array(
            'uploading' => true,
            'success' => false,
            'message' => '',
        );
        if (isset($_POST[$submit_name])) {
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'zip';
            $config['max_size'] = 8 * 1024;
            $config['overwrite'] = true;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($input_file_name)) {
                $data['uploading'] = true;
                $data['success'] = false;
                $data['message'] = $this->upload->display_errors();
            } else {
                $this->load->library('unzip');
                $upload_data = $this->upload->data();
                $this->unzip->extract($upload_data['full_path']);
                unlink($upload_data['full_path']);
                $data['uploading'] = true;
                $data['success'] = true;
                $data['message'] = '';
            }
        } else {
            $data['uploading'] = false;
            $data['success'] = false;
            $data['message'] = '';
        }

        return $data;
    }

    protected function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src.'/'.$file)) {
                    $this->recurse_copy($src.'/'.$file, $dst.'/'.$file);
                } else {
                    copy($src.'/'.$file, $dst.'/'.$file);
                }
            }
        }
        closedir($dir);
    }

    protected function rrmdir($dir)
    {
        foreach (glob($dir.'/*') as $file) {
            if (is_dir($file)) {
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
        $data = array();
        // upload new theme
        if (CMS_SUBSITE == '') {
            if (isset($_FILES['userfile'])) {
                // upload new module
                $directory = basename($_FILES['userfile']['name'], '.zip');
                // config
                $config_dir = FCPATH.'modules/'.$directory.'/config';
                $backup_config_dir = FCPATH.'modules/'.$directory.'_config';
                $config_backup = false;
                if (file_exists($config_dir) && is_dir($config_dir)) {
                    $this->recurse_copy($config_dir, $backup_config_dir);
                    $config_backup = true;
                }
            }

            $data['upload'] = $this->upload(FCPATH.'modules/', 'userfile', 'upload');
            if ($data['upload']['success']) {
                if ($config_backup) {
                    $this->recurse_copy($backup_config_dir, $config_dir);
                    $this->rrmdir($backup_config_dir);
                }
            }
        }

        $keyword = $this->input->post('keyword');
        if ($keyword == '') {
            $keyword = null;
        }
        $data['keyword'] = $keyword;
        // show the view
        $modules = $this->cms_get_module_list($keyword);
        for ($i = 0; $i < count($modules); ++$i) {
            $module = $modules[$i];
            $module_path = $module['module_path'];
        }
        $data['modules'] = $modules;
        $data['upload_new_module_caption'] = $this->cms_lang('Upload New Module');
        $this->view('main/main_module_management', $data, 'main_module_management');
    }

    public function change_theme($theme = null)
    {
        $this->cms_guard_page('main_change_theme');
        $data = array();
        // upload new theme
        if (CMS_SUBSITE == '') {
            $data['upload'] = $this->upload('./themes/', 'userfile', 'upload');
        }

        // show the view
        if (isset($theme)) {
            $this->cms_set_config('site_theme', $theme);
        }
        // keyword
        $keyword = $this->input->post('keyword');
        if ($keyword == '') {
            $keyword = null;
        }
        $data['keyword'] = $keyword;
        $data['themes'] = $this->cms_get_theme_list($keyword);
        $data['upload_new_theme_caption'] = $this->cms_lang('Upload New Theme');
        $this->view('main/main_change_theme', $data, 'main_change_theme');
    }

    //this is used for the real static page which doesn't has any URL in navigation management
    public function static_page($navigation_name=NULL)
    {
        if($navigation_name !== NULL){
            $this->view('CMS_View', null, $navigation_name);
        }else{
           $this->view('not_found_index', NULL, 'main_404');
        }
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
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run()) {
            if ($this->cms_do_login($identity, $password)) {
                //if old_url exist, redirect to old_url, else redirect to main/index
                if (isset($old_url)) {
                    $this->session->set_userdata('cms_old_url', null);
                    // seek for the closest url that exist in navigation table to avoid something like manage_x/index/edit/1/error to be appeared
                    $old_url_part = explode('/', $old_url);
                    while (count($old_url_part) > 0) {
                        $query = $this->db->select('url')
                            ->from(cms_table_name('main_navigation'))
                            ->like('url', implode('/', $old_url_part))
                            ->get();
                        if ($query->num_rows() > 0) {
                            $row = $query->row();
                            $old_url = $row->url;
                            break;
                        } else {
                            $new_old_url_part = array();
                            for ($i = 0; $i < count($old_url_part) - 1; ++$i) {
                                $new_old_url_part[] = $old_url_part[$i];
                            }
                            $old_url_part = $new_old_url_part;
                        }
                    }
                    // hook
                    $this->cms_call_hook('cms_after_login', array($this->cms_user_id(), $this->input->post()));
                    redirect($old_url, 'refresh');
                } else {
                    // hook
                    $this->cms_call_hook('cms_after_login', array($this->cms_user_id(), $this->input->post()));
                    redirect('', 'refresh');
                }
            } else {

                //view login again
                $data = array(
                    'identity' => $identity,
                    'message' => '{{ language:Error }}: {{ language:Login Failed }}',
                    'providers' => $this->cms_third_party_providers(),
                    'login_caption' => $this->cms_lang('Login'),
                    'register_caption' => $this->cms_lang('Register'),
                    'allow_register' => $allow_register,
                );
                $this->view('main/main_login', $data, 'main_login');
            }
        } else {
            //view login again
            $data = array(
                'identity' => $identity,
                'message' => '',
                'providers' => $this->cms_third_party_providers(),
                'login_caption' => $this->cms_lang('Login'),
                'register_caption' => $this->cms_lang('Register'),
                'allow_register' => $allow_register,
            );
            $this->view('main/main_login', $data, 'main_login');
        }
    }

    public function activate($activation_code)
    {
        $this->cms_activate_account($activation_code);
        redirect('', 'refresh');
    }

    public function forgot($activation_code = null)
    {
        $this->cms_guard_page('main_forgot');
        // if email not set correctly (still use default parameters)
        // this should not work
        $email_protocol = $this->cms_get_config('cms_email_protocol', TRUE);
        $smtp_user_name = $this->cms_get_config('cms_email_smtp_user', TRUE);
        $smtp_pass = $this->cms_get_config('cms_email_smtp_pass', TRUE);
        if( $email_protocol == 'smtp' && $smtp_user_name == 'your_gmail_address@gmail.com' && $smtp_pass == ''){
            $this->view('main/main_forgot_email_not_set', NULL, 'main_forgot');
        }else{

            if (isset($activation_code)) {
                //get user input
                $password = $this->input->post('password');
                //set validation rule
                $this->form_validation->set_rules('password', 'Password', 'required|matches[confirm_password]');
                $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required');

                if ($this->form_validation->run()) {
                    if ($this->cms_valid_activation_code($activation_code)) {
                        $this->cms_activate_account($activation_code, $password);
                        redirect('', 'refresh');
                    } else {
                        $main_forgot_url = $this->cms_navigation_url('main_forgot');
                        redirect($main_forgot_url, 'refresh');
                    }
                } else {
                    $data = array(
                        'activation_code' => $activation_code,
                        'change_caption' => $this->cms_lang('Change'),
                    );
                    $this->view('main/main_forgot_change_password', $data, 'main_forgot');
                }
            } else {
                //get user input
                $identity = $this->input->post('identity');

                //set validation rule
                $this->form_validation->set_rules('identity', 'Identity', 'required');

                if ($this->form_validation->run()) {
                    if ($this->cms_generate_activation_code($identity, true, 'FORGOT')) {
                        redirect('', 'refresh');
                    } else {
                        $data = array(
                            'identity' => $identity,
                            'send_activation_code_caption' => $this->cms_lang('Send activation code to my email'),
                        );
                        $this->view('main/main_forgot_fill_identity', $data, 'main_forgot');
                    }
                } else {
                    $data = array(
                        'identity' => $identity,
                        'send_activation_code_caption' => $this->cms_lang('Send activation code to my email'),
                    );
                    $this->view('main/main_forgot_fill_identity', $data, 'main_forgot');
                }
            }

        }
    }

    public function register()
    {
        $this->cms_guard_page('main_register');

        // the honey_pot, every fake input should be empty
        $honey_pot_pass = (strlen($this->input->post('user_name', '')) == 0) &&
            (strlen($this->input->post('email', '')) == 0) &&
            (strlen($this->input->post('real_name', '')) == 0) &&
            (strlen($this->input->post('password', '')) == 0) &&
            (strlen($this->input->post('confirm_password')) == 0);
        if (!$honey_pot_pass) {
            show_404();
            die();
        }

        $previous_secret_code = $this->session->userdata('__main_registration_secret_code');
        if ($previous_secret_code === null) {
            $previous_secret_code = $this->cms_random_string();
        }
        //get user input
        $user_name = $this->input->post($previous_secret_code.'user_name');
        $email = $this->input->post($previous_secret_code.'email');
        $real_name = $this->input->post($previous_secret_code.'real_name');
        $password = $this->input->post($previous_secret_code.'password');
        $confirm_password = $this->input->post($previous_secret_code.'confirm_password');

        //set validation rule
        $this->form_validation->set_rules($previous_secret_code.'user_name', 'User Name', 'required');
        $this->form_validation->set_rules($previous_secret_code.'email', 'E mail', 'required|valid_email');
        $this->form_validation->set_rules($previous_secret_code.'real_name', 'Real Name', 'required');
        $this->form_validation->set_rules($previous_secret_code.'password', 'Password', 'required|matches['.$previous_secret_code.'confirm_password]');
        $this->form_validation->set_rules($previous_secret_code.'confirm_password', 'Password Confirmation', 'required');

        // generate new secret code
        $secret_code = $this->cms_random_string();
        $this->session->set_userdata('__main_registration_secret_code', $secret_code);
        if ($this->form_validation->run() && !$this->cms_is_user_exists($user_name) &&
        !$this->cms_is_user_exists($email) && preg_match('/@.+\./', $email) &&
        $user_name != '' && $email != '') {
            $new_user_id = $this->cms_do_register($user_name, $email, $real_name, $password);
            // hook
            $this->cms_call_hook('cms_after_register', array($new_user_id, $this->input->post()));
            redirect('', 'refresh');
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
                'additional_input' => $additional_input,
            );
            $this->view('main/main_register', $data, 'main_register');
        }
    }

    public function check_registration()
    {
        if ($this->input->is_ajax_request()) {
            $user_name = $this->input->post('user_name');
            $email = $this->input->post('email');
            $user_name_exists = $this->cms_is_user_exists($user_name);
            $email_exists = $this->cms_is_user_exists($email);
            $valid_email = preg_match('/@.+\./', $email);
            $message = '';
            $error = false;
            if ($user_name == '') {
                $message = $this->cms_lang('Username is empty');
                $error = true;
            } else if ($user_name_exists) {
                $message = $this->cms_lang('Username already exists');
                $error = true;
            } else if (!$valid_email) {
                $message = $this->cms_lang('Invalid email address');
                $error = true;
            } else if ($email_exists) {
                $message = $this->cms_lang('Email already used');
                $error = true;
            } else{
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
                'exists' => $user_name_exists || $email_exists,
                'error' => $error,
                'message' => $message,
            );
            $this->cms_show_json($data);
        }
    }

    public function get_layout($theme = '')
    {
        if ($this->input->is_ajax_request()) {
            if ($theme == '') {
                $theme = $this->cms_get_config('site_theme');
            }
            $layout_list = array('');
            $this->load->helper('directory');
            $files = directory_map('themes/'.$theme.'/views/layouts/', 1);
            sort($files);
            foreach ($files as $file) {
                if (is_dir('themes/'.$theme.'/views/layouts/'.$file)) {
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
            $email = $this->input->post('email');
            $email_exists = $this->cms_is_user_exists($email, $this->cms_user_id());
            $valid_email = preg_match('/@.+\./', $email);
            $message = '';
            $error = false;
            if (!$valid_email) {
                $message = $this->cms_lang('Invalid email address');
                $error = true;
            } else if ($email_exists) {
                $message = $this->cms_lang('Email already used');
                $error = true;
            } else{
                $return_set = $this->cms_call_hook('cms_validate_change_profile', array($this->cms_user_id(), $this->input->post()));
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
                'exists' => $email_exists,
                'error' => $error,
                'message' => $message,
            );
            $this->cms_show_json($data);
        }
    }

    public function change_profile()
    {
        $this->cms_guard_page('main_change_profile');
        $query = $this->db->select('user_name, email, real_name, theme, language, sex, birthdate, profile_picture, self_description')
            ->from($this->cms_user_table_name())
            ->where('user_id', $this->cms_user_id())
            ->get();
        $row = $query->row();
        $user_name = $row->user_name;

        //get user input
        $email = $this->input->post('email');
        $real_name = $this->input->post('real_name');
        $change_password = $this->input->post('change_password');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        $theme = $this->input->post('theme');
        $language = $this->input->post('language');
        $sex = $this->input->post('sex');
        $birthdate = $this->input->post('birthdate');
        $self_description = $this->input->post('self_description');
        if (!$change_password) {
            $password = null;
        }
        // get from old values
        $email = $email !== NULL? $email : $row->email;
        $real_name = $real_name !== NULL? $real_name : $row->real_name;
        $theme = $theme !== NULL? $theme : $row->theme;
        $language = $language !== NULL? $language : $row->language;
        $sex = $sex !== NULL? $sex : $row->sex;
        $birthdate = $birthdate !== NULL? $birthdate : $row->birthdate;
        $self_description = $self_description !== NULL? $self_description : $row->self_description;
        $profile_picture = $row->profile_picture;

        //set validation rule
        $success = TRUE;
        $this->form_validation->set_rules('email', 'E mail', 'required|valid_email');
        $this->form_validation->set_rules('real_name', 'Real Name', 'required');
        if(!$this->form_validation->run() || $password != $confirm_password){
            $success = FALSE;
        }

        if ($success) {
            if(isset($_FILES['profile_picture'])){
                try{
                    // profile picture
                    $pp = $_FILES['profile_picture'];
                    if(isset($pp['tmp_name']) && $pp['tmp_name'] != '' && getimagesize($pp['tmp_name']) !== FALSE){
                        $pp_file_name = $this->cms_user_id().'_'.$pp['name'];
                        $file_name = FCPATH.'assets/nocms/images/profile_picture/'.$pp_file_name;
                        move_uploaded_file($pp['tmp_name'], $file_name);
                        @chmod($file_name, 644);
                        $this->cms_resize_image($file_name, 512, 512);
                        @chmod($file_name, 644);
                        // profile picture is pp_file_name
                        $profile_picture = $pp_file_name;
                    }
                }catch(Exception $e){
                    // do nothing
                }
            }
            // update secondary data
            $data = array(
                'theme' => $theme,
                'language' => $language,
                'sex' => $sex,
                'birthdate' => trim($birthdate) == ''? NULL :$birthdate,
                'self_description' => $self_description,
                'profile_picture' => $profile_picture,
            );
            $this->db->update($this->cms_user_table_name(),
                $data,
                array('user_id' => $this->cms_user_id())
            );
            // update email, real name, etc
            $this->cms_do_change_profile($email, $real_name, $password, $this->cms_user_id());
            $this->cms_call_hook('cms_after_change_profile', array($this->cms_user_id(), $this->input->post()));
        }
        // select the old data again
        $query = $this->db->select('user_name, email, real_name, theme, language, sex, birthdate, profile_picture, self_description')
            ->from($this->cms_user_table_name())
            ->where('user_id', $this->cms_user_id())
            ->get();
        $row = $query->row();
        $additional_input = $this->cms_call_hook('cms_change_profile_additional_input', array($this->cms_user_id()));
        $additional_input = implode(' ', $additional_input);
        $data = array(
            'user_name' => $row->user_name,
            'email' => $row->email,
            'real_name' => $row->real_name,
            'birthdate' => $row->birthdate,
            'theme' => $row->theme,
            'language' => $row->language,
            'sex' => $row->sex,
            'self_description' => $row->self_description,
            'profile_picture' => $row->profile_picture,
            'change_profile_caption' => $this->cms_lang('Change Profile'),
            'theme_list' => $this->cms_get_theme_list(),
            'language_list' => $this->cms_language_list(),
            'additional_input' => $additional_input,
        );
        $this->view('main/main_change_profile', $data, 'main_change_profile');
    }

    public function logout()
    {
        $this->cms_do_logout();
        redirect('', 'refresh');
    }

    public function index()
    {
        $this->cms_guard_page('main_index');
        $navigation = $this->cms_navigation('main_index');
        $data = array();
        if($navigation->is_static != 1){
            $data['submenu_screen'] = $this->cms_submenu_screen(null);
        }
        $this->view('main/main_index', $data, 'main_index');
    }

    public function management()
    {
        $this->cms_guard_page('main_management');
        $navigation = $this->cms_navigation('main_management');
        $data = array();
        if($navigation->is_static != 1){
            $data['submenu_screen'] = $this->cms_submenu_screen('main_management');
        }
        $this->view('main/main_management', $data, 'main_management');
    }

    public function language($language = null)
    {
        $this->cms_guard_page('main_language');
        if (isset($language)) {
            $this->cms_language($language);
            redirect('', 'refresh');
        } else {
            $data = array(
                'language_list' => $this->cms_language_list(),
                'current_language' => $this->cms_language(),
            );
            $this->view('main/main_language', $data, 'main_language');
        }
    }

    public function json_login_info()
    {
        $result = array(
            'is_login' => $this->cms_user_id() > 0,
            'user_name' => $this->cms_user_name(),
        );
        $this->cms_show_json($result);
    }

    public function widget_online_user()
    {
        $this->view('main/main_widget_online_user');
    }

    private function _get_token($unique_id)
    {
        $token_file = APPPATH.'config/tmp/_token'.$unique_id.'.php';
        if (!file_exists($token_file)) {
            return;
        }
        include $token_file;
        $token = @json_decode($token, true);

        return $token;
    }

    private function _set_token($unique_id, $token)
    {
        $token = @json_encode($token);
        $token_file = APPPATH.'config/tmp/_token'.$unique_id.'.php';
        $content = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'.PHP_EOL;
        $content .= '$token = \''.$token.'\';';
        file_put_contents($token_file, $content);
    }

    private function _remove_token($unique_id)
    {
        $token_file = APPPATH.'config/_token'.$unique_id.'.php';
        if (file_exists($token_file)) {
            unlink($token_file);
        }
    }

    public function check_login()
    {
        if (CMS_SUBSITE == '') {
            // get origin & server name from subsite
            $original_url = $this->input->get('__origin');
            $server_name = $this->input->get('__server_name');
            $unique_id = md5(rand().time());
            // prepare new record to token list
            $token = array(
                    'remote_addr' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'original_url' => $original_url,
                    'user_id' => $this->cms_user_id(),
                    'time' => time(),
                );
            $this->_set_token($unique_id, $token);
            // prepare redirection
            $ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true : false;
            $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
            $protocol = substr($sp, 0, strpos($sp, '/')).(($ssl) ? 's' : '');
            $redirection = $protocol.'://'.$server_name.'/main/landing?__origin='.urlencode($original_url).'&__token='.$unique_id;
            redirect($redirection);
        }
    }

    public function landing()
    {
        if (CMS_SUBSITE != '') {
            $original_url = $this->input->get('__origin');
            $unique_id = $this->input->get('__token');
            $token = $this->_get_token($unique_id);
            if ($token != null) {
                if ($_SERVER['REMOTE_ADDR'] == $token['remote_addr'] && $_SERVER['HTTP_USER_AGENT'] == $token['user_agent'] &&
                    $original_url == $token['original_url'] && time() < $token['time'] + 120) {
                    // get user name
                    $user_id = $token['user_id'];
                    // get other column in order to emulate login
                    $query = $this->db->select('user_id, user_name, real_name, email')
                        ->from($this->cms_user_table_name())
                        ->where('user_id', $user_id)
                        ->get();
                    if ($query->num_rows() > 0) {
                        $row = $query->row();
                        $this->cms_user_name($row->user_name);
                        $this->cms_user_id($row->user_id);
                        $this->cms_user_real_name($row->real_name);
                        $this->cms_user_email($row->email);
                    }
                }
            }
            $this->_remove_token($unique_id);
            redirect($original_url);
        }
    }

    public function widget_online_user_ajax()
    {
        $query = $this->db->select('user_name')
            ->from($this->cms_user_table_name())
            ->where('login', 1)
            ->where('last_active >=', microtime(true) - 600)
            ->get();
        $user_name_list = array();
        foreach ($query->result() as $row) {
            $user_name_list[] = $row->user_name;
        }
        if (count($user_name_list) > 0) {
            echo implode(', ', $user_name_list);
        } else {
            echo 'No user online';
        }
    }

    public function widget_logout()
    {
        $data = array(
            'user_real_name' => $this->cms_user_real_name(),
            'user_name' => $this->cms_user_name(),
            'logout_lang' => $this->cms_lang('Logout'),
        );
        $this->view('main/main_widget_logout', $data);
    }

    public function widget_login()
    {
        $this->login();
    }

    public function widget_left_nav($first = true, $navigations = null)
    {
        if (!isset($navigations)) {
            $navigations = $this->cms_navigations();
        }

        if (count($navigations) == 0) {
            return '';
        }
        if ($first) {
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
        } else {
            $result = '';
        }
        $result .= '<ul  class="dropdown-menu nav nav-pills nav-stacked" '.($first ? 'id="_first-left-dropdown"' : '').'>';
        foreach ($navigations as $navigation) {
            if ($navigation['hidden']) {
                continue;
            }
            if (($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']) {
                // create badge if needed
                $badge = '';
                if ($quicklink['notif_url'] != '') {
                    $badge_id = '__cms_notif_left_navigation_'.$quicklink['navigation_id'];
                    $badge = '&nbsp;<span id="'.$badge_id.'" class="badge"></span>';
                    $badge .= '<script type="text/javascript">
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
                if ($this->cms_ci_session('__cms_navigation_name') == $quicklink['navigation_name']) {
                    $active = 'active';
                }
                // make text
                $icon = '<span class="glyphicon '.$navigation['bootstrap_glyph'].'"></span>&nbsp;&nbsp;';
                if ($navigation['allowed'] && $navigation['active']) {
                    $text = '<a class="dropdown-toggle" href="'.$navigation['url'].'">'.$icon.$navigation['title'].$badge.'</a>';
                } else {
                    $text = $icon.$navigation['title'].$badge;
                }

                if (count($navigation['child']) > 0 && $navigation['have_allowed_children']) {
                    $result .= '<li class="dropdown-submenu '.$active.'">'.$text.$this->widget_left_nav(false, $navigation['child']).'</li>';
                } else {
                    $result .= '<li class="'.$active.'">'.$text.'</li>';
                }
            }
        }
        $result .= '</ul>';
        // show up
        if ($first) {
            $this->cms_show_html($result);
        } else {
            return $result;
        }
    }

    public function widget_top_nav($caption = 'Complete Menu', $first = true, $no_complete_menu = false, $no_quicklink = false, $navbar_class = 'navbar-default navbar-fixed-top', $navigations = null, &$notif = array())
    {
        $result = '';
        $caption = $this->cms_lang($caption);

        if (!$no_complete_menu) {
            if (!isset($navigations)) {
                $navigations = $this->cms_navigations();
            }
            if (count($navigations) == 0) {
                return '';
            }

            $result .= '<ul class="dropdown-menu">';
            foreach ($navigations as $navigation) {
                if ($navigation['hidden']) {
                    continue;
                }
                if (($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']) {
                    $navigation['bootstrap_glyph'] = $navigation['bootstrap_glyph'] == '' ? 'glyphicon-none' : $navigation['bootstrap_glyph'];
                    // make text
                    $icon = '<span class="glyphicon '.$navigation['bootstrap_glyph'].'"></span>&nbsp;&nbsp;';
                    $badge = '';
                    if ($navigation['notif_url'] != '') {
                        $badge_id = '__cms_notif_top_nav_'.$navigation['navigation_id'];
                        $badge = '&nbsp;<span id="'.$badge_id.'" class="badge"></span>';
                        if (!array_key_exists($navigation['notif_url'], $notif)) {
                            $notif[$navigation['notif_url']] = array();
                        }
                        $notif[$navigation['notif_url']][] = $badge_id;
                    }
                    if (!$navigation['allowed'] || !$navigation['active']) {
                        $navigation['url'] = '#';
                    }
                    $text = '<a href="'.$navigation['url'].'">'.$icon.
                        $navigation['title'].$badge.'</a>';

                    $all_child_hidden = TRUE;
                    foreach($navigation['child'] as $child){
                        if($child['hidden'] != 1){
                            $all_child_hidden = FALSE;
                            break;
                        }
                    }
                    if (count($navigation['child']) > 0 && !$all_child_hidden && $navigation['have_allowed_children']) {
                        $result .= '<li class="dropdown-submenu">'.
                            $text.$this->widget_top_nav($caption, false, $no_complete_menu, $no_quicklink, $inverse, $navigation['child'], $notif).'</li>';
                    } else {
                        $result .= '<li>'.$text.'</li>';
                    }
                }
            }
            // factory reset
            if($first && $this->cms_user_is_super_admin()){
                $result .= '<li role="separator" class="divider"></li>';
                $result .= '<li><a href="{{ site_url }}factory_reset?from='.$this->cms_get_origin_uri_string().'"><i class="glyphicon glyphicon-repeat"></i> Factory Reset</a></li>';
            }
            $result .= '</ul>';
        }

        // show up
        if ($first) {
            if (!$no_complete_menu) {
                //  hidden-sm hidden-xs
                $result = '<li class="dropdown">'.
                    '<a class="dropdown-toggle" data-toggle="dropdown" href="{{ site_url }}">'.
                        '<span class="anchor-text">'.
                            '<img id="navbar-logo" class="navbar-logo" src ="{{ site_favicon }}" style="max-height:20px; max-width:20px;" />'.
                            '<span id="navbar-caption" class="navbar-caption">'.$caption.'</span>'.
                        '</span>&nbsp;'.
                        '<span class="caret"></span>'.
                    '</a>'.
                    $result.'</li>';
            }
            if (!$no_quicklink) {
                $result .= $this->build_quicklink(null, true, $notif);
            }
            // toggle editing
            $toggle_editing = '';
            $edit_menu = '';
            if ($this->cms_user_is_super_admin()) {
                if ($this->cms_editing_mode()) {
                    $toggle_editing = '<a id="__toggle_editing_off" href="#" class="hidden-xs" style="font-size:small;">'.
                            '<i class="glyphicon glyphicon-eye-open"></i> Toggle View'.
                        '</a>';
                    $edit_menu = '<li class="dropdown hidden-xs" id="__edit_menu">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="http://localtest.me/No-CMS/main/management" style="padding-left: 15px; padding-right: 10px; font-size:small;"><i class="glyphicon glyphicon-pencil"></i> Edit <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li class="dropdown">
                                <a href="{{ site_url }}main/manage_navigation?from='.$this->cms_get_origin_uri_string().'">Navigation</a>
                            </li>
                            <li class="dropdown">
                                <a href="{{ site_url }}main/manage_quicklink?from='.$this->cms_get_origin_uri_string().'">Quicklink</a>
                            </li>
                            <li class="dropdown">
                                <a id="__editing_section_top_fix_link" href="#">Top Section</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ site_url }}factory_reset?from='.$this->cms_get_origin_uri_string().'"><i class="glyphicon glyphicon-repeat"></i> Factory Reset</a></li>
                        </ul>
                    </li>';
                } else {
                    $toggle_editing = '<a id="__toggle_editing_on" href="#" class="hidden-xs" style="font-size:small;">'.
                            '<i class="glyphicon glyphicon-pencil"></i> Toggle Edit'.
                        '</a>';
                }
            }

            $load_notif_script = '';
            $badge_index = 1;
            foreach ($notif as $url => $badge_id_list) {
                $changer_script = '';
                foreach ($badge_id_list as $badge_id) {
                    $changer_script .= '$("#'.$badge_id.'").html(response.notif);';
                }
                $load_notif_script .= '$(document).ready(function(){
                        function __get_badge_'.$badge_index.'(){
                            $.ajax({
                                dataType:"json",
                                url: "'.addslashes($url).'",
                                success: function(response){
                                    if(response.success){
                                        '.$changer_script.'
                                    }
                                }
                            });
                        }
                        __get_badge_'.$badge_index.'();
                        setInterval(function(){
                            __get_badge_'.$badge_index.'();
                        }, 50000);
                    });';
                ++$badge_index;
            }

            $result =
            '<style type="text/css">
                @media (min-width: 750px){
                    .dropdown-submenu{
                        position:relative;
                        overflow:initial!important;
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
                @media (min-width: 979px) {
                    body {
                        /*padding-top: 50px;*/
                    }
                }
                .__editing_widget_top_navigation, .__editing_widget_top_navigation_inverse, .__editing_widget_top_navigation_default, .__editing_widget_top_navigation_inverse_fixed, .__editing_widget_top_navigation_default_fixed, .__editing_widget_top_navigation_inverse_static, .__editing_widget_top_navigation_default_static, .__editing_widget_section_top_fix, .__editing_widget_navigation_right_partial{
                    display:none;
                }
                .glyphicon-none:before {
                    content: "\2122";
                    color: transparent !important;
                }
            </style>
            <div id="_top_navigation" class="navbar '.$navbar_class.'" role="navigation" style="margin-bottom:0;">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand hidden-lg hidden-md hidden-sm" href="{{ site_url }}">
                            <img class="navbar-logo" src ="{{ site_favicon }}" style="max-height:20px; max-width:20px;" />
                        </a>
                    </div>
                    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
                        <ul class="navbar-nav nav">'.$result.'</ul>
                        <ul class="navbar-nav nav navbar-right">
                            <li class="dropdown" id="__right_navbar">{{ widget_name:navigation_right_partial }}</li>
                            '.$edit_menu.'
                            <li class="dropdown">'.$toggle_editing.'</li>
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
                        var trial_left = 50;
                        while(need_transform && trial_left > 0){
                            need_transform = false;
                            trial_left --;
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
                                    var newFontSize = currentFontSizeNum * 0.9;
                                    $(".navbar-nav > li").css("font-size", newFontSize);
                                }
                            }
                        }
                    }
                    // #navbar-logo and #navbar-caption
                    if($(document).width()>=750){
                        $("#navbar-logo").show();
                        $("#navbar-caption").hide();
                    }else{
                        $("#navbar-logo").hide();
                        $("#navbar-caption").show();
                    }
                    // if it has navbar-fixed-top
                    if($("div.navbar").hasClass("navbar-fixed-top")){
                        var navbar_height = $(".navbar").height();
                        $("body").css("padding-top", navbar_height);
                    }
                }

                // MAIN PROGRAM
                $(document).ready(function(){
                    if($(".__editing_widget_section_top_fix a").length > 0){
                        $("#__editing_section_top_fix_link").attr("href", $(".__editing_widget_section_top_fix a").attr("href"));
                    }

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
                $("#__toggle_editing_on").click(function(event){
                    event.preventDefault();
                    $.ajax({
                        url: "{{ SITE_URL }}main/set_editing_mode",
                        success : function(response){
                            location.reload();
                        }
                    });
                });
                $("#__toggle_editing_off").click(function(event){
                    event.preventDefault();
                    $.ajax({
                        url: "{{ SITE_URL }}main/unset_editing_mode",
                        success : function(response){
                            location.reload();
                        }
                    });
                });
                '.$load_notif_script.'
            </script>';

            $this->cms_show_html($result);
        } else {
            return $result;
        }
    }

    public function set_editing_mode()
    {
        $this->cms_set_editing_mode();
    }

    public function unset_editing_mode()
    {
        $this->cms_unset_editing_mode();
    }

    public function widget_quicklink()
    {
        $this->widget_top_nav('', true, true, true, 'navbar-default', null);
    }

    public function widget_top_nav_default($caption = 'Complete Menu')
    {
        $this->widget_top_nav($caption, true, false, false, 'navbar-default', null);
    }

    public function widget_quicklink_default()
    {
        $this->widget_top_nav('', true, true, false, 'navbar-default', null);
    }

    public function widget_top_nav_inverse($caption = 'Complete Menu')
    {
        $this->widget_top_nav($caption, true, false, false, 'navbar-inverse', null);
    }

    public function widget_quicklink_inverse()
    {
        $this->widget_top_nav('', true, true, false, 'navbar-inverse', null);
    }

    public function widget_top_nav_default_fixed($caption = 'Complete Menu')
    {
        $this->widget_top_nav($caption, true, false, false, 'navbar-default navbar-fixed-top', null);
    }

    public function widget_quicklink_default_fixed()
    {
        $this->widget_top_nav('', true, true, false, 'navbar-default navbar-fixed-top', null);
    }

    public function widget_top_nav_inverse_fixed($caption = 'Complete Menu')
    {
        $this->widget_top_nav($caption, true, false, false, 'navbar-inverse navbar-fixed-top', null);
    }

    public function widget_quicklink_inverse_fixed()
    {
        $this->widget_top_nav('', true, true, false, 'navbar-inverse navbar-fixed-top', null);
    }

    public function widget_top_nav_default_static($caption = 'Complete Menu')
    {
        $this->widget_top_nav($caption, true, false, false, 'navbar-default navbar-static-top', null);
    }

    public function widget_quicklink_default_static()
    {
        $this->widget_top_nav('', true, true, false, 'navbar-default navbar-static-top', null);
    }

    public function widget_top_nav_inverse_static($caption = 'Complete Menu')
    {
        $this->widget_top_nav($caption, true, false, false, 'navbar-inverse navbar-static-top', null);
    }

    public function widget_quicklink_inverse_static()
    {
        $this->widget_top_nav('', true, true, false, 'navbar-inverse navbar-static-top', null);
    }

    private function build_quicklink($quicklinks = null, $first = true, &$notif = '')
    {
        if (!isset($quicklinks)) {
            $quicklinks = $this->cms_quicklinks();
        }

        $current_navigation_name = $this->cms_ci_session('__cms_navigation_name');
        $current_navigation_path = $this->cms_get_navigation_path($current_navigation_name);
        $html = '';

        foreach ($quicklinks as $quicklink) {
            // if navigation hidden, skip it
            if ($quicklink['hidden']) {
                continue;
            }
            // if navigation is not active then skip it
            if ((!$quicklink['allowed'] || !$quicklink['active']) && !$quicklink['have_allowed_children']) {
                continue;
            }
            // create icon if needed
            $icon = '';
            if ($first) {
                $icon_class = $quicklink['bootstrap_glyph'].' icon-white';
            } else {
                $icon_class = $quicklink['bootstrap_glyph'];
            }
            if ($quicklink['bootstrap_glyph'] != '' || !$first) {
                $icon_class = $icon_class == '' ? 'icon-white' : $icon_class;
                $icon = '<span class="glyphicon '.$icon_class.'"></span>&nbsp;&nbsp;';
            }
            // create badge if needed
            $badge = '';
            if ($quicklink['notif_url'] != '') {
                $badge_id = '__cms_notif_quicklink_'.$quicklink['navigation_id'];
                $badge = '&nbsp;<span id="'.$badge_id.'" class="badge"></span>';
                if (!array_key_exists($quicklink['notif_url'], $notif)) {
                    $notif[$quicklink['notif_url']] = array();
                }
                $notif[$quicklink['notif_url']][] = $badge_id;
            }
            // set active class
            $active = '';
            if ($current_navigation_name == $quicklink['navigation_name']) {
                $active = 'active';
            } else {
                foreach ($current_navigation_path as $navigation_parent) {
                    if ($quicklink['navigation_name'] == $navigation_parent['navigation_name']) {
                        $active = 'active';
                        break;
                    }
                }
            }
            if (!$quicklink['allowed'] || !$quicklink['active']) {
                $quicklink['url'] = '#';
            }
            // create li based on child availability
            $all_child_hidden = TRUE;
            foreach($quicklink['child'] as $child){
                if($child['hidden'] != 1){
                    $all_child_hidden = FALSE;
                    break;
                }
            }
            if (count($quicklink['child']) == 0 || !$quicklink['have_allowed_children'] || (count($quicklink['child'])>0 && $all_child_hidden)) {
                $html .= '<li class="'.$active.'">';
                $html .= anchor($quicklink['url'], '<span>'.$icon.$quicklink['title'].$badge.'</span>');
                $html .= '</li>';
            } else {
                if (!$quicklink['allowed'] || !$quicklink['active']) {
                    if ($first) {
                        $html .= '<li class="dropdown '.$active.'">';
                        $html .= '<a class="dropdown-toggle" data-toggle="dropdown" href="'.$quicklink['url'].'">'.
                            $icon.$quicklink['title'].$badge.
                            '&nbsp;<span class="caret"></span></a>'; // hidden-sm hidden-xs
                        $html .= $this->build_quicklink($quicklink['child'], false, $notif);
                        $html .= '</li>';
                    } else {
                        $html .= '<li class="dropdown-submenu '.$active.'">';
                        $html .= '<a href="'.$quicklink['url'].'">'.$icon.$quicklink['title'].$badge.'</a>';
                        $html .= $this->build_quicklink($quicklink['child'], false, $notif);
                        $html .= '</li>';
                    }
                } else {
                    if ($first) {
                        $html .= '<li class="dropdown '.$active.'">';
                        $html .= '<a class="dropdown-toggle" data-toggle="dropdown" href="'.$quicklink['url'].'">'.
                            '<span class="anchor-text">'.$icon.$quicklink['title'].$badge.'</span>'.
                            '&nbsp;<span class="caret"></span></a>'; // hidden-sm hidden-xs
                        $html .= $this->build_quicklink($quicklink['child'], false, $notif);
                        $html .= '</li>';
                    } else {
                        $html .= '<li class="dropdown-submenu '.$active.'">';
                        $html .= '<a href="'.$quicklink['url'].'">'.
                            '<span>'.$icon.$quicklink['title'].$badge.'</span></a>';
                        $html .= $this->build_quicklink($quicklink['child'], false, $notif);
                        $html .= '</li>';
                    }
                }
            }
        }

        if (!$first) {
            $html = '<ul class="dropdown-menu">'.$html.'</ul>';
        }

        return $html;
    }

    public function widget_user_button(){
        echo '<style type="text/css">.__editing_widget_user_button{display:none;}</style>';
        if($this->cms_user_id() > 0){
            echo '<div class="user-button btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img style="max-height:16px; margin-right:5px;" src="'.$this->cms_get_profile_picture($this->cms_user_id()).'" /> '.$this->cms_user_name().' <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ site_url }}main/change_profile">Change Profile</a></li>
                        <li><a href="{{ site_url }}main/logout">Logout</a></li>
                    </ul>
                </div>';
        }else{
            echo '<a class="user-button-login btn btn-primary btn-sm" href="{{ site_url }}main/login">Login</a>';
        }
    }
}
