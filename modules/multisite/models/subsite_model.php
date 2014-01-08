<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Subsite_Model
 *
 * @author No-CMS Module Generator
 */
class Subsite_Model extends  CMS_Model{

	public function get_data($keyword, $page=0){
		$limit = 10;
		$query = $this->db->select('subsite.id, subsite.name, subsite.use_subdomain, subsite.logo, subsite.description, subsite.modules, subsite.themes')
			->from($this->cms_complete_table_name('subsite').' as subsite')
			->like('subsite.name', $keyword)
			->or_like('subsite.description', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
		return $result;
	}

	public function module_list($subsite=NULL){

	}

	public function theme_list($subsite=NULL){

	}

}