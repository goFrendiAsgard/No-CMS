<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class nordrassil extends CMS_Controller {
    public function index(){
    	$this->cms_guard_page('nordrassil_index');
    	$this->load->model('nordrassil/data/nds_model');
		$data['projects'] = $this->nds_model->get_all_project();
		
    	$data['content'] = $this->cms_submenu_screen('nordrassil_index');
        $this->view('nordrassil/nordrassil_index', $data, 'nordrassil_index');
    }
}