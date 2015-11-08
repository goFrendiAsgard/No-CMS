<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CMS_Controller{
    public function users($keyword = ''){
        $query = $this->db->select('user_id, user_name')
            ->from(cms_table_name('main_user'))
            ->like('user_name', $keyword)
            ->limit(20)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->user_id,
                'caption' => $row->user_name,
            );
        }
        echo json_encode($result);
    }

    public function groups($keyword = ''){
        $query = $this->db->select('group_id, group_name')
            ->from(cms_table_name('main_group'))
            ->like('group_name', $keyword)
            ->limit(20)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->group_id,
                'caption' => $row->group_name,
            );
        }
        echo json_encode($result);
    }

    public function navigations($keyword = ''){
        $query = $this->db->select('navigation_id, navigation_name')
            ->from(cms_table_name('main_navigation'))
            ->like('navigation_name', $keyword)
            ->limit(20)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->navigation_id,
                'caption' => $row->navigation_name,
            );
        }
        echo json_encode($result);
    }

    public function privileges($keyword = ''){
        $query = $this->db->select('privilege_id, privilege_name')
            ->from(cms_table_name('main_privilege'))
            ->like('privilege_name', $keyword)
            ->limit(20)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->privilege_id,
                'caption' => $row->privilege_name,
            );
        }
        echo json_encode($result);
    }
}
