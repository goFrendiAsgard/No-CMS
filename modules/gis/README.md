GIS Module
==========
GIS Module is a container of maps. Each map can contains of:
* Layers
* Basemap Layers
* Custom HTML for Searching
* Custom Javascript

You can add, edit, or delete maps by accessing `GIS | Manage Map`.
To view your map, just click on `GIS` menu. There will be a list contains of your existing maps. Click one of them, and you will be able to see the map.

To bind any particular map into a navigation/page, Go to `CMS Management | Navigation Management`, create a new navigation with `static content` option disabled. Then put `gis/index/[map-id]` as `url`. You can get the `map-id` by viewing that particular map and observe the address url.

Layers
------
Layers can contains either `points`, `lines`, `polygons` or other geometry structure, usually stored in database. You can add, edit, or delete layer by accessing `GIS | Manage Map`, then add or edit existing map, and click `Layers` Tab.

Several layers can be merged into a `group`. For example, you have `Airport by usage` group which is contains of two layers `Civilian/Public` and `Military`. Please look at the example to get a better idea.

There is an `Edit` button next to each layer. You can click on them to edit the existing layer. You can also click on `Manage Layers` to fully manage the layers.

`Z-Index` determine layer's order. The bigger `z-index` means that the layer will be put on top of the others. Smaller `z-index` means that the layer will be put below the others.

`Shown` determine whether the layer is initally visible or not.

`Radius`, `Fill Color`, `Color`, `Weight`, `Opacity`, and `Fill Opacity` determine appearance of any `point`/`line`/`polygon` in the layer. If your features contains `point`, you can also use `Image Url` for custom image.

__JSON__

In GIS Module, the features is delivered by using AJAX as `geojson` format. While retrieving the feature, Gis Module will send the following POST data:

* __map_region__ : Map region in WKT format.
* __map_zoom__ : Map zoom level.
* __focus_layer_name__ : The layer name. Only used whenever the layer is loaded by user's action of clicking `Go to location` after search.
* __focus_code__ : Only used when user click `Go to location`.

If you want to add more data, you can make a javascript function in `Custom` section of your map. The function should be named `override_fetch_data` and should contains a parameter contains `layer_name`. This is especially useful if you want to make a layer that contains dynamic features based on user's search.

Below is the expected format:

```json
    {
        "type":"FeatureCollection",
        "features":[
            {
                "type":"Feature",
                "properties":{
                    "popupContent":"<b>PORT CLARENCE CGS<\/b><br \/><p> Usage : Other<br \/> Elevation : 9<br \/><\/p>",
                    "label":"PORT CLARENCE CGS"
                },
                "geometry":{
                    "type":"Point",
                    "coordinates":[-166.85852049347,65.253669742378]
                }
            },
            {
                "type":"Feature",
                "properties":{
                    "popupContent":"<b>RALPH M CALHOUN<\/b><br \/><p> Usage : Other<br \/> Elevation : 207<br \/><\/p>",
                    "label":"RALPH M CALHOUN"
                },
                "geometry":{
                    "type":"Point",
                    "coordinates":[-152.10939025352,65.174392701071]
                }
            },
        ]
    }
```

`Label` and `popupContent` are used to determine the label of your feature and what will be appeared when the feature is clicked.

There are two ways to provide JSON for your layer:

The first way is by using `JSON Url`. To use `JSON Url`, go to `JSON` tab, activate `Use JSON Url`, and fill out `JSON Url`.

The second way is by writing your own SQL. To use this, go to `JSON` tab, deactivate `Use JSON Url`, then you should fill out several things:

* __Json Sql__ : The SQL to select feature. You can use several parameters in your sql. `@map_region` is your map rectangle boundary in `WKT` format. `@map_zoom` is your map zooming level. If you have a large set of features you can use `@map_region` and `@map_zoom`, so that only necessary features loaded in the map. Here is an example:

    ```Sql
    SELECT `cat`, `name`, `use`, `elev`, astext(`shape`) as `shape`, x(`shape`) as `lat`, y(`shape`) as `long`
FROM cms_gis_alaska_airport
WHERE `use`='Civilian/Public' AND (MBRIntersects(`shape`,geomfromtext('@map_region'))=1) AND (@map_zoom > 3) ;
    ```

* __Json Shape Column__ : The column from your `Json Sql` that represent the feature in `WKT` format. By using the above `Json Sql`, the `Json Shape Column` should be `shape`.

* __Json Popup Content__ : The popup content of your feature. You can use `@[field-name]` where `field-name` is any field in your `Json Sql`. By using the above `Json Sql`, the following `Json Popup Content` is valid:

    ```HTML
        <b>Civilian Airport</b><br />
Name: @name<br />
Latitude: @lat<br />
Longitude:@long
    ```

__Search__
Layer can also be searchable. Whenever user click `Search on Map` button, the search request will be sent by using POST method. The request will at least contains __keyword__

If you want to send more data, you can make a javascript function in `Custom` section of your map. The function should be named `override_search_data` and should contains a parameter contains `layer_name`.

To make the layer searchable, first you must activate `searchable` option in your layer's `search` tab.

After activating `searchable` option, there are two ways to make your layer searchable.

The first way is by activating `Use search Url` and fill in the `Search Url` option.
Here is the expected response of your search url:

```json
    [
    {
        "result_content" : "<b>NOATAK<\/b><br \/>\nUsage : Other<br \/>\nElevation : 78<br \/>\nCoordinate: (-162.97528075057514,67.56208038542596)",
        "latitude":"67.56208038542596",
        "longitude":"-162.97528075057514",
        "code":"name-NOATAK-use-Other-elev-78-x--162.97528075057514-y-67.56208038542596"
    }
]
```

The second way is by deactivating `Use search Url` and write `Search Sql` instead. Here are several things you need to fill out:

* __Search sql__ : The SQL to select fature. You can use `@keyword` in your sql. Here is the sample of valid example:

    ```sql
    SELECT `name`, `use`, `elev`, x(`shape`) as `x`, y(`shape`) as `y`
FROM cms_gis_alaska_airport
WHERE name LIKE '%@keyword%';
    ```

* __Search result content__ : The HTML of each record. Example:

    ```HTML
    <b>@name</b><br />
Usage : @use<br />
Elevation : @elev<br />
Coordinate: (@x,@y)
    ```

* __Search result x column__ : The column represent longitude

* __Search result y column__ : The column represent latitude

Basemap Layers
--------------
The background of your map. You can add basemap layers by visiting `Basemap` tab in your map.

Custom HTML
-----------
Beside `keyword`, you can add another input to your search form.

Custom Javascript
-----------------
You can also add custom script. There are two reserved functions you can create in order to override default behavior:

* __override_search_data__
* __override_fetch_data__

Each functions take one parameter `layer name` and should return a javascript object representing data to be sent to the server
