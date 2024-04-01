


<div id="map"></div>



<script>
    //filtre_niveau();
    // variable
    var couche = getlayout(3); // charger la couche
    var baseslayout = baseLayersNiveau(3);
    var  baseslayout =JSON.parse(baseslayout)
    var  layout =JSON.parse(couche);
    var baseLayers ={};

    var couche_aglc;
    var  geojson;
    var couche_parc;
    var limit_parc;
    var parc;
    var overlayMaps_wilaya;
    // Create the map

    //var map = L.map('map').setView([20.7, -10], 5.8).setMinZoom(6).setMaxZoom(18).setMaxBounds([[27.6, -29], [13.4, 2.4]]);

    var map = new L.map('map', {
        center: new L.LatLng(20.7, -10),
        zoom: 5.8,
        minZoom: 6,
        maxZoom: 18,
        maxBounds: [[27.6, -29], [13.4, 2.4]],
        zoomControl: false

    });



    set_base_map(baseslayout);

    var info = L.control({position: 'topleft'});
    var legend = L.control({position: '{{ trans("text.pos_legend")}}'});
    var info_selected = L.control({position: 'bottomright'});
    var lastClickedLayer;
    geojson = L.geoJson(layout, {
        style: style,
        onEachFeature: onEachFeature
    }).addTo(map);


    //basemaps  WMS

    var LeafIcon = L.Icon.extend({
        options: {

            iconSize:     [30, 35],

            iconAnchor:   [1, 30],
            shadowAnchor: [4, 62],
            popupAnchor:  [-3, -76]
        }
    });



    var blueIcon = new L.Icon({
        iconUrl: racine+'vendor/leaflet/images/marker-icon-blue.png',
        shadowUrl: racine+'vendor/leaflet/images/marker-shadow.png',
        iconSize: [16, 26],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [26, 26]
    });
    // var wms2015 = L.tileLayer.wms('http://ideib.caib.es/pub_ideib/public/IMATGES_OR2015_R25/MapServer/WMSServer?');


    // wather
    var prec= L.OWM.precipitationClassic = L.OWM.precipitationClassic({showLegend: false, opacity: 0.5,appId: '8b816162ce03197c15265e47b0149f36'});
    var city = L.OWM.current({intervall: 5,showOwmStationLink: true,minZoom:2, lang: 'fr', appId:"8b816162ce03197c15265e47b0149f36"});
    var lacs = L.layerGroup();


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

                    marker = L.marker([lat, log], {icon: blueIcon,title: lib_mar});
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
        },
        overlayremove: function(e) {
            //if (e.name === 'Lacs et mares') alert('removed');
        }
    });

    var groupedOverlays = {

        "<b style=color:rgb(220,31,37);>Activités</b> <br>": {
            "activites <img src='https://image.flaticon.com/icons/png/512/236/236981.png' height=25  style= 'margin-left: 17px'> ": lacs
        }
    };

    //L.control.layers(baseLayers,null,{position: 'topleft',collapsed: false}).addTo(map);

    L.control.groupedLayers(baseLayers,groupedOverlays,{position: 'topright',collapsed:false}).addTo(map);

    L.control.zoom({
        position:'topleft'
    }).addTo(map);



    function set_base_map(baseslayout) {
        for (var k in baseslayout) {
            var libelle = baseslayout[k].libelle;
            var url = baseslayout[k].url;
            var attr = baseslayout[k].attr;
            var maxZoom = baseslayout[k].maxZoom;
            var active = baseslayout[k].active;
            var  element =libelle ;

            element  = L.tileLayer(url, {
                attribution: attr,
                maxZoom: maxZoom
            });
            baseLayers[libelle]=element;
            if(active)
                map.addLayer(element);
        }

    }
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
            fillColor: getColor(feature.properties.id)

        };
    }
    function onEachFeature(feature, layer) {

        var bounds = layer.getBounds();
        // Get center of bounds
        var center = bounds.getCenter();
        lastClickedLayer = layer;

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
                this._div.innerHTML = '<h5><i class="fa fa-info-circle"></i> {{ trans('text.info') }} <span style="color:#337ab7; font-weight:bold">{{ $bd_model }}</span> </h5>' + (props ?
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
                grades = [<?php echo $legands[0]; ?>],
                colors = [<?php echo $legands[1] ?>],
                //grades = ''
                //colors = ''
                labels = [],
                from, to;

            for (var i = 0; i < grades.length; i++) {

                labels.push(
                    '<li><span class="swatch" style="background:' + colors[i] + '!important;"></span> ' + grades[i] + '</li>');
            }
            div.innerHTML = '<h5><i class="fa fa-eye"></i> <?php echo $titre_legande ?></h5><ul>' + labels.join('') + '</ul><hr>{{ trans("text.nbr_wilaya") }} : <span class="badge app_bgcolor">' + nbr_wil + '</span>';
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
    function highlightFeature(e) {

        var popup = new L.Popup({autoPan: false});

        var layer = e.target;
        var arr = {<?php echo $getcolor; ?> }
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';

        if (arr[layer.feature.properties.id] != null) {
            popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
            info.update(1);
            layer.setStyle({
                weight: 3,
                dashArray: ''
            });
            // map_zoom_start(layer);

            popup.setLatLng(layer.getBounds().getCenter());

            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine + 'getInfoWilayaSelected/' + e.target.feature.properties.id + ',' + ref + ',' + module,
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
    function zoomToFeature(e) {
        map.fitBounds(e.target.getBounds());
        var couche = 0;
        //if (map.hasLayer(couche_aglc))
        //   couche = 1;
        // else
        //  couche = 0;

        /* appele ajax pour recuperer l'information au click*/
        var arr = {<?php echo $getcolor; ?> }
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        if (arr[e.target.feature.properties.id] != null) {
            $('#basicModal').modal('show');
            $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>chargement en cours</p></div>').fadeIn('fast');

            $.ajax({
                type: 'GET',
                url: racine + 'detaitInfoWilaya/' + e.target.feature.properties.id + ',' + ref + ',' + module + ',' + couche,
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

    function resetHighlight(e) {
        geojson.resetStyle(e.target);
        //reset_style_map_zoom(e.target);
        closeTooltip = window.setTimeout(function () {
            map.closePopup();
        }, 100);
        info.update();
    }

    //icon create





    //min pas
    // var miniMap = new L.Control.MiniMap(minis, { position: 'bottomleft',toggleDisplay: true, width:120, height:120, zoomLevelOffset:-4.5 }).addTo(map);

    //recherche
    // L.Control.geocoder().addTo(map);




</script>
