<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Browse_portfolio
 *
 * @author No-CMS Module Generator
 */

class Browse_portfolio extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('browse_portfolio');
        $URL_MAP[$module_path.'/browse_portfolio'] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        $URL_MAP[$module_path.'/browse_portfolio/get_data'] = $navigation_name;
        $URL_MAP[$module_path.'/get_data'] = $navigation_name;
        return $URL_MAP;
    }

    public function index(){
        $module_path = $this->cms_module_path();
        $data = array(
            'allow_navigate_backend'    => $this->cms_allow_navigate($this->n('manage_portfolio')),
            'have_add_privilege'        => $this->cms_have_privilege($this->n('add_portfolio')),
            'have_edit_privilege'       => $this->cms_have_privilege($this->n('edit_portfolio')),
            'have_delete_privilege'     => $this->cms_have_privilege($this->n('delete_portfolio')),
            'backend_url'               => site_url($this->cms_module_path().'/manage_portfolio/index'),
            'module_path'               => $this->cms_module_path(),
            'first_data'                => Modules::run($module_path.'/browse_portfolio/get_data', 0, '')
        );
        $this->view($this->cms_module_path().'/Browse_portfolio_view',$data,
            $this->n('browse_portfolio'));
    }

    public function get_data($page = 0, $keyword = ''){
        $module_path = $this->cms_module_path();
        // get page and keyword parameter
        $post_keyword   = $this->input->post('keyword');
        $post_page      = $this->input->post('page');
        if($keyword == '' && $post_keyword != NULL) $keyword = $post_keyword;
        if($page == 0 && $post_page != NULL) $page = $post_page;
        // get data from model
        $this->load->model($this->cms_module_path().'/portfolio_model');
        $this->Portfolio_model = new Portfolio_model();
        $result = $this->Portfolio_model->get_data($keyword, $page);
        $data = array(
            'result'                 =>$result,
            'allow_navigate_backend' => $this->cms_allow_navigate($this->n('manage_portfolio')),
            'have_add_privilege'     => $this->cms_have_privilege($this->n('add_portfolio')),
            'have_edit_privilege'    => $this->cms_have_privilege($this->n('edit_portfolio')),
            'have_delete_privilege'  => $this->cms_have_privilege($this->n('delete_portfolio')),
            'backend_url'            => site_url($module_path.'/manage_portfolio/index'),
        );
        $config = array('only_content'=>TRUE);
        $this->view($module_path.'/Browse_portfolio_partial_view',$data,
           $this->n('browse_portfolio'), $config);
    }

}