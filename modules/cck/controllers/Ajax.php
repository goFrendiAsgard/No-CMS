<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CMS_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model($this->cms_module_path().'/cck_model');
    }

    public function default_per_record_html_pattern($id_entity){
        echo $this->cck_model->get_default_per_record_html_pattern($id_entity);
    }

    public function input_pattern_by_template($id_template){
        echo $this->cck_model->get_input_pattern_by_template($id_template);
    }

    public function view_pattern_by_template($id_template){
        echo $this->cck_model->get_view_pattern_by_template($id_template);
    }

}
