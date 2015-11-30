<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Portfolio_model
 *
 * @author No-CMS Module Generator
 */
class Portfolio_model extends  CMS_Model{

    public function get_data($keyword, $page=0){
        $limit = 10;
        $query = $this->db->select('portfolio.id, portfolio.name, category.name as category_name, portfolio.url, portfolio.image, portfolio.description')
            ->from($this->t('portfolio').' as portfolio')
            ->join($this->t('category').' as category', 'portfolio.id_category=category.id', 'left')
           ->like('portfolio.name', $keyword)
           ->or_like('category.name', $keyword)
           ->or_like('portfolio.url', $keyword)
           ->or_like('portfolio.image', $keyword)
           ->or_like('portfolio.description', $keyword)
            ->limit($limit, $page*$limit)
            ->get();
        $result = $query->result();
        return $result;
    }

}