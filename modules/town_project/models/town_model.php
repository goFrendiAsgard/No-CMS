<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Town_Model
 *
 * @author No-CMS Module Generator
 */
class Town_Model extends  CMS_Model{
	
	public function get_data($keyword, $page=0){
	    $module_path = $this->cms_module_path();
		$limit = 10;
		$query = $this->db->select('town.town_id, country.name as country_name, town.name')
			->from(cms_module_table_name($module_path, 'town').' as town')
			->join(cms_module_table_name($module_path, 'country').' as country ', 'town.country_id=country.country_id', 'left')
			->like('country.name', $keyword)
			->or_like('town.name', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
		return $result;		
	}
	
}