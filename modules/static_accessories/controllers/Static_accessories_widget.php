<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Static_accessories_widget extends CMS_Controller {
    public function slide($slug = NULL){
        $this->load->model('slide_model');
        $data['slide_list'] = $this->slide_model->get($slug);
        $data['module_path'] = $this->cms_module_path();
        $data['show_edit'] = $this->cms_editing_mode() && $this->cms_allow_navigate($this->n('index'));

        $CONFIGURATION_LIST = array(
            'slide_height',
            'slide_parallax',
            'slide_hide_on_smallscreen',
            'slide_image_size',
            'slide_image_top',
        );
        foreach($CONFIGURATION_LIST as $configuration){
            $config_value = NULL;
            // get from per slug's config
            if($slug != NULL){
                $config_value = $this->cms_get_config('static_accessories_'.$configuration.'_'.$slug);
            }
            // get config
            if($config_value === NULL){
                $config_value = $this->cms_get_config('static_accessories_'.$configuration);
                // default value
                if($configuration == 'slide_height'){
                    if($config_value == NULL || trim($config_value) == ''){
                        $config_value = 400;
                    }
                    if(is_numeric($config_value)){
                        $config_value .= 'px';
                    }
                }
            }
            $data[$configuration] = $config_value;
        }
        $data['slug'] = $slug;
        $data['origin_uri_string'] = $this->cms_get_origin_uri_string();

        if(count($data['slide_list'])>0){
            $this->view($this->cms_module_path().'/widget_slide', $data);
        }
    }

    public function tab($slug = NULL){
        $this->load->model('tab_model');
        $data['tab_list'] = $this->tab_model->get($slug);
        if(count($data['tab_list'])>0){
            $this->view($this->cms_module_path().'/widget_tab', $data);
        }
    }

    public function visitor_counter(){
        $this->load->model('visitor_counter_model');
        $data['visitor_count'] = $this->visitor_counter_model->get();
        $this->view($this->cms_module_path().'/widget_visitor_counter', $data);
    }
}
