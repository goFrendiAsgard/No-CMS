<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author No-CMS Module Generator
 */
class Blog extends CMS_Front_Controller {

    protected $CONFIG_TEMPLATE_NAME = 'blog_article_record_template';
    protected $EDIT_TEMPLATE_PRIVILEGE_NAME = 'edit_article_record_template';
    protected $CONFIG_VIEW_PATH = 'Browse_article_template_config_view';

    protected $group_id_list = array();
    protected $group_name_list = array();

    public function __construct(){
        parent::__construct();
        $this->group_name_list = $this->cms_user_group();
        $this->group_id_list = $this->cms_user_group_id();
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

    public function index($article_url = NULL, $filter_category = NULL, $filter_archive = NULL, $filter_keyword = NULL){
        $module_path = $this->cms_module_path();
        $this->load->model($module_path.'/article_model');

        $article_url = $article_url == ''? NULL : $article_url;
        $filter_category = $this->input->get('category') == NULL?
            $filter_category : $this->input->get('category');
        $filter_archive  = $this->input->get('archive')  == NULL?
            $filter_archive : $this->input->get('archive');
        $filter_keyword  = $this->input->get('keyword')  == NULL?
            $filter_keyword : $this->input->get('keyword');
        $filter_category = $filter_category == ''? NULL : $filter_category;
        $filter_archive  = $filter_archive  == ''? NULL : $filter_archive;
        $filter_keyword  = $filter_keyword  == ''? NULL : $filter_keyword;


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
                    $filter_keyword,
                    0,
                    $filter_category,
                    $filter_archive
                );
        }

        $user_group = $this->cms_user_group();
        $data = array(
            'submenu_screen' => $this->cms_submenu_screen($this->n('index')),
            'allow_navigate_backend' => $this->cms_allow_navigate($this->n('manage_article')),
            'backend_url' => site_url($this->cms_module_path().'/manage_article/index'),
            'first_data'=> $first_data,
            'categories'=>$this->article_model->get_available_category(),
            'chosen_category' => $filter_category,
            'archive' => $filter_archive,
            'keyword' => $filter_keyword,
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
            'module_path' => $this->cms_module_path(),
            'user_id' => $this->cms_user_id(),
            'is_super_admin' => $this->cms_user_is_super_admin(),
            'is_blog_editor' => in_array('Blog Editor', $this->group_name_list),
            'is_blog_author' => in_array('Blog Author', $this->group_name_list),
            'is_blog_contributor' => in_array('Blog Contributor', $this->group_name_list),
            'form_url'=> $this->cms_module_path() == 'blog'?
                site_url($this->cms_module_path().'/index/'.$article_url.'/#comment-form') :
                site_url($this->cms_module_path().'/blog/index/'.$article_url.'/#comment-form'),
            'category_route_exists' => $this->cms_route_key_exists('blog/category/(:any)'),
            'can_publish' => in_array('Blog Editor', $user_group) || in_array('Blog Author', $user_group) || $this->cms_user_is_super_admin(),
            'have_edit_template_privilege' => $this->cms_have_privilege($this->n($this->EDIT_TEMPLATE_PRIVILEGE_NAME)),
        );

        $config = array();
        if(isset($article_url) && $article_url != ''){
            $article = $this->article_model->get_single_article($article_url);
            $data['article'] = $article;
            $config['title'] = $article['title'];
            $config['keyword'] = $article['keyword'];
            $config['description'] = $article['description'];
            $config['author'] = $article['author'];
            $config['type'] = 'article';
            $config['twitter_card'] = 'summary';
            // if article has several photos, take the first one as meta image
            if(count($article['photos'])>0){
                $photo = $article['photos'][0];
                $config['image'] = base_url('modules/'.$module_path.'/assets/uploads/'.$photo['url']);
            }else{ // if article doesn't have any photo, take it from article content
                preg_match('/<img.*?src="(.*?)"/', $article['content'], $matches);
                if(count($matches) == 2){
                    $image = $matches[1];
                    $image = $this->cms_parse_keyword($image);
                    if(strpos($image, 'http') !== 0){
                        $image = base_url($image);
                    }
                    $config['image'] = $image;
                }
            }

            $article_id = $article['id'];
            if(!isset($_SESSION['__blog_visited'])){
                $_SESSION['__blog_visited'] = array();
            }
            if(!in_array($article_id, $_SESSION['__blog_visited'])){
                $_SESSION['__blog_visited'][] = $article_id;
                // add visited
                $visited = $article['visited'];
                if($visited === NULL || $visited == ''){
                    $visited = 0;
                }
                $this->db->update($this->t('article'),
                    array('visited'=>$visited+1),
                    array('article_id'=>$article['id']));
            }
        }

