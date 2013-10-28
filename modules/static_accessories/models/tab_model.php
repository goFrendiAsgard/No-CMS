<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tab_Model extends CMS_Model{
    public function get(){
        $query = $this->db->select('caption, content')
            ->from($this->cms_complete_table_name('tab_content'))
            ->get();
        return $query->result_array();
    }
}