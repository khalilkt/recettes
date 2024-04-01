<?php

$libelle = trans("text.libelle_base");
$grade = "";
$color = "";
//echo $getcolor;
$i = 0;
$len = count($legands);
foreach ($legands as $l) {
    $grade = $grade . "'$l[$libelle]'";
    $color = $color . "'$l[color]'";
    if ($i != $len - 1) {
        $grade = $grade . ',';
        $color = $color . ',';
    }
    $i++;

}


?>

<div style="display: none;" id="entete_carte">
    <div style="width: 100%">
        @if(env('DCS_APP')=='0')
            <img src="{{ url('img/header.jpg')  }}">
        @else
            <img src="{{ url('img/header_medd.jpg')  }}">
        @endif
    </div>
    <h3 class="text-center">{{ trans("text.decoupage_admin") }}</h3>
    <h5>{{ trans("text.niveau") }} : {{ trans("text.Wilayas") }}</h5>
</div>
<div id="map"></div>

<div class="panel panel-default souscond_tb kk " >
    <div class="panel-heading"  role="tab" id="headingOne4">
        <form role="formexport"  id="formexport" name="formexport" class=""  method="post" >
            {{ csrf_field() }}
            <input type="hidden" name="data" value="{{ $getcolor }}" />
            <input type="hidden" name="module" value="{{ $module }}" />
            <input type="hidden" name="ref" value="{{ $ref }}" />
            <input type="hidden" name="niveau" value="3" />
        </form>
        <h4 class="panel-title">

            <a class="collapsed " role="button" data-toggle="collapse" data-parent="#tb" href="#tb" aria-expanded="false" aria-controls="tb"  onclick="plus_info_commune(this,{ <?php echo $getcolor; ?> },'{{ $module }}','{{ $ref }}',3)">
                <i class="more-less fa fa-plus " ></i> {{ trans("text.voir_result") }}
            </a>
            <button type="button" onclick="export_tab_commune(this,{ <?php echo $getcolor; ?> },'{{ $module }}','{{ $ref }}')" class="btn btn-success {{ trans('text.pul') }} exp_fiche_excel" style="padding: 3px 6px;font-size: 12px;margin-top: -5px;"><i class="fa fa-file-excel-o exp_excel"></i> {{ trans("text.export") }} </button>
        </h4>

    </div>
    <div id="tb" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tb">
        <div class="panel-body" style="padding: 3px!important;">
            <div id="resultat_detait_info">

            </div>
        </div>
    </div>

</div>
<input id="marker" type="hidden"  />
<table style='display: none; margin-top:20px; width: 100%!important;' class="imp" id="imp_carte_info">
    <tr>
        <td style="width: 50%;vertical-align: top;">
            <div id="filter" class="legende1 map-legend1"></div>
        </td>
        <td style="width: 50%; margin-right: 0px!important; padding-left:40px;">
            <div id="leg_imp" class="legende1 map-legend1"></div>
        </td>
    </tr>
</table>


