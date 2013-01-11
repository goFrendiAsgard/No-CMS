<?php

/**
 * WYSIWYG Editor
 *
 * @author gofrendi
 */
class Wysiwyg extends CMS_Controller {

    public function __construct() {
        parent::__construct();        
    }
    
    private function check_allow(){
        $result = $this->cms_allow_navigate("wysiwyg_index");
        if(!$result){
            redirect('main/login');
        }
        return $result;
    }
    
    public function index(){
    	$this->view($this->cms_module_path().'/wysiwyg_index', NULL, 'wysiwyg_index');
    }

    public function main() {
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
        $this->load->model($this->cms_module_path().'/navigation_model');
        $navigation_list = $this->navigation_model->get_all_navigation();
        foreach($navigation_list as $navigation){
            $data['navigation_list'][$navigation["id"]] = 
                '{'.$navigation["name"].'} - '.$navigation["title"];
        }
        
        $data['language_list'] =array();
        $this->load->model($this->cms_module_path().'/language_model');
        $language_list = $this->language_model->get_language();
        foreach($language_list as $language){
            $data['language_list'][$language] = $language;
        }
        $data['language'] = $this->cms_get_config('site_language');
        
        $this->view($this->cms_module_path().'/wysiwyg_main', $data, 'wysiwyg_index');
    }
    
    public function change_name(){
        $this->check_allow();
        $value = $this->input->post('value');
        $this->cms_set_config('site_name', $value);
    }
    public function change_slogan(){
        $this->check_allow();
        $value = $this->input->post('value');
        $this->cms_set_config('site_slogan', $value);
    }
    public function change_footer(){
        $this->check_allow();
        $value = $this->input->post('value');
        $this->cms_set_config('site_footer', $value);
    }
    public function change_language(){
        $this->check_allow();
        $value = $this->input->post('value');
        $this->cms_set_config('site_language', $value);
		$this->cms_language($value);
    }
    
    
    public function get_navigation(){
        $this->check_allow();
        $this->load->model($this->cms_module_path().'/navigation_model');
        $result = $this->navigation_model->get_navigation();
        $this->cms_show_json($result);
    }
    
    public function toggle_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/navigation_model');
        $this->navigation_model->toggle_navigation($id);
    }
    
    public function up_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/navigation_model');
        $this->navigation_model->up_navigation($id);
    }
    
    public function down_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/navigation_model');
        $this->navigation_model->down_navigation($id);
    }
    
    public function promote_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/navigation_model');
        $this->navigation_model->promote_navigation($id);
    }
    
    public function demote_navigation(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/navigation_model');
        $this->navigation_model->demote_navigation($id);
    }
    
    public function get_quicklink(){
        $this->check_allow();
        $this->load->model($this->cms_module_path().'/quicklink_model');
        $result = $this->quicklink_model->get_quicklink();
        $this->cms_show_json($result);
    }
    
    public function left_quicklink(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/quicklink_model');
        $this->quicklink_model->left_quicklink($id);
    }
    
    public function right_quicklink(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/quicklink_model');
        $this->quicklink_model->right_quicklink($id);
    }
    
    public function add_quicklink(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/quicklink_model');
        $this->quicklink_model->add_quicklink($id);
    }
    
    public function remove_quicklink(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/quicklink_model');
        $this->quicklink_model->remove_quicklink($id);
    }
    
    public function get_widget($slug){
        $this->check_allow();
        $slug = isset($slug)? $slug : $this->input->post('slug');
        $this->load->model($this->cms_module_path().'/widget_model');
        $result = $this->widget_model->get_widget($slug);
        $this->cms_show_json($result);
    }
    
    public function up_widget(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/widget_model');
        $this->widget_model->up_widget($id);
    }
    
    public function down_widget(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/widget_model');
        $this->widget_model->down_widget($id);
    }
    
    public function toggle_widget(){
        $this->check_allow();
        $id = $this->input->post('id');
        $this->load->model($this->cms_module_path().'/widget_model');
        $this->widget_model->toggle_widget($id);
    }
    
    public function upload_favicon(){
        $this->check_allow();
        
        include(BASEPATH.'../modules/'.$this->cms_module_path().'/fileuploader_library/php.php');
        
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array('jpeg', 'jpg', 'gif', 'png', 'ico');
        
        // max file size in bytes
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $sizeLimit = $max_upload * 1024 * 1024;

        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);        
        $result = $uploader->handleUpload(BASEPATH.'../assets/nocms/images/custom_favicon/');
        
        // to pass data through iframe you will need to encode all html tags
        $this->cms_show_html(json_encode($result), ENT_NOQUOTES);
    }
    
    public function upload_logo(){
        $this->check_allow();
        
        include(BASEPATH.'../modules/'.$this->cms_module_path().'/fileuploader_library/php.php');
        
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array('jpeg', 'jpg', 'gif', 'png', 'ico');
        
        // max file size in bytes
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $sizeLimit = $max_upload * 1024 * 1024;

        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);        
        $result = $uploader->handleUpload(BASEPATH.'../assets/nocms/images/custom_logo/');
        
        // to pass data through iframe you will need to encode all html tags
        $this->cms_show_html(json_encode($result), ENT_NOQUOTES);
    }
    
    public function change_favicon(){
    	$file_name = $this->input->post('file_name');
    	if($file_name){
    		$this->cms_set_config('site_favicon', '{{ base_url }}assets/nocms/images/custom_favicon/'.$file_name);
    	}
    	$this->get_favicon();
    }
    
    public function change_logo(){
    	$file_name = $this->input->post('file_name');
    	if($file_name){
    		$this->cms_set_config('site_logo', '{{ base_url }}assets/nocms/images/custom_logo/'.$file_name);
    	}
    	$this->get_logo();
    }
    
    public function get_favicon(){
        $this->check_allow();
        $result['value'] = $this->cms_get_config('site_favicon');
        $this->cms_show_json($result);
    }
    
    public function get_logo(){
        $this->check_allow();
        $result['value'] = $this->cms_get_config('site_logo');
        $this->cms_show_json($result);
    }
    
    
}