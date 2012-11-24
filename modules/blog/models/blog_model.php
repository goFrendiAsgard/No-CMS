<?php
class Blog_Model extends CMS_Model{
	
	private $separator = '';	
	public function __construct(){
		parent::__construct();
		$this->separator = '<div style="page-break-after: always;">'.PHP_EOL.'	<span style="display: none;">&nbsp;</span></div>';
	}
	
	public function get_count_article_url($article_url){
		$SQL = "SELECT article_id FROM blog_article WHERE article_url ='".addslashes($article_url)."'";
		$query = $this->db->query($SQL);
		return $query->num_rows();
	}
	
	public function get_article_url($article_id){
		$SQL = "
			SELECT
				article_id, article_url
			FROM blog_article
			WHERE article_id = $article_id";
		$query = $this->db->query($SQL);
		if($query->num_rows()>0){
			$row = $query->row();
			return $row->article_url;
		}else{
			return false;
		}		
	}
	
	
	public function get_available_category(){
		$result = array(''=>'No Category');
		$SQL = "SELECT category_name FROM blog_category";
		$query = $this->db->query($SQL);
		foreach($query->result() as $row){
			$result[$row->category_name] = $row->category_name;
		}
		return $result;
	}
	
	public function get_single_article($article_url){
		$where_article_url = isset($article_url)?
			"article_url = '".addslashes($article_url)."'":"TRUE";
		
		$SQL = "
			SELECT 
				article_id, article_title, article_url, content, date, allow_comment,
				real_name AS author
			FROM blog_article
			LEFT JOIN cms_user ON (cms_user.user_id = blog_article.author_user_id)
			WHERE $where_article_url";
		
		$query = $this->db->query($SQL);
		if($query->num_rows()>0){
			$row = $query->row();
			$contents = explode($this->separator, $row->content);
			$content = implode($this->separator, $contents);
			$result = array(
					"id" => $row->article_id,
					"title" => $row->article_title,
					"article_url" => $row->article_url,
					"content" => $this->cms_parse_keyword($content),
					"author" => $row->author,
					"date" => $row->date,
					"allow_comment" => $row->allow_comment,
					"comments" => $this->get_comments($row->article_id),
					"photos" => $this->get_photos($row->article_id)
			);
			return $result;
		}else{
			return false;
		}
	}
	
	public function get_articles($offset, $limit, $category, $search){
		$words = $search? explode(' ', $search) : array();
		
		$data = array();
		
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
		
		
		$SQL = "
			SELECT
				article_id, article_title, article_url, content, date, allow_comment, 
				real_name AS author
			FROM blog_article
			LEFT JOIN cms_user ON (cms_user.user_id = blog_article.author_user_id)
			WHERE
				$where_category AND
				$where_search			
		    ORDER BY date DESC, article_id DESC
			LIMIT $limit, $offset";
		
		$query = $this->db->query($SQL);
		foreach($query->result() as $row){			
			$contents = explode($this->separator, $row->content);
			$content = $contents[0];
		
			$data[] = array(
					"id" => $row->article_id,
					"title" => $row->article_title,
					"article_url" => $row->article_url,
					"content" => $this->cms_parse_keyword($content),
					"author" => $row->author,
					"date" => $row->date,
					"photos" => $this->get_photos($row->article_id)
			);
		}
		return $data;
	}
	
	public function add_comment($article_id, $name, $email, $website, $content){
		$SQL = "SELECT allow_comment FROM blog_article WHERE article_id = $article_id";
		$query = $this->db->query($SQL);
		$row = $query->row();
		if(isset($row->allow_comment) && ($row->allow_comment == 1)){
			$cms_user_id = $this->cms_user_id();
		
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
	}
	
	public function get_photos($article_id){
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
	
	public function get_comments($article_id){
		date_default_timezone_set('UTC');
		$search = array('<', '>');
		$replace = array('&lt;', '&gt;');
		
		$SQL = "SELECT comment_id, date, author_user_id, name, email, website, content
		FROM blog_comment
		WHERE article_id = '$article_id' ORDER BY `date` desc";
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
					"content" => str_replace($search, $replace, $row->content),
					"name" => $name,
					"website" => prep_url($row->website)
			);
			$data[] = $result;
		}
		return $data;
	}
}