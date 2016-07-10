<head>
	<link rel="stylesheet" type="text/css" href="{{ module_base_url }}assets/js/leaflet/dist/leaflet.css" />
	<style type="text/css">
	    .leaflet-container img{
	        z-index : -1;
	    }
	    #change_feature{
	        z-index:3;
	    }
	    .layer_legend{
	    	list-style-type: none;
	    }
	    .layer_legend>div, .leaflet-control-layers-overlays>label>div{
	    	margin-left: 5px;
	    	margin-right: 5px;
	    	width: 20px;
	    	height: 20px;
	    	display: inline-block;
	    	vertical-align: middle;
	    }
	    .layer_legend>div>div, .leaflet-control-layers-overlays>label>div>div{
	    	max-height: 20px;
	    	max-width: 20px;
	    	border-radius:5px;
	    	-moz-border-radius:5px;
	    	border: 1px solid;
	    }
	    .layer_legend>div>img, .leaflet-control-layers-overlays>label>div>img{
	    	max-height: 20px;
	    	max-width: 20px;
	    }
        .leaflet-control-layers ul{
            padding-left: 20px;
        }
	</style>

</head>
<body>
    <?php
		$html = "";
		// make the checkboxes
		$need_search_form = FALSE;
		for($i=0; $i<count($map["layer_groups"]); $i++){
			$group = $map["layer_groups"][$i];
			$layers = $group["layers"];
			for($j=0; $j<count($layers); $j++){
				$layer = $layers[$j];
				$layer_searchable = $layer["searchable"]>0;
				if($layer_searchable){
					$need_search_form = TRUE;
					$layer_name = $layer['layer_name'];
					$html.= '<div class="col-md-4 col-sm-3">
                        <label class=".checkbox">
    						<input id="gis_search_layer_'.$i.'_'.$j.'" name="layer[]" type="checkbox" value="' . $layer_name . '" checked />'.
    						$layer_name.'
                        </label>
                    </div>';
				}
			}
		}
        if($html != ''){
            $html = '<div class="col-xs-12">'.$html.'</div>';
        }
		// make the search form if needed
		if($need_search_form){
			$html.= '<div class="col-xs-12">'.$map['custom_form'].'
				<div class="col-md-8">
                    <input id="gis_search_keyword" class="form-control" name="keyword" type="text" placeholder="Keyword" />
                </div>
                <div class="col-md-4">
				    <button id="btn_gis_search" class="btn btn-primary" data-toggle="modal" data-target="#map-modal">Search On The Map</button>
                </div>
			</div>';
		}
		if($html != ""){
			echo '<div class="col-xs-12">'.$html.'</div>';
            echo '<div style="clear:both; margin-bottom:10px;"></div>'; // clear both
		}
	?>
    <div class="modal fade col-xs-12" id="map-modal" role="dialog">
        <div class="modal-dialog col-xs-12" style="width:100%!important;">
            <!-- Modal content-->
            <div class="modal-content" style="width:100%!important;">
                <div class="modal-header">
                    <button id="btn-close" style="padding:5px;" type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 id="map-modal-title" class="modal-title" style="display:inline-block;">Search Result</h4>
                    <div style="clear:both"></div>
                </div>
                <div id="map-modal-body" class="modal-body">
                    <div id="gis_search_result">No result</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="map-error-log" class="alert alert-danger" style="display:none;"></div>
	<div id="map" style="height: <?php echo $map["height"]; ?>; width: <?php echo $map["width"]; ?>"></div>
</body>

