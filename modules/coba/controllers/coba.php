<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coba extends CMS_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->cms_partial(false);
	}
	
	private function _example_output($output = null)
	{		
		//$this->load->view('example.php',$output);	
		$this->view('example.php', $output);		
	}
	
	public function index(){	    	    
	    $this->view('coba');
	}
	
	public function authorization(){
	    $crud = new grocery_CRUD();
	    
	    //real table name
		$crud->set_table('cms_authorization');
		//real columns name
		$crud->columns('authorization_code','title','description');
		//column alias
		$crud->display_as('authorization_code','Code')
			 ->display_as('title','Name')
			 ->display_as('description','Description');
		//title
		$crud->set_subject('Authorization List');
		
		//without add
		$crud->unset_add();
		//without delete
		$crud->unset_delete();
		//without edit
		$crud->unset_edit();
		
		$output = $crud->render();
		
		$this->_example_output($output);
	}
	
	public function user(){
	    $crud = new grocery_CRUD();

		$crud->set_table('cms_user');
		
		$output = $crud->render();
		
		$this->_example_output($output);
	}
	
	public function group(){
	    $crud = new grocery_CRUD();

		$crud->set_table('cms_group');
		
		$output = $crud->render();
		
		$this->_example_output($output);
	}
	
}
