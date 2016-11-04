<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Installation script for new_gis
 *
 * @author No-CMS Module Generator
 */
class Info extends CMS_Module {

    //////////////////////////////////////////////////////////////////////////////
    // NAVIGATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $NAVIGATIONS = array(
            // New Gis
            array(
                'navigation_name'   => 'index',
                'url'               => 'gis',
                'authorization_id'  => PRIV_EVERYONE,
                'default_layout'    => NULL,
                'title'             => 'GIS',
                'parent_name'       => NULL,
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),

        );

    protected $BACKEND_NAVIGATIONS = array(
            // Manage Map
            array(
                'entity_name'       => 'map',
                'url'               => 'manage_map',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Map',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
            // Manage Layer
            array(
                'entity_name'       => 'layer',
                'url'               => 'manage_layer',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Layer',
                'parent_name'       => 'manage_map',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => 1,
                'static_content'    => NULL,
            ),
            // Manage Alaska Airport
            array(
                'entity_name'       => 'alaska_airport',
                'url'               => 'manage_alaska_airport',
                'authorization_id'  => PRIV_AUTHORIZED,
                'default_layout'    => 'default-one-column',
                'title'             => 'Manage Alaska Airport (Sample Data)',
                'parent_name'       => 'index',
                'index'             => NULL,
                'description'       => NULL,
                'bootstrap_glyph'   => NULL,
                'notification_url'  => NULL,
                'hidden'            => NULL,
                'static_content'    => NULL,
            ),
        );

    //////////////////////////////////////////////////////////////////////////////
    // CONFIGURATIONS
    //////////////////////////////////////////////////////////////////////////////
    protected $CONFIGS = array(
        array('config_name' => 'gmap_api_key', 'value' => ''),
    );

    //////////////////////////////////////////////////////////////////////////////
    // PRIVILEGES
    //////////////////////////////////////////////////////////////////////////////
    protected $PRIVILEGES = array();

    //////////////////////////////////////////////////////////////////////////////
    // GROUPS
    //////////////////////////////////////////////////////////////////////////////
    protected $GROUPS = array(
            array('group_name' => 'Gis Manager', 'description' => 'Gis Manager'),
        );
    protected $GROUP_NAVIGATIONS = array();
    protected $GROUP_BACKEND_NAVIGATIONS = array(
            'Gis Manager' => array('alaska_airport', 'map', 'layer')
        );
    protected $GROUP_PRIVILEGES = array();
    protected $GROUP_BACKEND_PRIVILEGES = array(
            'Gis Manager' => array(
                'alaska_airport' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'map' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
                'layer' => array('read', 'add', 'edit', 'delete', 'list', 'back_to_list', 'print', 'export'),
            )
        );

    //////////////////////////////////////////////////////////////////////////////
    // TABLES and DATA
    //////////////////////////////////////////////////////////////////////////////
    protected $TABLES = array(
        // alaska_airport
        'alaska_airport' => array(
            'key'    => 'OGR_FID',
            'fields' => array(
                'OGR_FID'              => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'SHAPE'                => array("type" => 'geometry',   "null" => TRUE),
                'cat'                  => array("type" => 'decimal',    "null" => TRUE),
                'na3'                  => array("type" => 'varchar',    "constraint" => 80,  "null" => TRUE),
                'elev'                 => array("type" => 'double',     "null" => TRUE),
                'f_code'               => array("type" => 'varchar',    "constraint" => 80,  "null" => TRUE),
                'iko'                  => array("type" => 'varchar',    "constraint" => 80,  "null" => TRUE),
                'name'                 => array("type" => 'varchar',    "constraint" => 80,  "null" => TRUE),
                'use'                  => array("type" => 'varchar',    "constraint" => 80,  "null" => TRUE),
            ),
        ),
        // map
        'map' => array(
            'key'    => 'map_id',
            'fields' => array(
                'map_id'               => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'map_name'             => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'map_desc'             => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'latitude'             => array("type" => 'double',     "null" => TRUE),
                'longitude'            => array("type" => 'double',     "null" => TRUE),
                'gmap_roadmap'         => array("type" => 'tinyint',    "constraint" => 3,   "null" => TRUE),
                'gmap_satellite'       => array("type" => 'tinyint',    "constraint" => 3,   "null" => TRUE),
                'gmap_hybrid'          => array("type" => 'tinyint',    "constraint" => 3,   "null" => TRUE),
                'zoom'                 => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'height'               => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'width'                => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'layer'                => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'cloudmade_basemap'    => array("type" => 'varchar',    "constraint" => 255, "null" => TRUE),
                'custom_form'          => array("type" => 'text',       "null" => TRUE),
                'custom_javascript'    => array("type" => 'text',       "null" => TRUE),
            ),
        ),
        // cloudmade_basemap
        'cloudmade_basemap' => array(
            'key'    => 'basemap_id',
            'fields' => array(
                'basemap_id'           => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'map_id'               => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'basemap_name'         => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'url'                  => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'max_zoom'             => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'attribution'          => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
            ),
        ),
        // layer
        'layer' => array(
            'key'    => 'layer_id',
            'fields' => array(
                'layer_id'             => 'TYPE_INT_UNSIGNED_AUTO_INCREMENT',
                'map_id'               => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'layer_name'           => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'group_name'           => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'layer_desc'           => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'z_index'              => array("type" => 'tinyint',    "constraint" => 3,   "null" => TRUE),
                'shown'                => array("type" => 'tinyint',    "constraint" => 3,   "null" => TRUE),
                'radius'               => array("type" => 'int',        "constraint" => 10,  "null" => TRUE),
                'fill_color'           => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'color'                => array("type" => 'varchar',    "constraint" => 45,  "null" => TRUE),
                'weight'               => array("type" => 'double',     "null" => TRUE),
                'opacity'              => array("type" => 'double',     "null" => TRUE),
                'fill_opacity'         => array("type" => 'double',     "null" => TRUE),
                'image_url'            => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'json_sql'             => array("type" => 'text',       "null" => TRUE),
                'json_shape_column'    => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'json_popup_content'   => array("type" => 'text',       "null" => TRUE),
                'json_label'           => array("type" => 'text',       "null" => TRUE),
                'use_json_url'         => array("type" => 'tinyint',    "constraint" => 3,   "null" => TRUE),
                'json_url'             => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'searchable'           => array("type" => 'tinyint',    "constraint" => 3,   "null" => TRUE),
                'search_sql'           => array("type" => 'text',       "null" => TRUE),
                'search_result_content' => array("type" => 'text',       "null" => TRUE),
                'search_result_x_column' => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'search_result_y_column' => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
                'use_search_url'       => array("type" => 'tinyint',    "constraint" => 3,   "null" => TRUE),
                'search_url'           => array("type" => 'varchar',    "constraint" => 100, "null" => TRUE),
            ),
        ),
    );
    protected $DATA = array(
        'layer' => array(
            array('layer_id' => '1', 'map_id' => '1', 'layer_name' => 'All Airports', 'group_name' => '', 'layer_desc' => 'Airports in all alaska', 'z_index' => '0', 'shown' => '1', 'radius' => '4', 'fill_color' => '#ff7800', 'color' => '#000000', 'weight' => '1', 'opacity' => '1', 'fill_opacity' => '0.8', 'image_url' => '', 'json_sql' => '', 'json_shape_column' => '', 'json_popup_content' => '', 'json_label' => '', 'use_json_url' => '1', 'json_url' => '{{ module_site_url }}alaska_airport/geojson', 'searchable' => '1', 'search_sql' => 'SELECT `name`, `use`, `elev`, x(`shape`) as `x`, y(`shape`) as `y`\nFROM {{ table_alaska_airport }}\nWHERE name LIKE \'%@keyword%\';', 'search_result_content' => '<b>@name</b><br />\nUsage : @use<br />\nElevation : @elev<br />\nCoordinate: (@x,@y)', 'search_result_x_column' => 'x', 'search_result_y_column' => 'y', 'use_search_url' => '0', 'search_url' => ''),
            array('layer_id' => '2', 'map_id' => '1', 'layer_name' => 'Civilian/Public', 'group_name' => 'Airports by usage', 'layer_desc' => 'Civilian/Public Airport', 'z_index' => '0', 'shown' => '0', 'radius' => '20', 'fill_color' => '#ff7800', 'color' => '#000000', 'weight' => '1', 'opacity' => '1', 'fill_opacity' => '0.8', 'image_url' => '{{ module_base_url }}assets/images/black_plane.png', 'json_sql' => 'SELECT `cat`, `name`, `use`, `elev`, astext(`shape`) as `shape`, x(`shape`) as `lat`, y(`shape`) as `long`\nFROM {{ table_alaska_airport }}\nWHERE `use`=\'Civilian/Public\' AND (MBRIntersects(`shape`,geomfromtext(\'@map_region\'))=1) AND (@map_zoom > 3) ;', 'json_shape_column' => 'shape', 'json_popup_content' => '<b>Civilian Airport</b><br />Name: @name<br />Latitude: @lat<br />Longitude:@long', 'json_label' => '@name', 'use_json_url' => '0', 'json_url' => '', 'searchable' => '0', 'search_sql' => '', 'search_result_content' => '', 'search_result_x_column' => '', 'search_result_y_column' => '', 'use_search_url' => '0', 'search_url' => ''),
            array('layer_id' => '3', 'map_id' => '1', 'layer_name' => 'Military', 'group_name' => 'Airports by usage', 'layer_desc' => 'Military Airport', 'z_index' => '0', 'shown' => '0', 'radius' => '4', 'fill_color' => '#ff0000', 'color' => '#000000', 'weight' => '1', 'opacity' => '1', 'fill_opacity' => '0.8', 'image_url' => '', 'json_sql' => 'SELECT `cat`, `name`, `use`, `elev`, astext(`shape`) as `shape`, x(`shape`) as `lat`, y(`shape`) as `long`\nFROM {{ table_alaska_airport }}\nWHERE `use`=\'Military\' AND (MBRIntersects(`shape`,geomfromtext(\'@map_region\'))=1) AND (@map_zoom > 3) ;', 'json_shape_column' => 'shape', 'json_popup_content' => '<b>Military Airport</b><br />@name<br />Latitude: @lat<br />Longitude:@long', 'json_label' => '@name', 'use_json_url' => '0', 'json_url' => '', 'searchable' => '0', 'search_sql' => '', 'search_result_content' => '', 'search_result_x_column' => '', 'search_result_y_column' => '', 'use_search_url' => '0', 'search_url' => ''),
        ),
        'cloudmade_basemap' => array(
            array('basemap_id' => '1', 'map_id' => '1', 'basemap_name' => 'Map Box', 'url' => 'http://a.tiles.mapbox.com/v3/mapbox.world-bright/{z}/{x}/{y}.png', 'max_zoom' => '18', 'attribution' => ''),
        ),
        'map' => array(
            array('map_id' => '1', 'map_name' => 'Alaska', 'map_desc' => 'A map of Alaska', 'latitude' => '60.293165', 'longitude' => '-158.959803', 'gmap_roadmap' => '1', 'gmap_satellite' => '1', 'gmap_hybrid' => '1', 'zoom' => '5', 'height' => '500px', 'width' => '100%'),
            array('map_id' => '2', 'map_name' => 'Sorong', 'map_desc' => 'Map of Sorong, Papua, Indonesia', 'latitude' => '-0.87863315453434', 'longitude' => '131.2604984393', 'gmap_roadmap' => '0', 'gmap_satellite' => '0', 'gmap_hybrid' => '1', 'zoom' => '18', 'height' => '500px', 'width' => '100%'),
        ),
    );

    public function __construct(){
        parent::__construct();
        for($i=0; $i<count($this->DATA['layer']); $i++){
            foreach($this->DATA['layer'][$i] as $key=>$val){
                $val = str_replace('{{ table_alaska_airport }}', $this->t('alaska_airport'), $val);
                $this->DATA['layer'][$i][$key] = $val;
            }
        }
    }

    //////////////////////////////////////////////////////////////////////////////
    // ACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_activate(){
        // TODO : write your module activation script here
        $this->db->query("
        	INSERT INTO `".$this->t('alaska_airport')."` (`OGR_FID`, `SHAPE`, `cat`, `na3`, `elev`, `f_code`, `iko`, `name`, `use`) VALUES
	        	( '1',geomfromtext('POINT(-162.97528075057514 67.56208038542596)'),'1','US00157','78.000','Airport/Airfield','PA','NOATAK','Other'),
				( '2',geomfromtext('POINT(-157.85360716963905 67.10610962051074)'),'2','US00229','264.000','Airport/Airfield','PA','AMBLER','Other'),
				( '3',geomfromtext('POINT(-151.52806090867935 66.9152832037312)'),'3','US00186','585.000','Airport/Airfield','PABT','BETTLES','Other'),
				( '4',geomfromtext('POINT(-162.59855650743486 66.88468170387709)'),'4','US59150','9.000','Airport/Airfield','PAOT','RALPH WIEN MEM','Civilian/Public'),
				( '5',geomfromtext('POINT(-159.9861907863258 66.6000289935081)'),'5','US00173','21.000','Airport/Airfield','PA','SELAWIK','Other'),
				( '6',geomfromtext('POINT(-153.70428466202193 65.99279022313692)'),'6','US00193','1113.000','Airport/Airfield','PA','INDIAN MOUNTAIN LRRS','Other'),
				( '7',geomfromtext('POINT(-161.15197752856332 65.98228454805457)'),'7','US00177','21.000','Airport/Airfield','PA','BUCKLAND','Other'),
				( '8',geomfromtext('POINT(-167.9225005954405 65.56416321178065)'),'8','US00146','243.000','Airport/Airfield','PATC','TIN CITY LRRS','Other'),
				( '9',geomfromtext('POINT(-161.2799987685303 65.40499878161447)'),'9','US00150','1329.000','Airport/Airfield','PA','GRANITE MOUNTAIN AFS','Other'),
				( '10',geomfromtext('POINT(-166.85852049347008 65.25366974237791)'),'10','US03057','9.000','Airport/Airfield','PA','PORT CLARENCE CGS','Other'),
				( '11',geomfromtext('POINT(-152.10939025351573 65.1743927010712)'),'11','US00188','207.000','Airport/Airfield','PATA','RALPH M CALHOUN','Other'),
				( '12',geomfromtext('POINT(-161.1580505262659 64.93444061520005)'),'12','US00155','108.000','Airport/Airfield','PA','KOYUK','Other'),
				( '13',geomfromtext('POINT(-156.9374999917138 64.73611450361221)'),'13','US75867','138.000','Airport/Airfield','PAGA','EDWARD G PITKA SR','Joint Military/Civilian'),
				( '14',geomfromtext('POINT(-162.05722044749686 64.69805908468658)'),'14','US60244','12.000','Airport/Airfield','PA','MOSES POINT','Other'),
				( '15',geomfromtext('POINT(-165.44525145114966 64.51219940534808)'),'15','US42171','33.000','Airport/Airfield','PAOM','NOME','Civilian/Public'),
				( '16',geomfromtext('POINT(-156.84333800436613 64.42444610765973)'),'16','US00211','1461.000','Airport/Airfield','PA','KALAKAKET CREEKAS','Military'),
				( '17',geomfromtext('POINT(-160.7989501843509 63.88836288711196)'),'17','US00436','18.000','Airport/Airfield','PAUN','UNALAKLEET','Other'),
				( '18',geomfromtext('POINT(-152.30067443268598 63.88055420029708)'),'18','US00327','624.000','Airport/Airfield','PA','MINCHUMINA','Other'),
				( '19',geomfromtext('POINT(-171.73283384441595 63.766777044102895)'),'19','US91222','24.000','Airport/Airfield','PA','GAMBELL','Other'),
				( '20',geomfromtext('POINT(-170.4926452465237 63.68638992827347)'),'20','US00453','48.000','Airport/Airfield','PA','SAVOONGA','Other'),
				( '21',geomfromtext('POINT(-155.60577391772972 62.95288849053581)'),'21','US80563','306.000','Airport/Airfield','PAMC','MC GRATH','Civilian/Public'),
				( '22',geomfromtext('POINT(-155.97639464502458 62.894443513808355)'),'22','US00342','858.000','Airport/Airfield','PATL','TATALINA LRRS','Other'),
				( '23',geomfromtext('POINT(-164.49110411231055 62.78527832409856)'),'23','US00466','12.000','Airport/Airfield','PA','EMMONAK','Other'),
				( '24',geomfromtext('POINT(-160.1898956188945 62.64858246126401)'),'24','US00455','282.000','Airport/Airfield','PA','ANVIK','Other'),
				( '25',geomfromtext('POINT(-150.09368895982536 62.320499421150416)'),'25','US95665','327.000','Airport/Airfield','PATK','TALKEETNA','Civilian/Public'),
				( '26',geomfromtext('POINT(-163.3022155630188 62.06055450808827)'),'26','US00458','282.000','Airport/Airfield','PA','ST MARYS','Other'),
				( '27',geomfromtext('POINT(-166.03889463833326 61.78027725674379)'),'27','US00440','417.000','Airport/Airfield','PACZ','CAPE ROMANZOF LRRS','Other'),
				( '28',geomfromtext('POINT(-159.5430450330111 61.58159637739974)'),'28','US00317','78.000','Airport/Airfield','PA','ANIAK','Other'),
				( '29',geomfromtext('POINT(-155.57472228140594 61.097221376640746)'),'29','US00338','1449.000','Airport/Airfield','PASV','SPARREVOHN LRRS','Other'),
				( '30',geomfromtext('POINT(-161.83799742387436 60.779777530536094)'),'30','US38091','111.000','Airport/Airfield','PABE','BETHEL','Civilian/Public'),
				( '31',geomfromtext('POINT(-151.24752806994613 60.571998597610154)'),'31','US45021','87.000','Airport/Airfield','PAEN','KENAI MUNI','Civilian/Public'),
				( '32',geomfromtext('POINT(-151.03825377804444 60.474983216730834)'),'32','US34970','96.000','Airport/Airfield','PA','SOLDOTNA','Other'),
				( '33',geomfromtext('POINT(-166.27061460841657 60.37141800437253)'),'33','US00447','42.000','Airport/Airfield','PA','MEKORYUK','Other'),
				( '34',geomfromtext('POINT(-145.25054931525864 66.57138824475342)'),'34','US00201','393.000','Airport/Airfield','PFYU','FORT YUKON','Other'),
				( '35',geomfromtext('POINT(-147.61444091508713 64.83750152629912)'),'35','US99779','408.000','Airport/Airfield','PAFB','WAINWRIGHT AAF','Military'),
				( '36',geomfromtext('POINT(-147.8596649139725 64.81366729780883)'),'36','US90129','396.000','Airport/Airfield','PAFA','FAIRBANKS INTL','Civilian/Public'),
				( '37',geomfromtext('POINT(-147.10139465065822 64.66555786171527)'),'37','US49463','501.000','Airport/Airfield','PAEI','EIELSON AFB','Military'),
				( '38',geomfromtext('POINT(-149.07350158315575 64.54897308409042)'),'38','US18668','330.000','Airport/Airfield','PA','NENANA MUNI','Other'),
				( '39',geomfromtext('POINT(-149.12014770121792 64.30120086732457)'),'39','US00191','504.000','Airport/Airfield','PACL','CLEAR','Other'),
				( '40',geomfromtext('POINT(-145.7216491677743 63.99454879792356)'),'40','US11435','1167.000','Airport/Airfield','PABI','ALLEN AAF','Military'),
				( '41',geomfromtext('POINT(-143.3355865467078 63.37435913102234)'),'41','US34092','1416.000','Airport/Airfield','PA','TANACROSS','Other'),
				( '42',geomfromtext('POINT(-141.92913818299675 62.961334228599284)'),'42','US33180','1569.000','Airport/Airfield','PAOR','NORTHWAY','Civilian/Public'),
				( '43',geomfromtext('POINT(-145.4566345189184 62.15488815351677)'),'43','US91368','1443.000','Airport/Airfield','PAGK','GULKANA','Civilian/Public'),
				( '44',geomfromtext('POINT(-149.08882140645085 61.59474182222864)'),'44','US33235','225.000','Airport/Airfield','PAAQ','PALMER MUNI','Civilian/Public'),
				( '45',geomfromtext('POINT(-149.81390380348776 61.53556823835532)'),'45','US00343','135.000','Airport/Airfield','PA','BIG LAKE','Other'),
				( '46',geomfromtext('POINT(-149.65469359841418 61.26250076400407)'),'46','US00341','345.000','Airport/Airfield','PAFR','BRYANT AHP','Military'),
				( '47',geomfromtext('POINT(-149.80650329070806 61.25136184801247)'),'47','US58704','192.000','Airport/Airfield','PAED','ELMENDORF AFB','Military'),
				( '48',geomfromtext('POINT(-149.84616088344762 61.21438980212513)'),'48','US01693','123.000','Airport/Airfield','PAMR','MERRILL FLD','Civilian/Public'),
				( '49',geomfromtext('POINT(-149.99618529741286 61.17432022207521)'),'49','US77679','129.000','Airport/Airfield','PANC','ANCHORAGE INTL','Civilian/Public'),
				( '50',geomfromtext('POINT(-146.24836730628428 61.133945465714156)'),'50','US96982','108.000','Airport/Airfield','PAVD','VALDEZ','Other'),
				( '51',geomfromtext('POINT(-145.4776458709465 60.49183273375414)'),'51','US40776','36.000','Airport/Airfield','PACV','MERLE K MUDHOLE SMITH','Other'),
				( '52',geomfromtext('POINT(-149.41880797807755 60.12693786739825)'),'52','US80341','18.000','Airport/Airfield','PA','SEWARD','Other'),
				( '53',geomfromtext('POINT(-154.91722106067155 59.75277710187726)'),'53','US00483','189.000','Airport/Airfield','PAIL','ILIAMNA','Other'),
				( '54',geomfromtext('POINT(-151.47657775214063 59.64555740517424)'),'54','US53682','75.000','Airport/Airfield','PAHO','HOMER','Other'),
				( '55',geomfromtext('POINT(-155.25721739823047 59.361667635479414)'),'55','US00488','606.000','Airport/Airfield','PA','BIG MOUNTAIN AFS','Military'),
				( '56',geomfromtext('POINT(-158.50334166369532 59.045413974218015)'),'56','US07889','78.000','Airport/Airfield','PADL','DILLINGHAM','Civilian/Public'),
				( '57',geomfromtext('POINT(-156.64916991180434 58.6766662626614)'),'57','US81498','51.000','Airport/Airfield','PAKN','KING SALMON','Joint Military/Civilian'),
				( '58',geomfromtext('POINT(-162.06056212029765 58.64722061593822)'),'58','US00476','492.000','Airport/Airfield','PAEH','CAPE NEWENHAM LRRS','Other'),
				( '59',geomfromtext('POINT(-152.49386595896283 57.7499732992624)'),'59','US22587','66.000','Airport/Airfield','PADQ','KODIAK','Joint Military/Civilian'),
				( '60',geomfromtext('POINT(-170.22044370642809 57.167331703287715)'),'60','US00475','57.000','Airport/Airfield','PASN','ST PAUL ISLAND','Other'),
				( '61',geomfromtext('POINT(-158.63182066685732 56.95943451310793)'),'61','US00482','78.000','Airport/Airfield','PA','PORT HEIDEN','Other'),
				( '62',geomfromtext('POINT(-169.66139219291665 56.578609474290204)'),'62','US00477','114.000','Airport/Airfield','PA','ST GEORGE','Other'),
				( '63',geomfromtext('POINT(-162.7242584078442 55.205600744120815)'),'63','US95048','87.000','Airport/Airfield','PACD','COLD BAY','Civilian/Public'),
				( '64',geomfromtext('POINT(-166.54350278976236 53.900138862403665)'),'64','US95921','18.000','Airport/Airfield','PADU','UNALASKA','Other'),
				( '65',geomfromtext('POINT(-168.85000608390814 52.94166565811451)'),'65','US00578','66.000','Airport/Airfield','PA','NIKOLSKI AS','Military'),
				( '66',geomfromtext('POINT(-174.20634458136723 52.22034836961871)'),'66','US76610','51.000','Airport/Airfield','PA','ATKA','Other'),
				( '67',geomfromtext('POINT(-139.66021728460072 59.503360748387685)'),'67','US47439','30.000','Airport/Airfield','PAYA','YAKUTAT','Civilian/Public'),
				( '68',geomfromtext('POINT(-135.31567382934887 59.4600563047468)'),'68','US97565','39.000','Airport/Airfield','PAGY','SKAGWAY','Other'),
				( '69',geomfromtext('POINT(-135.5222167979631 59.2452774046164)'),'69','US62749','12.000','Airport/Airfield','PA','HAINES','Other'),
				( '70',geomfromtext('POINT(-135.70750427326004 58.42444229112622)'),'70','US19342','30.000','Airport/Airfield','PA','GUSTAVUS','Other'),
				( '71',geomfromtext('POINT(-135.4096984871562 58.096084594586046)'),'71','US47648','18.000','Airport/Airfield','PA','HOONAH','Other'),
				( '72',geomfromtext('POINT(-133.90826416125603 56.960479736135404)'),'72','US94530','156.000','Airport/Airfield','PA','KAKE','Other'),
				( '73',geomfromtext('POINT(-132.94528198382665 56.80166625952452)'),'73','US14079','96.000','Airport/Airfield','PA','PETERSBURG JAMES A JOHNSON','Other'),
				( '74',geomfromtext('POINT(-132.36982727203377 56.48432540867274)'),'74','US38648','39.000','Airport/Airfield','PA','WRANGELL','Other'),
				( '75',geomfromtext('POINT(-133.07611084090223 55.57916641215566)'),'75','US05477','72.000','Airport/Airfield','PA','KLAWOCK','Other'),
				( '76',geomfromtext('POINT(-131.57223510887206 55.04243469211528)'),'76','US11438','108.000','Airport/Airfield','PANT','ANNETTE ISLAND','Other');

        ");
    }

    //////////////////////////////////////////////////////////////////////////////
    // DEACTIVATION
    //////////////////////////////////////////////////////////////////////////////
    public function do_deactivate(){
        // TODO : write your module deactivation script here
    }

    //////////////////////////////////////////////////////////////////////////////
    // UPGRADE
    //////////////////////////////////////////////////////////////////////////////
    // TODO: write your upgrade function: do_upgrade_to_x_x_x
    public function do_upgrade_to_0_1_0(){
        $this->cms_remove_navigation("gis_map");
        $this->cms_remove_navigation("gis_cloudmade_basemap");
        $this->cms_remove_navigation("gis_layer");
        $this->cms_remove_navigation("gis_index");
    }
}
