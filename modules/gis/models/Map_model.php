<?php
	class Map_model extends CMS_Model{

		public function get_map($map_id=NULL){
			if(isset($map_id) && is_numeric($map_id)){
				$SQL = "SELECT * FROM ".$this->t('map')." WHERE map_id = ".addslashes($map_id);
				$query = $this->db->query($SQL);
				if($query->num_rows()>0){
					$row = $query->row_array();
					$row['scripts'] = array();
					$row['layer_groups'] = $this->get_layer_group($map_id);
					$row['cloudmade_basemap'] = $this->get_cloudmade_basemap($map_id);
					return $row;
				}else{
					return NULL;
				}
			}else{
				$SQL = "SELECT * FROM ".$this->t('map');
				$query = $this->db->query($SQL);
				$data = array();
				foreach($query->result_array() as $row){
					$row["scripts"] = array();
					$data[] = $row;
				}
				return $data;
			}
		}

		public function get_layer_group($map_id){
			$SQL = "SELECT
					DISTINCT(IF(group_name='' OR group_name iS NULL, layer_name, group_name)) AS name,
					MAX(shown) AS shown
				FROM ".$this->t('layer')."
				WHERE map_id = '".addslashes($map_id)."'
				GROUP BY name
				ORDER BY MIN(z_index), MIN(layer_id)";
			$query = $this->db->query($SQL);
			$data = array();
			foreach($query->result_array() as $row){
				$row['group_name'] = $row['name'];
				unset($row['name']);
				$row['layers'] = $this->get_layer($map_id, $row['group_name']);
				$data[] = $row;
			}
			return $data;
		}

		public function get_layer($map_id, $group_name){
			$SQL = "SELECT layer_id, layer_name, layer_desc,
						IF(group_name='' OR group_name iS NULL, layer_name, group_name) AS group_name,
		    		    shown, radius, fill_color, color, weight,
					    opacity, fill_opacity, image_url, use_json_url,
					    json_url, search_url, use_search_url, searchable
				    FROM ".$this->t('layer')."
				    WHERE
				    	map_id = '".addslashes($map_id)."' AND
				    	IF(group_name='' OR group_name iS NULL, layer_name, group_name) = '".addslashes($group_name)."'
					ORDER BY z_index, layer_id";
			$query = $this->db->query($SQL);
			$data = array();
			foreach($query->result_array() as $row){
				$row["image_url"] = $this->cms_parse_keyword($row["image_url"]);
				// json_url
				$row["json_url"] = $this->cms_parse_keyword($row["json_url"]);
				if($row["use_json_url"]==0){
					$row["json_url"] = $this->cms_parse_keyword('{{ site_url }}'.$this->cms_module_path().'/gis/geojson/'.$row["layer_id"]);
				}
				unset($row["use_json_url"]);

				//search_url
				$row["search_url"] = $this->cms_parse_keyword($row["search_url"]);
				if($row["use_search_url"]==0){
					$row["search_url"] = $this->cms_parse_keyword('{{ site_url }}'.$this->cms_module_path().'/search/'.$row["layer_id"]);
				}
				unset($row["use_search_url"]);
				$data[] = $row;
			}
			return $data;
		}

		public function get_layer_json_parameter($layer_id){
			$SQL = "SELECT json_sql, json_shape_column, json_popup_content, json_label
			        FROM ".$this->t('layer')." WHERE layer_id = '".addslashes($layer_id)."'";
			$query = $this->db->query($SQL);
			return $query->row_array();
		}

		public function get_layer_search_parameter($layer_id){
			$SQL = "SELECT search_sql, search_result_x_column,
					search_result_y_column, search_result_content
					FROM ".$this->t('layer')." WHERE layer_id = '".addslashes($layer_id)."'";
			$query = $this->db->query($SQL);
			return $query->row_array();
		}



		public function get_cloudmade_basemap($map_id){
			$SQL = "SELECT * FROM ".$this->t('cloudmade_basemap')." WHERE map_id = '".addslashes($map_id)."'";
			$query = $this->db->query($SQL);
			$data = array();
			foreach($query->result_array() as $row){
				$data[] = $row;
			}
			return $data;
		}

	}
?>
