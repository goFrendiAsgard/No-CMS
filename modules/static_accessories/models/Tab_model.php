<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tab_model extends CMS_Model{
    public function get($slug = NULL){
        if($slug != NULL){
            $this->db->like('slug', $slug);
        }else{
            $this->db->where('slug', '');
            $this->db->or_where('slug', NULL);
        }
        $query = $this->db->select('caption, content')
            ->from($this->t('tab_content'))
            ->get();
        return $query->result_array();
    }
}
