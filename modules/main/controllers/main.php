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

    public function module_management()
    {
        $this->cms_guard_page('main_module_management');
        // upload new module
        $data['upload'] = $this->upload('./modules/', 'userfile', 'upload');

        // show the view
        $data['modules'] = $this->cms_get_module_list();
        $data['upload_new_module_caption'] = $this->cms_lang('Upload New Module');
        $this->view('main/main_module_management', $data, 'main_module_management');
    }

    public function change_theme($theme = NULL)
    {
        $this->cms_guard_page('main_change_theme');
        // upload new theme
        $data['upload'] = $this->upload('./themes/', 'userfile', 'upload');

        // show the view
        if (isset($theme)) {
            $this->cms_set_config('site_theme', $theme);
            redirect('main/change_theme');
        } else {
            $data['themes'] = $this->cms_get_layout_list();
            $data['upload_new_theme_caption'] = $this->cms_lang('Upload New Theme');
            $this->view('main/main_change_theme', $data, 'main_change_theme');
        }
    }

    //this is used for the real static page which doesn't has any URL in navigation management
    public function static_page($navigation_name)
    {
        $this->view('CMS_View', NULL, $navigation_name);
    }

    public function login()
    {
        $this->cms_guard_page('main_login');
        //retrieve old_url from flashdata if exists
        $this->load->library('session');
        $old_url = $this->session->flashdata('cms_old_url');

        //get user input
        $identity = $this->input->post('identity');
        $password = $this->input->post('password');

        //set validation rule
        $this->form_validation->set_rules('identity', 'Identity', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean');

        if ($this->form_validation->run()) {
            if ($this->cms_do_login($identity, $password)) {
                //if old_url exist, redirect to old_url, else redirect to main/index
                if (!is_bool($old_url)) {
                    redirect($old_url);
                } else {
                    redirect('main/index');
                }
            } else {
                //the login process failed
                //save the old_url again
                if (!is_bool($old_url)) {
                    $this->session->keep_flashdata('cms_old_url');
                }

                //view login again
                $data = array(
                    "identity" => $identity,
                    "message" => '{{ language:Error }}: {{ language:Login Failed }}',
                    "providers" => $this->cms_third_party_providers(),
                    "login_caption" => $this->cms_lang("Login"),
                    "register_caption" => $this->cms_lang("Register"),
                );
                $this->view('main/main_login', $data, 'main_login');
            }
        } else {
            //save the old_url again
            if (!is_bool($old_url)) {
                $this->session->keep_flashdata('cms_old_url');
            }

            //view login again
            $data = array(
                "identity" => $identity,
                "message" => '',
                "providers" => $this->cms_third_party_providers(),
                "login_caption" => $this->cms_lang("Login"),
                "register_caption" => $this->cms_lang("Register"),
            );
            $this->view('main/main_login', $data, 'main_login');
        }
    }

    public function activate($activation_code)
    {
        $this->cms_activate_account($activation_code);
        redirect('main/index');
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
                    redirect('main/index');
                } else {
                    redirect('main/forgot');
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
                    redirect('main/index');
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
        //get user input
        $user_name        = $this->input->post('user_name');
        $email            = $this->input->post('email');
        $real_name        = $this->input->post('real_name');
        $password         = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');

        //set validation rule
        $this->form_validation->set_rules('user_name', 'User Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'E mail', 'required|xss_clean|valid_email');
        $this->form_validation->set_rules('real_name', 'Real Name', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean|matches[confirm_password]');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required|xss_clean');

        if ($this->form_validation->run() && !$this->cms_is_user_exists($user_name)) {
            $this->cms_do_register($user_name, $email, $real_name, $password);
            redirect('main/index');
        } else {
            $data = array(
                "user_name" => $user_name,
                "email" => $email,
                "real_name" => $real_name,
                "register_caption" => $this->cms_lang('Register'),
            );
            $this->view('main/main_register', $data, 'main_register');
        }
    }

    public function check_registration()
    {
        if ($this->input->is_ajax_request()) {
            $user_name = $this->input->post('user_name');
            $exists    = $this->cms_is_user_exists($user_name);
            $message   = "";
            if ($user_name == "") {
                $message = $this->cms_lang("Username is empty");
            } else if ($exists) {
                $message = $this->cms_lang("Username already exists");
            }
            $data = array(
                "exists" => $exists,
                "message" => $message
            );
            $this->cms_show_json($data);
        }
    }

    public function check_change_profile()
    {
        if ($this->input->is_ajax_request()) {
            $user_name = $this->input->post('user_name');
            $exists    = $this->cms_is_user_exists($user_name) && $user_name != $this->cms_user_name();
            $message   = "";
            if ($user_name == "") {
                $message = $this->cms_lang("Username is empty");
            } else if ($exists) {
                $message = $this->cms_lang("Username already exists");
            }
            $data = array(
                "exists" => $exists,
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
            redirect('main/index');
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
        redirect('main/index');
    }

    public function index()
    {        
        //$this->cms_guard_page('main_index');
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
            redirect('main/index');
        } else {
            $data = array(
                "language_list" => $this->cms_language_list()
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
        $crud->set_subject($this->cms_lang('User'));

        $crud->required_fields('user_name');

        $crud->columns('user_name', 'email', 'real_name', 'active', 'groups');
        $crud->edit_fields('user_name', 'email', 'real_name', 'active', 'groups');
        $crud->add_fields('user_name', 'email', 'password', 'real_name', 'active', 'groups');
        $crud->field_type('active', 'true_false');

        $crud->display_as('user_name', $this->cms_lang('User Name'))
            ->display_as('email', $this->cms_lang('Email'))
            ->display_as('real_name', $this->cms_lang('Real Name'))
            ->display_as('active', $this->cms_lang('Active'))
            ->display_as('groups', $this->cms_lang('Groups'));

        $crud->set_relation_n_n('groups', cms_table_name('main_group_user'), cms_table_name('main_group'), 'user_id', 'group_id', 'group_name');
        $crud->callback_before_insert(array(
            $this,
            'before_insert_user'
        ));
        $crud->callback_before_delete(array(
            $this,
            'before_delete_user'
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

        $this->view('main/main_user', $output, 'main_user_management');
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

    public function before_delete_user($primary_key, $post_array)
    {
        //The super admin user cannot be deleted, a user cannot delete his/her own account
        if (($primary_key == 1) || ($primary_key == $this->cms_user_id())) {
            return false;
        }
        return $post_array;
    }

    // GROUP ===================================================================
    public function group()
    {
        $this->cms_guard_page('main_group_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_group'));
        $crud->set_subject($this->cms_lang('User Group'));

        $crud->required_fields('group_name');

        $crud->columns('group_name', 'description');
        $crud->edit_fields('group_name', 'description', 'users', 'navigations', 'privileges');
        $crud->add_fields('group_name', 'description', 'users', 'navigations', 'privileges');
        $crud->display_as('group_name', $this->cms_lang('Group'))
            ->display_as('description', $this->cms_lang('Description'))
            ->display_as('users', $this->cms_lang('Users '))
            ->display_as('navigations', $this->cms_lang('Navigations'))
            ->display_as('privileges', $this->cms_lang('Privileges'));


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

        $this->view('main/main_group', $output, 'main_group_management');
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
        return $post_array;
    }

    // NAVIGATION ==============================================================
    public function navigation($parent_id=NULL)
    {
        $this->cms_guard_page('main_navigation_management');
        $crud = $this->new_crud();

        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_navigation'));
        $crud->set_subject($this->cms_lang('Navigation (Page)'));

        $crud->required_fields('navigation_name');
	$crud->required_fields('title');

        $crud->columns('navigation_name', 'navigation_child', 'title', 'active');
        $crud->edit_fields('navigation_name', 'parent_id', 'title', 'page_title', 'page_keyword', 'description', 'active', 'only_content', 'is_static', 'static_content', 'default_theme', 'url', 'authorization_id', 'groups');
        $crud->add_fields('navigation_name', 'parent_id', 'title', 'page_title', 'page_keyword', 'description', 'active', 'only_content', 'is_static', 'static_content', 'default_theme', 'url', 'authorization_id', 'groups');
        $crud->field_type('active', 'true_false');
        $crud->field_type('is_static', 'true_false');
        // get themes to give options for default_theme field
        $themes     = $this->cms_get_layout_list();
        $theme_path = array();
        foreach ($themes as $theme) {
            $theme_path[] = $theme['path'];
        }
        $crud->field_type('default_theme', 'enum', $theme_path);
        $crud->display_as('navigation_name', $this->cms_lang('Navigation Code'))
            ->display_as('is_root', $this->cms_lang('Is Root'))
            ->display_as('navigation_child', $this->cms_lang('Children'))
            ->display_as('parent_id', $this->cms_lang('Parent'))
            ->display_as('title', $this->cms_lang('Navigation Title (What visitor see)'))
            ->display_as('page_title', $this->cms_lang('Page Title'))
            ->display_as('page_keyword', $this->cms_lang('Page Keyword (Comma Separated)'))
            ->display_as('description', $this->cms_lang('Description'))
            ->display_as('url', $this->cms_lang('URL (Where is it point to)'))
            ->display_as('active', $this->cms_lang('Active'))
            ->display_as('is_static', $this->cms_lang('Static'))
            ->display_as('static_content', $this->cms_lang('Static Content'))
            ->display_as('authorization_id', $this->cms_lang('Authorization'))
            ->display_as('groups', $this->cms_lang('Groups'))
            ->display_as('only_content', $this->cms_lang('Only show content'))
            ->display_as('default_theme', $this->cms_lang('Default Theme'));

        $crud->order_by('parent_id, index', 'asc');

        $crud->unset_texteditor('description');
        $crud->field_type('only_content', 'true_false');

        $crud->set_relation('parent_id', cms_table_name('main_navigation'), 'navigation_name');
        $crud->set_relation('authorization_id', cms_table_name('main_authorization'), 'authorization_name');

        $crud->set_relation_n_n('groups', cms_table_name('main_group_navigation'), cms_table_name('main_group'), 'navigation_id', 'group_id', 'group_name');

        if(isset($parent_id) && intval($parent_id)>0){
            $crud->where(cms_table_name('main_navigation').'.parent_id', $parent_id);
            $state = $crud->getState();
            if($state == 'add'){
                $crud->change_field_type('parent_id', 'hidden', $parent_id);
            }
        }else{
            $crud->where(array(cms_table_name('main_navigation').'.parent_id' => NULL));
        }
        $crud->add_action('Move Up', base_url('modules/'.$this->cms_module_path().'/assets/action_icon/up.png'),
            site_url($this->cms_module_path().'/action_navigation_move_up').'/');
        $crud->add_action('Move Down', base_url('modules/'.$this->cms_module_path().'/assets/action_icon/down.png'),
            site_url($this->cms_module_path().'/action_navigation_move_down').'/');

        $crud->callback_column('active', array(
            $this,
            'column_navigation_active'
        ));

        $crud->callback_column('navigation_child', array(
            $this,
            'column_navigation_child'
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

        $this->view('main/main_navigation', $output, 'main_navigation_management');
    }

    public function action_navigation_move_up($primary_key){
        $query = $this->db->select('navigation_name, parent_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_id', $primary_key)
            ->get();
        $row = $query->row();
        $navigation_name = $row->navigation_name;
        $parent_id = $row->parent_id;

        // move up
        $this->cms_do_move_up_navigation($navigation_name);

        // redirect
        if(isset($parent_id)){
            redirect('main/navigation/'.$parent_id.'#record_'.$primary_key);
        }else{
            redirect('main/navigation'.'#record_'.$primary_key);
        }
    }

    public function action_navigation_move_down($primary_key){
        $query = $this->db->select('navigation_name, parent_id')
            ->from(cms_table_name('main_navigation'))
            ->where('navigation_id', $primary_key)
            ->get();
        $row = $query->row();
        $navigation_name = $row->navigation_name;
        $parent_id = $row->parent_id;

        // move down
        $this->cms_do_move_down_navigation($navigation_name);

        // redirect
        if(isset($parent_id)){
            redirect('main/navigation/'.$parent_id.'#record_'.$primary_key);
        }else{
            redirect('main/navigation'.'#record_'.$primary_key);
        }
    }

    public function before_insert_navigation($post_array)
    {
        //get parent's navigation_id
        $SQL   = "SELECT navigation_id FROM ".cms_table_name('main_navigation')." WHERE navigation_id='" . $post_array['parent_id'] . "'";
        $query = $this->db->query($SQL);
        $row   = $query->row();

        $parent_id = isset($row->navigation_id) ? $row->navigation_id : NULL;

        //index = max index+1
        if (isset($parent_id)) {
            $whereParentId = "(parent_id = $parent_id)";
        } else {
            $whereParentId = "(parent_id IS NULL)";
        }
        $SQL   = "SELECT max(".$this->db->protect_identifiers('index').")+1 AS newIndex FROM ".cms_table_name('main_navigation')." WHERE $whereParentId";
        $query = $this->db->query($SQL);
        $row   = $query->row();
        $index = $row->newIndex;
        if (!isset($index))
            $index = 1;

        $post_array['index'] = $index;

        if (!isset($post_array['authorization_id']) || $post_array['authorization_id'] == '') {
            $post_array['authorization_id'] = 1;
        }

        return $post_array;
    }

    public function before_delete_navigation($primary_key)
    {
        $this->db->delete(cms_table_name('main_quicklink'), array(
            'navigation_id' => $primary_key
        ));
    }

    public function column_navigation_active($value, $row)
    {
        $html = '<a name="record_'.$row->navigation_id.'">&nbsp;</a>';
        $target = site_url($this->cms_module_path() . '/toggle_navigation_active/' . $row->navigation_id);
        if ($value == 0) {
            $html .= '<span target="' . $target . '" class="navigation_active">Inactive</span>';
        } else {
            $html .= '<span target="' . $target . '" class="navigation_active">Active</span>';
        }
        return $html;
    }

    public function column_navigation_child($value, $row)
    {
        $html = '';
        $this->db->select('navigation_id')
            ->from(cms_table_name('main_navigation'))
            ->where('parent_id', $row->navigation_id);
        $query = $this->db->get();
        $child_count = $query->num_rows();
        if($child_count<=0){
            $html .= $this->cms_lang('No Child');
        }else{
            $html .= '<a href="'.site_url($this->cms_module_path().'/navigation/'.$row->navigation_id).'">'.
                $this->cms_lang('Manage Children')
                .'</a>';
        }
        $html .= '&nbsp;|&nbsp;<a href="'.site_url($this->cms_module_path().'/navigation/'.$row->navigation_id).'/add">'.
            $this->cms_lang('Add Child')
            .'</a>';
        return $html;
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
        $crud->set_subject($this->cms_lang('Quick Link'));

        $crud->required_fields('navigation_id');

        $crud->columns('navigation_id');
        $crud->edit_fields('navigation_id');
        $crud->add_fields('navigation_id');

        $crud->display_as('navigation_id', $this->cms_lang('Navigation Code'));

        $crud->order_by('index', 'asc');

        $crud->set_relation('navigation_id', cms_table_name('main_navigation'), 'navigation_name');

        $crud->callback_before_insert(array(
            $this,
            'before_insert_quicklink'
        ));

        $crud->add_action('Move Up', base_url('modules/'.$this->cms_module_path().'/assets/action_icon/up.png'),
            site_url($this->cms_module_path().'/action_quicklink_move_up').'/');
        $crud->add_action('Move Down', base_url('modules/'.$this->cms_module_path().'/assets/action_icon/down.png'),
            site_url($this->cms_module_path().'/action_quicklink_move_down').'/');

        $crud->callback_column($this->unique_field_name('navigation_id'), array(
            $this,
            'column_quicklink_navigation_id'
        ));

        $crud->set_language($this->cms_language());

        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_quicklink_management');
    }

    public function before_insert_quicklink($post_array)
    {
        $SQL   = "SELECT max(".$this->db->protect_identifiers('index').")+1 AS newIndex FROM ".cms_table_name('main_quicklink');
        $query = $this->db->query($SQL);
        $row   = $query->row();
        $index = $row->newIndex;

        if (!isset($index))
            $index = 1;

        $post_array['index'] = $index;

        return $post_array;
    }

    public function column_quicklink_navigation_id($value, $row)
    {
        $html = '<a name="record_'.$row->quicklink_id.'">&nbsp;</a>';
        $html .= $value;
        return $html;
    }

    public function action_quicklink_move_up($primary_key){
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_quicklink'))
            ->where('quicklink_id', $primary_key)
            ->get();
        $row = $query->row();
        $navigation_id = $row->navigation_id;

        // move up
        $this->cms_do_move_up_quicklink($navigation_id);

        // redirect
        redirect('main/quicklink'.'#record_'.$primary_key);
    }

    public function action_quicklink_move_down($primary_key){
        $query = $this->db->select('navigation_id')
            ->from(cms_table_name('main_quicklink'))
            ->where('quicklink_id', $primary_key)
            ->get();
        $row = $query->row();
        $navigation_id = $row->navigation_id;

        // move up
        $this->cms_do_move_down_quicklink($navigation_id);

        // redirect
        redirect('main/quicklink'.'#record_'.$primary_key);
    }

    // PRIVILEGE ===============================================================
    public function privilege()
    {
        $this->cms_guard_page('main_privilege_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_privilege'));
        $crud->set_subject($this->cms_lang('Privilege'));

        $crud->required_fields('privilege_name');

        $crud->columns('privilege_name','title','description');
        $crud->edit_fields('privilege_name','title','description','authorization_id','groups');
        $crud->add_fields('privilege_name','title','description','authorization_id','groups');

        $crud->set_relation('authorization_id', cms_table_name('main_authorization'), 'authorization_name'); //, 'groups');

        $crud->set_relation_n_n('groups', cms_table_name('main_group_privilege'), cms_table_name('main_group'), 'privilege_id', 'group_id', 'group_name');

        $crud->display_as('authorization_id', $this->cms_lang('Authorization'))
            ->display_as('groups', $this->cms_lang('Groups'))
            ->display_as('privilege_name', $this->cms_lang('Privilege Code'))
            ->display_as('title', $this->cms_lang('Title'))
            ->display_as('description', $this->cms_lang('Description'));

        $crud->unset_texteditor('description');

        $crud->set_language($this->cms_language());

        $output = $crud->render();

        $this->view('main/main_privilege', $output, 'main_privilege_management');
    }

    // WIDGET ==================================================================
    public function widget()
    {
        $this->cms_guard_page('main_widget_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_widget'));
        $crud->set_subject($this->cms_lang('Widget'));

        $crud->required_fields('widget_name');
	$crud->required_fields('title');

        $crud->columns('widget_name', 'title', 'active', 'slug');
        $crud->edit_fields('widget_name', 'title', 'active', 'description', 'is_static', 'static_content', 'url', 'slug', 'authorization_id', 'groups');
        $crud->add_fields('widget_name', 'title', 'active', 'description', 'is_static', 'static_content', 'url', 'slug', 'authorization_id', 'groups');
        $crud->field_type('active', 'true_false');
        $crud->field_type('is_static', 'true_false');
        $crud->field_type('index', 'integer');

        $crud->display_as('widget_name', $this->cms_lang('Widget Code'))
            ->display_as('title', $this->cms_lang('Title (What visitor see)'))
            ->display_as('active', $this->cms_lang('Active'))
            ->display_as('description', $this->cms_lang('Description'))
            ->display_as('url', $this->cms_lang('URL (Where is it point to)'))
            ->display_as('index', $this->cms_lang('Order'))
            ->display_as('is_static', $this->cms_lang('Static'))
            ->display_as('static_content', $this->cms_lang('Static Content'))
            ->display_as('slug', $this->cms_lang('Slug'))
            ->display_as('authorization_id', $this->cms_lang('Authorization'))
            ->display_as('groups', $this->cms_lang('Groups'));

        $crud->order_by('index, slug', 'asc');

        $crud->unset_texteditor('static_content');
        $crud->unset_texteditor('description');

        $crud->set_relation('authorization_id', cms_table_name('main_authorization'), 'authorization_name');

        $crud->set_relation_n_n('groups', cms_table_name('main_group_widget'), cms_table_name('main_group'), 'widget_id', 'group_id', 'group_name');

        $crud->callback_before_insert(array(
            $this,
            'before_insert_widget'
        ));

        $crud->add_action('Move Up', base_url('modules/'.$this->cms_module_path().'/assets/action_icon/up.png'),
            site_url($this->cms_module_path().'/action_widget_move_up').'/');
        $crud->add_action('Move Down', base_url('modules/'.$this->cms_module_path().'/assets/action_icon/down.png'),
            site_url($this->cms_module_path().'/action_widget_move_down').'/');

        $crud->callback_column('active', array(
            $this,
            'column_widget_active'
        ));

        $crud->set_language($this->cms_language());

        $output = $crud->render();

        $this->view('main/main_widget', $output, 'main_widget_management');
    }

    public function before_insert_widget($post_array)
    {
        $SQL   = "SELECT max(".$this->db->protect_identifiers('index').")+1 AS newIndex FROM ".cms_table_name('main_widget');
        $query = $this->db->query($SQL);
        $row   = $query->row();
        $index = $row->newIndex;

        if (!isset($index))
            $index = 1;

        $post_array['index'] = $index;

        if (!isset($post_array['authorization_id']) || $post_array['authorization_id'] == '') {
            $post_array['authorization_id'] = 1;
        }

        return $post_array;
    }

    public function column_widget_active($value, $row)
    {
        $html = '<a name="record_'.$row->widget_id.'">&nbsp;</a>';
        $target = site_url($this->cms_module_path() . '/toggle_widget_active/' . $row->widget_id);
        if ($value == 0) {
            $html.= '<span target="' . $target . '" class="widget_active">Inactive</span>';
        } else {
            $html.= '<span target="' . $target . '" class="widget_active">Active</span>';
        }
        return $html;
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

    public function action_widget_move_up($primary_key){
        $query = $this->db->select('widget_name')
            ->from(cms_table_name('main_widget'))
            ->where('widget_id', $primary_key)
            ->get();
        $row = $query->row();
        $widget_name = $row->widget_name;

        // move up
        $this->cms_do_move_up_widget($widget_name);

        // redirect
        redirect('main/widget'.'#record_'.$primary_key);
    }

    public function action_widget_move_down($primary_key){
        $query = $this->db->select('widget_name')
            ->from(cms_table_name('main_widget'))
            ->where('widget_id', $primary_key)
            ->get();
        $row = $query->row();
        $widget_name = $row->widget_name;

        // move up
        $this->cms_do_move_down_widget($widget_name);

        // redirect
        redirect('main/widget'.'#record_'.$primary_key);
    }

    // CONFIG ==================================================================
    public function config()
    {
        $this->cms_guard_page('main_config_management');
        $crud = $this->new_crud();
        $crud->unset_jquery();

        $crud->set_table(cms_table_name('main_config'));
        $crud->set_subject($this->cms_lang('Configuration'));

        $crud->columns('config_name', 'value');
        $crud->edit_fields('config_name', 'value', 'description');
        $crud->add_fields('config_name', 'value', 'description');

        $crud->display_as('config_name', $this->cms_lang('Configuration Key'))
            ->display_as('value', $this->cms_lang('Configuration Value'))
            ->display_as('description', $this->cms_lang('Description'));

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

        $this->view('main/main_config', $output, 'main_config_management');
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
            $style = 'display: block; position: static; border:none; margin:0px; background-color:light-gray;';
        }else{
            $style = 'background-color:light-gray;';
        }
        $result = '<ul  class="dropdown-menu nav nav-pills nav-stacked" style="'.$style.'">';
        foreach($navigations as $navigation){
            if(($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']){
                // make text
                if($navigation['allowed'] && $navigation['active']){
                    $text = '<a class="dropdown-toggle" href="'.$navigation['url'].'">'.$navigation['title'].'</a>';
                }else{
                    $text = $navigation['title'];
                }

                if(count($navigation['child'])>0 && $navigation['have_allowed_children']){
                    $result .= '<li class="dropdown-submenu">'.$text.$this->widget_left_nav(FALSE, $navigation['child']).'</li>';
                }else{
                    $result .= '<li>'.$text.'</li>';
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

    public function widget_top_nav($caption = 'Complete Menu', $first = TRUE, $no_complete_menu=FALSE, $no_quicklink=FALSE, $navigations = NULL){
        $result = '';

        if(!$no_complete_menu){
            if(!isset($navigations)){
                $navigations = $this->cms_navigations();
            }
            if(count($navigations) == 0) return '';


            $result .= '<ul class="dropdown-menu">';
            foreach($navigations as $navigation){
                if(($navigation['allowed'] && $navigation['active']) || $navigation['have_allowed_children']){
                    // make text
                    if($navigation['allowed'] && $navigation['active']){
                        $text = '<a href="'.$navigation['url'].'">'.$navigation['title'].'</a>';
                    }else{
                        $text = '<a href="#">'.$navigation['title'].'</a>';
                    }

                    if(count($navigation['child'])>0 && $navigation['have_allowed_children']){
                        $result .= '<li class="dropdown-submenu">'.
                            $text.$this->widget_top_nav($caption, FALSE, $no_complete_menu, $no_quicklink, $navigation['child']).'</li>';
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
                $result = '<li class="dropdown">'.
                    '<a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$caption.' <span class="caret"></span></a>'.
                    $result.'</li>';
            }
            if(!$no_quicklink){
                $result .= $this->build_quicklink();
            }
            $result = '
            <div class="navbar navbar-fixed-top">
              <div class="navbar-inner">
                <div class="container-fluid">
                    <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">
                        <img src ="{{ site_logo }}" style="max-height:20px; max-width:20px;" />
                    </a>
                    <div class="nav-collapse in collapse" id="main-menu">
                        <ul class="nav">'.$result.'</ul>
                    </div>
                </div>
              </div>
            </div>';
            $this->cms_show_html($result);
        }else{
            return $result;
        }
    }

    public function widget_top_nav_no_quicklink($caption = 'Complete Menu'){
        $this->widget_top_nav($caption, TRUE, FALSE, TRUE, NULL);
    }

    public function widget_quicklink(){
        $this->widget_top_nav('', TRUE, TRUE, FALSE, NULL);
    }

    private function build_quicklink($quicklinks = NULL,$first = TRUE){
        if(!isset($quicklinks)){
            $quicklinks = $this->cms_quicklinks();
        }
        if(count($quicklinks) == 0) return '';
        $html = '';

        foreach($quicklinks as $quicklink){
        	// if navigation is not active then skip it
        	if(!$quicklink['active']){
        		continue;
        	}
			// create li based on child availability
            if(count($quicklink['child'])==0){
                $html.= '<li>';
                $html.= anchor($quicklink['url'], $quicklink['title']);
                $html.= '</li>';
            }else{
                if($first){
                    $html.= '<li class="dropdown">';
                    $html.= '<a class="dropdown-toggle" data-toggle="dropdown" href="'.$quicklink['url'].'">'.
                        '<span onclick="if(event.stopPropagation){event.stopPropagation();}event.cancelBubble=true;window.location = \''.$quicklink['url'].'\'">'.$quicklink['title'].'</span>'.
                        '&nbsp;<span class="caret"></span></a>';
                    $html.= $this->build_quicklink($quicklink['child'],FALSE);
                    $html.= '</li>';
                }else{
                    $html.= '<li class="dropdown-submenu">';
                    $html.= '<a href="'.$quicklink['url'].'">'.
                        '<span>'.$quicklink['title'].'</span></a>';
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
