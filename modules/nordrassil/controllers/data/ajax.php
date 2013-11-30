<?php
class Ajax extends CMS_Controller{

	// get restricted project options
	public function get_restricted_project_option($template_id=0){
        if(!is_numeric($template_id)){
            $template_id = 0;
        }
		$query = $this->db->select('option_id')
			->from($this->cms_complete_table_name('template_option'))
			->where('option_type !=','project')
			->or_where('template_id !=',$template_id)
			->get();
		$option_id = array();
		foreach($query->result() as $row){
			$option_id[] = $row->option_id;
		}
		$this->cms_show_json($option_id);
	}

	// get restricted table options
	public function get_restricted_table_option($project_id=0){
        if(!is_numeric($project_id)){
            $project_id = 0;
        }
		$SQL = "SELECT option_id
			FROM ".$this->cms_complete_table_name('template_option')."
			WHERE option_type <>'table' OR
			template_id NOT IN (SELECT template_id FROM ".$this->cms_complete_table_name('project')." WHERE project_id=$project_id)";
		$query = $this->db->query($SQL);
		$option_id = array();
		foreach($query->result() as $row){
			$option_id[] = $row->option_id;
		}
		$this->cms_show_json($option_id);
	}

	// get restricted column options
	public function get_restricted_column_option($table_id=0){
        if(!is_numeric($table_id)){
            $table_id = 0;
        }
		$SQL = "SELECT option_id
			FROM ".$this->cms_complete_table_name('template_option')."
			WHERE option_type <>'column' OR
			template_id NOT IN (
				SELECT template_id
				FROM ".$this->cms_complete_table_name('project').", ".$this->cms_complete_table_name('table')."
				WHERE ".$this->cms_complete_table_name('project').".project_id =
				    ".$this->cms_complete_table_name('table').".project_id AND ".$this->cms_complete_table_name('table').".table_id=$table_id)";
		$query = $this->db->query($SQL);
		$option_id = array();
		foreach($query->result() as $row){
			$option_id[] = $row->option_id;
		}
		$this->cms_show_json($option_id);
	}

	// get restricted table sibling
	public function get_restricted_table_sibling($table_id=0){
        if(!is_numeric($table_id)){
            $table_id = 0;
        }
		$SQL = "SELECT table_id
			FROM ".$this->cms_complete_table_name('table')."
			WHERE project_id NOT IN (
				SELECT project_id
				FROM ".$this->cms_complete_table_name('table')."
				WHERE table_id=$table_id)";
		$query = $this->db->query($SQL);
		$table_id = array();
		foreach($query->result() as $row){
			$table_id[] = $row->table_id;
		}
		$this->cms_show_json($table_id);
	}

	// get restricted table sibling
	public function get_restricted_column($table_id=0){
        if(!is_numeric($table_id)){
            $table_id = 0;
        }
		$SQL = "SELECT column_id
			FROM ".$this->cms_complete_table_name('column')."
			WHERE
				(table_id<>$table_id) OR
				(role='detail many to many') OR
				(role='detail one to many')";
		$query = $this->db->query($SQL);
		$column_id = array();
		foreach($query->result() as $row){
			$column_id[] = $row->column_id;
		}
		$this->cms_show_json($column_id);
	}


}
?>