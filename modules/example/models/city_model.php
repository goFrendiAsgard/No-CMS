<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of City_Model
 *
 * @author No-CMS Module Generator
 */
class City_Model extends  CMS_Model{

	public function get_data($keyword, $page=0){
		$limit = 10;
		$query = $this->db->select('city.city_id, country.name as country_name, city.name')
			->from($this->cms_complete_table_name('city').' as city')
			->join($this->cms_complete_table_name('country').' as country', 'city.country_id=country.country_id', 'left')
			->like('country.name', $keyword)
			->or_like('city.name', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
		return $result;
	}

}