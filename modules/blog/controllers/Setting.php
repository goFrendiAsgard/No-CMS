<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends CMS_Secure_Controller {

    public function index(){
        $config_key   = array('blog_moderation', 'blog_max_slide_image');
        $config_list  = array();
        $changed      = FALSE;
        // save
        if(count($_POST)>0){
            foreach($config_key as $key){
                $this->cms_set_config($key, $this->input->post($key));
            }
            $changed = TRUE;
        }
        // get
        foreach($config_key as $key){
            $config_list[$key] = $this->cms_get_config($key, TRUE);
        }
        // data
        $data['config_list']   = $config_list;
        $data['changed']       = $changed;
        $data['config_prefix'] = $this->n('');
        $this->view('setting_index', $data, $this->n('setting'));
    }

}
