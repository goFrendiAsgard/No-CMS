<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');
        if(file_exists(APPPATH.'config/main')){
            redirect('');
        }else{
		    $this->load->view('welcome_message');
        }
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
