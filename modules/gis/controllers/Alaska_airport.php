<?php
class Alaska_airport extends CMS_Controller{

	public function index(){

	}

	public function geojson(){
		$this->load->Model($this->cms_module_path().'/geoformat');

		$SQL = "
			SELECT `cat`, `name`, `use`, `elev`, astext(`shape`) as `shape`
			FROM ".$this->t('alaska_airport')." 
			WHERE
				(MBRIntersects(`shape`,geomfromtext('@map_region'))=1) AND
				(@map_zoom > 3)";
		$shape_column = 'shape';

		$popup_content = '';
		$popup_content .= '<b>@name</b><br />';
		$popup_content .= '<p>';
		$popup_content .= ' Usage : @use<br />';
		$popup_content .= ' Elevation : @elev<br />';
		$popup_content .= '</p>';

		$label = '@name';

		$this->cms_show_html($this->geoformat->sql2json($SQL, $shape_column, $popup_content, $label));
	}

}
?>
