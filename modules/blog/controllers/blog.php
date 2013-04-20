<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Blog extends CMS_Priv_Strict_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $URL_MAP[$module_path] = $this->cms_complete_navigation_name('index');
        $URL_MAP[$module_path.'/blog'] = $this->cms_complete_navigation_name('index');
        return $URL_MAP;
    }

    private function __random_string($length=10){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $size = strlen( $chars );
        $str = '';
        for( $i = 0; $i < $length; $i++ ){
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }
        return $str;
    }

    public function index($article_url = NULL){
        $this->load->model($this->cms_module_path().'/article_model');

        // add comment
        // TODO: add scenario as in http://stackoverflow.com/a/10948623/755319
        // or call "Kay Nine" :)
        $article_id = $this->input->post('article_id', TRUE);
        $name = $this->input->post('xname', TRUE);
        $email = $this->input->post('xemail', TRUE);
        $website = $this->input->post('xwebsite', TRUE);
        $content = $this->input->post('xcontent', TRUE);
        $secret_code = $this->input->post('secret_code', TRUE);
        // the honey_pot, every fake input should be empty
        $honey_pot_pass = (strlen($this->input->post('name', ''))==0) &&
            (strlen($this->input->post('email', ''))==0) &&
            (strlen($this->input->post('website', ''))==0) &&
            (strlen($this->input->post('content', ''))==0);
        if($content && $honey_pot_pass){
            $previous_secret_code = $this->session->flashdata('secret_code');
            if($secret_code === $previous_secret_code){
                $this->article_model->add_comment($article_id, $name, $email, $website, $content);
            }
        }

        // generate new secret code
        $secret_code = $this->__random_string();
        $this->session->set_flashdata('secret_code', $secret_code);

        $data = array(
            'submenu_screen' => $this->cms_submenu_screen($this->cms_complete_navigation_name('index')),
            'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_article')),
            'backend_url' => site_url($this->cms_module_path().'/manage_article/index'),
            'categories'=>$this->article_model->get_available_category(),
            'chosen_category' => $this->input->get('category'),
            'keyword' => $this->input->get('keyword'),
            'module_path' => $this->cms_module_path(),
            'is_user_login' => $this->cms_user_id()>0,
            'secret_code' => $secret_code,
        );


        if(isset($article_url)){
            $data['article'] = $this->article_model->get_single_article($article_url);
        }
        $this->view($this->cms_module_path().'/browse_article_view',$data,
            $this->cms_complete_navigation_name('index'));
    }

    public function get_data(){
        // only accept ajax request
        if(!$this->input->is_ajax_request()) $this->cms_redirect();
        // get page and keyword parameter
        $keyword = $this->input->post('keyword');
        $page = $this->input->post('page');
        $category = $this->input->post('category');
        if(!$keyword) $keyword = '';
        if(!$page) $page = 0;
        if(!$category) $category = '';
        $limit = 5;
        // get data from model
        $this->load->model($this->cms_module_path().'/article_model');
        $this->Article_Model = new Article_Model();
        $result = $this->Article_Model->get_articles($page, $limit, $category, $keyword);;
        $data = array(
            'articles'=>$result,
            'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_article')),
            'backend_url' => site_url($this->cms_module_path().'/manage_article/index'),
        );
        $config = array('only_content'=>TRUE);
        $this->view($this->cms_module_path().'/browse_article_partial_view',$data,
           $this->cms_complete_navigation_name('index'), $config);
    }
}