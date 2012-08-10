<?php
/**
 * Description of blog
 *
 * @author gofrendi
 */
class Blog extends CMS_Controller {
	
	private $article_per_page;
	
	public function __construct(){
		parent::__construct();
		$this->article_per_page = 5;
	}
	
    public function index($article_url=NULL){
    	$this->load->model($this->cms_module_path().'/blog_model');
    	
        $category = $this->input->post('category');
        $search = $this->input->post('search');
        $page = $this->input->post('page');  
        if(!$page) $page = 0;      
        $single_article = isset($article_url);
        
        $data['only_show_article'] = $this->input->post('only_article');
        
        $data['category'] = $category;
        $data['search'] = $search;
        $data['available_category'] = $this->blog_model->get_available_category();
        $data['single_article'] = $single_article;
        if($single_article){
        	$data['article'] = $this->blog_model->get_single_article($article_url);
        }else{
        	$limit = $this->article_per_page;
        	$offset = $this->article_per_page*$page;
        	$data['articles'] = $this->blog_model->get_articles($limit, $offset, 
        			$category, $search);
        }
        
        $this->view("blog_view", $data, 'blog_index');
    }
    
    public function add_comment($article_id){
    	$this->load->model($this->cms_module_path().'/blog_model');
    	
    	$name = $this->input->post('name', TRUE);
    	$email = $this->input->post('email', TRUE);
    	$website = $this->input->post('website', TRUE);
    	$content = $this->input->post('content', TRUE);
    	if($content){
    		$this->blog_model->add_comment($article_id, $name, $email, $website, $content);
    	}
    	redirect($this->cms_module_path('gofrendi.blog').'/blog/index/'. $this->blog_model->get_article_url($article_id));
    }
    
    public function manage(){
        $this->view("manage_view", NULL, 'blog_management');
    }
    
    public function article(){
        $crud = new grocery_CRUD();

        $crud->set_table('blog_article');
        $crud->columns('article_title','content', 'Categories', 'author_user_id', 'date', 'allow_comment');
        $crud->edit_fields('article_title', 'article_url','content', 'Categories', 'date', 'author_user_id', 'allow_comment');
        $crud->add_fields('article_title', 'article_url','content', 'Categories', 'date', 'author_user_id', 'allow_comment');
        $crud->display_as('article_title','Title')
        		 ->display_as('article_url', 'URL')
                 ->display_as('content','Content')
                 ->display_as('date','Date Created')
                 ->display_as('author_user_id','Author');
        $crud->set_subject('Article');
        $crud->callback_before_insert(array($this,'before_insert_article'));
        $crud->callback_before_update(array($this,'before_insert_article'));
        $crud->set_relation_n_n('Categories', 'blog_category_article', 'blog_category', 'article_id', 'category_id' , 'category_name');
        $crud->set_relation('author_user_id', 'cms_user', 'real_name');
        
        $crud->change_field_type('author_user_id', 'hidden');
        $crud->change_field_type('date', 'hidden');
        $crud->change_field_type('article_url', 'hidden');
        $crud->change_field_type('allow_comment', 'true_false');
        
        $crud->add_action('Photos', base_url().'modules/blog/assets/images/photo.png', 'blog/photo');
        $crud->add_action('Comments', base_url().'modules/blog/assets/images/comment.png', 'blog/comment');
        
        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'blog_article');        
    }
    
    public function photo($article_id=NULL){
    	$crud = new grocery_CRUD();
    	
    	$crud->set_table('blog_photo');
    	if(isset($article_id) && intval($article_id)>0){
    		$crud->where('blog_photo.article_id', $article_id);
    		$crud->change_field_type('article_id', 'hidden');
    	}
        $crud->display_as('article_id','Article\'s title');
    	$crud->set_field_upload('url','assets/uploads/files');
    	$crud->set_relation('article_id', 'blog_article', 'article_title');
    	
    	$crud->callback_before_insert(array($this,'before_insert_photo'));
    	
    	$output = $crud->render();
    	
    	$this->view('grocery_CRUD', $output, 'blog_photo');
    	
    }
    
    public function comment($article_id=NULL){
    	$crud = new grocery_CRUD();
    	
    	$crud->set_table('blog_comment');
    	if(isset($article_id) && intval($article_id)>0){
    		$crud->where('blog_comment.article_id', $article_id);
    		$crud->change_field_type('article_id', 'hidden');
    	}
    	$crud->columns('article_id', 'content');
    	$crud->unset_add();
    	$crud->unset_edit();
    	
    	$crud->display_as('article_id', 'title');
    	
    	$crud->set_relation('article_id', 'blog_article', 'article_title');
    	
    	$crud->callback_before_insert(array($this,'before_insert_comment'));
    	
    	$output = $crud->render();
    	
    	$this->view('grocery_CRUD', $output, 'blog_comment');
    }
    
    public function before_insert_comment($post_array){
    	$post_array['article_id'] = $this->uri->segment(3);
    	return $post_array;
    }
    
    public function before_insert_photo($post_array){
    	$post_array['article_id'] = $this->uri->segment(3);
    	return $post_array;
    }
    
    public function before_insert_article($post_array){
    	$this->load->helper('url');
    	$this->load->model($this->cms_module_path().'/blog_model');
    	$url = url_title($post_array['article_title']);
    	$count_url = $this->blog_model->get_count_article_url($url);
    	if($count_url>0){
    		$index = $count_url;
    		while($this->blog_model->get_count_article_url($url.'_'.$index)>0){
    			$index++;
    		}
    		$url .= '_'.$index;
    	}
    	    	
        $post_array['author_user_id'] = $this->cms_userid();
        $post_array['date'] = date('Y-m-d H:i:s');
        $post_array['article_url'] = $url;
        return $post_array;
    }
    
    public function category(){
        $crud = new grocery_CRUD();

        $crud->set_table('blog_category');
        $crud->columns('category_name','description');
        $crud->edit_fields('category_name','description');
        $crud->add_fields('category_name','description');
        $crud->display_as('category_name','Category')
                 ->display_as('description','Description');
        $crud->set_subject('Category');
        $crud->set_relation_n_n('Articles', 'blog_category_article', 'blog_article', 'category_id', 'article_id' , 'article_title');
        
        $crud->unset_texteditor('description');

        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'blog_category');
        
    }
}

?>
