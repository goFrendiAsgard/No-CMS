<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Barang_Model
 *
 * @author No-CMS Module Generator
 */
class Barang_Model extends  CMS_Model{

	public function get_data($keyword, $page=0){
		$limit = 10;
		$query = $this->db->select('barang.id_barang, barang.kode_barang, barang.nama')
			->from($this->cms_complete_table_name('barang').' as barang')
			->like('barang.kode_barang', $keyword)
			->or_like('barang.nama', $keyword)
			->limit($limit, $page*$limit)
			->get();
		$result = $query->result();
		return $result;
	}

}