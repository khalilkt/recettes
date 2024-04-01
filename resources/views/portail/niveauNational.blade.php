<?php

$libelle = trans("text.libelle_base");
$grade = "";
$color = "";
//echo $getcolor;
$i = 0;
/*$len = count($legands);
foreach ($legands as $l) {
    $grade = $grade . "'$l[$libelle]'";
    $color = $color . "'$l[color]'";
    if ($i != $len - 1) {
        $grade = $grade . ',';
        $color = $color . ',';
    }
    $i++;

}*/


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

    geojson = L.geoJson(mauritanie, {
        style: style,
        onEachFeature: onEachFeature
    }).addTo(map);






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

    map.addLayer(sat);
    // création d'un contrôle des couches pour modifier les couches de fond de plan
    var baseLayers = {
        'Satellite':sat,
        'watercolorLayer':watercolorLayer,
        'OpenStreetMap': osmLayer

    };



    //baselayers.OrthoRM.addTo(map);

    // Add a layer control element to the map
    //layerControl = L.control.layers(baseLayers,overlayMaps, {position: 'topleft'});
    //layerControl.addTo(map);

    var info = L.control({position: 'topleft'});
    var legend = L.control({position: 'topright'});
    var info_selected = L.control({position: 'bottomright'});





    //geojson.bringToBack();
    //couche_aglc.bringToFront();
    L.control.layers(baseLayers, '', {position: 'topright', collapsed: false}).addTo(map);
    //L.control.layers(null, overlayMaps,,{position: 'topleft'}).addTo(map);

    map.attributionControl.addAttribution('Disigned by &copy; <a href="http://dcs-sarl.com/" target="__back">DCS-sarl</a>');


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


    function style(feature) {

        return {
            weight: 1,
            opacity: 1,
            color: '#555',
            dashArray: '2',
            fillOpacity: 0.8,
            fillColor: '#49719d'

        };
    }

    function highlightFeature(e) {

        var popup = new L.Popup({autoPan: false});

        var layer = e.target;
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';

        //if (arr[layer.feature.properties.code] != null) {
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
                url: racine + 'getInfoNationalSelected/'+ ref + ',' + module,
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
        //}

    }
    function reset_style_map_zoom(layer,color='#666')
    {
        map.on("zoomstart", function() {
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
                    fillOpacity: 0
                });

            }
        });
    }

    function map_zoom_start(layer,color='#666')
    {
        map.on("zoomstart", function() {
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
                    fillOpacity: 0
                });

            }
        });
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


    function zoomToFeature(e) {
        map.fitBounds(e.target.getBounds());
        var couche = 0;
        if (map.hasLayer(couche_aglc))
            couche = 1;
        else
            couche = 0;

        /* appele ajax pour recuperer l'information au click*/

        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        //if (arr[e.target.feature.properties.code] != null) {
            $('#basicModal').modal('show');
            $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>chargement en cours</p></div>').fadeIn('fast');


            $.ajax({
                type: 'GET',
                url: racine + 'detaitInfoNational/'+ ref + ',' + module,
                success: function (data) {
                    $("#resutClick").html(data);

                },
                error: function () {
                    //alert('La requête n\'a pas abouti');
                    console.log('La requête n\'a pas abouti');
                }
            });

      //  }
    }

    function onEachFeature(feature, layer) {
        // layer.bindLabel('kk ',{noHide:false});
        var bounds = layer.getBounds();
        // Get center of bounds
        var center = bounds.getCenter();
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




    function init_cart() {
        /*
         *  name      : init_cart
         * parametres :
         * return     :
         * Descrption : initialiser la carte  en appelant du fichier geojson
         */
        //
        info_right();
       // legand();
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
                this._div.innerHTML = '<h5><i class="glyphicon glyphicon-info-sign"></i> {{ trans('text.info') }} <span style="color:#337ab7; font-weight:bold"></span> </h5>' + (props ?
                    props.info_right
                    : '{{ trans('text.survole') }}');
            }

        };
        info.addTo(map);

    }

    /*function legand() {
        /*
         *  name      : legand
         * parametres :
         * return     :
         * Descrption : permet de l'affichage du legede a droit
         */

      /*  legend.onAdd = function (map) {
            var nbr_wil = '<?php //echo $nbr_wil ?>';
            var div = L.DomUtil.create('div', 'legende map-legend'),
                grades = [<?php //echo $grade; ?>],
                colors = [<?php //echo $color ?>],
                //grades = ''
                //colors = ''
                labels = [],
                from, to;

            for (var i = 0; i < grades.length; i++) {

                labels.push(
                    '<li><span class="swatch" style="background:' + colors[i] + '!important;"></span> ' + grades[i] + '</li>');
            }
            div.innerHTML = '<h5><?php //echo $titre_legande ?></h5><ul>' + labels.join('') + '</ul><hr>{{ trans("text.nbr_wilaya") }} : <span class="badge app_bgcolor">' + nbr_wil + '</span>';
            $('#leg_imp').html('<h5><?php //echo $titre_legande ?> :</h5><ul>' + labels.join('') + '</ul>');
            $('#filter').html('<?php //echo $filter ?>');
            return div;
        };
        legend.addTo(map);
    }*/
    $('#basicModal').on('hidden.bs.modal', function (e) {
        zoomToFeature
        map.setZoom(6);
    })

    L.easyPrint({
        title: 'Imprimer la carte',
        elementsToHide: '.gitButton,#left_zone,#footer,#common-header, .info,.legende,.kk,#axe_stategique,#fl_p'
    }).addTo(map);

    function format(x) {
        return isNaN(x) ? "" : x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }



</script>
