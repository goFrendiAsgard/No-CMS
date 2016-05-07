<?php if (!defined('BASEPATH')) { exit('No direct script access allowed');}

// A CMS Controller that don't do autoupdate, profiler enabled, default navigation = main_index, default layout = minimal

class CMS_Test_Controller extends CMS_Controller{
    protected $__cms_base_model_name = 'no_cms_model'; // don't do autoupdate

    public function __construct(){
        parent::__construct();
        // get developer address
        $developer_addr = '';
        $t_config = $this->cms_complete_main_site_table_name('main_config', '');
        $row_config = $this->cms_get_record($t_config, 'config_name', 'site_developer_addr');
        if($row_config != NULL){
            $developer_addr = $row_config->value;
        }
        // if developer address is match to remote address, then enable cms profiler, otherwise show 404
        if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == $developer_addr || preg_match('/'.$developer_addr.'/si', $_SERVER['REMOTE_ADDR'])){
            $this->output->enable_cms_profiler(TRUE);
        }else{
            show_404();
        }
    }

    protected function view($view_url='CMS_View', $data = NULL, $navigation_name = NULL, $config = NULL, $return_as_string = FALSE){
        // prepare parameters
        if (is_bool($navigation_name) && count($config) == 0) {
            $return_as_string = $navigation_name;
            $navigation_name = null;
            $config = null;
        } else if (is_bool($config)) {
            $return_as_string = $config;
            $config = null;
        }
        if (!isset($return_as_string)) {
            $return_as_string = false;
        }
        if (!isset($config)) {
            $config = array();
        }
        // adjust navigation (default to main_index)
        $navigation_name = $navigation_name == NULL? 'main_index' : $navigation_name;
        // adjust config (adjust config, default layout to minimal)
        if(!array_key_exists('layout', $config)){
            $config['layout'] = 'minimal';
        }
        // set output data
        if(!isset($data)){
            $data = array();
        }
        if(!array_key_exists('_content', $data)){
            $data['_content'] = '';
        }
        $this->output->set_cms_data($data);
        // return
        return parent::view($view_url, $data, $navigation_name, $config, $return_as_string);
    }
}
