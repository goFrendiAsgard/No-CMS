<?php
/**
 * Description of Main
 *
 * @author gofrendi
 */
class Main extends CMS_Controller {
    public function login(){
        //get user input
        $identity = $this->input->post('identity');
        $password = $this->input->post('password');
        
        //set validation rule
        $this->form_validation->set_rules('identity', 'Identity', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
        
        if($this->form_validation->run()){
            if($this->do_login($identity, $password)) redirect('main/index');
            else {
                $data = array("identity"=>$identity);
                $this->view('main/login',$data, 'main_login');
            }
        }else{
            $data = array("identity"=>$identity);
            $this->view('main/login',$data, 'main_login');
        }
    }
    
    public function forgot($activation_code=NULL){
        if(isset($activation_code)){
            //get user input
            $password = $this->input->post('password');
            //set validation rule
            $this->form_validation->set_rules('password', 'Password', 'required|xss_clean|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required|xss_clean');
            
            if($this->form_validation->run()){
                if($this->valid_activation_code($activation_code)){
                    $this->forgot_password($activation_code, $password);
                    redirect('main/index');
                }else{                
                    redirect('main/forgot');
                }
            }else{
                $data = array("activation_code"=>$activation_code);
                $this->view('main/forgot_change_password', $data, 'main_forgot');
            }
        }else{
        
            //get user input
            $identity = $this->input->post('identity');

            //set validation rule
            $this->form_validation->set_rules('identity', 'Identity', 'required|xss_clean');

            if($this->form_validation->run()){
                if($this->generate_activation_code($identity)) redirect('main/index');
                else{ 
                    $data = array("identity"=>$identity);
                    $this->view('main/forgot_fill_identity',$data, 'main_forgot');
                }
            }else{
                $data = array("identity"=>$identity);
                $this->view('main/forgot_fill_identity',$data, 'main_forgot');
            }
        }
    }
    
    public function register(){
        //get user input
        $user_name = $this->input->post('user_name');
        $email = $this->input->post('email');
        $real_name = $this->input->post('real_name');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        
        //set validation rule
        $this->form_validation->set_rules('user_name', 'User Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'E mail', 'required|xss_clean|valid_email');
        $this->form_validation->set_rules('real_name', 'Real Name', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean|matches[confirm_password]');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required|xss_clean');
        
        if($this->form_validation->run()){
            $this->do_register($user_name, $email, $real_name, $password);
            redirect('main/index');
        }else{
            $data = array("user_name"=>$user_name,
                "email"=>$email,
                "real_name"=>$real_name);
            $this->view('main/register',$data, 'main_register');
        }        
    }
    
    public function change_profile(){
        //get user input
        $user_name = $this->input->post('user_name');
        $email = $this->input->post('email');
        $real_name = $this->input->post('real_name');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        
        //set validation rule
        $this->form_validation->set_rules('user_name', 'User Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'E mail', 'required|xss_clean|valid_email');
        $this->form_validation->set_rules('real_name', 'Real Name', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean|matches[confirm_password]');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required|xss_clean');
        
        if($this->form_validation->run()){
            $this->do_change_profile($user_name, $email, $real_name, $password);
            redirect('main/index');
        }else{
            $data = array("user_name"=>$user_name,
                "email"=>$email,
                "real_name"=>$real_name);
            $this->view('main/change_profile',$data, 'main_change_profile');
        } 
    }
    
    public function logout(){
        $this->do_logout();
        redirect('main/index');
    }
    
    public function widget_logout(){
        $data = array(
            "user_name" => $this->cms_username()
        );
        $this->view('main/widget_logout', $data);
    }
    
    public function widget_login(){
        $this->login();
    }
    
    public function widget_facebook_like(){
        $data = array(
            "url" => base_url()
        );
        $this->view('main/widget_facebook_like', $data);
    }
    
    public function index(){
        $this->view('main/index', NULL, 'main_index');
    }
    
    public function management(){
        $this->view('main/management', NULL, 'main_management');
    }
    
    public function authorization(){
        $crud = new grocery_CRUD();
        
        $crud->set_table('cms_authorization');
        $crud->set_subject('Authorization List');
        
        $crud->columns('authorization_id','authorization_name','description');
        $crud->display_as('authorization_id','Code')
                 ->display_as('authorization_name','Name')
                 ->display_as('description','Description');
        $crud->set_subject('Authorization List');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

        //$crud->set_theme('datatables');
        $output = $crud->render();

        $this->view('grocery_CRUD', $output);
    }
    
    public function user(){
        $crud = new grocery_CRUD();

        $crud->set_table('cms_user');
        $crud->set_subject('User List');
        $crud->required_fields('user_name');
        
        $crud->columns('user_name','email','real_name','active', 'groups');
        $crud->edit_fields('user_name','email','real_name','active', 'groups');
        $crud->add_fields('user_name','email', 'password','real_name','active', 'groups');
        $crud->change_field_type('active', 'true_false');
        
        $crud->display_as('user_name','User Name')
                 ->display_as('email','E mail')
                 ->display_as('real_name','Real Name')
                 ->display_as('active','Active')
                 ->display_as('groups','Groups');
        
        $crud->set_relation_n_n('groups', 'cms_group_user', 'cms_group', 'user_id', 'group_id' , 'group_name');
        $crud->callback_before_insert(array($this,'before_insert_user'));
        $crud->callback_before_delete(array($this,'before_delete_user'));
        
        
        //$crud->set_theme('datatables');
        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_user_management');
    }
    
    public function before_insert_user($post_array)
    {
        $post_array['password'] = md5($post_array['password']);
        return $post_array;
    }
    
    public function before_delete_user($post_array)
    {
        if($post_array['user_id']==1){
            return false;
        }
        return $post_array;
    } 

    public function group(){
        $crud = new grocery_CRUD();

        $crud->set_table('cms_group');
        $crud->columns('group_name','description');
        $crud->edit_fields('group_name','description','users','navigations', 'privileges');
        $crud->add_fields('group_name','description','users','navigations', 'privileges');
        $crud->display_as('group_name','Group')
                 ->display_as('description','Description')
                 ->display_as('users','Users')
                 ->display_as('navigations','Navigations')
                 ->display_as('privileges','Privileges');
        $crud->set_subject('User List');
        $crud->set_relation_n_n('users', 'cms_group_user', 'cms_user', 'group_id', 'user_id' , 'user_name');
        $crud->set_relation_n_n('navigations', 'cms_group_navigation', 'cms_navigation', 'group_id', 'navigation_id' , 'navigation_name');
        $crud->set_relation_n_n('privileges', 'cms_group_privilege', 'cms_privilege', 'group_id', 'privilege_id' , 'privilege_name');
        $crud->callback_before_delete(array($this,'before_delete_group'));

        //$crud->set_theme('datatables');
        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_group_management');
    }
    
    public function before_delete_group($post_array)
    {
        if($post_array['group_id']==1){
            return false;
        }
        return $post_array;
    } 
    
    public function navigation(){
        $crud = new grocery_CRUD();

        $crud->set_table('cms_navigation');
        $crud->columns('navigation_name', 'is_root', 'parent_id', 'title', 'description', 'url', 'authorization_id', 'groups');
        $crud->edit_fields('navigation_name', 'is_root', 'parent_id', 'title', 'description', 'url', 'authorization_id', 'groups');
        $crud->add_fields('navigation_name', 'is_root', 'parent_id', 'title', 'description', 'url', 'authorization_id', 'groups');
        $crud->change_field_type('is_root', 'true_false');
        
        $crud->display_as('navigation_name', 'Navigation Code')
                ->display_as('is_root', 'Is Root')
                ->display_as('parent_id', 'Parent')
                ->display_as('title', 'Title (What visitor see)')
                ->display_as('description', 'Description')
                ->display_as('url', 'URL (Where is it point to)')
                ->display_as('authorization_id', 'Authorization')
                ->display_as('groups', 'Groups');
       
        $crud->set_relation('parent_id', 'cms_navigation', 'navigation_name');
        $crud->set_relation('authorization_id', 'cms_authorization', 'authorization_name');
        
        $crud->set_relation_n_n('groups', 'cms_group_navigation', 'cms_group', 'navigation_id', 'group_id' , 'group_name');
        

        //$crud->set_theme('datatables');
        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_navigation_management');
    }  
    
    public function privilege(){
        $crud = new grocery_CRUD();

        $crud->set_table('cms_privilege');
        $crud->set_relation('authorization_id', 'cms_authorization', 'authorization_name', 'groups');
        
        $crud->set_relation_n_n('groups', 'cms_group_privilege', 'cms_group', 'privilege_id', 'group_id' , 'group_name');

        //$crud->set_theme('datatables');
        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_privilege_management');
    }
    
    public function widget(){
        $crud = new grocery_CRUD();

        $crud->set_table('cms_widget');
        $crud->columns('widget_name', 'title', 'active', 'description', 'url', 'authorization_id', 'groups');
        $crud->edit_fields('widget_name', 'title', 'active', 'description', 'url', 'authorization_id', 'groups');
        $crud->add_fields('widget_name', 'title', 'active', 'description', 'url', 'authorization_id', 'groups');
        $crud->change_field_type('active', 'true_false');
        
        $crud->display_as('widget_name', 'Widget Code')
                ->display_as('title', 'Title (What visitor see)')
                ->display_as('active', 'Active')
                ->display_as('description', 'Description')
                ->display_as('url', 'URL (Where is it point to)')
                ->display_as('authorization_id', 'Authorization')
                ->display_as('groups', 'Groups');
       
        $crud->set_relation('authorization_id', 'cms_authorization', 'authorization_name');
        
        $crud->set_relation_n_n('groups', 'cms_group_widget', 'cms_group', 'widget_id', 'group_id' , 'group_name');
        

        //$crud->set_theme('datatables');
        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_navigation_management');
    }
    
    public function module_list(){
        $data['modules'] = $this->get_module_list();
        $this->view('main/module_list',$data,'main_module_management');
    }
}

?>