<script type="text/javascript">
    // variable
    // Initialisation de la map
    // variable global
    var couche_aglc;
    var  geojson;
    var couche_parc;
    var limit_parc;
    var parc;
    var overlayMaps_wilaya;

   /* $(document).ready(function () {


        couche_aglc = L.geoJson(aglc, {

            //pane: "pane450",

            style: style1,
            onEachFeature: onEachFeature1,

        });
        couche_parc = L.geoJson(parc, {
            style: style2,
            onEachFeature: onEachFeature2,

        });
        limit_parc = L.geoJson(limit_pnba, {
            style: style_limit_parc,
            onEachFeature: onEachFeature_limit_parc,

        });


        parcs = L.layerGroup([couche_parc, limit_parc]);



    });*/




        //var map = L.map('map').setView([21, -10], 6).setMinZoom(6).setMaxZoom(12).setMaxBounds([[28, -21], [14, 5]]);
        var map = L.map('map').setView([20.7, -10], 5.8).setMinZoom(6).setMaxZoom(18).setMaxBounds([[27.6, -29], [13.4, 2.4]]);
        /*// Ajout du couche Open Streets Map
         L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
         attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
         }).addTo(map);
         // Declaration des Variables*/

    geojson = L.geoJson(wilaya, {
        style: style,
        onEachFeature: onEachFeature
    }).addTo(map);

    couche_aglc = L.geoJson(aglc, {

        //pane: "pane450",

        style: style1,
        onEachFeature: onEachFeature1,

    });
    couche_parc = L.geoJson(parc, {
        style: style2,
        onEachFeature: onEachFeature2,

    });
    limit_parc = L.geoJson(limit_pnba, {
        style: style_limit_parc,
        onEachFeature: onEachFeature_limit_parc,

    });


    parcs = L.layerGroup([couche_parc, limit_parc]);

    var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmAttrib='Map data © <a href="http://osm.org/copyright">OpenStreetMap</a> contributors';

    var token = 'pk.eyJ1IjoiZG9tb3JpdHoiLCJhIjoiY2o0OHZuY3MwMGo1cTMybGM4MTFrM2dxbCJ9.yCQe43DMRqobazKewlhi9w';
    var mapboxUrl = 'https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}@2x?access_token=' + token;
    var mapboxAttrib = 'Map data © <a href="http://osm.org/copyright">OpenStreetMap</a> contributors. Tiles from <a href="https://www.mapbox.com">Mapbox</a>.';


    var osmLayer = L.tileLayer(osmUrl, {
        attribution: osmAttrib,
        maxZoom: 19
    });

    var mapbox = L.tileLayer(map, {
        attribution: mapboxAttrib,
        maxZoom: 19
    });

   var  url_trans = 'http://{s}.tile.thunderforest.com/transport/{z}/{x}/{y}.png';
    var Attrib_trans='&copy; <a href="http://openstreetmap.org">OpenStreetMap</a>">OpenStreetMap</a>&& <a href="http://thunderforest.com/">Thunderforest</a> ';

    var trans = L.tileLayer(url_trans, {
        attribution: Attrib_trans,
        maxZoom: 19
    });

   /* mapLink =
            '<a href="http://www.esri.com/">Esri</a>';
    wholink =
            'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP,\
           and the GIS User Community';
    L.tileLayer(
            'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServe\
          r/tile/{z}/{y}/{x}', {
                attribution: '&copy; '+mapLink+', '+wholink,
                maxZoom: 18,
            }).addTo(map);*/

    var  url_sat = 'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServe\
          r/tile/{z}/{y}/{x}';
    var Attrib_sat='<a href="http://www.esri.com/">Esri</a> i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP,and the GIS User Community';


    var sat = L.tileLayer(url_sat, {
        attribution: Attrib_sat,
        maxZoom: 19
    });
    // la couche "osmLayer" est ajoutée à la carte
   /* map.addLayer(osmLayer);

    var osmLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        });*/

        // la couche "osmLayer" est ajoutée à la carte
        //map.addLayer(osmLayer);

        // création d'une couche "watercolorLayer"
        var watercolorLayer = L.tileLayer('http://{s}.tile.stamen.com/watercolor/{z}/{x}/{y}.jpg', {
            attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        });

  /*  var ggRoadmap = new L.Google('ROADMAP');
    var ggSatellite = new L.Google('');
    var ggTerrain = new L.Google('TERRAIN');
    var ggHybrid = new L.Google('HYBRID');*/
        /* var transport = L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
         attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
         maxZoom: 19
         });*/

        // la couche "watercolorLayer" est ajoutée à la carte
        // map.addLayer(watercolorLayer);
    map.addLayer(sat);
        // création d'un contrôle des couches pour modifier les couches de fond de plan
        var baseLayers = {
            '{{ trans("text.satellite") }}':sat,
            '{{ trans("text.wc")}}':watercolorLayer,
            '{{ trans("text.osm") }}': osmLayer



        };

    var greenIcon = L.icon({
        iconUrl: racine+'vendor/leaflet/images/lake.png',
        shadowUrl: racine+'vendor/leaflet/images/lake.png'

       /*iconSize:     [38, 95], // size of the icon
        shadowSize:   [50, 64], // size of the shadow
        iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
        shadowAnchor: [4, 62],  // the same for the shadow
        popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor*/
    });


     /*var alague = L.marker([17.1112,  -14.0012], {icon: greenIcon}).bindPopup('Lac d’Aleg'),
            mal    = L.marker([16.925, -13.389722200000051], {icon: greenIcon}).bindPopup('Lac de Maâl'),
            bougue    = L.marker([16.5413889, -10.800555600000052], {icon: greenIcon}).bindPopup('La mare de Bougari'),
            tintane    = L.marker([16.24128, -10.09335], {icon: greenIcon}).bindPopup('La mare de Tintane'),
            kour    = L.marker([16.42097, -10.11081], {icon: greenIcon}).bindPopup('La mare de Kour'),
            oum_lyli    = L.marker([16.23091, -9.18108], {icon: greenIcon}).bindPopup('La mare d’Oum Lelli'),
            kervy    = L.marker([16.6912149, -9.425], {icon: greenIcon}).bindPopup('La mare de Kervy'),
            zeweina    = L.marker([16.6912149, -9.39538], {icon: greenIcon}).bindPopup('La mare de Zeweina'),
            swel    = L.marker([16.6912149, -9.54509740000031], {icon: greenIcon}).bindPopup('La mare de Swell'),
            mahmouda    = L.marker([16.3591648, -7.68737060], {icon: greenIcon}).bindPopup('La mare de Mahmouda'),
            tough_mare    = L.marker([16.0747222, -7.56944440], {icon: greenIcon}).bindPopup('La mare de Tough');*/

    var poly=L.polygon([[16.514281,-16.292108],
        [16.629352,-16.151819],
        [16.703818,-16.124713],
        [16.694979,-16.0947],
        [16.664042,-16.07854],
        [16.614808,-16.022292],
        [16.584236,-15.817499],
        [16.607182,-15.739401],
        [16.642071,-15.704175],
        [16.705477,-15.680728],
        [16.772281,-15.56482],
        [16.804618,-15.53144],
        [16.836221,-15.52217],
        [16.89521,-15.537904],
        [16.936536,-15.499082],
        [17.00953,-15.36436],
        [17.010124,-15.290623],
        [16.952736,-15.282702],
        [16.921123,-15.268451],
        [16.907431,-15.24461],
        [16.903582,-15.181835],
        [16.926855,-15.097961],
        [17.029404,-14.960158],
        [17.145186,-14.860802],
        [17.213002,-14.82538],
        [17.228852,-14.770496],
        [17.220848,-14.709387],
        [17.265517,-14.568782],
        [17.218791,-14.445234],
        [17.212512,-14.326957],
        [17.364534,-14.209152],
        [17.392174,-14.297264],
        [17.383851,-14.436059],
        [17.452916,-14.547952],
        [17.399849,-14.660583],
        [17.391086,-14.73745],
        [17.373558,-14.894545],
        [17.346008,-14.945178],
        [17.241655,-15.01313],
        [17.12079,-15.110489],
        [17.075717,-15.158439],
        [17.09019,-15.175718],
        [17.165221,-15.176481],
        [17.194955,-15.31498],
        [17.145867,-15.476431],
        [17.037262,-15.61375],
        [16.988854,-15.708814],
        [16.971241,-15.721891],
        [16.90742,-15.704466],
        [16.826373,-15.792591],
        [16.764347,-15.877692],
        [16.798254,-15.982232],
        [16.878888,-16.043573],
        [16.898323,-16.088068],
        [16.887572,-16.155457],
        [16.81227,-16.250023],
        [16.727589,-16.311608],
        [16.601246,-16.429623],
        [16.5988,-16.399025],
        [16.590506,-16.36799],
        [16.585632,-16.336571],
        [16.578026,-16.31238],
        [16.5791,-16.293715]
    ]);
    poly.setStyle({color:"#00ff00", weight:2, fillColor:"#00ff00",fillOpacity: 0});
    //poly.addTo(map);
    var lacs = L.layerGroup();

    //acine+'vendor/leaflet/images/lake.png'
   /* var imageUrl = racine+'/img/os_new2.jpg',
        imageBounds = [center, [-29, 27]];
*/
   var imageUrl = racine+'/img/os_new2.jpg';
   // var atlas = L.imageOverlay(imageUrl, imageBounds);


    var atlas = L.imageOverlay(imageUrl, {
        attribution: '© <a href="http://osm.org/copyright">Atlas</a> Atlas',
        maxZoom: 1200
    });

    overlayMaps_wilaya = {
        /*'Wilaya':geojson,*/
        "{{ trans('text.parc')}}": parcs,
        "{{ trans('text.zone_humide')}}" : lacs,
        "{{ trans('text.gmv')}}" :poly,
        '<span style="font-size:11px;font-family:serif" > {{ trans("text.assoc_com")}}  <span>': couche_aglc,
    };


        //baselayers.OrthoRM.addTo(map);

        // Add a layer control element to the map
        //layerControl = L.control.layers(baseLayers,overlayMaps, {position: 'topleft'});
        //layerControl.addTo(map);

        var info = L.control({position: 'topleft'});
        var legend = L.control({position: '{{ trans("text.pos_legend")}}'});
        var info_selected = L.control({position: 'bottomright'});
         var lastClickedLayer;
    //geojson.bringToBack();
        //couche_aglc.bringToFront();
        L.control.layers(baseLayers, overlayMaps_wilaya, {position: 'topright', collapsed: false}).addTo(map);
        //L.control.layers(null, overlayMaps,,{position: 'topleft'}).addTo(map);

        map.attributionControl.addAttribution('Disigned by &copy; <a href="http://dcs-sarl.com/" target="__back">DCS-sarl</a>');

    map.on("overlayMaps_wilaya", function (event) {
        couche_parc.bringToFront();
    });


    map.on({
        overlayadd: function(e) {

            if(map.hasLayer(lacs))
            {
               var res_zone_hum = coordonnee_gps_zone_humide();
               //alert(res_zone_hum);
                var  coordinates =JSON.parse(res_zone_hum);

                for (var k in coordinates) {
                    let id_marler = coordinates[k].id;

                    let  lat = parseFloat(coordinates[k].lat);
                    let  log = parseFloat(coordinates[k].log);
                    let  lib_mar =  coordinates[k].libelle_z;

                    //alert(racine+coordinates[i][2])
                    /*var customPopup =  '<h5><i class="glyphicon glyphicon-map-marker"></i> '+coordinates[i][0]+' <span style="color:#337ab7; font-weight:bold"></span> </h5><img style="width:500px;" src="'+racine+coordinates[i][6]+'" />' +
                            '<div class="well well-lg"> <div class="row" style=""> <div class="col-md-6"> <div><i class="fa fa-globe"></i> <b>Wilaya: </b> Tagant</div> <div><b><i class="fa fa-users"></i> Population: </b>  <span class="direction"> 80 962</span></div> </div> <div class="col-md-6"> <div><i class="fa fa-globe"></i> <b>Nbr moughataas:</b> 3  </div> <div><i class="fa fa-globe"></i> <b>Nbr communes : </b> 10</div> <div> <i class="fa fa-eye"></i> <b><a id="voir_cart" rel="9" href="#">Voir la carte</a>  </b> </div> </div> </div> </div>';
                    // specify popup options
                    var customOptions =
                    {
                        'maxWidth': '500',
                        'className' : 'custom'
                    }*/

                    //alert(lat+'&&'+log);
                   // marker = L.marker([coordinates[i][4], coordinates[i][5]], {icon: greenIcon}).bindPopup(customPopup,customOptions);
                    marker = L.marker([lat, log], {icon: greenIcon,title: lib_mar});
                    lacs.addLayer(marker);
                    marker.bindPopup('<h5><i class="glyphicon glyphicon-map-marker"></i> '+lib_mar+' <span style="color:#337ab7; font-weight:bold"></span> </h5>');
                   /* var tooltip = L.tooltip({
                        target: marker,
                        map: map,
                        html: coordinates[i][1]
                    });*/
                    marker.on('mouseover',function(){
                         this.openPopup();
                     });
                    marker.on('mouseout',function(){
                        //this.closePopup();
                    });
                    marker.on('click', function(){
                        onMapClick(id_marler)
                    } );

                }
            }

            if(map.hasLayer(poly)) {

                //var popup_ploy = new L.Popup({autoPan: false});


                    //popup.setContent('<div class="text-center"><h5><i class="glyphicon glyphicon-map-marker"></i> '+lib_poly+' <span style="color:#337ab7; font-weight:bold"></span> </h5></div>');
                var lib_poly ='Carte verte';
                poly.bindPopup('<h5><i class="glyphicon glyphicon-map-marker"></i> '+lib_poly+' <span style="color:#337ab7; font-weight:bold"></span> </h5>');
                /* var tooltip = L.tooltip({
                 target: marker,
                 map: map,
                 html: coordinates[i][1]
                 });*/
            }
        },
        overlayremove: function(e) {
            //if (e.name === 'Lacs et mares') alert('removed');
        }
    });

    /*poly.on("mouseover", function (e) {

        var popup_pol = $("<div></div>", {
            id: "popup_pol" + 1,
            css: {
                position: "absolute",
                bottom: "85px",
                left: "50px",
                zIndex: 1002,
                backgroundColor: "white",
                padding: "8px",
                border: "1px solid #ccc"
            }
        });
    });
        var hed = $("<div></div>", {
            text: "District  sssssssssss+ properties.DISTRICT  properties.REPRESENTATIVE",
            css: {fontSize: "16px", marginBottom: "3px"}
        }).appendTo(popup_pol);
    popup_pol.appendTo("#map");*/
    poly.on('mouseover',function(){
        this.openPopup();

    });
    poly.on('click', onPolyClick);
        lc = L.control.locate({
            strings: {
                title: "Montre moi où je suis !"
            }
        }).addTo(map);


    function getColor(id) {
        var def_col = '<?php echo $def_color ?>';
        //var arr = { "1" : "blue", "2" : "#fff", "#000": 3 };
        var arr = {<?php echo $getcolor; ?> }
        if (arr[id] != null)
            return arr[id];
        else
            return def_col;

    }
    function style(feature) {

        return {
            weight: 1,
            opacity: 1,
            color: '#555',
            dashArray: '2',
            fillOpacity: 0.6,
            fillColor: getColor(feature.properties.code)

        };
    }

    function style1(feature) {

        return {
            weight: 1,
            opacity: 1,
            color: 'red',
            dashArray: '2',
            fillOpacity: 0.5,
            zindex: 12000,
            fillColor: '#eee'
        };
    }
    function style2(feature) {

        return {
            weight: 1,
            opacity: 1,
            color: 'blue',
            dashArray: '2',
            fillOpacity: 0.5,
            zindex: 12000,
            fillColor: '#eee'
        };
    }
    function style_limit_parc(feature) {

        return {
            weight: 1,
            opacity: 1,
            color: 'blue',
            dashArray: '2',
            fillOpacity: 0.5,
            zindex: 12000,
            fillColor: '#eee'
        };
    }

    function highlightFeature(e) {

        var popup = new L.Popup({autoPan: false});

        var layer = e.target;
        var arr = {<?php echo $getcolor; ?> }
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';

        if (arr[layer.feature.properties.code] != null) {
            popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
            info.update(1);
            layer.setStyle({
                weight: 3,
                dashArray: ''
            });
            map_zoom_start(layer);

//alert(zoomLev);
            popup.setLatLng(layer.getBounds().getCenter());

            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine + 'getInfoWilayaSelected/' + e.target.feature.properties.code + ',' + ref + ',' + module,
                success: function (data) {
                    popup.setContent('<div class="marker-title" style="display: inline">' + data.nom + '</div>' + data.info_hover);
                    info.update(data);

                    // map.removeLayer(info);
                    //$("#resultat").html("sidi maarouf");
                },

                error: function () {
                    //alert('La requête n\'a pas abouti');
                    console.log('La requête n\'a pas abouti');
                }
            });
            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                layer.bringToBack();
            }
            if (!popup._map) popup.openOn(map);
            window.clearTimeout(closeTooltip);
            layer.bringToBack();
        }

    }
    function reset_style_map_zoom(layer,color='#666')
    {
        /*map.on("zoomstart", function() {
            zoomLev = map.getZoom();
            if (zoomLev < 8) {
                layer.setStyle({
                    //color: color,
                    fillOpacity: 0.6
                });
            }
            else {
                layer.setStyle({
                    //color: color,
                    fillOpacity: 0.6
                });

            }
        });*/
    }

    function map_zoom_start(layer,color='#666')
    {
       /* map.on("zoomstart", function() {
            zoomLev = map.getZoom();
            if (zoomLev < 8) {
                layer.setStyle({
                    color: color,
                    fillOpacity: 0.6
                });
            }
            else {
                layer.setStyle({
                    color: color,
                    fillOpacity: 0.6
                });

            }
        });*/
    }
  /*  map.on('zoomend', function () {
        if (map.getZoom() >= 17.6 && map.hasLayer(activated_on_map_detail)==false) {
            map.addLayer(activated_on_map_detail);
        }
        if (map.getZoom() < 17.6 && map.hasLayer(activated_on_map_detail)) {
            map.removeLayer(activated_on_map_detail);
        }
    });*/


    function highlightFeature1(e) {
        var popup = new L.Popup({autoPan: false});

        var layer = e.target;

        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        //layer.bringToFront();
        /*if( arr[layer.feature.properties.ID]  != null)
         {*/
        popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
        info.update(1);
        layer.setStyle({
            weight: 2,
            dashArray: '',
            zIndex: 1
        });
        map_zoom_start(layer,'red');
        popup.setLatLng(layer.getBounds().getCenter());

        /* appel ajax pour recuperer le resultat au serveur */

        $.ajax({
            type: 'GET',
            //data:{id:layer.feature.properties.ID},
            url: racine + 'getInfoObjetSelected/' + e.target.feature.properties.ID + "," + ref + "," + module,
            cache: false,
            success: function (data) {
                popup.setContent('<div class="marker-title" style="display: inline">' + data.nom + '</div>' + data.info_hover);
                info.update(data);

                // map.removeLayer(info);
                //$("#resultat").html("sidi maarouf");
            },

            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }

        });

        if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
            layer.bringToFront();
        }
        if (!popup._map) popup.openOn(map);
        window.clearTimeout(closeTooltip);


        // layer.bindLabel("str").addTo(map);
        //}

    }
    function highlightFeature2(e) {
        var popup = new L.Popup({autoPan: false});

        var layer = e.target;

        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        //layer.bringToFront();

        if (layer.feature.properties.Code != 'null') {
            popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
            info.update(1);
            layer.setStyle({
                weight: 2,
                dashArray: '',
                zIndex: 1
            });
            map_zoom_start(layer,'blue');
            popup.setLatLng(layer.getBounds().getCenter());


            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine + 'getInfoObjetSelected/' + e.target.feature.properties.Code + "," + ref + "," + module+','+true,
                cache: false,
                success: function (data) {
                    popup.setContent('<div class="marker-title" style="display: inline">' + data.nom + '</div>' + data.info_hover);
                    info.update(data);

                    // map.removeLayer(info);
                    //$("#resultat").html("sidi maarouf");
                },

                error: function () {
                    //alert('La requête n\'a pas abouti');
                    console.log('La requête n\'a pas abouti');
                }

            });

            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                layer.bringToFront();
            }
            if (!popup._map) popup.openOn(map);
            window.clearTimeout(closeTooltip);


            // layer.bindLabel("str").addTo(map);
        }
        layer.bringToFront();

    }

    function highlightFeature_limit_parc(e) {
        var popup = new L.Popup({autoPan: false});

        var layer = e.target;

        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        //layer.bringToFront();

        if (layer.feature.properties.ID != 'null') {
            popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
            info.update(1);
            layer.setStyle({
                weight: 2,
                dashArray: '',
                zIndex: 1
            });
            map_zoom_start(layer,'blue');
            popup.setLatLng(layer.getBounds().getCenter());

            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine + 'getInfoObjetSelected/' + e.target.feature.properties.ID + "," + ref + "," + module,
                cache: false,
                success: function (data) {
                    popup.setContent('<div class="marker-title" style="display: inline">' + data.nom + '</div>' + data.info_hover);
                    info.update(data);

                    // map.removeLayer(info);
                    //$("#resultat").html("sidi maarouf");
                },

                error: function () {
                    //alert('La requête n\'a pas abouti');
                    console.log('La requête n\'a pas abouti');
                }

            });

            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                layer.bringToBack();
            }
            if (!popup._map) popup.openOn(map);
            window.clearTimeout(closeTooltip);
            layer.bringToFront();

            // layer.bindLabel("str").addTo(map);
        }
        layer.bringToBack();
        geojson.bringToBack();
        //layer.bringToFront();

    }
    function resetHighlight(e) {
        //geojson.resetStyle(e.target);
        reset_style_map_zoom(e.target);
        closeTooltip = window.setTimeout(function () {
            map.closePopup();
        }, 100);
        info.update();
    }

    // Identify function
    function resetHighlight1(e) {
        //couche_aglc.resetStyle(e.target);
        reset_style_map_zoom(e.target,'red');
        closeTooltip = window.setTimeout(function () {
            map.closePopup();
        }, 100);

        info.update();
    }
    function resetHighlight2(e) {
        //couche_parc.resetStyle(e.target);
        reset_style_map_zoom(e.target,'blue');
        closeTooltip = window.setTimeout(function () {
            map.closePopup();
        }, 100);

        info.update();
    }
    function resetHighlight_limit_parc(e) {
        //limit_parc.resetStyle(e.target);
        reset_style_map_zoom(e.target,'blue');
        closeTooltip = window.setTimeout(function () {
            map.closePopup();
        }, 100);

        info.update();
    }

    function zoomToFeature(e) {
        map.fitBounds(e.target.getBounds());
        var couche = 0;
        if (map.hasLayer(couche_aglc))
            couche = 1;
        else
            couche = 0;

        /* appele ajax pour recuperer l'information au click*/
        var arr = {<?php echo $getcolor; ?> }
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        if (arr[e.target.feature.properties.code] != null) {
            $('#basicModal').modal('show');
            $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>chargement en cours</p></div>').fadeIn('fast');

            $.ajax({
                type: 'GET',
                url: racine + 'detaitInfoWilaya/' + e.target.feature.properties.code + ',' + ref + ',' + module + ',' + couche,
                success: function (data) {
                    $("#resutClick").html(data);
                },
                error: function () {
                    //alert('La requête n\'a pas abouti');
                    console.log('La requête n\'a pas abouti');
                }
            });
        }
    }
    function zoomToFeature1(e) {

        map.fitBounds(e.target.getBounds());
        /* appele ajax pour recuperer l'information au click*/
        //var arr = {<?php echo $getcolor; ?> }
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        /* if (arr[e.target.feature.properties.ID] != null)
         {*/
        // alert(e.target.feature.properties.ID);

        $('#basicModal').modal("show");

        //loading_show();
        $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine + 'detaitInfoObjet/' + e.target.feature.properties.ID + ',' + ref + ',' + module,
            cache: false,
            success: function (data) {

                //$('#example').html(data.msg + 'popilation' + data.pp);
                //loading_hide();
                $("#resutClick").html(data);

            },
            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }
        });
        return false;
        //  }

    }

    function zoomToFeature2(e) {

        map.fitBounds(e.target.getBounds());
        /* appele ajax pour recuperer l'information au click*/
        //var arr = {<?php echo $getcolor; ?> }
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        /* if (arr[e.target.feature.properties.ID] != null)
         {*/
        // alert(e.target.feature.properties.ID);

        $('#basicModal').modal("show");

        //loading_show();
        $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine + 'detaitInfoObjet/' + e.target.feature.properties.ID + ',' + ref + ',' + module,
            cache: false,
            success: function (data) {

                //$('#example').html(data.msg + 'popilation' + data.pp);
                //loading_hide();
                $("#resutClick").html(data);

            },
            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }
        });
        return false;
        //  }

    }

    function zoomToFeature_limit_parc(e) {

        map.fitBounds(e.target.getBounds());
        /* appele ajax pour recuperer l'information au click*/
        //var arr = {<?php echo $getcolor; ?> }
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        /* if (arr[e.target.feature.properties.ID] != null)
         {*/
        // alert(e.target.feature.properties.ID);

        $('#basicModal').modal("show");

        //loading_show();
        $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine + 'detaitInfoObjet/' + e.target.feature.properties.ID + ',' + ref + ',' + module,
            cache: false,
            success: function (data) {

                //$('#example').html(data.msg + 'popilation' + data.pp);
                //loading_hide();
                $("#resutClick").html(data);

            },
            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }
        });
        return false;
        //  }

    }
    function onEachFeature(feature, layer) {
        // layer.bindLabel('kk ',{noHide:false});
        var bounds = layer.getBounds();
        // Get center of bounds
        var center = bounds.getCenter();
        lastClickedLayer = layer;
        // Use center to put marker on map
        /*var marker = L.marker(center).addTo(map);*/

        /* var label = L.marker(center, {
         icon: L.divIcon({
         className: 'label',
         html: 'sidi maarouf',
         iconSize: [100, 40]
         })
         }).addTo(map);*/
        layer.on({
            mouseover: highlightFeature,
            mouseout: resetHighlight,
            click: zoomToFeature
        });
    }

    function onEachFeature1(feature, layer) {
        // layer.bindLabel('kk ',{noHide:false});

        layer.on({
            mouseover: highlightFeature1,
            mouseout: resetHighlight1,
            click: zoomToFeature1
        });

    }

    function onEachFeature2(feature, layer) {
        // layer.bindLabel('kk ',{noHide:false});

        layer.on({
            mouseover: highlightFeature2,
            mouseout: resetHighlight2,
            click: zoomToFeature2
        });

    }

    function onEachFeature_limit_parc(feature, layer) {
        // layer.bindLabel('kk ',{noHide:false});

        layer.on({
            mouseover: highlightFeature_limit_parc,
            mouseout: resetHighlight_limit_parc,
            click: zoomToFeature_limit_parc
        });

    }
    function init_cart() {
        /*
         *  name      : init_cart
         * parametres :
         * return     :
         * Descrption : initialiser la carte  en appelant du fichier geojson
         */
        //
        info_right();
        legand();
    }

    init_cart();
    //geojson.bringToBack();
    //couche_aglc.bringToFront();
    function info_right() {
        /*
         *  name      : info_right
         * parametres :
         * return     :
         * Descrption : Afficher les info en haut a droite
         */
        //
        info.onAdd = function (map) {
            this._div = L.DomUtil.create('div', 'info top-right');
            this.update();
            return this._div;
        };

        info.update = function (props) {

            if (props == 1) {
                this._div.innerHTML = '<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>';
            }
            else {
                this._div.innerHTML = '<h5><i class="glyphicon glyphicon-info-sign"></i> {{ trans('text.info') }} <span style="color:#337ab7; font-weight:bold">{{ $bd_model }}</span> </h5>' + (props ?
                                props.info_right
                                : '{{ trans('text.survole') }}');
            }

        }
        info.addTo(map);
    }

    function legand() {
        /*
         *  name      : legand
         * parametres :
         * return     :
         * Descrption : permet de l'affichage du legede a droit
         */
        legend.onAdd = function (map) {
            var nbr_wil = '<?php echo $nbr_wil ?>';
            var div = L.DomUtil.create('div', 'legende map-legend'),
                    grades = [<?php echo $grade; ?>],
                    colors = [<?php echo $color ?>],
            //grades = ''
            //colors = ''
                    labels = [],
                    from, to;

            for (var i = 0; i < grades.length; i++) {

                labels.push(
                        '<li><span class="swatch" style="background:' + colors[i] + '!important;"></span> ' + grades[i] + '</li>');
            }
            div.innerHTML = '<h5><?php echo $titre_legande ?></h5><ul>' + labels.join('') + '</ul><hr>{{ trans("text.nbr_wilaya") }} : <span class="badge app_bgcolor">' + nbr_wil + '</span>';
            $('#leg_imp').html('<h5><?php echo $titre_legande ?> :</h5><ul>' + labels.join('') + '</ul>');
            $('#filter').html('<?php echo $filter ?>');
            return div;
        };
        legend.addTo(map);
    }

    $('#basicModal').on('hidden.bs.modal', onFeatureGroupClick);

    function onFeatureGroupClick(e) {
        var group = e.target;

        map.setZoom(6);
        group.setStyle(style);
        //layer.setStyle(highlight);
    }

    function layer_active()
    {
        L.Control.Layers.include({
            getOverlays: function() {
                // create hash to hold all layers
                var control, layers;
                layers = {};
                control = this;

                // loop thru all layers in control
                control._layers.forEach(function(obj) {
                    var layerName;

                    // check if layer is an overlay
                    if (obj.overlay) {
                        // get name of overlay
                        layerName = obj.name;
                        // store whether it's present on the map or not
                        return layers[layerName] = control._map.hasLayer(obj.layer);
                    }
                });

                return layers;
            }
        });
    }

    L.easyPrint({
        title: 'Imprimer la carte',
        elementsToHide: '.gitButton,#left_zone,#footer,#common-header, .info,.legende,.kk,#axe_stategique,#fl_p'
    }).addTo(map);

    function format(x) {
        return isNaN(x) ? "" : x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }



</script>
