<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
//created by goFrendiAsgard, with no guarantee, but with best wishes :D

class CMS_Controller extends CI_Controller{
    
    private $is_mobile = false;
    
    public function __construct(){
        parent::__construct();
		
        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->helper('form');
        $this->load->library('user_agent');
        $this->load->library('session');
        $this->load->library('form_validation');
        /* ------------------ */	

        $this->load->library('grocery_CRUD');
        $this->load->library('template');        

        $this->is_mobile = $this->agent->is_mobile();
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : key,value   
     * @desc : if value specified, this will set CI session, else, it will return CI session  
     */
    private function set_userdata($key, $value = NULL){
        if (isset($value)){
            $this->session->set_userdata($key, $value);
        }
        return $this->session->userdata($key);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : key   
     * @desc : delete a CI session 
     */
    private function unset_userdata($key){
        $this->session->unset_userdata($key);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : username  
     * @desc : if username specified, this will set cms_username session, else, it will return cms_username  
     */
    protected function cms_username($username = NULL){
        return $this->set_userdata('cms_username', $username);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : userid
     * @desc : if userid specified, this will set cms_userid session, else, it will return cms_userid  
     */
    protected function cms_userid($userid = NULL){
        return $this->set_userdata('cms_userid', $userid);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : parent_id, max_menu_depth
     * @desc : return navigation child if parent_id specified, else it will return root navigation
     */
    private function cms_navigations($parent_id = NULL, $max_menu_depth = NULL){
        $user_name = $this->cms_username();    
        $user_id = $this->cms_userid(); 
        $not_login = !$user_name?"TRUE":"FALSE";
        $login = $user_name?"TRUE":"FALSE";
        $super_user = $user_id==1?"TRUE":"FALSE";
        
        //get max_menu_depth from configuration
        if(!isset($parent_id)){
            $max_menu_depth = $this->get_config('max_menu_depth');
        }
        
        if($max_menu_depth>0){
            $max_menu_depth--;            
        }else{
            return array();
        }
        
        $where_is_root = !isset($parent_id)?"is_root=1":"is_root=0 AND parent_id = '".addslashes($parent_id)."'";
        $query = $this->db->query(
                "SELECT navigation_id, navigation_name, title, description, url 
                FROM cms_navigation AS n WHERE
                    (
                        (authorization_id = 1) OR
                        (authorization_id = 2 AND $not_login) OR
                        (authorization_id = 3 AND $login) OR
                        (
                            (authorization_id = 4 AND $login) AND 
                            (
                                (SELECT COUNT(*) FROM cms_group_user AS gu WHERE gu.group_id=1 AND gu.user_id ='".addslashes($user_id)."')>0
                                    OR $super_user OR
                                (SELECT COUNT(*) FROM cms_group_navigation AS gn
                                    WHERE 
                                        gn.navigation_id=n.navigation_id AND
                                        gn.group_id IN 
                                            (SELECT group_id FROM cms_group_user WHERE user_id = '".addslashes($user_id)."')
                                )>0
                            )
                        )
                    ) AND $where_is_root"
                );
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                "navigation_name" => $row->navigation_name,
                "title" => $row->title,
                "description" => $row->description,
                "url" => $row->url,
                "child" => $this->cms_navigations($row->navigation_id, $max_menu_depth)
            );
        }
        return $result;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : parent_id, max_menu_depth
     * @desc : return navigation child if parent_id specified, else it will return root navigation
     */
    private function cms_widgets(){
        $user_name = $this->cms_username();    
        $user_id = $this->cms_userid(); 
        $not_login = !$user_name?"TRUE":"FALSE";
        $login = $user_name?"TRUE":"FALSE";
        $super_user = $user_id==1?"TRUE":"FALSE";
        
        $query = $this->db->query(
                "SELECT widget_id, widget_name, title, description, url 
                FROM cms_widget AS w WHERE
                    (                        
                        (authorization_id = 1) OR
                        (authorization_id = 2 AND $not_login) OR
                        (authorization_id = 3 AND $login) OR
                        (
                            (authorization_id = 4 AND $login) AND 
                            (
                                (SELECT COUNT(*) FROM cms_group_user AS gu WHERE gu.group_id=1 AND gu.user_id ='".addslashes($user_id)."')>0
                                    OR $super_user OR
                                (SELECT COUNT(*) FROM cms_group_widget AS gw
                                    WHERE 
                                        gw.widget_id=w.widget_id AND
                                        gw.group_id IN 
                                            (SELECT group_id FROM cms_group_user WHERE user_id = '".addslashes($user_id)."')
                                )>0
                            )
                        )
                    ) AND active=1"
                );
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                "widget_name" => $row->widget_name,
                "title" => $row->title,
                "description" => $row->description,
                "url" => $row->url
            );
        }
        return $result;
        
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : navigation_name
     * @desc : return parent of navigation_name's detail, only used for get_navigation_path
     */
    private function get_navigation_parent($navigation_name){
        if(!$navigation_name) return false;
        $query = $this->db->query(
                "SELECT navigation_id, navigation_name, title, description, url  
                    FROM cms_navigation 
                    WHERE navigation_id = (
                        SELECT parent_id FROM cms_navigation
                        WHERE navigation_name = '".addslashes($navigation_name)."'
                    )"
                );
        if($query->num_rows == 0) return false;
        else{
            foreach($query->result() as $row){
                return array(
                    "navigation_name" => $row->navigation_name,
                    "title" => $row->title,
                    "description" => $row->description,
                    "url" => $row->url
                );
            }
        }
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : navigation_name
     * @desc : return navigation detail, only used for get_navigation_path
     */
    private function get_navigation($navigation_name){
        if(!$navigation_name) return false;
        $query = $this->db->query(
                "SELECT navigation_id, navigation_name, title, description, url 
                    FROM cms_navigation 
                    WHERE navigation_name = '".addslashes($navigation_name)."'"
                );
        if($query->num_rows == 0) return false;
        else{
            foreach($query->result() as $row){
                return array(
                    "navigation_name" => $row->navigation_name,
                    "title" => $row->title,
                    "description" => $row->description,
                    "url" => $row->url
                );
            }
        }
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : navigation_name
     * @desc : return navigation path, used for layout
     */    
    private function get_navigation_path($navigation_name = NULL){
        if(!isset($navigation_name)) return array();
        $result = array($this->get_navigation($navigation_name));
        while($parent = $this->get_navigation_parent($navigation_name)){
            $result[] = $parent;
            $navigation_name = $parent["navigation_name"];
        }
        //result should be in reverse order
        for($i=0; $i<ceil(count($result)/2); $i++){
            $temp = $result[$i];
            $result[$i] = $result[count($result)-1-$i];
            $result[count($result)-1-$i] = $temp;            
        } 
        return $result;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @desc : return privileges of current user
     */
    private function cms_privileges(){
        $user_name = $this->cms_username();
        $user_id = $this->cms_userid(); 
        $not_login = !isset($user_name)?"TRUE":"FALSE";
        $login = isset($user_name)?"TRUE":"FALSE";
        $super_user = $user_id==1?"TRUE":"FALSE";
        
        $query = $this->db->query(
                "SELECT privilege_name, title, description 
                FROM cms_privilege AS p WHERE
                    (authorization_id = 1) OR
                    (authorization_id = 2 AND $not_login) OR
                    (authorization_id = 3 AND $login) OR
                    (
                        (authorization_id = 4 AND $login AND 
                        (
                            (SELECT COUNT(*) FROM cms_group_user AS gu WHERE gu.group_id=1 AND gu.user_id ='".addslashes($user_id)."')>0
                                OR $super_user OR
                            (SELECT COUNT(*) FROM cms_group_privilege AS gp
                                WHERE 
                                    gp.privilege_id=p.privilege_id AND
                                    gp.group_id IN 
                                        (SELECT group_id FROM cms_group_user WHERE user_id = '".addslashes($user_id)."')
                            )>0)
                        )
                    )"
                );
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                "privilege_name" => $row->privilege_name,
                "title" => $row->title,
                "description" => $row->description
            );
        }
        return $result;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : active
     * @desc : next call of $this->view will only load content. Will be used if you plan to use ajax
     */
    public function cms_only_content($active = NULL){
        return $this->set_userdata('cms_partial', $active);     
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : navigation, navigations
     * @desc : only used in allow_navigate
     */
    private function _allow_navigate($navigation, $navigations = NULL){
        if(!isset($navigations)) $navigations = $this->cms_navigations();
        for($i=0; $i<count($navigations); $i++){
            if($navigation == $navigations[$i]["navigation_name"] || 
                $this->_allow_navigate($navigation, $navigations[$i]["child"])) 
            return true;
        }
        return false;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : navigation
     * @desc : check if user authorized to navigate into a page specified in parameter
     */
    protected function allow_navigate($navigation){
        return $this->_allow_navigate($navigation);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : privilege
     * @desc : check if user have privilege specified in parameter
     */
    protected function have_privilege($privilege){
        $privileges = $this->cms_privileges();
        for($i=0; $i<count($privileges); $i++){
            if($privilege == $privileges[$i]["privilege_name"]) return true;
        }
        return false;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : identity, password
     * @desc : login
     */
    protected function do_login($identity, $password){
         $query = $this->db->query(
                "SELECT user_id, user_name FROM cms_user WHERE
                    (user_name = '".$identity."' OR email = '".$identity."') AND
                    password = '".md5($password)."' AND
                    active = TRUE"
                );
        foreach($query->result() as $row){
            $this->cms_username($row->user_name);
            $this->cms_userid($row->user_id);
            return true;
        }
        return false;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : 
     * @desc : logout
     */
    protected function do_logout(){
        return $this->unset_userdata('cms_username');
        return $this->unset_userdata('cms_userid');
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : user_name, email, real_name, password
     * @desc : register
     */
    protected function do_register($user_name, $email, $real_name, $password){
        $data = array(
            "user_name" => $user_name,
            "email" => $email,
            "real_name" => $real_name,
            "password" => md5($password),
            "active" =>TRUE
        );
        $this->db->insert('cms_user', $data); 
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : user_name, email, real_name, password
     * @desc : change profile
     */
    protected function do_change_profile($user_name, $email, $real_name, $password){
        $data = array(
            "user_name" => $user_name,
            "email" => $email,
            "real_name" => $real_name,
            "password" => md5($password),
            "active" =>1
        );
        $where = array(
           "user_name" => $user_name 
        );
        $this->db->update('cms_user', $data, $where); 
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : view_url, data, navigation_name, privilege_required
     * @desc : replace $this->load->view. This method will also load header, menu etc except there are cms_only_content call before
     */
    protected function view($view_url, $data = NULL, $navigation_name = NULL, $privilege_required = NULL){  
               
        //check allowance
        if(!isset($navigation_name) || $this->_allow_navigate($navigation_name)){
            if(!isset($privilege_required)){
                $allowed = true;
            }else if(count($privilege_required)>0){//privilege_required is array
                $allowed = true;
                foreach($privilege_required as $privilege){
                    $allowed = $allowed && $this->have_privilege($privilege);
                    if(!$allowed) break;
                }                
            }else{//privilege_required is string
                $allowed = $this->have_privilege($privilege_required);
            }
        }else{
            $allowed = false;
        }
        
        //if allowed then show, else don't
        if($allowed){
            if($this->cms_only_content() || (isset($_REQUEST['_as_widget']))){
                $this->load->view($view_url, $data);
                $this->cms_only_content(false);
            }else{
                //get configuration                
                $data_partial['site_name'] = $this->get_config('site_name');
                $data_partial['site_slogan'] = $this->get_config('site_slogan');
                $data_partial['site_footer'] = $this->get_config('site_footer');
                $data_partial['site_theme'] = $this->get_config('site_theme');
                
                //get navigations
                $this->load->library('CMS_layout');
                $navigations = $this->cms_navigations();
                $navigation_path = $this->get_navigation_path($navigation_name);
                $data_partial['navigations'] = $this->cms_layout->build_menu($navigations, $navigation_path);
                $data_partial['navigation_path'] = $this->cms_layout->build_menu_path($navigation_path);
                $data_partial['user_name'] = $this->cms_username();
                
                //determine theme from configuration
                $this->template->set_theme($data_partial['site_theme']); 
                
                //get widget
                $widget = $this->cms_widgets();
                $widget = $this->cms_layout->build_widget($widget);
                
                //set layout and partials
                if($this->is_mobile){
                    $this->template->set_layout('mobile');
                    $this->template->set_partial('header', 'layouts/partials/mobile/header.php', $data_partial);
                    $this->template->set_partial('navigation', 'layouts/partials/mobile/navigation.php', $data_partial);
                    $this->template->set_partial('footer', 'layouts/partials/mobile/footer.php', $data_partial);
                    $this->template->inject_partial('widget', $widget, $data_partial);
                }else{
                    $this->template->set_layout('desktop');
                    $this->template->set_partial('header', 'layouts/partials/desktop/header.php', $data_partial);
                    $this->template->set_partial('navigation', 'layouts/partials/desktop/navigation.php', $data_partial);
                    $this->template->set_partial('footer', 'layouts/partials/desktop/footer.php', $data_partial);
                    $this->template->inject_partial('widget', $widget, $data_partial);
                }
                
                $this->template->build($view_url, $data);
            }     
        }else{
            //if user not authorized, show 'page not found'
            //I think it would be better than 'permission dennied'
            show_404();
        }   
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : module_name
     * @desc : checked if module installed
     */
    protected function is_module_installed($module_name){
        $query = $this->db->query(
                "SELECT count(*) as reccount FROM cms_module WHERE module_name = '".addslashes($module_name)."'");
        foreach($query->result() as $row){
            if($row->reccount>0){
                return true;
            }
            else{
                return false;
            }
        }
        return false;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : 
     * @desc : get module list
     */
    protected function get_module_list(){
        $this->load->helper('directory');
        $directories = directory_map('modules',1);
        $module = array();
        foreach($directories as $directory){
            if(!is_dir('modules/'.$directory)) continue;
            
            //temporary module_name = directory_name
            //TODO : extract information from controller
            $module_name=$directory;
            
            $module[]=array(                    
                "path"=>$directory,
                "installed"=>$this->is_module_installed($module_name)
            );
        }
        return $module;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : identity 
     * @desc : generate activation code, 
     */
    protected function generate_activation_code($identity){
        $query = $this->db->query(
                "SELECT user_name, real_name, user_id, email FROM cms_user WHERE
                    (user_name = '".$identity."' OR email = '".$identity."') AND
                    active = TRUE"
                );
        foreach($query->result() as $row){
            $user_id = $row->user_id;
            $email = $row->email;
            $user_name = $row->user_name;
            $real_name = $row->real_name;
            $activation_code = random_string();
            
            //update, add activation_code
            $data = array("activation_code"=>md5($activation_code));
            $where = array("user_id"=>$user_id);
            $this->db->update('cms_user',$data,$where);
            $this->load->library('email');
            
            //send activation email to user
            $email_address = $this->get_config('cms_email_address');
            $email_name = $this->get_config('cms_email_name');
            $email_subject = $this->get_config('cms_email_forgot_subject');
            $email_message = $this->get_config('cms_email_forgot_message');
            $activation_link = base_url('index.php/main/forgot/'.$activation_code);
            
            $email_message = str_replace('@realname', $real_name, $email_message);
            $email_message = str_replace('@activation_link', $activation_link, $email_message);
            
            //send email to user
            $this->email->from($email_address, $email_name);
            $this->email->to($email); 
            $this->email->subject($email_subject);
            $this->email->message($email_message);	

            $this->email->send();

            echo $this->email->print_debugger();
            return true;
        }
        return false;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : activation_code, new_password
     * @desc : generate_activation_code
     */
    protected function forgot_password($activation_code, $new_password){
        $query = $this->db->query(
                "SELECT user_id FROM cms_user WHERE
                    (activation_code = '".md5($activation_code)."') AND
                    active = TRUE"
                );
        foreach($query->result() as $row){
            $user_id = $row->user_id;
            $data = array(
                "password"=>md5($new_password),
                "activation_code"=>NULL
                );
            $where = array("user_id"=>$user_id);
            $this->db->update('cms_user',$data,$where);
        }
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : activation_code 
     * @desc : valid_activation_code
     */
    protected function valid_activation_code($activation_code){
        $query = $this->db->query(
                "SELECT activation_code FROM cms_user WHERE
                    (activation_code = '".md5($activation_code)."') AND
                    (activation_code IS NOT NULL) AND
                    active = TRUE"
                );
        if($query->num_rows()>0) return true;
        else return false;
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : name, value
     * @desc : set config
     */
    protected function set_config($name, $value){
        $query = $this->db->query(
                "SELECT config_id FROM cms_config WHERE
                    config_name = '".  addslashes($name)."'"
                );
        if($query->num_rows()>0){
            $data = array("value"=>$value);
            $where = array("config_name"=>$name);
            $this->db->update("cms_config",$data,$where);
        }
        else{
            $data = array(
                "value"=>$value,
                "config_name"=>$name
            );
            $this->db->insert("cms_config",$data);
        }
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : name
     * @desc : unset config
     */
    protected function unset_config($name){
        $where = array("config_name"=>$name);
        $query = $this->db->delete("cms_config",$where);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : name
     * @desc : get config
     */
    protected function get_config($name){
        $query = $this->db->query(
                "SELECT `value` FROM cms_config WHERE
                    config_name = '".  addslashes($name)."'"
                );
        foreach($query->result() as $row){
            return $row->value;
        }
    }
}