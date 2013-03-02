<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Browse_Town
 *
 * @author No-CMS Module Generator
 */

class Browse_Town extends CMS_Controller {

	public function index(){
	    $module_path = $this->cms_module_path();
	    $this->cms_guard_page(cms_well_name($module_path,'browse_town'));
		$data = array(
			'allow_navigate_backend' => $this->cms_allow_navigate(cms_well_name($module_path,'manage_town')),
			'backend_url' => site_url($this->cms_module_path().'/manage_town/index'),
		);
        $this->view($this->cms_module_path().'/browse_town_view',$data, cms_well_name($module_path,'browse_town'));
    }
    
    public function get_data(){
        $module_path = $this->cms_module_path();
        $this->cms_guard_page(cms_well_name($module_path,'browse_town'));
    	// only accept ajax request
    	if(!$this->input->is_ajax_request()) $this->cms_redirect();
    	// get page and keyword parameter
    	$keyword = $this->input->post('keyword');
    	$page = $this->input->post('page');
    	if(!$keyword) $keyword = '';
    	if(!$page) $page = 0;
    	// get data from model
    	$this->load->model('town_project/town_model');
    	$this->Town_Model = new Town_Model();
    	$result = $this->Town_Model->get_data($keyword, $page);
    	$data = array(
    		'result'=>$result,
    		'allow_navigate_backend' => $this->cms_allow_navigate(cms_well_name($module_path,'manage_town')),
			'backend_url' => site_url($this->cms_module_path().'/manage_town/index'),
    	);
    	$this->load->view($this->cms_module_path().'/browse_town_partial_view',$data);
	}
    
}