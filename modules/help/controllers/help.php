<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of help
 *
 * @author theModuleGenerator
 */
class help extends CMS_Controller {	
    public function index(){
    	$this->load->model($this->cms_module_path().'/help_model');
    	$data = array(
    			"toc"=>$this->help_model->group(),
    		);
        $this->view($this->cms_module_path().'/help_index', $data, 'help_index');
    } 
    
    public function group($underscored_name=NULL){
    	$this->load->model($this->cms_module_path().'/help_model');
    	$data = array(
    			"toc"=>$this->help_model->group($underscored_name),
    		);
        $this->view($this->cms_module_path().'/help_group', $data, 'help_index');    	
    }
    
    public function topic($underscored_title=NULL){
    	$this->load->model($this->cms_module_path().'/help_model');
    	$data = array(
    			"content"=>$this->help_model->topic_content($underscored_title),
    	);
    	$this->view($this->cms_module_path().'/help_topic', $data, 'help_index');    	
    }

    public function data_group(){
        $crud = new grocery_CRUD();
        
        // table name
        $crud->set_table("help_group");
        
        // displayed columns on list
        $crud->columns('name', 'content');
        // displayed columns on edit operation
        $crud->edit_fields('name', 'content');
        // displayed columns on add operation
        $crud->add_fields('name', 'content');
        
        // caption of each columns
        $crud->display_as('name','Name');
        
        // render
        $output = $crud->render();
        $this->view("grocery_CRUD", $output, "help_group");
    }

    public function data_topic(){
        $crud = new grocery_CRUD();
        
        // table name
        $crud->set_table("help_topic");
        
        // displayed columns on list
        $crud->columns('title', 'group_id', 'content');
        // displayed columns on edit operation
        $crud->edit_fields('title', 'group_id', 'content');
        // displayed columns on add operation
        $crud->add_fields('title', 'group_id', 'content');
        
        // caption of each columns
        $crud->display_as('group_id','Group')
            ->display_as('title','Title')
            ->display_as('content','Content');
        
        $crud->set_relation('group_id', 'help_group', 'name');
        
        // render
        $output = $crud->render();
        $this->view("grocery_CRUD", $output, "help_topic");
    }


    
}

?>
