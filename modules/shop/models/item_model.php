<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Item_Model
 *
 * @author No-CMS Module Generator
 */
class Item_Model extends  CMS_Model{

	public function get_data($keyword, $page=0){
		$limit = 10;
		$query = $this->db->select('item.id, item.name, item.price, item.discount, item.photo, item.description, item.availability')
			->from($this->cms_complete_table_name('item').' as item')
			->like('item.name', $keyword)
			->or_like('item.price', $keyword)
			->or_like('item.discount', $keyword)
			->or_like('item.photo', $keyword)
			->or_like('item.description', $keyword)
			->or_like('item.availability', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
		return $result;
	}

}