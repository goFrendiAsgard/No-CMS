<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help_Model extends CMS_Model{
	
	public function group($underscored_name = NULL){
		$this->load->helper('inflector');
		// wheres
		$where_name = isset($underscored_name)?
		"(name LIKE '".addslashes(humanize($underscored_name))."')" : "TRUE";
		
		$SQL = "SELECT id, name, content FROM help_group WHERE $where_name ORDER BY id";
		$query = $this->db->query($SQL);
		$data = array();
		foreach($query->result() as $row){
			$data[] = array(
					"name" => $row->name,
					"underscored_name" => underscore($row->name),
					"topics" => $this->topic(NULL, $row->id),
					"content" => $row->content,
				);
		}
		return $data;
	}
	
	public function topic($keyword = NULL, $group_id = NULL){
		$this->load->helper('inflector');
		// wheres
		$where_keyword = isset($keyword)?
			"(title LIKE '%".addslashes($keyword).
			"%' OR content LIKE '%".addslashes($keyword)."%')" : "TRUE"; 
		$where_group_id = isset($group_id)?
			"(group_id = '".addslashes($group_id)."')" : "TRUE";
		
		$SQL = "SELECT title
			FROM help_topic 
			WHERE $where_keyword AND $where_group_id ORDER BY id";
		$query = $this->db->query($SQL);
		$data = array();
		foreach($query->result() as $row){
			$data[] = array(
					"title" => $row->title,
					"underscored_title" => underscore($row->title)
				);
		}
		return $data;		
		
	}
	
	public function topic_content($underscored_title = NULL){
		$this->load->helper('inflector');
		// wheres
		$where_title = isset($underscored_title)?
		"(title LIKE '".addslashes(humanize($underscored_title))."')" : "TRUE";
	
		$SQL = "SELECT title, content
		FROM help_topic
		WHERE $where_title ORDER BY id";
		$query = $this->db->query($SQL);
		$data = array();
		foreach($query->result() as $row){
			$data[] = array(
					"title" => $row->title,
					"underscored_title" => underscore($row->title),
					"content" => $row->content,
			);
		}
		return $data;
	
	}
	
}

?>
