<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slide_model extends CMS_Model{
    public function get(){
        $query = $this->db->select('image_url, content')
            ->from($this->t('slide'))
            ->get();
        return $query->result_array();
    }
}
