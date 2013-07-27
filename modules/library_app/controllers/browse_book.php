<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Browse_Book
 *
 * @author No-CMS Module Generator
 */

class Browse_Book extends CMS_Priv_Strict_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->cms_complete_navigation_name('browse_book');
        $URL_MAP[$module_path.'/browse_book/get_data'] = $navigation_name;
        return $URL_MAP;
    }

	public function index(){
		$data = array(
			'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_book')),
			'backend_url' => site_url($this->cms_module_path().'/manage_book/index'),
			'module_path' => $this->cms_module_path(),
		);
        $this->view($this->cms_module_path().'/browse_book_view',$data,
            $this->cms_complete_navigation_name('browse_book'));
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
    	$this->load->model($this->cms_module_path().'/book_model');
    	$this->Book_Model = new Book_Model();
    	$result = $this->Book_Model->get_data($keyword, $page);
    	$data = array(
    		'result'=>$result,
    		'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_book')),
			'backend_url' => site_url($this->cms_module_path().'/manage_book/index'),
    	);
    	$config = array('only_content'=>TRUE);
    	$this->view($this->cms_module_path().'/browse_book_partial_view',$data,
    	   $this->cms_complete_navigation_name('browse_book'), $config);
	}

}