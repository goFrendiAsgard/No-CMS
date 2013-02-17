<?php
/**
 * Description of blog
 *
 * @author gofrendi
 */
class Blog extends CMS_Controller {
	
    public function index($article_url=NULL){
    	$this->cms_guard_page('blog_index');
    	$this->load->model($this->cms_module_path('gofrendi.blog').'/blog_model');
    	$data = array(
			'allow_navigate_backend' => $this->cms_allow_navigate('blog_article'),
			'backend_url' => site_url($this->cms_module_path('gofrendi.blog').'/article'),
			'categories'=>$this->blog_model->get_available_category(),
			'chosen_category' => $this->input->get('category'),
			'keyword' => $this->input->get('keyword'),
		);
		if(isset($article_url)){
			$data['article'] = $this->blog_model->get_single_article($article_url);
		}
        $this->view($this->cms_module_path('gofrendi.blog').'/blog_index',$data, 'blog_index');
    }

	public function get_article(){
		$this->cms_guard_page('blog_index');
		// get page and keyword parameter
    	$keyword = $this->input->post('keyword');
    	$page = $this->input->post('page');
		$category = $this->input->post('category');
    	if(!$keyword) $keyword = '';
    	if(!$page) $page = 0;
		if(!$category) $category = '';
		$limit = 5;
    	// get data from model
    	$this->load->model($this->cms_module_path('gofrendi.blog').'/blog_model');
    	$articles = $this->blog_model->get_articles($page, $limit, $category, $keyword);
    	$data = array(
    		'articles'=>$articles,
    		'allow_navigate_backend' => $this->cms_allow_navigate('blog_article'),
			'backend_url' => site_url($this->cms_module_path('gofrendi.blog').'/article'),
    	);
    	$this->view($this->cms_module_path().'/blog_show_article',$data,'blog_index',array('only_content'=>TRUE));
	}
    
    public function add_comment($article_id){
    	$this->cms_guard_page('blog_index');
    	$this->load->model($this->cms_module_path('gofrendi.blog').'/blog_model');
    	
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
    	$this->cms_guard_page('blog_management');
    	$data = array("submenu_screen"=>$this->cms_submenu_screen('blog_management'));
        $this->view($this->cms_module_path('gofrendi.blog')."/manage_view", $data, 'blog_management');
    }
    
    public function article(){
    	$this->cms_guard_page('blog_article');
        $crud = new grocery_CRUD();
		$crud->unset_jquery();

        $crud->set_table('blog_article');
        $crud->columns('article_title','Categories', 'author_user_id', 'date', 'allow_comment', 'photos', 'comments');
        $crud->edit_fields('article_title', 'article_url','content', 'Categories', 'date', 'author_user_id', 'allow_comment', 'photos','comments');
        $crud->add_fields('article_title', 'article_url','content', 'Categories', 'date', 'author_user_id', 'allow_comment');
        $crud->display_as('article_title','Title')
        		 ->display_as('article_url', 'URL')
                 ->display_as('content','Content')
                 ->display_as('date','Date Created')
                 ->display_as('author_user_id','Author')
				 ->display_as('photos','Photos')
				 ->display_as('comments','Comments');
        $crud->set_subject('Article');
		$crud->callback_column('photos',array($this,'article_callback_column_photos'));
		$crud->callback_edit_field('photos', array($this,'article_callback_edit_field_photos'));
		$crud->callback_column('comments',array($this,'article_callback_column_comments'));
		$crud->callback_edit_field('comments', array($this,'article_callback_edit_field_comments'));
        $crud->callback_before_insert(array($this,'before_insert_article'));
        $crud->set_relation_n_n('Categories', 'blog_category_article', 'blog_category', 'article_id', 'category_id' , 'category_name');
        $crud->set_relation('author_user_id', 'cms_user', 'real_name');
        
        $crud->change_field_type('author_user_id', 'hidden');
        $crud->change_field_type('date', 'hidden');
        $crud->change_field_type('article_url', 'hidden');
        $crud->change_field_type('allow_comment', 'true_false');
		$crud->set_language($this->cms_language());
        $output = $crud->render();

        $this->view($this->cms_module_path('gofrendi.blog').'/blog_article', $output, 'blog_article');        
    }
    
    public function photo($article_id=NULL){
    	$this->cms_guard_page('blog_photo');
    	$crud = new grocery_CRUD();
		$crud->unset_jquery();
    	
    	$crud->set_table('blog_photo');
    	if(isset($article_id) && intval($article_id)>0){
    		$crud->where('blog_photo.article_id', $article_id);
    		$crud->change_field_type('article_id', 'hidden');
    	}
        $crud->display_as('article_id','Article\'s title');
    	$crud->set_field_upload('url','modules/'.$this->cms_module_path().'/assets/uploads');
    	$crud->set_relation('article_id', 'blog_article', 'article_title');
    	
    	$crud->callback_before_insert(array($this,'before_insert_photo'));
		$crud->callback_column($this->unique_field_name('article_id'), array($this,'callback_column_article_id'));
		$crud->callback_after_upload(array($this,'after_upload_photo'));
    	$crud->set_language($this->cms_language());
    	$output = $crud->render();
		$output->article_id = $article_id;
    	
    	$this->view($this->cms_module_path('gofrendi.blog').'/blog_photo', $output, 'blog_photo');
    	
    }
    
