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
                var localite_id = '{{ $localite_id }}';
                var niveau = 1;
                var id_commune = '{{ env("APP_COMMUNE") }}'

                var couche = getlayout(niveau); // charger la couche
                var baseslayout = baseLayersNiveau(niveau);
                var layout = JSON.parse(couche);

                var baseLayers = {};
                var groupedOverlaysEquipement = {};
                var latlngsLocalite;
                var couche_aglc;
                var coordonneePolygon = [];

                coordonneePolygon = cordinateLocalite(localite_id);
                coordonneePolygon = JSON.parse(coordonneePolygon);

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

                var drawnItems = new L.FeatureGroup();

                map.addLayer(drawnItems);

                var cordinates_p =[];
                for (var k in coordonneePolygon) {
                    {

                        var lat = coordonneePolygon[k].lat;
                        var long = coordonneePolygon[k].long;
                        var cord_p =[lat,long]
                        cordinates_p.push(cord_p);
                    }
                }

               if(cordinates_p != null)
               {
                   var polygon = new L.Polygon(cordinates_p);
                   drawnItems.addLayer(polygon);
               }
                //polygon.editing.enable();
                //map.addLayer(polygon);

                // Set the title to show on the polygon button
                L.drawLocal.draw.toolbar.buttons.polygon = 'Ajouter polygon!';

                var drawControl = new L.Control.Draw({
                    position: 'topright',
                    draw: {
                        polyline: false,
                        polygon: true,
                        circle: false,
                        marker: false
                    },
                    edit: {
                        featureGroup: drawnItems,
                        remove: true
                    }
                });
                map.addControl(drawControl);


                map.on('draw:created', function (e) {
                    var type = e.layerType,
                        layer = e.layer;
                    drawnItems.addLayer(layer);

                    shape_for_db = layer.toGeoJSON().geometry.coordinates;

                    var form_data = new FormData();
                    form_data.append("datas",shape_for_db);
                    form_data.append("localite_id", localite_id);

                    $.ajax({
                        url: racine+'get_cordinate_polygon', // point to server-side PHP script
                        dataType: 'text',  // what to expect back from the PHP script, if anything
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function (php_script_response) {
                            console.log("created");
                            updateCordonateLocalite();
                        }
                    });
                    //getShapes(layer);
                    // Process them any way you want and save to DB
                });


                map.on(L.Draw.Event.EDITED, function (e) {
                    var layers = e.layers;

                    var countOfEditedLayers = 0;
                    layers.eachLayer(function (layer) {
                        countOfEditedLayers++;
                        shape_for_db = layer.toGeoJSON().geometry.coordinates;
                        var form_data = new FormData();
                        form_data.append("datas",shape_for_db);
                        form_data.append("localite_id", localite_id);
                        $.ajax({
                            url: racine+'get_cordinate_polygon', // point to server-side PHP script
                            dataType: 'text',  // what to expect back from the PHP script, if anything
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            type: 'post',
                            success: function (php_script_response) {
                                updateCordonateLocalite();
                                console.log("created");
                            }
                        });

                    });

                    console.log("Edited " + countOfEditedLayers + " layers");
                });

                map.on(L.Draw.Event.DELETED, function (e) {
                    var layers = e.layers;

                    var countOfEditedLayers = 0;
                    layers.eachLayer(function (layer) {
                        countOfEditedLayers++;

                        $.ajax({
                            url: racine+'delete_polygon_localite/'+localite_id, // point to server-side PHP script
                            type: 'get',
                            success: function (php_script_response) {
                                updateCordonateLocalite();
                                console.log("deleted");
                            }
                        });

                    });

                    console.log("Edited " + countOfEditedLayers + " layers");
                });

               /* L.DomUtil.get('changeColor').onclick = function () {
                    drawControl.setDrawingOptions({rectangle: {shapeOptions: {color: '#004a80'}}});
                };*/



            </script>
        </div>
    </div>
</div>
