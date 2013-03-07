<?php
/**
 *
 * @author gofrendi
 */
class Widget extends CMS_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model($this->cms_module_path().'/article_model');
	}

	public function newest($how_many=5){
		$data = array();
    	$data['articles'] = $this->article_model->get_articles(0, $how_many,
    			NULL, NULL);
		$this->view($this->cms_module_path().'/widget_newest', $data);
	}

    public function category(){
        $data = array();
        $data['categories'] = $this->article_model->get_available_category();
        $this->view($this->cms_module_path().'/widget_category', $data);
    }
}