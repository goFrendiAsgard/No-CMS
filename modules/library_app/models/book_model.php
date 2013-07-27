<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Book_Model
 *
 * @author No-CMS Module Generator
 */
class Book_Model extends  CMS_Model{

	public function get_data($keyword, $page=0){
		$limit = 10;
		$query = $this->db->select('book.id, book.isbn, book.title, book.author, book.publisher, book.description')
			->from($this->cms_complete_table_name('book').' as book')
			->like('book.isbn', $keyword)
			->or_like('book.title', $keyword)
			->or_like('book.author', $keyword)
			->or_like('book.publisher', $keyword)
			->or_like('book.description', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
		return $result;
	}

}