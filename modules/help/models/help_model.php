<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help_Model extends CMS_Model{
	
	public function count_group($url){
		$SQL = "SELECT id FROM help_group WHERE url = '".addslashes($url)."'";
		$query = $this->db->query($SQL);
		return $query->num_rows();
	}
	
	public function count_topic($url){
		$SQL = "SELECT id FROM help_topic WHERE url = '".addslashes($url)."'";
		$query = $this->db->query($SQL);
		return $query->num_rows();
	}
	
	public function group($url = NULL){
		// wheres
		$where_name = isset($url)?
		"(url = '".addslashes($url)."')" : "TRUE";
		
		$SQL = "SELECT id, url, name, content FROM help_group WHERE $where_name ORDER BY id";
		$query = $this->db->query($SQL);
		$data = array();
		foreach($query->result() as $row){
			$topics = $this->topic(NULL, $row->id);
			$data[] = array(
					"name" => $row->name,
					"url" => $row->url,
					"topic_count" => count($topics),
					"topics" => $topics,
					"content" => $row->content,
				);
		}
		return $data;
	}
	
	public function topic($keyword = NULL, $group_id = NULL){
		// wheres
		$where_keyword = isset($keyword)?
			"(title LIKE '%".addslashes($keyword).
			"%' OR content LIKE '%".addslashes($keyword)."%')" : "TRUE"; 
		$where_group_id = isset($group_id)?
			"(group_id = '".addslashes($group_id)."')" : "TRUE";
		
		$SQL = "SELECT title, url
			FROM help_topic 
			WHERE $where_keyword AND $where_group_id ORDER BY id";
		$query = $this->db->query($SQL);
		$data = array();
		foreach($query->result() as $row){
			$data[] = array(
					"title" => $row->title,
					"url" => $row->url
				);
		}
		return $data;		
		
	}
	
	public function topic_content($url = NULL){
		// wheres
		$where_title = isset($url)?
		"(url LIKE '".addslashes($url)."')" : "TRUE";
	
		$SQL = "SELECT title, content, url
		FROM help_topic
		WHERE $where_title ORDER BY id";
		$query = $this->db->query($SQL);
		$data = array();
		foreach($query->result() as $row){
			$data[] = array(
					"title" => $row->title,
					"url" => $row->url,
					"content" => $row->content,
			);
		}
		return $data;
	
	}
	
}

?>
