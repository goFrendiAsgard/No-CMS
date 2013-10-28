<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Slide_Model extends CMS_Model{
    public function get(){
        $query = $this->db->select('image_url, content')
            ->from($this->cms_complete_table_name('slide'))
            ->get();
        return $query->result_array();
    }
}