<script type="text/javascript" src="{{ module_base_url }}assets/js/leaflet/dist/leaflet.js"></script>
<script type="text/javascript" src="{{ module_base_url }}assets/js/leaflet-label/Label.js"></script>
<?php
// only load google's stuff if needed
if ($map["gmap_roadmap"] || $map["gmap_satellite"] || $map["gmap_hybrid"]){
	echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.2&sensor=false"></script>';
	echo '<script type="text/javascript" src="{{ module_base_url }}assets/js/leaflet-google/Google.js"></script>';
}
for($i=0; $i<count($map["scripts"]); $i++){
	echo '<script type="text/javscript" src="'.$map["scripts"][$i].'"></script>';
}
?>
<script type="text/javascript">
	// variables from php
	var MAP_LONGITUDE = <?php echo $map["longitude"]; ?>;
	var MAP_LATITUDE = <?php echo $map["latitude"]; ?>;
	var MAP_ZOOM = <?php echo $map["zoom"]; ?>;
	var MAP_CLOUDMADE = <?php echo json_encode($map["cloudmade_basemap"]); ?>;
	var MAP_GMAP_ROADMAP = <?php echo $map["gmap_roadmap"]; ?>;
	var MAP_GMAP_SATELLITE = <?php echo $map["gmap_satellite"]; ?>;
	var MAP_GMAP_HYBRID = <?php echo $map["gmap_hybrid"]; ?>;
	var MAP_LAYER_GROUPS = <?php echo json_encode($map["layer_groups"]); ?>;

    // when user click "go to location" after search for a feature, the clicked layer name and feature code should be sent to the server. These variables store them for a while
    var FOCUS_LAYER_NAME = '';
    var FOCUS_CODE = '';

	// google captions
	var GOOGLE_ROADMAP_CAPTION = 'Google Roadmap';
	var GOOGLE_SATELLITE_CAPTION = 'Google Satellite';
	var GOOGLE_HYBRID_CAPTION = 'Google Hybrid';

	// BASE_MAPS
	var BASE_MAPS = new Object();
	var BASE_MAP_EXISTS = false;
	var SELECTED_BASE_MAP = null;

	// LAYER_GROUPS
	var LAYER_GROUPS = new Object();
	var LAYER_GROUP_INDEXES = new Object();
	var OVERLAY_MAPS = new Object();
	var SHOWN_OVERLAY_MAPS = new Object();

    // The real map object
    var MAP = null;

	// render the base maps and default_shown_base_map
	for (var i =0; i<MAP_CLOUDMADE.length; i++){
		cloudmade = MAP_CLOUDMADE[i];
		cloudmade_attribution = cloudmade["attribution"];
		cloudmade_url = cloudmade["url"];
		cloudmade_name = cloudmade["basemap_name"];
		cloudmade_max_zoom = cloudmade["max_zoom"];
		cloudmade_options = {maxZoom: cloudmade_max_zoom, attribution: cloudmade_attribution};
        try{
			BASE_MAPS[cloudmade_name] = new L.TileLayer(cloudmade_url, cloudmade_options);
			if(!BASE_MAP_EXISTS){
				SELECTED_BASE_MAP = BASE_MAPS[cloudmade_name];
				BASE_MAP_EXISTS = true;
			}
        }catch(err){
            log_error('<b>Error</b> Cannot create basemap <i>' + cloudmade_name + '</i>, make sure you are connected to the internet<br />');
        }
	}
    // render google map
	try{
		if(MAP_GMAP_ROADMAP){
			BASE_MAPS[GOOGLE_ROADMAP_CAPTION] = new L.Google('ROADMAP');
			if(!BASE_MAP_EXISTS){
				SELECTED_BASE_MAP = BASE_MAPS[GOOGLE_ROADMAP_CAPTION];
				BASE_MAP_EXISTS = true;
			}
		}
		if(MAP_GMAP_SATELLITE){
			BASE_MAPS[GOOGLE_SATELLITE_CAPTION] = new L.Google('SATELLITE');
			if(!BASE_MAP_EXISTS){
				SELECTED_BASE_MAP = BASE_MAPS[GOOGLE_SATELLITE_CAPTION];
				BASE_MAP_EXISTS = true;
			}
		}
		if(MAP_GMAP_HYBRID){
			BASE_MAPS[GOOGLE_HYBRID_CAPTION] = new L.Google('HYBRID');
			if(!BASE_MAP_EXISTS){
				SELECTED_BASE_MAP = BASE_MAPS[GOOGLE_HYBRID_CAPTION];
				BASE_MAP_EXISTS = true;
			}
		}
	}catch(err){
		log_error('<b>Error</b> Cannot create google maps, make sure you are connected to the internet<br />');
	}


	// get layer groups
	for(var i=0; i<MAP_LAYER_GROUPS.length; i++){
		var group = MAP_LAYER_GROUPS[i];
		var label = group['group_name'];
		var shown = group['shown'];
		LAYER_GROUPS[label] = new L.LayerGroup();
		if(shown>0){
			SHOWN_OVERLAY_MAPS[label] = LAYER_GROUPS[label];
    	}
		OVERLAY_MAPS[label] = LAYER_GROUPS[label];
		LAYER_GROUP_INDEXES[label] = i+1;
	}

	// define map parameter
	var max_zoom = 18;
	if(MAP_ZOOM>18){
		max_zoom = MAP_ZOOM
	}
	MAP = new L.Map('map', {
		center: new L.LatLng(MAP_LATITUDE, MAP_LONGITUDE),
		zoom: MAP_ZOOM,
		maxZoom: max_zoom,
	});
	if(BASE_MAP_EXISTS){
		MAP.addLayer(SELECTED_BASE_MAP);
	}
	for(key in OVERLAY_MAPS){
		MAP.addLayer(OVERLAY_MAPS[key]);
	}

	// add layer control, so that user can adjust the visibility of the layers
	layersControl = new L.Control.Layers(BASE_MAPS, OVERLAY_MAPS, {'collapsed':false});
	MAP.addControl(layersControl);


	// jquery css hack to show the legends and uncheck the un-shown layer groups
	for(var i=0; i<MAP_LAYER_GROUPS.length; i++){
		var group = MAP_LAYER_GROUPS[i];
		var group_name = group['group_name'];
		var layers = group['layers'];
		var layer_count = layers.length;
		// css hack to modify the legends
		var label_index = LAYER_GROUP_INDEXES[group_name];
		var label_identifier = '.leaflet-control-layers-overlays label:nth-child('+label_index+')';
		if (layer_count > 1){
			var ul_identifier = label_identifier+'>ul';
			// add ul
			$(label_identifier).append('<ul></ul>');
			for(var j=0; j<layers.length; j++){
				var layer = layers[j];
				var layer_name = layer['layer_name'];
				$(ul_identifier).append('<li id="layer_'+j+'" class="layer_legend"><div></div>'+layer_name+'</li>');
				var div_identifier = ul_identifier+' li#layer_'+j+' div';
				// add image or symbol
				if(layer['image_url']!=''){
					$(div_identifier).html('<img src="'+layer['image_url']+'" />');
					$(div_identifier+'>img').css({
						'width':layer['radius'],
						'height':layer['radius'],
						'margin-top': 10-Math.ceil(layer['radius']/2),
						'margin-left': 10-Math.ceil(layer['radius']/2),
					});
				}else{
					$(div_identifier).html('<div></div>');
					$(div_identifier+'>div').css({
						'background-color': layer['fill_color'],
						'border-color':layer['color'],
						'width':layer['radius'],
						'height':layer['radius'],
						'border-radius':Math.ceil(layer['radius']/2),
						'-moz-border-radius':Math.ceil(layer['radius']/2),
						'margin-top': 10-Math.ceil(layer['radius']/2),
						'margin-left': 10-Math.ceil(layer['radius']/2),
					});
				}
			}
		}else{
			var checkbox_identifier = label_identifier+'>input';
			var div_identifier = label_identifier+'>div';
			var layer = layers[0];
			// add div after checkbox
			$(checkbox_identifier).after('<div></div>');
			// add image or symbol
			if(layer['image_url']!=''){
				$(div_identifier).html('<img src="'+layer['image_url']+'" />');
				$(div_identifier+'>img').css({
					'width':layer['radius'],
					'height':layer['radius'],
					'margin-top': 10-Math.ceil(layer['radius']/2),
					'margin-left': 10-Math.ceil(layer['radius']/2),
				});
			}else{
				$(div_identifier).html('<div></div>');
				$(div_identifier+'>div').css({
					'background-color': layer['fill_color'],
					'border-color':layer['color'],
					'width':layer['radius'],
					'height':layer['radius'],
					'border-radius':Math.ceil(layer['radius']/2),
					'-moz-border-radius':Math.ceil(layer['radius']/2),
					'margin-top': 10-Math.ceil(layer['radius']/2),
					'margin-left': 10-Math.ceil(layer['radius']/2),
				});
			}
		}
		// uncheck and remove layer group which is not shown by default
		if(!(group_name in SHOWN_OVERLAY_MAPS)){
			var checkbox_identifier = label_identifier+'>input';
			$(checkbox_identifier).attr('checked', false);
			MAP.removeLayer(OVERLAY_MAPS[group_name]);
		}

		// control height
		var map_identifier = "#map";
		var control_identifier = ".leaflet-control-layers";
		var map_height = $(map_identifier).height();
		var control_height = $(control_identifier).height();
		var control_width = $(control_identifier).width();
		if(control_height>map_height-50){
			$(control_identifier).height(map_height-50);
			$(control_identifier).width(control_width+20);
		}
		$(control_identifier).css('overflow', 'auto');
	}// end of jquery css hack

	// fetch the layers
	fetch_layer();

	// refresh the features
	MAP.on('dragend', fetch_layer);
	MAP.on('zoomend', fetch_layer);

	// search button
	$("#btn_gis_search").click(function(){
		$('#gis_search_result').html('');
		for(var i=0; i<MAP_LAYER_GROUPS.length; i++){
			var layer_group = MAP_LAYER_GROUPS[i];
			for(var j=0; j<layer_group['layers'].length; j++){
				var layer = layer_group['layers'][j];
				var searchable = layer["searchable"]>0;
				if(searchable){
					var layer_name = layer["layer_name"];
                    var data = {keyword: $('#gis_search_keyword').val()};
                    // override data sent by using AJAX
                    if(typeof override_search_data === 'function'){
                        var return_value = override_search_data(layer_name);
                        if(typeof return_value !== 'undefined' && return_value !== {}){
                            return_value.keyword = data.keyword;
                            data = return_value;
                        }
                    }
                    // AJAX call
					if($('#gis_search_layer_'+i+'_'+j).prop('checked')){
						//console.log('checked');
						var search_url = layer["search_url"];
						$.ajax({
                            'parse_data': {'layer_name': layer_name,},
							'url': search_url,
							'data': data,
							'type': 'POST',
							'dataType': 'json',
							'success': function(response){
								//console.log(response);
								for(var j=0; j<response.length; j++){
									var data = response[j];
									var html = '';
									html += '<div class="well">';
									html += '<div class="result_content">'+data.result_content+'</div>';
									html += '<a class="result_link btn btn-default" data-dismiss="modal" href="<?php echo site_url($gis_path.'/index/'.$map["map_id"]);?>/'+
										data.longitude+'/'+data.latitude+'" focus_layer_name="'+this.parse_data.layer_name+'" focus_code="'+(typeof data.code == 'undefined'? '' : data.code)+'" longitude="'+data.longitude+'" latitude="'+data.latitude+'">Go To Location</a>';
									html += '<div>';
									$('#gis_search_result').append(html);
								}

							}
						});
					}
				}
			}
		}
	}); // end of search button click

	// result_link
	$('body').on('click', 'a.result_link',function(){
        // get longitude and latitude
        var longitude = $(this).attr('longitude');
        var latitude = $(this).attr('latitude');
        // let the server now, which layer and what is the code
        FOCUS_LAYER_NAME = $(this).attr('focus_layer_name');
        FOCUS_CODE = $(this).attr('focus_code');
        // teleport
        var newLocation = new L.LatLng(latitude, longitude);
        MAP.setView(newLocation, 19);
        fetch_layer();
		return false;
	});

    function log_error(message){
        $('div#map-error-log').append(message.trim());
        if($('div#map-error-log').html() != ''){
            $('div#map-error-log').show();
        }else{
            $('div#map-error-log').hide();
        }
    }

	function fetch_layer(){
		var loaded_group = new Object();
		var map_zoom = MAP.getZoom();
		var bounds = MAP.getBounds();
		var southWest = bounds.getSouthWest();
		var northEast = bounds.getNorthEast();
		var map_region = 'POLYGON(('+
			northEast.lng+' '+northEast.lat+', '+
			northEast.lng+' '+southWest.lat+', '+
			southWest.lng+' '+southWest.lat+', '+
			southWest.lng+' '+northEast.lat+', '+
			northEast.lng+' '+northEast.lat+'))';

		// delete layers from groups
		for(var i=0; i<MAP_LAYER_GROUPS.length; i++){
			var group = MAP_LAYER_GROUPS[i];
			var group_name = group['group_name'];
			var layers = group['layers'];
			// delete everything from the LAYER_GROUPS
			LAYER_GROUPS[group_name].clearLayers();
			// add layers to the groups
			for(var j=0; j<layers.length; j++){
				var layer = layers[j];
				var json_url = layer["json_url"];
                var layer_name = layer['layer_name'];
                // define data to be sent via ajax and override if necessary
                var data = {
                    'map_region': map_region,
                    'map_zoom': map_zoom,
                    'focus_layer_name': FOCUS_LAYER_NAME,
                    'focus_code': FOCUS_CODE,
                };
                if(typeof override_fetch_data === 'function'){
                    var return_value = override_fetch_data(layer_name);
                    if(typeof return_value !== 'undefined' && return_value !== {}){
                        return_value.map_region = data.map_region;
                        return_value.map_zoom = data.map_zoom;
                        return_value.focus_layer_name = data.focus_layer_name;
                        return_value.focus_code = data.focus_code;
                        data = return_value;
                    }
                }
				// get geoJSON from the server
				$.ajax({
					//async: false,
					'parse_data': {
						'layer': layer,
						'group_name': group_name},
					'url' : json_url,
					'type' : 'POST',
					'data' : data,
					'dataType' : 'json',
					'error' : function(response, textStatus, errorThrown){
						log_error("<b>Error</b> Failed to load <i>"+
							this.parse_data.layer["layer_name"]+"</i> layer with status <i>"+
							textStatus+'</i>, '+errorThrown+'<br />');
					},
					'success' : function(response){
						//console.log(response);
						//console.log(response,'Geo JSON From '+this.parse_data.layer['layer_name']);
						layer = this.parse_data.layer;
						group_name = this.parse_data.group_name;
						geojson_feature = response;
						// make geojson layer
						var point_config = null;
						var style = null;
						if(geojson_feature['features'].length>0){
							var feature_type = geojson_feature['features'][0]['geometry']['type'];
							feature_type = feature_type.toUpperCase();
							var is_point = (feature_type=='POINT');
							var is_linestring = (feature_type=='LINESTRING');
							var is_polygon = (feature_type=='POLYGON');
							// style
							style = {
								radius : layer['radius'],
								fillColor: layer['fill_color'],
								color: layer['color'],
								weight: layer['weight'],
								opacity: layer['opacity'],
								fillOpacity: layer['fill_opacity'],
								stroke: true,
							};

							// if point
							if(is_point){
								if(layer['image_url']){
									var image_url = layer['image_url'];
									point_config = {
										pointToLayer: function (latlng){
									        return new L.Marker(latlng, {
									            icon: new L.Icon({
									            	iconUrl: image_url,
													shadowUrl: null,
													iconSize: new L.Point(layer['radius'], layer['radius']),
													shadowSize: null,
													iconAnchor: new L.Point(Math.ceil(layer['radius']/2), Math.ceil(layer['radius']/2)),
													popupAnchor: new L.Point(2, -layer['radius'])
									            }),
									        });
									    }
									};
								}else{
									point_config = {
									    pointToLayer: function (latlng) {
									        return new L.CircleMarker(latlng, style);
									    },
									};
									//console.log(style);
								}
							}

							var geojson_layer = null;
							if(is_point){
								geojson_layer = new L.GeoJSON(geojson_feature, point_config);
							}else{
								geojson_layer = new L.GeoJSON(geojson_feature);
							}

							geojson_layer.on("featureparse", function (e) {
								// the popups
								if (e.properties && e.properties.popupContent) {
							        popupContent = e.properties.popupContent;
							        e.layer.bindPopup(popupContent);
							    }

							    // the style (for point we need special treatment)
							    if(!is_point){
							    	e.layer.setStyle(style);
							    }

							});

							geojson_layer.addGeoJSON(geojson_feature);

							// add geojson layer to LAYER_GROUPS
							LAYER_GROUPS[group_name].addLayer(geojson_layer);

							// labels
							if(is_point || is_polygon || is_linestring){
								// for each point, we should make a more elegant way
								for(var i=0; i<geojson_feature['features'].length; i++){
									var geojson_single_feature = new Object();
									if(is_point){
										geojson_single_feature = {
											"type":"FeatureCollection",
											"features":[geojson_feature['features'][i]]
										};
									}else if(is_linestring){
										var original_feature = geojson_feature['features'][i];
                                        var middle_index =  Math.floor(original_feature['geometry']['coordinates'].length/2);
										geojson_single_feature = {
											"type":"FeatureCollection",
											"features":[{
												"type": "Feature",
												"properties": original_feature['properties'],
												"geometry":{
													"type": "Point",
													"coordinates": original_feature['geometry']['coordinates'][middle_index]
												}
											}]
										};
									}else if(is_polygon){
										var original_feature = geojson_feature['features'][i];
                                        var middle_index =  Math.floor(original_feature['geometry']['coordinates'][0].length/2);
										geojson_single_feature = {
											"type":"FeatureCollection",
											"features":[{
												"type": "Feature",
												"properties": original_feature['properties'],
												"geometry":{
													"type": "Point",
													"coordinates": original_feature['geometry']['coordinates'][0][middle_index]
												}
											}]
										};
									}

									var label = geojson_feature['features'][i]['properties']['label'];
									var point_config = {
										pointToLayer: function (latlng){
									        return new L.Marker(latlng,{
									            icon: new L.Icon.Text(label,{})
									        });
									    }
									};

									// console.log(geojson_single_feature,'- feature for label '+label);

									var geojson_label = new L.GeoJSON(geojson_single_feature, point_config);
									geojson_label.on("featureparse", function (e) {
										// the popups
										if (e.properties && e.properties.popupContent) {
									        popupContent = e.properties.popupContent;
									    }else{
										    popupContent = '';
									    }
									    e.layer.bindPopup(popupContent);

									});

									geojson_label.addGeoJSON(geojson_single_feature);

									// add geojson_label to layer_group
									LAYER_GROUPS[group_name].addLayer(geojson_label);
								}
							}// end of if label


						}

						// add loaded_group
						loaded_group[group_name]=true;
						var complete_response = true;
						for(key in LAYER_GROUPS){
							if(!loaded_group[key]){
								complete_response = false;
								break;
							}
						}
						// if all layer groups has been loaded, then sort them
						if(complete_response){
							// remove all layer group add them in reverse order to maintain z-index
							var keys=new Array();
							for(key in LAYER_GROUPS){
								if(MAP.hasLayer(LAYER_GROUPS[key])){
									MAP.removeLayer(LAYER_GROUPS[key]);
									keys[keys.length] = key;
								}
							}
							for(var i=0; i<keys.length; i++){
								group = MAP_LAYER_GROUPS;
								MAP.addLayer(LAYER_GROUPS[keys[i]]);
							}
						}

					}// end of success
				});// end of $.ajax
			}// end for add layers to group
		}

	}// end of function fetch_layer


    <?php echo $map['custom_javascript']; ?>
</script>
