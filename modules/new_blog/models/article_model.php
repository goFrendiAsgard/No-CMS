<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Article_Model
 *
 * @author No-CMS Module Generator
 */
class Article_Model extends  CMS_Model{
	
	public function get_data($keyword, $page=0){
	    $module_path = $this->cms_module_path();
		$limit = 10;
		$query = $this->db->select('article.article_id, article.article_title, article.article_url, article.date, article.author_user_id, article.content, article.allow_comment')
			->from(cms_module_table_name($module_path, 'article').' as article')
			->like('article.article_title', $keyword)
			->or_like('article.article_url', $keyword)
			->or_like('article.date', $keyword)
			->or_like('article.author_user_id', $keyword)
			->or_like('article.content', $keyword)
			->or_like('article.allow_comment', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
		return $result;		
	}
	
}