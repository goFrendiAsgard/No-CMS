<?php
/**
 * Description of blog
 *
 * @author gofrendi
 */
class Blog extends CMS_Controller {
    //put your code here
    public function index($article_id=NULL){
        
        $category = $this->input->post('category');
        $search = $this->input->post('search');
        $words = $search? explode(' ', $search) : array();
        
        $data = array();
        $data['category'] = $category? $category : '';
        $data['search'] = $search? $search : '';
        
        $data['available_category'] = array(''=>'No Category');
        $SQL = "SELECT category_name FROM blog_category";
        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
        	$data['available_category'][$row->category_name] = $row->category_name;
        }
        
        $data['article'] = array();
        
        $where_category = isset($category) && ($category!="")? "article_id IN 
            (SELECT article_id FROM blog_category_article, blog_category 
                WHERE blog_category.category_id = blog_category_article.category_id
                 AND category_name ='".addslashes($category)."'
            )" : "TRUE";
        
        if($search){
            $where_search = "(FALSE ";
            foreach($words as $word){
                $where_search .= " OR (article_title LIKE '%".addslashes($word)."%' OR content LIKE '%".addslashes($word)."%')";
            }
            $where_search .=")";
        }else{
            $where_search = "TRUE";
        }
        
        $where_article_id = isset($article_id)?"article_id=$article_id":"TRUE";
        
                
        $SQL = "SELECT article_id, article_title, content, date, allow_comment,
                    real_name AS author
                FROM blog_article
                LEFT JOIN cms_user ON (cms_user.user_id = blog_article.author_user_id)
                WHERE 
                    $where_category AND
                    $where_search AND $where_article_id";
        
        $query = $this->db->query($SQL);
        foreach($query->result() as $row){
            
            if(isset($article_id)){
                $contents = explode('<!-- pagebreak -->',$row->content);
                $content = implode('',$contents);
            }else{
                $contents = explode('<!-- pagebreak -->',$row->content);
                $content = $contents[0];
            }
            
            $result = array(
                "title" => $row->article_title,
                "content" => $content,
                "author" => $row->author,
                "date" => $row->date,
                "id" => $row->article_id,
                "allow_comment" => isset($article_id) && $row->allow_comment,
                "comments" => $this->get_comments($row->article_id),
                "photos" => $this->get_photos($row->article_id)
            );
            $data['article'][] = $result;
        }
        
        $data['view_readmore'] = !isset($article_id);
        
        $this->view("blog_view", $data, 'blog_index');
    }
    
    public function add_comment($article_id){
    	$SQL = "SELECT allow_comment FROM blog_article WHERE article_id = $article_id";
    	$query = $this->db->query($SQL);
    	$row = $query->row();
    	if(isset($row->allow_comment) && ($row->allow_comment == 1)){
    		$cms_user_id = $this->cms_userid();
    		$name = $this->input->post('name');
    		$email = $this->input->post('email');
    		$website = $this->input->post('website');
    		$content = $this->input->post('content');
    		
    		$data = array(
    			'article_id' => $article_id,
    			'name' => $name,
    			'email' => $email,
    			'website' => $website,
    			'content' => $content    			
    		);
    		if(isset($cms_user_id) && ($cms_user_id>0)){
    			$data['author_user_id'] = $cms_user_id;
    		}
    		$this->db->insert('blog_comment', $data);
    	}
    	redirect('blog/index/'.$article_id);
    }
    
    private function get_photos($article_id){
    	$SQL = "SELECT url FROM blog_photo WHERE article_id = '".$article_id."'";
    	$query = $this->db->query($SQL);
    	
    	$data = array();
    	foreach($query->result() as $row){
    		$result = array(
    			"url" => $row->url
    		);
    		$data[] = $result;
    	}    	
    	return $data;
    }
    
    private function get_comments($article_id){
    	$SQL = "SELECT comment_id, date, author_user_id, name, email, website, content
    		FROM blog_comment
    		WHERE article_id = '$article_id'";
    	$query = $this->db->query($SQL);
    	
    	$data = array();
    	foreach($query->result() as $row){
    		
    		if(isset($row->author_user_id)){
    			$SQL_user = "SELECT real_name FROM cms_user WHERE user_id = ".$row->author_user_id;
    			$query_user = $this->db->query($SQL_user);
    			$row_user = $query_user->row();
    			$name = $row_user->real_name;
    		}else{
    			$name = $row->name;
    		}
    		$this->load->helper('url');
    		$result = array(
    	                "date" => date('Y-m-d'),
    	                "content" => $row->content,
    	                "name" => $name,
    	                "website" => prep_url($row->website)
    		);
    		$data[] = $result;
    	}
    	return $data;
    }
    
    public function manage(){
        $this->view("manage_view", NULL, 'blog_management');
    }
    
    public function article(){
        $crud = new grocery_CRUD();

        $crud->set_table('blog_article');
        $crud->columns('article_title','content', 'Categories', 'author_user_id', 'date', 'allow_comment');
        $crud->edit_fields('article_title','content', 'Categories', 'date', 'author_user_id', 'allow_comment');
        $crud->add_fields('article_title','content', 'Categories', 'date', 'author_user_id', 'allow_comment');
        $crud->display_as('article_title','Title')
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
        $post_array['author_user_id'] = $this->cms_userid();
        $post_array['date'] = date('Y-m-d');
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
