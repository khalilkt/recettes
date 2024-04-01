<div class="modal-header">
    <h5 class="modal-title">Selectionner l'emplacement sur la carte</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">

        <div id="map"></div>

        <div class="col-md-12 p-0">
            <div id="resultat"></div>

            <script src="{{ URL::asset('vendor/leaflet/leaflet-openweathermap.js') }}"></script>
            <script type="text/javascript">


                $('.souscond_tb').on('hidden.bs.collapse', toggleIcon);
                $('.souscond_tb').on('shown.bs.collapse', toggleIcon);

                function toggleIcon(e) {
                    $(e.target)
                        .prev('.card-header')
                        .find(".more-less")
                        .toggleClass('fa-plus fa-minus');
                }

                // variable
                // Initialisation de la map
                // variable global
                var niveau = '{{ $niveau }}';
                var niveau = 1;
                var id_commune = '{{ env("APP_COMMUNE") }}'

                var couche = getlayout(niveau); // charger la couche
                var baseslayout = baseLayersNiveau(niveau);
                var layout = JSON.parse(couche);


                var baseLayers = {};
                var groupedOverlaysEquipement = {};
                var couche_aglc;
                var geojson;
                var lastClickedLayer;
                var baseslayout = JSON.parse(baseslayout)
                var popupMarker = new L.Popup({autoPan: false});

                //alert(baseSelected.url);
                // var map = L.map('map').setView([21, -10], 5.8).setMinZoom(5.8).setMaxZoom(18).setMaxBounds([[28, -21], [14, 5]]);
                // var map = L.map('map').setView([20.7, -13], 5.8).setMinZoom(6).setMaxZoom(18).setMaxBounds([[27.6, -29], [13.4, 2.4]]);
                var map = new L.map('map', {
                    center: new L.LatLng(20.7, -10),
                    zoom: 5.8,
                    minZoom: 6,
                    maxZoom: 18,
                    maxBounds: [[27.6, -29], [13.4, 2.4]],
                    zoomControl: false,
                    cursor: true

                });
                map.attributionControl.addAttribution('Disigned by &copy; <a href="http://dcs-sarl.com/" target="__back">DCS-sarl</a>');

                // base layout
                get_base_layers(baseslayout);

                var info = L.control({position: 'topleft'});
                var legend = L.control({position: '{{ trans("text.pos_legend")}}'});

                // object connune
                var firstpoly = L.geoJson(layout, {
                    style: style,
                    filter: function (feature, layer) {
                        return feature.properties.id == parseInt(id_commune);
                    }
                });
                firstpoly.addTo(map);
                map.fitBounds(firstpoly.getBounds());

                var groupedOverlays = {};

                L.control.groupedLayers(baseLayers, groupedOverlays, {
                    position: 'topright',
                    collapsed: false,
                    background: '#eee'
                }).addTo(map);

                //L.control.layers(null, overlayMaps,,{position: 'topleft'}).addTo(map);

                map.attributionControl.addAttribution('Disigned by &copy; <a href="http://dcs-sarl.com/" target="__back">DCS-sarl</a>');

                L.control.zoom({
                    position: 'topleft'
                }).addTo(map);

                //echel
                L.control.scale({position: 'bottomleft', maxWidth: 150, metric: true}).addTo(map);

                L.control.mousePosition({position: 'topleft'}).addTo(map);


                function get_base_layers(baseslayout) {
                    for (var k in baseslayout) {
                        var libelle = baseslayout[k].libelle;
                        var url = baseslayout[k].url;
                        var attr = baseslayout[k].attr;
                        var maxZoom = baseslayout[k].maxZoom;
                        var active = baseslayout[k].active;
                        var element = libelle;
                        var overlayMaps_wilaya;
                        element = L.tileLayer(url, {
                            attribution: attr,
                            maxZoom: maxZoom
                        });
                        baseLayers[libelle] = element;
                        if (active)
                            map.addLayer(element);
                    }
                }


                function getColor(id) {
                    var def_col = '<?php echo $def_color ?>';
                    //var arr = { "1" : "blue", "2" : "#fff", "#000": 3 };
                    var arr = { <?php echo $getcolor; ?> }
                    if (arr[id] != null)
                        return arr[id];
                    else
                        return def_col;
                }

                function style(feature) {

                    return {
                        weight: 1,
                        opacity: 1,
                        color: '#b342a5',
                        /* dashArray: '2',*/
                        fillOpacity: 0.05
                        //fillColor: getColor(feature.properties.id)
                    };
                }

                function style1(feature) {

                    return {
                        weight: 1,
                        opacity: 1,
                        color: 'red',
                        dashArray: '2',
                        fillOpacity: 0.6,
                        zindex: 12000,
                        fillColor: '#eee'
                    };
                }

                function highlightFeature(e) {
                    var popup = new L.Popup({autoPan: false});
                    var obj_selected;
                    var layer = e.target;
                    var arr = { <?php echo $getcolor; ?> }
                    var ref = '<?php echo $ref ?>';
                    var nbr_com = '<?php echo $nbr_com ?>';
                    var module = '<?php echo $module ?>';

                    //layer.bringToFront();
                    switch (niveau) {
                        case '1':
                            obj_selected = 'getInfoCommuneSelected/';
                            break;
                        case '2':
                            obj_selected = 'getInfoMoughataaSelected/';
                            break;
                        case '3':
                            obj_selected = 'getInfoWilayaSelected/';
                            break;
                        default :
                            obj_selected = 'getInfoCommuneSelected/';
                            break;
                    }
                    if (arr[layer.feature.properties.id] != null) {
                        popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
                        info.update(1);
                        layer.setStyle({
                            weight: 2,
                            dashArray: '',
                            zIndex: 450
                        });
                        map_zoom_start(layer)
                        popup.setLatLng(layer.getBounds().getCenter());

                        /* appel ajax pour recuperer le resultat au serveur */

                        $.ajax({
                            type: 'GET',
                            //data:{id:layer.feature.properties.ID},
                            url: racine + obj_selected + e.target.feature.properties.id + "," + ref + "," + nbr_com + "," + module,
                            cache: false,
                            success: function (data) {
                                popup.setContent('<div class="marker-title" style="display: inline">' + data.nom + '</div>' + data.info_hover);
                                info.update(data);

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
                        //layer.bringToBack();

                    }

                }

                function set_infoPopup(id) {

                    /* $.ajax({
                         type: 'GET',
                         //data:{id:layer.feature.properties.ID},
                         url: racine+'getInfoActiviteSelected/'+id,
                         cache: false,
                         success: function (data) {
                             //popup.setContent('<div class="marker-title" style="display: inline">'+ data.nom +'</div>'+ data.info_hover);
                             //alert(data.info_right)
                            // alert(data.nom);
                             info.update(data);
                         },
                         error: function () {
                             //alert('La requête n\'a pas abouti');
                             console.log('La requête n\'a pas abouti');
                         }
                     }); */

                    //info.update('<h2>Sidi maarouf</h2>');
                }

                function highlightFeature1(e) {


                }

                function reset_style_map_zoom(layer, color = '#666') {
                    map.on("zoomstart", function () {
                        zoomLev = map.getZoom();
                        if (zoomLev < 8) {
                            layer.setStyle({
                                weight: 2,
                                color: color,
                                dashArray: '',
                                fillOpacity: 0.6
                            });
                        } else {
                            layer.setStyle({
                                weight: 2,
                                color: color,
                                dashArray: '',
                                fillOpacity: 0
                            });

                        }
                    });
                }

                function map_zoom_start(layer, color = '#666') {
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
                                 fillOpacity: 0
                             });

                         }
                     });*/
                }

                function resetHighlight(e) {
                    geojson.resetStyle(e.target);
                    // reset_style_map_zoom(e.target);
                    closeTooltip = window.setTimeout(function () {
                        map.closePopup();
                    }, 100);

                    info.update();
                }

                // Identify function
                function resetHighlight1(e) {
                    couche_aglc.resetStyle(e.target);
                    closeTooltip = window.setTimeout(function () {
                        map.closePopup();
                    }, 100);
                    info.update();
                }

                // Identify function
                function zoomToFeature(e) {

                    map.fitBounds(e.target.getBounds());
                    /* appele ajax pour recuperer l'information au click*/
                    var arr = {<?php echo $getcolor; ?> }
                    var ref = '<?php echo $ref ?>';
                    var module = '<?php echo $module ?>';

                    var obj_detait;
                    switch (niveau) {
                        case '1':
                            obj_detait = 'detaitInfoCommune/';
                            break;
                        case '2':
                            obj_detait = 'detaitInfoMoughataa/';
                            break;
                        case '3':
                            obj_detait = 'detaitInfoWilaya/';
                            break;
                    }
                    if (arr[e.target.feature.properties.id] != null) {
                        $('#basicModal').modal("show");

                        //loading_show();
                        $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

                        $.ajax({
                            type: 'GET',
                            url: racine + obj_detait + e.target.feature.properties.id + ',' + ref + ',' + module,
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
                    }
                }

                function zoomToFeature1(e) {

                    map.fitBounds(e.target.getBounds());
                    /* appele ajax pour recuperer l'information au click*/
                    var arr = {<?php echo $getcolor; ?> }
                    var ref = '<?php echo $ref ?>';
                    var module = '<?php echo $module ?>';
                    if (arr[e.target.feature.properties.id] != null) {
                        $('#basicModal').modal("show");

                        //loading_show();
                        $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

                        $.ajax({
                            type: 'GET',
                            url: racine + 'detaitInfoCommune/' + e.target.feature.properties.id + ',' + ref + ',' + module,
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
                    }

                }

                function onEachFeature(feature, layer) {
                    // layer.bindLabel('kk ',{noHide:false});

                    layer.bringToFront();

                    lastClickedLayer = layer;
                    layer.on({
                        //mouseover: highlightFeature,
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


                //geojson.bringToBack();


                function info_selected() {
                    /*
                     *  name      : legand
                     * parametres :
                     * return     :
                     * Descrption : permet de l'affichage du legede a droit
                     */

                    info_selected.onAdd = function (map) {
                        var div = L.DomUtil.create('div', 'legende map-legend');

                        div.innerHTML = '<h5><?php echo $titre_legande ?></h5>';
                        return div;
                    };
                    info_selected.addTo(map);

                }


                $('#basicModal').on('hidden.bs.modal', onFeatureGroupClick);

                function onFeatureGroupClick(e) {
                    var group = e.target;

                    map.setZoom(6);
                    group.setStyle(style);
                    //layer.setStyle(highlight);
                }
              icone_url = '{{ $icon }}'
                var icone_marker = L.icon({
                    iconUrl: icone_url,
                    iconSize: [30, 30],
                    iconAnchor: [10, 15],
                });

                var theMarker = {};

                if($('#latitude').val().length != 0  && $('#longitude').val().length != 0)
                {
                    lat = $('#latitude').val();
                    lon = $('#longitude').val();
                    theMarker = L.marker([lat, lon],{icon: icone_marker}).addTo(map);
                }
                map.on('click', function (e) {
                    lat = e.latlng.lat;
                    lon = e.latlng.lng;
                    //console.log("You clicked the map at LAT: "+ lat+" and LONG: "+lon );
                    //Clear existing marker,

                    if (theMarker != undefined) {
                        map.removeLayer(theMarker);
                    }

                    theMarker = L.marker([lat, lon],{icon: icone_marker});
                    /*var results = leafletPip.pointInLayer([lon,lat], firstpoly);
                    if(results)
                        theMarker.addTo(map);
                   else
                   map.removeLayer(theMarker);*/

                    /*var results = leafletPip.pointInLayer([lat, log], firstpoly);
                    alert(results);*/
// results is an array of L.Polygon objects containing that point
                    //Add a marker to show where you clicked.
                    //if(firstpoly.getBounds().contains(theMarker.getLatLng()))
                    if (firstpoly.getBounds().contains(theMarker.getLatLng())) {
                        theMarker.addTo(map);
                        get_cordinate_marker(lat, lon)
                    } else {
                        map.removeLayer(theMarker);
                    }
                });

            </script>
        </div>
    </div>
</div>
