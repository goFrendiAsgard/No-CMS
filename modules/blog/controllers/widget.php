<?php
/**
 *
 * @author gofrendi
 */
class Widget extends CMS_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model($this->cms_module_path().'/blog_model');
	}
	
	public function newest($how_many=5){
		$data = array();
    	$data['articles'] = $this->blog_model->get_articles(0, $how_many, 
    			NULL, NULL);
		$this->view($this->cms_module_path().'/widget_newest', $data);
	}
}