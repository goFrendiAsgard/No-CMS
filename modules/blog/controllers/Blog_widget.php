<?php
/**
 *
 * @author gofrendi
 */
class Blog_widget extends CMS_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model($this->cms_module_path().'/article_model');
	}

	public function newest($how_many=5){
		$data = array();
    	$data['articles'] = $this->article_model->get_articles(0, $how_many,
    			NULL, NULL);
        $data['module_path'] = $this->cms_module_path();
        $data['article_route_exists'] = $this->cms_route_key_exists('blog/(:any)\.html');
		$this->view($this->cms_module_path().'/widget_newest', $data);
	}

    public function popular($how_many=5){
        $data = array();
        $articles = $this->article_model->get_articles(0, $how_many,
                NULL, NULL, NULL, FALSE, 'visited');
        $data['articles'] = array();
        foreach($articles as $article){
            if($article['visited']>0){
                $data['articles'][] = $article;
            }
        }
        $data['module_path'] = $this->cms_module_path();
        $data['article_route_exists'] = $this->cms_route_key_exists('blog/(:any)\.html');
        $this->view($this->cms_module_path().'/widget_popular', $data);
    }

    public function featured($how_many=5){
        $data = array();
        $data['articles'] = $this->article_model->get_articles(0, $how_many,
                NULL, NULL, NULL, TRUE);
        $data['module_path'] = $this->cms_module_path();
        $data['article_route_exists'] = $this->cms_route_key_exists('blog/(:any)\.html');
        $this->view($this->cms_module_path().'/widget_featured', $data);
    }

    public function category(){
        $data = array();
        $data['categories'] = $this->article_model->get_available_category();
        $data['module_path'] = $this->cms_module_path();
        $data['article_route_exists'] = $this->cms_route_key_exists('blog/(:any)\.html');
        $data['category_route_exists'] = $this->cms_route_key_exists('blog/category/(:any)');
        $this->view($this->cms_module_path().'/widget_category', $data);
    }

    public function archive(){
        $data = array();
        $data['archives'] = $this->article_model->get_archive();
        $data['module_path'] = $this->cms_module_path();
        $data['archive_route_exists'] = $this->cms_route_key_exists('blog/category/(:any)');
        $this->view($this->cms_module_path().'/widget_archive', $data);
    }
}