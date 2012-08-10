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
    
    public function group($url=NULL){
    	$this->load->model($this->cms_module_path().'/help_model');
    	$data = array(
    			"toc"=>$this->help_model->group($url),
    		);
        $this->view($this->cms_module_path().'/help_group', $data, 'help_index');    	
    }
    
    public function topic($url=NULL){
    	$this->load->model($this->cms_module_path().'/help_model');
    	$data = array(
    			"content"=>$this->help_model->topic_content($url),
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
        $crud->add_fields('name', `url`, 'content');
        
        // caption of each columns
        $crud->display_as('name','Name')
            ->display_as('content','Content');
        
        $crud->change_field_type('url', 'hidden');
        $crud->callback_before_insert(array($this,'before_insert_group'));
        
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
        $crud->add_fields('title', 'url', 'group_id', 'content');
        
        // caption of each columns
        $crud->display_as('group_id','Group')
            ->display_as('title','Title')
            ->display_as('content','Content');
        
        $crud->change_field_type('url', 'hidden');
        $crud->set_relation('group_id', 'help_group', 'name');
        
        $crud->callback_before_insert(array($this,'before_insert_topic'));
        
        // render
        $output = $crud->render();
        $this->view("grocery_CRUD", $output, "help_topic");
    }
    
    public function before_insert_group($post_array){
    	$this->load->helper('url');
    	$this->load->model($this->cms_module_path().'/help_model');
    	$url = url_title($post_array['name']);
    	$count_url = $this->help_model->count_group($url);
    	if($count_url>0){
    		$index = $count_url;
    		while($this->help_model->get_count_group($url.'_'.$index)>0){
    			$index++;
    		}
    		$url .= '_'.$index;
    	}
    	
    	$post_array['url'] = $url;    	
    	return $post_array;    	
    }
    
    public function before_insert_topic($post_array){
    	$this->load->helper('url');
    	$this->load->model($this->cms_module_path().'/help_model');
    	$url = url_title($post_array['title']);
    	$count_url = $this->help_model->count_topic($url);
    	if($count_url>0){
    		$index = $count_url;
    		while($this->help_model->get_count_topic($url.'_'.$index)>0){
    			$index++;
    		}
    		$url .= '_'.$index;
    	}
    	
    	$post_array['url'] = $url;    	
    	return $post_array; 
    }


    
}

?>
