<?php
/**
 *
 * @author gofrendi
 */
class Blog_Widget extends CMS_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model($this->cms_module_path().'/article_model');
	}

	public function newest($how_many=5){
		$data = array();
    	$data['articles'] = $this->article_model->get_articles(0, $how_many,
    			NULL, NULL);
        $data['module_path'] = $this->cms_module_path();
		$this->view($this->cms_module_path().'/widget_newest', $data);
	}

    public function category(){
        $data = array();
        $data['categories'] = $this->article_model->get_available_category();
        $data['module_path'] = $this->cms_module_path();
        $this->view($this->cms_module_path().'/widget_category', $data);
    }

    public function archive(){
        $data = array();
        $data['archives'] = $this->article_model->get_archive();
        $data['module_path'] = $this->cms_module_path();
        $this->view($this->cms_module_path().'/widget_archive', $data);
    }
}