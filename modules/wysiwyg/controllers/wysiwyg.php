<?php

/**
 * WYSIWYG Editor
 *
 * @author gofrendi
 */
class Wysiwyg extends CMS_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_allow();
    }
    
    private function check_allow(){
        $result = $this->cms_allow_navigate("wysiwyg_index");
        if(!$result){
            redirect('main/login');
        }
        return $result;
    }

    public function index() {
        $data = NULL;
        $data['site_favicon'] = $this->cms_get_config('site_favicon');
        $data['site_logo'] = $this->cms_get_config('site_logo');
        $data['site_name'] = $this->cms_get_config('site_name');
        $data['site_slogan'] = $this->cms_get_config('site_slogan');
        $data['site_footer'] = $this->cms_get_config('site_footer');
        $data['site_theme'] = $this->cms_get_config('site_theme');
        $data['site_logo'] = $this->cms_get_config('site_logo');
        $data['site_favicon'] = $this->cms_get_config('site_favicon');
        
        $data['navigation_list'] =array();
        $this->load->model('wysiwyg/navigation_model');
        $navigation_list = $this->navigation_model->get_all_navigation();
        foreach($navigation_list as $navigation){
            $data['navigation_list'][$navigation["id"]] = 
                '{'.$navigation["name"].'} - '.$navigation["title"];
        }
        
        $data['language_list'] =array();
        $this->load->model('wysiwyg/language_model');
        $language_list = $this->language_model->get_language();
        foreach($language_list as $language){
            $data['language_list'][$language] = $language;
        }
        $data['language'] = $this->cms_get_config('site_language');
        
        $this->view('wysiwyg_index', $data, 'wysiwyg_index');
    }
    
    public function change_name(){
        $value = $this->input->post('value');
        $this->cms_set_config('site_name', $value);
    }
    public function change_slogan(){
        $value = $this->input->post('value');
        $this->cms_set_config('site_slogan', $value);
    }
    public function change_footer(){
        $value = $this->input->post('value');
        $this->cms_set_config('site_footer', $value);
    }
    public function change_language(){
        $value = $this->input->post('value');
        $this->cms_set_config('site_language', $value);
    }
    
    
    public function get_navigation(){
        $this->load->model('wysiwyg/navigation_model');
        $result = $this->navigation_model->get_navigation();
        echo json_encode($result);
    }
    
    public function toggle_navigation(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->toggle_navigation($id);
    }
    
    public function up_navigation(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->up_navigation($id);
    }
    
    public function down_navigation(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->down_navigation($id);
    }
    
    public function promote_navigation(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->promote_navigation($id);
    }
    
    public function demote_navigation(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/navigation_model');
        $this->navigation_model->demote_navigation($id);
    }
    
    public function get_quicklink(){
        $this->load->model('wysiwyg/quicklink_model');
        $result = $this->quicklink_model->get_quicklink();
        echo json_encode($result);
    }
    
    public function left_quicklink(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/quicklink_model');
        $this->quicklink_model->left_quicklink($id);
    }
    
    public function right_quicklink(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/quicklink_model');
        $this->quicklink_model->right_quicklink($id);
    }
    
    public function add_quicklink(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/quicklink_model');
        $this->quicklink_model->add_quicklink($id);
    }
    
    public function remove_quicklink(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/quicklink_model');
        $this->quicklink_model->remove_quicklink($id);
    }
    
    public function get_widget($slug){
        $slug = isset($slug)? $slug : $this->input->post('slug');
        $this->load->model('wysiwyg/widget_model');
        $result = $this->widget_model->get_widget($slug);
        echo json_encode($result);
    }
    
    public function up_widget(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/widget_model');
        $this->widget_model->up_widget($id);
    }
    
    public function down_widget(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/widget_model');
        $this->widget_model->down_widget($id);
    }
    
    public function toggle_widget(){
        $id = $this->input->post('id');
        $this->load->model('wysiwyg/widget_model');
        $this->widget_model->toggle_widget($id);
    }
    
    
}