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
}