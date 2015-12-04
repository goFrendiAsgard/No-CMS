<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Not_found extends CMS_Controller{
	public function index(){
		$this->output->set_status_header('404');
		$this->view('not_found_index', NULL, 'main_404');
	}
}
