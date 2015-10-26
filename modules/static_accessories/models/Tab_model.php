<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tab_model extends CMS_Model{
    public function get(){
        $query = $this->db->select('caption, content')
            ->from($this->t('tab_content'))
            ->get();
        return $query->result_array();
    }
}