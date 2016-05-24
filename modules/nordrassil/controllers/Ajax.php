<?php
class Ajax extends CMS_Controller{

    public function get_project_option($template_id = 0){
        if(!is_numeric($template_id)){
            $template_id = 0;
        }
        $query = $this->db->select('option_id, name')
            ->from($this->t('template_option'))
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
            FROM ".$this->t('template_option')."
            WHERE option_type = 'table' AND
            template_id IN (SELECT template_id FROM ".$this->t('project')." WHERE project_id=$project_id)";
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
            FROM ".$this->t('template_option')."
            WHERE option_type ='column' AND
            template_id IN (
                SELECT template_id
                FROM ".$this->t('project').", ".$this->t('table')."
                WHERE ".$this->t('project').".project_id =
                    ".$this->t('table').".project_id AND ".$this->t('table').".table_id=$table_id)";
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
            FROM ".$this->t('table')."
            WHERE project_id IN (
                SELECT project_id
                FROM ".$this->t('table')."
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
        log_message('error', $table_id);
        if(!is_numeric($table_id)){
            $table_id = 0;
        }
        $SQL = "SELECT column_id, name
            FROM ".$this->t('column')."
            WHERE
                (table_id = '$table_id') AND
                (
                    (
                        (role <> 'detail many to many') AND
                        (role <> 'detail one to many')
                    ) OR role IS NULL
                )";
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
