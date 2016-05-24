<?php
class Geoformat extends CMS_Model{
	public function replace($str,$search,$replace){
		if(count($search)==count($replace)){
			for($i=0; $i<count($search); $i++){
				$str = str_replace($search, $replace, $str);
			}
		}
		return $str;
	}
	
	public function sql2json($SQL, $shape_column, $popup_content=NULL, $label=NULL){
		
		require_once(APPPATH.'../modules/'.
				$this->cms_module_path('gofrendi.gis.core').
				'/classes/geoPHP/geoPHP.inc');
				
		$map_region = $this->input->post('map_region');
		$map_zoom = $this->input->post('map_zoom');
		$search = array('@map_region', '@map_zoom');
		$replace = array($map_region, $map_zoom);
		$SQL = $this->replace($SQL, $search, $replace);
		
		$features = array();
		$query = $this->db->query($SQL);
		
		foreach($query->result_array() as $row){
			$geom = geoPHP::load($row[$shape_column],'wkt');
			$json = $geom->out('json');
			
			$real_popup_content = "";
			$real_label = "";
			$search = array();
			$replace = array();
			foreach($row as $column=>$value){
				$search[] = '@'.$column;
				$replace[] = $value;
			}
			if(isset($popup_content))
				$real_popup_content = $this->replace($popup_content, $search, $replace);
			if(isset($label))
				$real_label = $this->replace($label, $search, $replace);
				
			$features[] = array(
					"type" => "Feature",
					"properties" => array(
							"popupContent"=> $real_popup_content,
							"label"=> $real_label,
					),
					"geometry" => json_decode($json),
			);
		}
		
		$feature_collection = array(
				"type" => "FeatureCollection",
				"features" => $features,
		);		 
		 
		return json_encode($feature_collection);
	}
	
} 
?>