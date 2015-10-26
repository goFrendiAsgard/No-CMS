<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of City_model
 *
 * @author No-CMS Module Generator
 */
class City_model extends  CMS_Model{

    public function get_data($keyword, $page=0){
        $limit = 10;
        $query = $this->db->select('city.city_id, country.name as country_name, city.name')
            ->from($this->t('city').' as city')
            ->join($this->t('country').' as country', 'city.country_id=country.country_id', 'left')
           ->like('country.name', $keyword)
           ->or_like('city.name', $keyword)
            ->limit($limit, $page*$limit)
            ->get();
        $result = $query->result();
        return $result;
    }

}