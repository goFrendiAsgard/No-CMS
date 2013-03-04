<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Browse_Article
 *
 * @author No-CMS Module Generator
 */

class Browse_Article extends CMS_Priv_Strict_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->cms_complete_navigation_name('browse_article');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        return $URL_MAP;
    }

	public function index(){
		$data = array(
			'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_article')),
			'backend_url' => site_url($this->cms_module_path().'/manage_article/index'),
			'module_path' => $this->cms_module_path(),
		);
        $this->view($this->cms_module_path().'/browse_article_view',$data,
            $this->cms_complete_navigation_name('browse_article'));
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
    	$this->load->model($this->cms_module_path().'/article_model');
    	$this->Article_Model = new Article_Model();
    	$result = $this->Article_Model->get_data($keyword, $page);
    	$data = array(
    		'result'=>$result,
    		'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_article')),
			'backend_url' => site_url($this->cms_module_path().'/manage_article/index'),
    	);
    	$config = array('only_content'=>TRUE);
    	$this->view($this->cms_module_path().'/browse_article_partial_view',$data,
    	   $this->cms_complete_navigation_name('browse_article'), $config);
	}

}