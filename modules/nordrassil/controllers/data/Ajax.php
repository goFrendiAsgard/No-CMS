<?php
class Ajax extends CMS_Controller{

    public function get_project_option($template_id = 0){
        if(!is_numeric($template_id)){
            $template_id = 0;
        }
        $query = $this->db->select('option_id, name')
            ->from($this->cms_complete_table_name('template_option'))
            ->where('option_type','project')
            ->where('template_id',$template_id)
            ->get();
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->option_id,
                'caption' => $row->name,
            );
        }
        $this->cms_show_json($result);
    }

    public function get_table_option($project_id = 0){
        if(!is_numeric($project_id)){
            $project_id = 0;
        }
        $SQL = "SELECT option_id, name
            FROM ".$this->cms_complete_table_name('template_option')."
            WHERE option_type = 'table' AND
            template_id IN (SELECT template_id FROM ".$this->cms_complete_table_name('project')." WHERE project_id=$project_id)";
        $query = $this->db->query($SQL);
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->option_id,
                'caption' => $row->name,
            );
        }
        $this->cms_show_json($result);
    }

    public function get_column_option($table_id = 0){
        if(!is_numeric($table_id)){
            $table_id = 0;
        }
        $SQL = "SELECT option_id, name
            FROM ".$this->cms_complete_table_name('template_option')."
            WHERE option_type ='column' AND
            template_id IN (
                SELECT template_id
                FROM ".$this->cms_complete_table_name('project').", ".$this->cms_complete_table_name('table')."
                WHERE ".$this->cms_complete_table_name('project').".project_id =
                    ".$this->cms_complete_table_name('table').".project_id AND ".$this->cms_complete_table_name('table').".table_id=$table_id)";
        $query = $this->db->query($SQL);
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->option_id,
                'caption' => $row->name,
            );
        }
        $this->cms_show_json($result);
    }

    public function get_table_sibling($table_id = 0){
        if(!is_numeric($table_id)){
            $table_id = 0;
        }
        $SQL = "SELECT table_id, name
            FROM ".$this->cms_complete_table_name('table')."
            WHERE project_id IN (
                SELECT project_id
                FROM ".$this->cms_complete_table_name('table')."
                WHERE table_id=$table_id)";
        $query = $this->db->query($SQL);
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->table_id,
                'caption' => $row->name,
            );
        }
        $this->cms_show_json($result);
    }

    public function get_column($table_id = 0){
        if(!is_numeric($table_id)){
            $table_id = 0;
        }
        $SQL = "SELECT column_id, name
            FROM ".$this->cms_complete_table_name('column')."
            WHERE
                (table_id = '$table_id') AND
                (role <> 'detail many to many') AND
                (role <> 'detail one to many')";
        $query = $this->db->query($SQL);
        $result = array();
        foreach($query->result() as $row){
            $result[] = array(
                'value' => $row->column_id,
                'caption' => $row->name,
            );
        }
        $this->cms_show_json($result);
    }



}
?>
