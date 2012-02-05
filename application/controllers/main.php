<?php
/**
 * The Main Controller of No-CMS
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
            if($this->cms_do_login($identity, $password)) redirect('main/index');
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
                if($this->cms_valid_activation_code($activation_code)){
                    $this->cms_forgot_password($activation_code, $password);
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
                if($this->cms_generate_activation_code($identity)) redirect('main/index');
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
            $this->cms_do_register($user_name, $email, $real_name, $password);
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
            $this->cms_do_change_profile($user_name, $email, $real_name, $password);
            redirect('main/index');
        }else{
            $data = array("user_name"=>$user_name,
                "email"=>$email,
                "real_name"=>$real_name);
            $this->view('main/change_profile',$data, 'main_change_profile');
        } 
    }
    
    public function logout(){
        $this->cms_do_logout();
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
        
        $crud->unset_texteditor('description');
        
        $crud->set_subject('Authorization List');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

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
        //The super admin user cannot be deleted
        //A user cannot 
    	if(($post_array['user_id']==1) || ($post_array['user_id'] == $this->cms_userid())){
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
        
        $crud->unset_texteditor('description');

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
        $crud->columns('navigation_name', 'parent_id', 'title', 'is_static', 'authorization_id', 'groups');
        $crud->edit_fields('navigation_name', 'is_root', 'parent_id', 'title', 'description', 'index', 'is_static', 'static_content', 'url', 'authorization_id', 'groups');
        $crud->add_fields('navigation_name', 'is_root', 'parent_id', 'title', 'description', 'index', 'is_static', 'static_content', 'url', 'authorization_id', 'groups');
        $crud->change_field_type('is_root', 'true_false');
        $crud->change_field_type('is_static', 'true_false');
        $crud->change_field_type('index', 'integer');
        
        $crud->display_as('navigation_name', 'Navigation Code')
        ->display_as('is_root', 'Is Root')
        ->display_as('parent_id', 'Parent')
        ->display_as('title', 'Title (What visitor see)')
        ->display_as('description', 'Description')
        ->display_as('url', 'URL (Where is it point to)')
        ->display_as('index', 'Order')
        ->display_as('is_static', 'Static')
        ->display_as('static_content', 'Static Content')
        ->display_as('authorization_id', 'Authorization')
        ->display_as('groups', 'Groups');
        
        $crud->order_by('parent_id, index', 'asc');
        
        $crud->unset_texteditor('description');
       
        $crud->set_relation('parent_id', 'cms_navigation', 'navigation_name');
        $crud->set_relation('authorization_id', 'cms_authorization', 'authorization_name');
        
        $crud->set_relation_n_n('groups', 'cms_group_navigation', 'cms_group', 'navigation_id', 'group_id' , 'group_name');
        
        $crud->callback_before_insert(array($this,'before_insert_navigation'));

        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_navigation_management');
    } 

    public function quicklink(){
    	$crud = new grocery_CRUD();
    	
    	$crud->set_table('cms_quicklink');
    	$crud->columns('navigation_id');
    	$crud->edit_fields('navigation_id', 'index');
    	$crud->add_fields('navigation_id', 'index');
    	$crud->change_field_type('index', 'integer');
    	
    	$crud->display_as('navigation_id', 'Navigation Name')
    		->display_as('index', 'Order');
    	
    	$crud->order_by('index', 'asc');
    	 
    	$crud->set_relation('navigation_id', 'cms_navigation', 'navigation_name');
    	
    	$crud->callback_before_insert(array($this,'before_insert_quicklink'));
    	
    	$output = $crud->render();
    	
    	$this->view('grocery_CRUD', $output, 'main_quicklink_management');
    }
    
    public function privilege(){
        $crud = new grocery_CRUD();

        $crud->set_table('cms_privilege');
        $crud->set_relation('authorization_id', 'cms_authorization', 'authorization_name', 'groups');
        
        $crud->set_relation_n_n('groups', 'cms_group_privilege', 'cms_group', 'privilege_id', 'group_id' , 'group_name');
        
        $crud->display_as('authorization_id', 'Authorization');
        
        $crud->unset_texteditor('description');
        
        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_privilege_management');
    }
    
    public function widget(){
        $crud = new grocery_CRUD();

        $crud->set_table('cms_widget');
        $crud->columns('widget_name', 'title', 'active', 'is_static', 'description', 'authorization_id', 'groups');
        $crud->edit_fields('widget_name', 'title', 'active', 'description', 'index', 'is_static', 'static_content', 'url', 'authorization_id', 'groups');
        $crud->add_fields('widget_name', 'title', 'active', 'description', 'index', 'is_static', 'static_content', 'url', 'authorization_id', 'groups');
        $crud->change_field_type('active', 'true_false');
        $crud->change_field_type('is_static', 'true_false');
        $crud->change_field_type('index', 'integer');
        
        $crud->display_as('widget_name', 'Widget Code')
                ->display_as('title', 'Title (What visitor see)')
                ->display_as('active', 'Active')
                ->display_as('description', 'Description')
                ->display_as('url', 'URL (Where is it point to)')
                ->display_as('index', 'Order')
                ->display_as('is_static', 'Static')
                ->display_as('static_content', 'Static Content')
                ->display_as('authorization_id', 'Authorization')
                ->display_as('groups', 'Groups');
        
        $crud->unset_texteditor('static_content');
        $crud->unset_texteditor('description');
       
        $crud->set_relation('authorization_id', 'cms_authorization', 'authorization_name');
        
        $crud->set_relation_n_n('groups', 'cms_group_widget', 'cms_group', 'widget_id', 'group_id' , 'group_name');
        
        $crud->callback_before_insert(array($this,'before_insert_widget'));
        
		$output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_widget_management');
    }
    
    public function before_insert_widget($post_array){
    	if(isset($slug)){
    		$whereSlug = "(slug = '".$post_array['slug']."')";
    	}else{
    		$whereSlug = "(slug IS NULL)";
    	}
    	$SQL = "SELECT max(`index`)+1 AS newIndex FROM `cms_widget` WHERE $whereSlug";
    	$query = $this->db->query($SQL);
    	$row = $query->row();
    	$index = $row->newIndex;
    	
    	if(!isset($index)) $index = 0;
    	
    	$post_array['index'] = $index;
    	
    	return $post_array;
    }
    
    public function before_insert_quicklink($post_array){
    	$SQL = "SELECT max(`index`)+1 AS newIndex FROM `cms_quicklink`";
    	$query = $this->db->query($SQL);
    	$row = $query->row();
    	$index = $row->newIndex;
    	
    	if(!isset($index)) $index = 0;
    	
    	$post_array['index'] = $index;
    	
    	return $post_array;
    }
    
    public function before_insert_navigation($post_array){
    	//get parent's navigation_id
    	$SQL = "SELECT navigation_id FROM cms_navigation WHERE navigation_id='".$post_array['parent_id']."'";
    	$query = $this->db->query($SQL);
    	$row = $query->row();
    	
    	$parent_id = isset($row->navigation_id)? $row->navigation_id: NULL;
    	
    	//index = max index+1
    	if(isset($parent_id)){
    		$whereParentId = "(parent_id = $parent_id)";
    	}else{
    		$whereParentId = "(parent_id IS NULL)";
    	}
    	$SQL = "SELECT max(`index`)+1 AS newIndex FROM `cms_navigation` WHERE $whereParentId";
    	$query = $this->db->query($SQL);
    	$row = $query->row();
    	$index = $row->newIndex;
    	if(!isset($index)) $index = 0;
    	
    	$post_array['index'] = $index;
    		
    	return $post_array;
    }
    
    public function config(){
        $crud = new grocery_CRUD();

        $crud->set_table('cms_config');
        $crud->columns('config_name', 'value', 'description');
        $crud->edit_fields('config_name', 'value', 'description');
        $crud->add_fields('config_name', 'value', 'description');
        
        $crud->display_as('config_name', 'Configuration Key')
                ->display_as('value', 'Configuration Value')
                ->display_as('description', 'Description');
        
        $crud->unset_texteditor('description');
        $crud->unset_texteditor('value');
        
		$output = $crud->render();

        $this->view('grocery_CRUD', $output, 'main_config_management');
    }
    
    public function module_management(){
        $data['modules'] = $this->cms_get_module_list();
        $this->view('main/module_management',$data,'main_module_management');
    }
    
    public function change_theme($theme = NULL){
        if(isset($theme)){
            $this->cms_set_config('site_theme', $theme);
            redirect('main/change_theme');
        }else{
            $data['themes'] = $this->cms_get_layout_list();
            $this->view('main/change_theme',$data,'main_change_theme');
        }
    }
    
    public function show_static_widget($id){
        if(isset($id)){
            $SQL = "SELECT static_content FROM cms_widget WHERE widget_id=".$id;
            $query = $this->db->query($SQL);
            foreach($query->result() as $row){
                $data['content'] = $row->static_content;
            }
            $this->view('main/static_page', $data);
        }else{
            echo "invalid widget";
        }
    }
    
    public function show_static_page($id){
        if(isset($id)){
            $navigation_name = "";
            $SQL = "SELECT navigation_name, static_content FROM cms_navigation WHERE navigation_id=".$id;
            $query = $this->db->query($SQL);
            foreach($query->result() as $row){
                $data['content'] = $row->static_content;
                $navigation_name = $row->navigation_name;
            }
            $this->view('main/static_page', $data, $navigation_name);
        }else{
            echo "invalid widget";
        }
    } 
    
}

?>