    public function comment($article_id=NULL){
    	$this->cms_guard_page('blog_comment');
    	$crud = new grocery_CRUD();
		$crud->unset_jquery();
    	
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
		$crud->callback_column($this->unique_field_name('article_id'), array($this,'callback_column_article_id'));
    	$crud->set_language($this->cms_language());
    	$output = $crud->render();
		$output->article_id = $article_id;
    	
    	$this->view($this->cms_module_path('gofrendi.blog').'/blog_comment', $output, 'blog_comment');
    }

	public function article_callback_column_photos($value, $row){
		$this->load->model('blog_model');
		$result = '';
		$photos = $this->blog_model->get_photos($row->article_id);
		$result .= anchor(site_url($this->cms_module_path().'/photo/'.$row->article_id),'Manage Photos');
		if(count($photos)>0){
			$result .= br();
			foreach($photos as $photo){
				$result .= '<a target="_blank" class="photo_'.$row->article_id.'" href="'.base_url('modules/'.$this->cms_module_path().'/assets/uploads/'.$photo['url']).'">';
				$result .= '<img class="photo_thumbnail_grid" src="'.base_url('modules/'.$this->cms_module_path().'/assets/uploads/'.$photo['url']).'" />';
				$result .= '</a>';
			}
		}		
		return $result;
	}
	
	public function article_callback_edit_field_photos($value, $primary_key){
		$this->load->model('blog_model');
		$result = '';
		$photos = $this->blog_model->get_photos($primary_key);
		if(count($photos)>0){
			foreach($photos as $photo){
				$result .= '<a target="_blank" class="photo_'.$primary_key.'" href="'.base_url('modules/'.$this->cms_module_path().'/assets/uploads/'.$photo['url']).'">';
				$result .= '<img class="photo_thumbnail_grid" src="'.base_url('modules/'.$this->cms_module_path().'/assets/uploads/'.$photo['url']).'" />';
				$result .= '</a>';
			}
			$result .= br();	
		}		
		$result .= anchor(site_url($this->cms_module_path().'/photo/'.$primary_key),'Manage Photos');
		return $result;
	}

	public function article_callback_column_comments($value, $row){
		$this->load->model('blog_model');
		$comments = $this->blog_model->get_comments($row->article_id);
		$comment_count = count($comments);
		$result = '';
		if($comment_count==0){
			$result .= 'There is no comment yet';			
		}else if($comment_count==1){
			$result .= anchor(site_url($this->cms_module_path().'/comment/'.$row->article_id),'There is 1 comment');
		}else{
			$result .= anchor(site_url($this->cms_module_path().'/comment/'.$row->article_id),'There are '.$comment_count.' comments');
		}
		
		return $result;
	}
	
	public function article_callback_edit_field_comments($value, $primary_key){
		$this->load->model('blog_model');
		$comments = $this->blog_model->get_comments($primary_key);
		$comment_count = count($comments);
		$result = '';
		if($comment_count==0){
			$result .= 'There is no comment yet';			
		}else if($comment_count==1){
			$result .= anchor(site_url($this->cms_module_path().'/comment/'.$primary_key),'There is 1 comment');
		}else{
			$result .= anchor(site_url($this->cms_module_path().'/comment/'.$primary_key),'There are '.$comment_count.' comments');
		}
		
		return $result;
	}
	
	public function callback_column_article_id($value, $row){
		$this->load->model('blog_model');
		$article_url = $this->blog_model->get_article_url($row->article_id);
		$article = $this->blog_model->get_single_article($article_url);
		$title = $article['title'];
		return anchor(site_url($this->cms_module_path().'/article/edit/'.$row->article_id),$title);
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
    	    	
        $post_array['author_user_id'] = $this->cms_user_id();
        $post_array['date'] = date('Y-m-d H:i:s');
        $post_array['article_url'] = $url;
        return $post_array;
    }

	public function after_upload_photo($uploader_response,$field_info, $files_to_upload){
	    $this->load->library('image_moo');	 
	    //Is only one file uploaded so it ok to use it with $uploader_response[0].
	    $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name; 
		$thumbnail_name = $field_info->upload_path.'/thumb_'.$uploader_response[0]->name;
	 
	    $this->image_moo->load($file_uploaded)->resize(800,75)->save($thumbnail_name,true);
	 
	    return true;
	}
    
    public function category(){
    	$this->cms_guard_page('blog_category');
        $crud = new grocery_CRUD();
		$crud->unset_jquery();

        $crud->set_table('blog_category');
        $crud->columns('category_name','description');
        $crud->edit_fields('category_name','description');
        $crud->add_fields('category_name','description');
        $crud->display_as('category_name','Category')
                 ->display_as('description','Description');
        $crud->set_subject('Category');
        $crud->set_relation_n_n('Articles', 'blog_category_article', 'blog_article', 'category_id', 'article_id' , 'article_title');
        
        $crud->unset_texteditor('description');
		$crud->set_language($this->cms_language());
        $output = $crud->render();

        $this->view('grocery_CRUD', $output, 'blog_category');
        
    }
	
	private function unique_field_name($field_name) {
            return 's'.substr(md5($field_name),0,8); //This s is because is better for a string to begin with a letter and not with a number
    }
}