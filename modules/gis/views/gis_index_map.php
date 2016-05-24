<head>
	<link rel="stylesheet" type="text/css" href="{{ module_base_url }}assets/js/leaflet/dist/leaflet.css" />
	<!--[if lte IE 8]><link rel="stylesheet" href="{{ module_base_url }}assets/js/leaflet/dist/leaflet.ie.css" /><![endif]-->
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

	</style>

</head>
<body>
	<div id="map" style="height: <?php echo $map["height"]; ?>; width: <?php echo $map["width"]; ?>"></div>
	<div id="message"></div>
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
					$html.= '<label class=".checkbox">
						<input id="gis_search_layer_'.$i.'_'.$j.'" name="options" type="checkbox" value="'.$layer_name.'" checked />'.
						$layer_name.'</label>&nbsp;&nbsp;';
				}
			}
		}
		// make the search form if needed
		if($need_search_form){
			$html.= '<div class="well form-inline">
				<input id="gis_search_keyword" type="text" />
				<input id="btn_gis_search" type="button" value="Search On The Map" />
			</div>';
		}
		if($html != ""){
			echo '<div class="well form-inline">'.$html.'</div>';
			echo '<div id="gis_search_result"></div>';
		}
	?>
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
		var map_longitude = <?php echo $map["longitude"]; ?>;
		var map_latitude = <?php echo $map["latitude"]; ?>;
		var map_zoom = <?php echo $map["zoom"]; ?>;
		var map_cloudmade = <?php echo json_encode($map["cloudmade_basemap"]); ?>;
		var map_gmap_roadmap = <?php echo $map["gmap_roadmap"]; ?>;
		var map_gmap_satellite = <?php echo $map["gmap_satellite"]; ?>;
		var map_gmap_hybrid = <?php echo $map["gmap_hybrid"]; ?>;
		var map_layer_groups = <?php echo json_encode($map["layer_groups"]); ?>;

		// google captions
		var google_roadmap_caption = 'Google Roadmap';
		var google_satellite_caption = 'Google Satellite';
		var google_hybrid_caption = 'Google Hybrid';

		// baseMaps
		var baseMaps = new Object();
		var baseMapExists = false;
		var selectedBaseMap = null;

		// layer_groups
		var layer_groups = new Object();
		var layer_group_indexes = new Object();
		var overlayMaps = new Object();
		var shown_overlayMaps = new Object();

		$(document).ready(function(){

			// render the base maps and default_shown_base_map
			for (var i =0; i<map_cloudmade.length; i++){
				cloudmade = map_cloudmade[i];
				cloudmade_attribution = cloudmade["attribution"];
				cloudmade_url = cloudmade["url"];
				cloudmade_name = cloudmade["basemap_name"];
				cloudmade_max_zoom = cloudmade["max_zoom"];
				cloudmade_options = {maxZoom: cloudmade_max_zoom, attribution: cloudmade_attribution};
				baseMaps[cloudmade_name] = new L.TileLayer(cloudmade_url, cloudmade_options);
				if(!baseMapExists){
					selectedBaseMap = baseMaps[cloudmade_name];
					baseMapExists = true;
				}
			}
			try{
				if(map_gmap_roadmap){
					baseMaps[google_roadmap_caption] = new L.Google('ROADMAP');
					if(!baseMapExists){
						selectedBaseMap = baseMaps[google_roadmap_caption];
						baseMapExists = true;
					}
				}
				if(map_gmap_satellite){
					baseMaps[google_satellite_caption] = new L.Google('SATELLITE');
					if(!baseMapExists){
						selectedBaseMap = baseMaps[google_satellite_caption];
						baseMapExists = true;
					}
				}
				if(map_gmap_hybrid){
					baseMaps[google_hybrid_caption] = new L.Google('HYBRID');
					if(!baseMapExists){
						selectedBaseMap = baseMaps[google_hybrid_caption];
						baseMapExists = true;
					}
				}
			}catch(err){
				$("div#message").append('Cannot create google maps');
			}


			// get layer groups
			for(var i=0; i<map_layer_groups.length; i++){
				var group = map_layer_groups[i];
				var label = group['group_name'];
				var shown = group['shown'];
				layer_groups[label] = new L.LayerGroup();
				if(shown>0){
					shown_overlayMaps[label] = layer_groups[label];
		    	}
				overlayMaps[label] = layer_groups[label];
				layer_group_indexes[label] = i+1;
			}

			// define map parameter
			var max_zoom = 18;
			if(map_zoom>18){
				max_zoom = map_zoom
			}
			var map = new L.Map('map', {
				center: new L.LatLng(map_latitude, map_longitude),
				zoom: map_zoom,
				maxZoom: max_zoom,
			});
			if(baseMapExists){
				map.addLayer(selectedBaseMap);
			}
			for(key in overlayMaps){
				map.addLayer(overlayMaps[key]);
			}

			// add layer control, so that user can adjust the visibility of the layers
			layersControl = new L.Control.Layers(baseMaps, overlayMaps, {'collapsed':false});
			map.addControl(layersControl);


			// jquery css hack to show the legends and uncheck the un-shown layer groups
			for(var i=0; i<map_layer_groups.length; i++){
				var group = map_layer_groups[i];
				var group_name = group['group_name'];
				var layers = group['layers'];
				var layer_count = layers.length;
				// css hack to modify the legends
				var label_index = layer_group_indexes[group_name];
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
				if(!(group_name in shown_overlayMaps)){
					var checkbox_identifier = label_identifier+'>input';
					$(checkbox_identifier).attr('checked', false);
					map.removeLayer(overlayMaps[group_name]);
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
			fetchLayer();

			// refresh the features
			map.on('dragend', fetchLayer);
			map.on('zoomend', fetchLayer);

			// search button
			$("#btn_gis_search").click(function(){
				$('#gis_search_result').html('');
				for(var i=0; i<map_layer_groups.length; i++){
					var layer_group = map_layer_groups[i];
					for(var j=0; j<layer_group['layers'].length; j++){
						var layer = layer_group['layers'][j];
						var searchable = layer["searchable"]>0;
						if(searchable){
							layer_name = layer["layer_name"];
							if($('#gis_search_layer_'+i+'_'+j).attr('checked')){
								//console.log('checked');
								var search_url = layer["search_url"];
								$.ajax({
									url: search_url,
									data: {keyword: $('#gis_search_keyword').val()},
									type: 'POST',
									dataType: 'json',
									success: function(response){
											//console.log(response);
											for(var j=0; j<response.length; j++){
												var data = response[j];
												var html = '';
												html += '<div>';
												html += '<div class="result_content">'+data.result_content+'</div>';
												html += '<input class="result_longitude" type="hidden" value="'+data.longitude+'" />';
												html += '<input class="result_latitude" type="hidden" value="'+data.latitude+'" />';
												html += '<a class="result_link" href="<?php echo site_url($gis_path.'/index/'.$map["map_id"]);?>/'+
													data.longitude+'/'+data.latitude+'">Go To Location</a>';
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
			$('a.result_link').live('click',function(){
				var longitude = $(this).parent().children('.result_longitude').val();
				var latitude = $(this).parent().children('.result_latitude').val();
				var newLocation = new L.LatLng(latitude, longitude);
		        // teleport
		        map.setView(newLocation, 19);
		        fetchLayer();
				return false;
			});




			function fetchLayer(){
				var loaded_group = new Object();
				var map_zoom = map.getZoom();
				var bounds = map.getBounds();
				var southWest = bounds.getSouthWest();
				var northEast = bounds.getNorthEast();
				var map_region = 'POLYGON(('+
					northEast.lng+' '+northEast.lat+', '+
					northEast.lng+' '+southWest.lat+', '+
					southWest.lng+' '+southWest.lat+', '+
					southWest.lng+' '+northEast.lat+', '+
					northEast.lng+' '+northEast.lat+'))';

				// delete layers from groups
				for(var i=0; i<map_layer_groups.length; i++){
					var group = map_layer_groups[i];
					var group_name = group['group_name'];
					var layers = group['layers'];
					// delete everything from the layer_groups
					layer_groups[group_name].clearLayers();
					// add layers to the groups
					for(var j=0; j<layers.length; j++){
						var layer = layers[j];
						var json_url = layer["json_url"];

						// get geoJSON from the server
						$.ajax({
							//async: false,
							parse_data: {
								layer: layer,
								group_name: group_name},
							url : json_url,
							type : 'POST',
							data : {
								map_region: map_region,
								map_zoom: map_zoom},
							dataType : 'json',
							error : function(response, textStatus, errorThrown){
									$("#message").append("Failed to load <b>"+
											this.parse_data.layer["layer_name"]+"</b> layer with status <b>"+
											textStatus+'</b>, '+errorThrown+'<br />');
								},
							success : function(response){
									//console.log(response);
									// console.log(response,'Geo JSON From '+this.parse_data.layer['layer_name']);
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
														            })
													        });
													    }
													};
											}else{
												point_config = {
													    pointToLayer: function (latlng) {
													        return new L.CircleMarker(latlng,
															        style
													        );
													    },
													};
												//console.log(style);
											}
										}

										var geojson_layer = null;
										if(is_point){
											geojson_layer = new L.GeoJSON(geojson_feature, point_config	);
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

										// add geojson layer to layer_groups
										layer_groups[group_name].addLayer(geojson_layer);

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
													geojson_single_feature = {
															"type":"FeatureCollection",
															"features":[{
																	"type": "Feature",
																	"properties": original_feature['properties'],
																	"geometry":{
																			"type": "Point",
																			"coordinates": original_feature['geometry']['coordinates'][0]
																		}
																}]
														};
												}else if(is_polygon){
													var original_feature = geojson_feature['features'][i];
													geojson_single_feature = {
															"type":"FeatureCollection",
															"features":[{
																	"type": "Feature",
																	"properties": original_feature['properties'],
																	"geometry":{
																			"type": "Point",
																			"coordinates": original_feature['geometry']['coordinates'][0][0]
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

												var geojson_label = new L.GeoJSON(geojson_single_feature, point_config	);
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
												layer_groups[group_name].addLayer(geojson_label);
											}
										}// end of if label


									}

									// add loaded_group
									loaded_group[group_name]=true;
									var complete_response = true;
									for(key in layer_groups){
										if(!loaded_group[key]){
											complete_response = false;
											break;
										}
									}
									// if all layer groups has been loaded, then sort them
									if(complete_response){
										// remove all layer group add them in reverse order to maintain z-index
										var keys=new Array();
										for(key in layer_groups){
											if(map.hasLayer(layer_groups[key])){
												map.removeLayer(layer_groups[key]);
												keys[keys.length] = key;
											}
										}
										for(var i=keys.length-1; i>=0; i--){
											group = map_layer_groups;
											map.addLayer(layer_groups[keys[i]]);
										}
									}

								}// end of success
						});// end of $.ajax
					}// end for add layers to group
				}

			}// end of function fetchLayer


		});
	</script>
