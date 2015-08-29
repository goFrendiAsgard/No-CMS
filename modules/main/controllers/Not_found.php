<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Not_found extends CMS_Controller{
	public function index(){
		$this->view('not_found_index', NULL, 'main_404');
	}
}