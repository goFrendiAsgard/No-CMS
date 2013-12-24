<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Browse_Subsite
 *
 * @author No-CMS Module Generator
 */

class Browse_Subsite extends CMS_Priv_Strict_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->cms_complete_navigation_name('browse_subsite');
        $URL_MAP[$module_path.'/browse_subsite/get_data'] = $navigation_name;
        return $URL_MAP;
    }

    public function index(){
        $data = array(
            'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_subsite')),
            'backend_url' => site_url($this->cms_module_path().'/manage_subsite/index'),
            'module_path' => $this->cms_module_path(),
        );
        $this->view($this->cms_module_path().'/browse_subsite_view',$data,
            $this->cms_complete_navigation_name('browse_subsite'));
    }

    public function get_data(){
        // only accept ajax request
        if(!$this->input->is_ajax_request()) $this->cms_redirect();
        // get page and keyword parameter
        $keyword = $this->input->post('keyword');
        $page = $this->input->post('page');
        if(!$keyword) $keyword = '';
        if(!$page) $page = 0;
        // get data from model
        $this->load->model($this->cms_module_path().'/subsite_model');
        $this->Subsite_Model = new Subsite_Model();
        $result = $this->Subsite_Model->get_data($keyword, $page);
        $data = array(
            'result'=>$result,
            'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_subsite')),
            'backend_url' => site_url($this->cms_module_path().'/manage_subsite/index'),
        );
        $config = array('only_content'=>TRUE);
        $this->view($this->cms_module_path().'/browse_subsite_partial_view',$data,
           $this->cms_complete_navigation_name('browse_subsite'), $config);
    }

}