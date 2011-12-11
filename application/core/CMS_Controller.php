<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * Description of cms_model
 *
 * @author gofrendi
 */

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
        
        $this->load->model('CMS_Model');

        $this->is_mobile = $this->agent->is_mobile();
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : key,value   
     * @desc : if value specified, this will set CI session, else, it will return CI session  
     */
    private function ci_session($key, $value = NULL){
        return $this->CMS_Model->ci_session($key, $value);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : key   
     * @desc : delete a CI session 
     */
    private function unset_ci_session($key){
        return $this->CMS_Model->unset_ci_session($key);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : username  
     * @desc : if username specified, this will set cms_username session, else, it will return cms_username  
     */
    protected function cms_username($username = NULL){
        return $this->CMS_Model->cms_username($username);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : userid
     * @desc : if userid specified, this will set cms_userid session, else, it will return cms_userid  
     */
    protected function cms_userid($userid = NULL){
        return $this->CMS_Model->cms_userid($userid);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : parent_id, max_menu_depth
     * @desc : return navigation child if parent_id specified, else it will return root navigation
     */
    private function cms_navigations($parent_id = NULL, $max_menu_depth = NULL){
        return $this->CMS_Model->cms_navigations($parent_id, $max_menu_depth);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : parent_id, max_menu_depth
     * @desc : return navigation child if parent_id specified, else it will return root navigation
     */
    private function cms_widgets(){
        return $this->CMS_Model->cms_widgets();
        
    }    
    /** 
     * @author : goFrendiAsgard
     * @param : navigation_name
     * @desc : return navigation path, used for layout
     */    
    private function get_navigation_path($navigation_name = NULL){
        return $this->CMS_Model->get_navigation_path($navigation_name);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @desc : return privileges of current user
     */
    private function cms_privileges(){
        return $this->CMS_Model->cms_privileges();
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : active
     * @desc : next call of $this->view will only load content. Will be used if you plan to use ajax
     */
    public function cms_only_content($active = NULL){
        return $this->CMS_Model->cms_only_content($active);    
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : navigation
     * @desc : check if user authorized to navigate into a page specified in parameter
     */
    protected function allow_navigate($navigation){
       return $this->CMS_Model->allow_navigate($navigation);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : privilege
     * @desc : check if user have privilege specified in parameter
     */
    protected function have_privilege($privilege){
        return $this->CMS_Model->have_privilege($privilege);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : identity, password
     * @desc : login
     */
    protected function do_login($identity, $password){
        return $this->CMS_Model->do_login($identity, $password);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : 
     * @desc : logout
     */
    protected function do_logout(){
        $this->CMS_Model->do_logout();
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : user_name, email, real_name, password
     * @desc : register
     */
    protected function do_register($user_name, $email, $real_name, $password){
        return $this->CMS_Model->do_register($user_name, $email, $real_name, $password);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : user_name, email, real_name, password
     * @desc : change profile
     */
    protected function do_change_profile($user_name, $email, $real_name, $password){
        return $this->CMS_Model->do_change_profile($user_name, $email, $real_name, $password); 
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : view_url, data, navigation_name, privilege_required
     * @desc : replace $this->load->view. This method will also load header, menu etc except there are cms_only_content call before
     */
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
        return $this->CMS_Model->is_module_installed($module_name);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : 
     * @desc : get module list
     */
    protected function get_module_list(){
        return $this->CMS_Model->get_module_list();
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : identity 
     * @desc : generate activation code, 
     */
    protected function generate_activation_code($identity){
        return $this->CMS_Model->generate_activation_code($identity);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : activation_code, new_password
     * @desc : generate_activation_code
     */
    protected function forgot_password($activation_code, $new_password){
        return $this->CMS_Model->forgot_password($activation_code, $new_password);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : from_address, from_name, to_address, subject, message
     * @desc : generate activation code, 
     */
    protected function send_email($from_address, $from_name, $to_address, $subject, $message){                    
        return $this->CMS_Model->send_email($from_address, $from_name, $to_address, $subject, $message);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : activation_code 
     * @desc : valid_activation_code
     */
    protected function valid_activation_code($activation_code){
        return $this->CMS_Model->valid_activation_code($activation_code);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : name, value, description
     * @desc : set config
     */
    protected function set_config($name, $value, $description = NULL){
        return $this->CMS_Model->set_config($name, $value, $description);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : name
     * @desc : unset config
     */
    protected function unset_config($name){
        return $this->CMS_Model->unset_config($name);
    }
    
    /** 
     * @author : goFrendiAsgard
     * @param : name
     * @desc : get config
     */
    protected function get_config($name){
        return $this->CMS_Model->get_config($name);
    }
}