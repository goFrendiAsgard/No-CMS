<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Layout extends CMS_Controller{
    
    protected $theme = 'neutral';
    
    public function __construct(){
        parent::__construct();
        $this->theme = $this->cms_get_config('site_theme');
    }
    
    private function update_static_content($widget_name, $content){
        $this->db->update(cms_table_name('main_widget'), array('static_content'=>$content), array('widget_name'=>$widget_name));
    }

    public function index(){
        // save the uploaded files
        if(isset($_FILES['site_logo'])){
            $site_logo = $_FILES['site_logo'];
            if(isset($site_logo['tmp_name']) && $site_logo['tmp_name'] != ''){
                move_uploaded_file($site_logo['tmp_name'], BASEPATH.'../assets/nocms/images/custom_logo/'.$site_logo['name']);
                $this->cms_set_config('site_logo', '{{ base_url }}assets/nocms/images/custom_logo/'.$site_logo['name']);
            }
        }
        if(isset($_FILES['site_favicon'])){
            $site_favicon = $_FILES['site_favicon'];
            if(isset($site_favicon['tmp_name']) && $site_favicon['tmp_name'] != ''){
                move_uploaded_file($site_favicon['tmp_name'], BASEPATH.'../assets/nocms/images/custom_favicon/'.$site_favicon['name']);
                $this->cms_set_config('site_favicon', '{{ base_url }}assets/nocms/images/custom_favicon/'.$site_favicon['name']);
            }
        }
        if(count($_POST)>0){
            // save the section widgets
            $this->update_static_content('section_top_fix', $this->input->post('section_top_fix'));
            $this->update_static_content('section_banner', $this->input->post('section_banner'));
            $this->update_static_content('section_left', $this->input->post('section_left'));
            $this->update_static_content('section_right', $this->input->post('section_right'));
            $this->update_static_content('section_bottom', $this->input->post('section_bottom'));
            // save configurations
            $this->cms_set_config('site_name', $this->input->post('site_name'));
            $this->cms_set_config('site_slogan', $this->input->post('site_slogan'));
            $this->cms_set_config('site_footer', $this->input->post('site_footer'));
            $this->cms_set_config('site_language', $this->input->post('site_language'));
            $this->cms_language($this->input->post('site_language'));
            
        }
        // redirection
        if(count($_POST)>0 || isset($_FILES['site_logo']) || isset($_FILES['site_favicon'])){
            redirect('main/layout/index');
        }
        // widgets
        $query = $this->db->select('widget_id, widget_name, static_content')->from(cms_table_name('main_widget'))->get();
        $widget_list = $query->result_array();
        $normal_widget_list = array();
        $section_widget_list = array();
        foreach($widget_list as $widget){
            if($widget['widget_id']<6){
                $section_widget_list[$widget['widget_name']] = $widget;
            }else{
                $normal_widget_list[] = $widget;
            }
        }
        // languages
        $language_list = $this->cms_language_list();
        // config
        $query = $this->db->select('config_name, value')->from(cms_table_name('main_config'))->get();
        $config_list = array();
        foreach($query->result_array() as $row){
            $config_list[$row['config_name']] = $row['value'];
        }      
        // send to the view
        $data['normal_widget_list'] = $normal_widget_list;
        $data['section_widget_list'] = $section_widget_list;
        $data['language_list'] = $language_list;
        $data['config_list'] = $config_list;
        $data['current_language'] = $this->cms_get_config('site_language', True);
        $this->view('layout_index', $data, 'main_layout');
    }
}
