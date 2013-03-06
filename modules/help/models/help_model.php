<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Help_Model extends CMS_Model{

	public function count_group($url){
		$SQL = "SELECT id FROM ".$this->cms_complete_table_name('group')." WHERE url = '".addslashes($url)."'";
		$query = $this->db->query($SQL);
		return $query->num_rows();
	}

	public function count_topic($url){
		$SQL = "SELECT id FROM ".$this->cms_complete_table_name('topic')." WHERE url = '".addslashes($url)."'";
		$query = $this->db->query($SQL);
		return $query->num_rows();
	}

	public function group($url = NULL, $keyword = NULL){
		// wheres
		$where_url = isset($url)?
			"(url = '".addslashes($url)."')" : "TRUE";
		$where_keyword = isset($keyword)?
			"(name LIKE '%".addslashes($keyword)."%')
			OR (
				SELECT count(id) FROM ".$this->cms_complete_table_name('topic')."
				WHERE
					(title LIKE '%".addslashes($keyword)."%' OR
					content LIKE '%".addslashes($keyword)."%') AND
					".$this->cms_complete_table_name('topic').".group_id = ".$this->cms_complete_table_name('group').".id
			)>0 " : "TRUE";

		$SQL = "SELECT id, url, name, content
			FROM ".$this->cms_complete_table_name('group')."
			WHERE $where_keyword AND $where_url ORDER BY id";
		$query = $this->db->query($SQL);
		$data = array();
		foreach($query->result() as $row){
			$topics = $this->topic($row->id, $keyword);
			$data[] = array(
					"id" => $row->id,
					"name" => $row->name,
					"url" => $row->url,
					"topic_count" => count($topics),
					"topics" => $topics,
					"content" => $row->content,
				);
		}
		return $data;
	}

	public function topic($group_id = NULL, $keyword = NULL){
		// wheres
		$where_keyword = isset($keyword)?
			"(title LIKE '%".addslashes($keyword).
			"%' OR content LIKE '%".addslashes($keyword)."%')" : "TRUE";
		$where_group_id = isset($group_id)?
			"(group_id = '".addslashes($group_id)."')" : "TRUE";

		$SQL = "SELECT id, title, url
			FROM ".$this->cms_complete_table_name('topic')."
			WHERE $where_keyword AND $where_group_id ORDER BY id";
		$query = $this->db->query($SQL);
		$data = array();
		foreach($query->result() as $row){
			$data[] = array(
					"id" => $row->id,
					"title" => $row->title,
					"url" => $row->url
				);
		}
		return $data;

	}

	public function topic_content($url){

		$SQL = "SELECT id, title, content, url
			FROM ".$this->cms_complete_table_name('topic')."
			WHERE (url LIKE '".addslashes($url)."')  ORDER BY id";
		$query = $this->db->query($SQL);
		if($query->num_rows()>0){
			$row = $query->row();
			$data = array(
					"success" => true,
					"id" => $row->id,
					"title" => $row->title,
					"url" => $row->url,
					"content" => $row->content,
			);
			return $data;
		}else{
			$data = array(
					"success" => false,
				);
			return $data;
		}

	}

}
