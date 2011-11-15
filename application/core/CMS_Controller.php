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
    
    private function set_userdata($key, $value = NULL){
        if (isset($value)){
            $this->session->set_userdata($key, $value);
        }
        return $this->session->userdata($key);              
    }
    
    private function unset_userdata($key){
        $this->session->unset_userdata($key);
    }
    
    protected function cms_username($username = NULL){
        return $this->set_userdata('cms_username', $username);
    }
    
    protected function cms_userid($username = NULL){
        return $this->set_userdata('cms_userid', $username);
    }
    
    private function cms_navigations($parent_id = NULL){
        $user_name = $this->cms_username();    
        $user_id = $this->cms_userid(); 
        $not_login = !$user_name?"TRUE":"FALSE";
        $login = $user_name?"TRUE":"FALSE";
        
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
                                OR
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
                "child" => $this->cms_navigations($row->navigation_id)
            );
        }
        return $result;
    }
    
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
    
    private function cms_privileges(){
        $user_name = $this->cms_username();
        $user_id = $this->cms_userid(); 
        $not_login = !isset($user_name)?"TRUE":"FALSE";
        $login = isset($user_name)?"TRUE":"FALSE";
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
                            OR
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
    
    public function cms_partial($active = NULL){
        return $this->set_userdata('cms_partial', $active);     
    }
    
    private function allow_navigate($navigation, $navigations = NULL){
        if(!isset($navigations)) $navigations = $this->cms_navigations();
        for($i=0; $i<count($navigations); $i++){
            if($navigation == $navigations[$i]["navigation_name"] || 
                $this->allow_navigate($navigation, $navigations[$i]["child"])) 
            return true;
        }
        return false;
    }
    
    private function have_privilege($privilege){
        $privileges = $this->cms_privileges();
        for($i=0; $i<count($privileges); $i++){
            if($privileges == $privilege) return true;
        }
        return false;
    }
    
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
    
    protected function do_logout(){
        return $this->unset_userdata('cms_username');
        return $this->unset_userdata('cms_userid');
    }
    
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
    
    protected function view($view_url, $data = NULL, $navigation_name = NULL, $privilege_required = NULL){  
                
        //check allowance
        if(!isset($navigation_name) || $this->allow_navigate($navigation_name)){
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
            if($this->cms_partial()){
                $this->load->view($view_url, $data);
            }else{
                //get configuration
                $this->config->load('cms', true);
                $data_partial = $this->config->config['cms'];
                
                //get navigations
                $this->load->library('CMS_layout');
                $navigations = $this->cms_navigations();
                $navigation_path = $this->get_navigation_path($navigation_name);
                $data_partial['navigations'] = $this->cms_layout->build_menu($navigations, $navigation_path);
                $data_partial['navigation_path'] = $this->cms_layout->build_menu_path($navigation_path);
                $data_partial['user_name'] = $this->cms_username();
                
                //determine theme from configuration
                $this->template->set_theme($data_partial['site_theme']); 
                
                //set layout and partials
                if($this->is_mobile){
                    $this->template->set_layout('mobile');
                    $this->template->set_partial('header', 'layouts/partials/mobile/header.php', $data_partial);
                    $this->template->set_partial('navigation', 'layouts/partials/mobile/navigation.php', $data_partial);
                    $this->template->set_partial('footer', 'layouts/partials/mobile/footer.php', $data_partial);
                }else{
                    $this->template->set_layout('desktop');
                    $this->template->set_partial('header', 'layouts/partials/desktop/header.php', $data_partial);
                    $this->template->set_partial('navigation', 'layouts/partials/desktop/navigation.php', $data_partial);
                    $this->template->set_partial('footer', 'layouts/partials/desktop/footer.php', $data_partial);
                }
                
                $this->template->build($view_url, $data);
            }     
        }else{
            //if user not authorized, show 'page not found'
            //I think it would be better than 'permission dennied'
            show_404();
        }   
    }
}