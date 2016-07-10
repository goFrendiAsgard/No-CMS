<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for
 *
 * @author No-CMS Module Generator
 */
class Gis extends CMS_Secure_Controller {

    protected function do_override_url_map($URL_MAP){
        $module_path = $this->cms_module_path();
        $navigation_name = $this->n('index');
        $URL_MAP[$module_path.'/'.$module_path] = $navigation_name;
        $URL_MAP[$module_path] = $navigation_name;
        return $URL_MAP;
    }

    public function index($map_id=NULL, $longitude=NULL, $latitude=NULL, $zoom=NULL){
    	$this->load->model($this->cms_module_path().'/map_model');
    	if(!isset($map_id)){ //show list
    		$map = $this->map_model->get_map();
    		$data = array("map_list"=> $map);
    		$this->view($this->cms_module_path().'/gis_index_list', $data, $this->n('index'));
    	}else{ //show the map
    		$map = $this->map_model->get_map($map_id);
    		if(isset($longitude)) $map["longitude"] = $longitude;
    		if(isset($latitude)) $map["latitude"] = $latitude;
    		if(isset($zoom)) $map["zoom"] = $zoom;
    		$data = array(
    			"map"=> $map,
    			"gis_path"=> $this->cms_module_path('gofrendi.gis.core')
			);
            // determine the correct navigation name
            $navigation_name = $this->n('index');
            if(is_numeric($map_id)){
                $url = $this->cms_module_path('gofrendi.gis.core').'/index/'.$map_id;
                $navigation = $this->cms_get_record(cms_table_name('main_navigation'), 'url', $url);
                if($navigation != NULL){
                    $navigation_name = $navigation->navigation_name;
                }
            }
    		$this->view($this->cms_module_path().'/gis_index_map', $data, $navigation_name);
    	}
    }

    public function geojson($layer_id){
    	$this->load->model($this->cms_module_path().'/map_model');
		$this->load->model($this->cms_module_path().'/geoformat');

    	// get parameter from model
    	$config = $this->map_model->get_layer_json_parameter($layer_id);
    	$SQL = $config["json_sql"];
    	$popup_content = $config["json_popup_content"];
    	$label = $config["json_label"];
    	$shape_column = $config["json_shape_column"];
    	$this->cms_show_html($this->geoformat->sql2json($SQL, $shape_column, $popup_content, $label));
    }

    public function search($layer_id, $keyword=NULL){
    	// get keyword
    	if(!isset($keyword)){
    		$keyword = $this->input->post('keyword');
    	}
    	$keyword = addslashes($keyword);

    	// load model and library
    	$this->load->model($this->cms_module_path().'/map_model');
		$this->load->model($this->cms_module_path().'/geoformat');

    	// get parameter from model
    	$config = $this->map_model->get_layer_search_parameter($layer_id);
    	$SQL = $config["search_sql"];
    	$result_content = $config["search_result_content"];
    	$long_column = $config["search_result_x_column"];
    	$lat_column = $config["search_result_y_column"];

    	// merge keyword into SQL
    	$search = array('@keyword');
    	$replace = array($keyword);
    	$SQL = $this->geoformat->replace($SQL, $search, $replace);

    	$data = array();
    	$query = $this->db->query($SQL);
    	foreach($query->result_array() as $row){
    		// real result content
    		$search = array();
    		$replace = array();
    		foreach($row as $label=>$value){
    			$search[] = '@'.$label;
    			$replace[] = $value;
    		}
    		$real_result_content = $this->geoformat->replace($result_content, $search, $replace);

    		$real_lat_column = $row[$lat_column];
    		$real_long_column = $row[$long_column];

            // code to distinguish record. Since we cannot determine the primary key, we will use the record itself as the code
            $code = '';
            foreach($row as $key=>$val){
                // no dash for the first key-val pair
                if($code != ''){
                    $code .= '-';
                }
                $code .= $key.'-'. (string)$val;
            }

    		$data[] = array(
    				"result_content" => $real_result_content,
    				"latitude" => $real_lat_column,
    				"longitude" => $real_long_column,
                    "code" => $code,
    			);
    	}
    	$this->cms_show_json($data);

    }

}