        $this->view($this->cms_module_path().'/browse_article_view',$data,
            $this->n('index'), $config);
    }

    public function get_data($keyword = '', $page = 0, $category = '', $archive = ''){
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
        // get max slid image from configuration. The default is 6
        $blog_max_slide_image = $this->cms_get_config('blog_max_slide_image');
        if(!is_numeric($blog_max_slide_image) || $blog_max_slide_image < 0){
            $blog_max_slide_image = 6;
        }
        $data = array(
            'articles'=>$result,
            'blog_max_slide_image' => $blog_max_slide_image,
            'allow_navigate_backend' => $this->cms_allow_navigate($this->n('manage_article')),
            'backend_url' => site_url($this->cms_module_path().'/manage_article/index'),
            'is_super_admin' => $this->cms_user_is_super_admin(),
            'is_blog_editor' => in_array('Blog Editor', $this->group_name_list),
            'is_blog_author' => in_array('Blog Author', $this->group_name_list),
            'is_blog_contributor' => in_array('Blog Contributor', $this->group_name_list),
            'module_path' => $this->cms_module_path(),
            'user_id' => $this->cms_user_id(),
            'article_route_exists'=>$this->cms_route_key_exists('blog/(:any)\.html'),
            'category_route_exists' => $this->cms_route_key_exists('blog/category/(:any)'),
            'record_template'         => $this->cms_get_config($this->CONFIG_TEMPLATE_NAME, TRUE),
            'default_record_template' => $this->cms_get_module_config($this->CONFIG_TEMPLATE_NAME),
        );
        $config = array('only_content'=>TRUE);
        $this->view($this->cms_module_path().'/browse_article_partial_view',$data,
           $this->n('index'), $config);
    }

    public function quick_write(){
        if($this->cms_user_id() < 1){return NULL;}
        $title   = $this->input->post('title');
        $content = $this->input->post('content');
        $status  = $this->input->post('status');
        // all data must valid
        if($title == '' || $content == '' || !in_array($status, array('published', 'draft'))){
            return NULL;
        }
        $this->load->model('blog/article_model');
        // automatic data
        $date    = date('Y-m-d H:i:s');
        $url     = urlencode(url_title($this->cms_parse_keyword($title)));
        $count_url = $this->article_model->get_count_article_url($url);
        if($count_url>0){
            $index = $count_url;
            while($this->article_model->get_count_article_url($url.'_'.$index)>0){
                $index++;
            }
            $url .= '_'.$index;
        };
        $author_user_id = $this->cms_user_id();
        // insert article
        $this->db->insert($this->t('article'), array(
                'article_title' => $title,
                'content' => $content,
                'status' => $status,
                'date' => $date,
                'article_url' => $url,
                'status' => $status,
                'author_user_id' => $author_user_id,
            ));
    }

    public function export(){
        echo 'test';
    }

    public function import(){
        $module_path = $this->cms_module_path();
        $data = array('success' => TRUE, 'message'=> '');
        // get content
        if(isset($_FILES['file'])){
            $this->load->model($module_path.'/wp_exim');
            $content = file_get_contents($_FILES['file']['tmp_name']);
            $data = $this->wp_exim->import($content);
        }
        // show the content
        $this->view($module_path.'/import', $data, $this->n('import'));
    }
}
