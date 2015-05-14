<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Blog extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $URL_MAP[$module_path] = $this->cms_complete_navigation_name('index');
        $URL_MAP[$module_path.'/blog'] = $this->cms_complete_navigation_name('index');
        $URL_MAP[$module_path.'/get_data'] = $this->cms_complete_navigation_name('index');
        $URL_MAP[$module_path.'/blog/get_data'] = $this->cms_complete_navigation_name('index');
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
        $module_path = $this->cms_module_path();
        $this->load->model($module_path.'/article_model');

        // the honey_pot, every fake input should be empty
        $honey_pot_pass = (strlen($this->input->post('name', ''))==0) &&
            (strlen($this->input->post('email', ''))==0) &&
            (strlen($this->input->post('website', ''))==0) &&
            (strlen($this->input->post('content', ''))==0);
        if(!$honey_pot_pass){
            show_404();
            die();
        }

        // get previously generated secret code
        $previous_secret_code = $this->session->userdata('__blog_comment_secret_code');
        if($previous_secret_code === NULL){
            $previous_secret_code = $this->__random_string();
        }

        $success = NULL;
        $error_message = "";
        $article_id = $this->input->post('article_id', TRUE);
        $name = $this->input->post($previous_secret_code.'xname', TRUE);
        $email = $this->input->post($previous_secret_code.'xemail', TRUE);
        $website = $this->input->post($previous_secret_code.'xwebsite', TRUE);
        $content = $this->input->post($previous_secret_code.'xcontent', TRUE);
        $parent_comment_id = $this->input->post('parent_comment_id', TRUE);
        if($parent_comment_id == ''){
            $parent_comment_id = NULL;
        }
        if($content && $honey_pot_pass){
            if(!($this->cms_user_id()>0)){
                $valid_email = preg_match('/@.+\./', $email);
                if(!$valid_email){
                    $success = FALSE;
                    $error_message = "Invalid email";
                }
            }
            if($success !== FALSE){
                $success = TRUE;
                $this->article_model->add_comment($article_id, $name, $email, $website, $content, $parent_comment_id);
                $name = '';
                $email = '';
                $website = '';
                $content = '';
                $parent_comment_id = NULL;
            }
        }

        // generate new secret code
        $secret_code = $this->__random_string();
        $this->session->set_userdata('__blog_comment_secret_code', $secret_code);

        $first_data = NULL;
        if($article_url === NULL){
            $first_data = Modules::run($module_path.'/blog/get_data', 
                    $this->input->get('keyword'),
                    0,
                    $this->input->get('category'),
                    $this->input->get('archive')
                );
        }

        $data = array(
            'submenu_screen' => $this->cms_submenu_screen($this->cms_complete_navigation_name('index')),
            'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_article')),
            'backend_url' => site_url($this->cms_module_path().'/manage_article/index'),
            'first_data'=> $first_data,
            'categories'=>$this->article_model->get_available_category(),
            'chosen_category' => $this->input->get('category'),
            'archive' => $this->input->get('archive'),
            'keyword' => $this->input->get('keyword'),
            'module_path' => $this->cms_module_path(),
            'is_user_login' => $this->cms_user_id()>0,
            'secret_code' => $secret_code,
            "success" => $success,
            "error_message" => $error_message,
            "name" => $name,
            "email" => $email,
            "website" => $website,
            "content" => $content,
            "parent_comment_id" => $parent_comment_id,
            'is_super_admin' => $this->cms_user_id() == 1 || in_array(1, $this->cms_user_group_id()),
            'module_path' => $this->cms_module_path(),
            'user_id' => $this->cms_user_id(),
            'form_url'=> $this->cms_module_path() == 'blog'?
                site_url($this->cms_module_path().'/index/'.$article_url.'/#comment-form') :
                site_url($this->cms_module_path().'/blog/index/'.$article_url.'/#comment-form'),
        );

        $config = array();
        if(isset($article_url)){
            $article = $this->article_model->get_single_article($article_url);
            $data['article'] = $article;
            $config['title'] = $article['title'];
            $config['keyword'] = $article['keyword'];
            $config['description'] = $article['description'];
            $config['author'] = $article['author'];
            // add visited
            $query = $this->db->select('visited')
                ->from($this->cms_complete_table_name('article'))
                ->where('article_id', $article['id'])
                ->get();            
            $row = $query->row();
            $visited = $row->visited;
            if($visited === NULL || $visited == ''){
                $visited = 0;
            }
            $this->db->update($this->cms_complete_table_name('article'),
                array('visited'=>$visited+1),
                array('article_id'=>$article['id']));
        }

        $this->view($this->cms_module_path().'/browse_article_view',$data,
            $this->cms_complete_navigation_name('index'), $config);
    }

    public function get_data($keyword = '', $page = 0, $category = '', $archive = ''){
        // only accept ajax request
        //if(!$first && !$this->input->is_ajax_request()) $this->cms_redirect();
        // get page and keyword parameter
        $post_keyword = $this->input->post('keyword');
        $post_page = $this->input->post('page');
        $post_category = $this->input->post('category');
        $post_archive = $this->input->post('archive');
        if($keyword == '' && $post_keyword !== NULL) $keyword = $post_keyword;
        if($page == 0 && $post_page !== NULL) $page = $post_page;
        if($category == '' && $post_category !== NULL) $category = $post_category;
        if($archive == '' && $post_archive !== NULL) $archive = $post_archive;
        $limit = 5;
        // get data from model
        $this->load->model($this->cms_module_path().'/article_model');
        $result = $this->article_model->get_articles($page, $limit, $category, $archive, $keyword);
        $data = array(
            'articles'=>$result,
            'allow_navigate_backend' => $this->cms_allow_navigate($this->cms_complete_navigation_name('manage_article')),
            'backend_url' => site_url($this->cms_module_path().'/manage_article/index'),
            'is_super_admin' => $this->cms_user_id() == 1 || in_array(1, $this->cms_user_group_id()),
            'module_path' => $this->cms_module_path(),
            'user_id' => $this->cms_user_id(),
        );
        $config = array('only_content'=>TRUE);
        $this->view($this->cms_module_path().'/browse_article_partial_view',$data,
           $this->cms_complete_navigation_name('index'), $config);
    }
}