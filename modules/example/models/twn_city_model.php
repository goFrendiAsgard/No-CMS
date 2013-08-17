<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Twn_City_Model
 *
 * @author No-CMS Module Generator
 */
class Twn_City_Model extends  CMS_Model{

	public function get_data($keyword, $page=0){
		$limit = 10;
		$query = $this->db->select('twn_city.city_id, twn_country.name as twn_country_name, twn_city.name')
			->from($this->cms_complete_table_name('twn_city').' as twn_city')
			->join($this->cms_complete_table_name('twn_country').' as twn_country', 'twn_city.country_id=twn_country.country_id', 'left')
			->like('twn_country.name', $keyword)
			->or_like('twn_city.name', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
		return $result;
	}

}