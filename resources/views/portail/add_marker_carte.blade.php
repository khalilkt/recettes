

<div style="display: none;" id="entete_carte">
    <div style="width: 100%" >
        <img src="{{ url('img/header.jpg')  }}">
    </div>
    <h3 class="text-center">{{ trans("text.decoupage_admin") }}</h3>
    <h5>{{ trans("text.niveau") }} : {{ trans("text.Communes") }}</h5>
</div>

<div id="map" ></div>

<div class="col-md-12 p-0">
    <div id="resultat"></div>


    <table style='display: none; margin-top:10px; width: 100%!important;' class="imp" id="imp_carte_info">
        <tr>
            <td style="width: 50%;vertical-align: top;">
                <div  id="filter" class="legende1 map-legend1"></div>
            </td>
            <td style="width: 50%; margin-right: 0px!important; padding-left:40px;">
                <div  id="leg_imp" class="legende1 map-legend1"></div>
            </td>
        </tr>
    </table>


    <script src="{{ URL::asset('vendor/leaflet/leaflet-openweathermap.js') }}"> </script>
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
        var niveau ='{{ $niveau }}';
        var niveau =1;
        var  id_commune ='{{ env("app_commune") }}'

        var couche = getlayout(niveau); // charger la couche
        var baseslayout = baseLayersNiveau(niveau);
        var  layout =JSON.parse(couche);
        var setEquipements  = typeEquipementsLayers();
        var getEquipements = JSON.parse(setEquipements);

        var baseLayers ={};
        var groupedOverlaysEquipement ={};
        var couche_aglc;
        var geojson;
        var lastClickedLayer;
        var  baseslayout =JSON.parse(baseslayout)
        var popupMarker = new L.Popup({ autoPan: false });

        //alert(baseSelected.url);
        // var map = L.map('map').setView([21, -10], 5.8).setMinZoom(5.8).setMaxZoom(18).setMaxBounds([[28, -21], [14, 5]]);
        // var map = L.map('map').setView([20.7, -13], 5.8).setMinZoom(6).setMaxZoom(18).setMaxBounds([[27.6, -29], [13.4, 2.4]]);
        var map = new L.map('map', {
            center: new L.LatLng(20.7, -10),
            zoom: 5.8,
            minZoom: 6,
            maxZoom: 18,
            maxBounds: [[27.6, -29], [13.4, 2.4]],
            zoomControl: false

        });
        map.attributionControl.addAttribution('Disigned by &copy; <a href="http://dcs-sarl.com/" target="__back">DCS-sarl</a>');

        var  url_sat = 'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServe\ r/tile/{z}/{y}/{x}';
        var Attrib_sat='<a href="http://www.esri.com/">Esri</a> i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP,and the GIS User Community';

        // base layout
        get_base_layers(baseslayout);

        var info = L.control({position: 'topleft'});
        var legend = L.control({position: '{{ trans("text.pos_legend")}}'});
        var prec= L.OWM.precipitationClassic = L.OWM.precipitationClassic({showLegend: false, opacity: 0.5,appId: '8b816162ce03197c15265e47b0149f36'});
        var plui= L.OWM.rain = L.OWM.rain({showLegend: false, opacity: 0.5,appId: '8b816162ce03197c15265e47b0149f36'});
        var city = L.OWM.current({intervall: 5,showOwmStationLink: true,minZoom:2, lang: 'fr', appId:"8b816162ce03197c15265e47b0149f36"});


        // groupe overlays(type equipement
        get_groupes_layers(getEquipements)


        // object connune
        var firstpoly = L.geoJson(layout, {
            style:style,
            filter: function(feature, layer) {
                return feature.properties.id == parseInt(id_commune);
            }
        });
        firstpoly.addTo(map);
        map.fitBounds(firstpoly.getBounds());


        var groupedOverlays = {


            "<b style=color:rgb(220,31,37);>Localité</b>": {
                " Localité":   city
            },
            "<b style=color:rgb(220,31,37);>Type d\'equipements</b>": groupedOverlaysEquipement
        };

        L.control.groupedLayers(baseLayers, groupedOverlays, {position: 'topright', collapsed: false,background:'#eee'}).addTo(map);

        //L.control.layers(null, overlayMaps,,{position: 'topleft'}).addTo(map);

        map.attributionControl.addAttribution('Disigned by &copy; <a href="http://dcs-sarl.com/" target="__back">DCS-sarl</a>');

        L.control.zoom({
            position:'topleft'
        }).addTo(map);

        // on a production site, omit the "lc = "!
        lc = L.control.locate({
            strings: {
                title: "Montre moi où je suis !"
            }
        }).addTo(map);

        //echel
        L.control.scale({position: 'bottomleft', maxWidth:150, metric:true}).addTo(map);

        // mini map
        //min pas
        var minis=L.tileLayer('http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'});


        var miniMap = new L.Control.MiniMap(minis, { position: 'bottomleft',toggleDisplay: true, width:120, height:120, zoomLevelOffset:-4.5 }).addTo(map);




        function get_groupes_layers(getEquipements)
        {
            for (var k in getEquipements) {
                var id = getEquipements[k].id;
                var icone = getEquipements[k].icone;
                var libelle = getEquipements[k].libelle+"<img src='"+icone+"' height=25 style= 'margin-left: 32px'/>";
                var name_el  = 'elem'+k;
                name_el = L.layerGroup();
                var set_cordinate  = coordonnee_gps_equipements(id);
                var get_cordinate = JSON.parse(set_cordinate);
                get_marker_type(get_cordinate,icone,name_el);

                element  = name_el;
                groupedOverlaysEquipement[libelle]=element;
                //console.log(groupedOverlaysEquipement[libelle]);
            }
        }

        function get_base_layers(baseslayout)
        {
            for (var k in baseslayout) {
                var libelle = baseslayout[k].libelle;
                var url = baseslayout[k].url;
                var attr = baseslayout[k].attr;
                var maxZoom = baseslayout[k].maxZoom;
                var active = baseslayout[k].active;
                var  element =libelle ;
                var overlayMaps_wilaya;
                element  = L.tileLayer(url, {
                    attribution: attr,
                    maxZoom: maxZoom
                });
                baseLayers[libelle]=element;
                if(active)
                    map.addLayer(element);
            }

        }

        function get_marker_type(coordinates,icone,element)
        {
            var icone_marker = L.icon({
                iconUrl: icone,
                iconSize: [30, 30],
                iconAnchor: [10, 15],
            });
            for (var k in coordinates) {
                var id_marler = coordinates[k].id;
                //alert(coordinates[k].lat)
                var  lat = parseFloat(coordinates[k].lat);
                var  log = parseFloat(coordinates[k].log);
                var  lib_mar =  coordinates[k].libelle_z;

                marker = L.marker([lat, log],{icon: icone_marker ,id:id_marler,title: lib_mar});
                element.addLayer(marker);
                //lacs.addLayer(marker);
                //marker.bindPopup('<h5><i class="glyphicon glyphicon-map-marker"></i> '+lib_mar+' <span style="color:#337ab7; font-weight:bold"></span> </h5>');
                marker.bindPopup('<div class="marker-title" style="display: inline">'+ lib_mar +'</div>');
                marker.on('mouseover',function(e){
                    this.openPopup();
                    //alert(this.options.id);
                    // set_infoPopup(this.options.id);
                })

                marker.on('mouseout',function(){
                    //this.closePopup();
                });
                marker.on('click', function(){
                    //alert(this.options.id)
                    //openModal(id_marler)
                    //openObjectModal(this.options.id,'activites2')
                    //$("#info").html('sidi maarpuf'+id_marler)
                    //$(".info").html("Sidi Maarouf"+id_marler);
                } );
            }
        }
        function getColor(id) {
            var def_col ='<?php echo $def_color ?>';
            //var arr = { "1" : "blue", "2" : "#fff", "#000": 3 };
            var arr = { <?php echo $getcolor; ?> }
            if(arr[id] != null)
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
                zindex:12000,
                fillColor:'#eee'
            };
        }

        function highlightFeature(e) {
            var popup = new L.Popup({ autoPan: false });
            var obj_selected;
            var layer = e.target;
            var arr = { <?php echo $getcolor; ?> }
            var ref='<?php echo $ref ?>';
            var nbr_com ='<?php echo $nbr_com ?>';
            var module ='<?php echo $module ?>';

            //layer.bringToFront();
            switch (niveau) {
                case '1':
                    obj_selected='getInfoCommuneSelected/';
                    break;
                case '2':
                    obj_selected='getInfoMoughataaSelected/';
                    break;
                case '3':
                    obj_selected='getInfoWilayaSelected/';
                    break;
                default :
                    obj_selected='getInfoCommuneSelected/';
                    break;
            }
            if( arr[layer.feature.properties.id]  != null)
            {
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
                    url: racine+obj_selected+ e.target.feature.properties.id+","+ref+","+nbr_com+","+module,
                    cache: false,
                    success: function (data) {
                        popup.setContent('<div class="marker-title" style="display: inline">'+ data.nom +'</div>'+ data.info_hover);
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
        function reset_style_map_zoom(layer,color='#666')
        {
            map.on("zoomstart", function() {
                zoomLev = map.getZoom();
                if (zoomLev < 8) {
                    layer.setStyle({
                        weight: 2,
                        color: color,
                        dashArray: '',
                        fillOpacity: 0.6
                    });
                }
                else {
                    layer.setStyle({
                        weight: 2,
                        color: color,
                        dashArray: '',
                        fillOpacity: 0
                    });

                }
            });
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
                         fillOpacity: 0
                     });

                 }
             });*/
        }
        function resetHighlight(e) {
            geojson.resetStyle(e.target);
            // reset_style_map_zoom(e.target);
            closeTooltip = window.setTimeout(function() {
                map.closePopup();
            }, 100);

            info.update();
        }
        // Identify function
        function resetHighlight1(e) {
            couche_aglc.resetStyle(e.target);
            closeTooltip = window.setTimeout(function() {
                map.closePopup();
            }, 100);
            info.update();
        }
        // Identify function
        function zoomToFeature(e) {

            map.fitBounds(e.target.getBounds());
            /* appele ajax pour recuperer l'information au click*/
            var arr = {<?php echo $getcolor; ?> }
            var ref='<?php echo $ref ?>';
            var module ='<?php echo $module ?>';

            var obj_detait;
            switch (niveau) {
                case '1':
                    obj_detait='detaitInfoCommune/';
                    break;
                case '2':
                    obj_detait='detaitInfoMoughataa/';
                    break;
                case '3':
                    obj_detait='detaitInfoWilaya/';
                    break;
            }
            if (arr[e.target.feature.properties.id] != null)
            {
                $('#basicModal').modal("show");

                //loading_show();
                $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

                $.ajax({
                    type: 'GET',
                    url: racine+obj_detait+ e.target.feature.properties.id+','+ref+','+module,
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
            var ref='<?php echo $ref ?>';
            var module ='<?php echo $module ?>';
            if (arr[e.target.feature.properties.id] != null)
            {
                $('#basicModal').modal("show");

                //loading_show();
                $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

                $.ajax({
                    type: 'GET',
                    url: racine+'detaitInfoCommune/' + e.target.feature.properties.id+','+ref+','+module,
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

        function init_cart()
        {
            /*
             *  name      : init_cart
             * parametres :
             * return     :
             * Descrption : initialiser la carte  en appelant du fichier geojson
             */
            //
            info_right(info);
            //legand();
        }

        init_cart();
        //geojson.bringToBack();


        function info_right(info)
        {
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

                if(props ==1)
                {
                    this._div.innerHTML='<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>';
                }
                else {
                    this._div.innerHTML = '<h5><i class="fa fa-info-circle"></i> {{ trans("text.info") }} <span style="color:#337ab7; font-weight:bold">{{ $bd_model }}</span></h5>' + (props ?
                        props.info_right
                        : '{{ trans("text.survole") }}');
                }

            }
            info.addTo(map);
        }


        function info_selected()
        {
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

        L.easyPrint({
            title: 'Imprimer la carte',
            //position: 'topleft',
            elementsToHide: '.gitButton,#left_zone,#footer,#common-header, .info,.legenden,.kk,#axe_stategique,#fl_p',
        }).addTo(map);


        function format(x) {
            return isNaN(x)?"":x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $("#v_tableau").on('click',function(){

            $("#result_format_tableau").html("Test");

        })

        var theMarker = {};

        map.on('click',function(e){
            lat = e.latlng.lat;
            lon = e.latlng.lng;
            //console.log("You clicked the map at LAT: "+ lat+" and LONG: "+lon );
            //Clear existing marker,
            if (theMarker != undefined) {
                map.removeLayer(theMarker);
            };
            theMarker = L.marker([lat,lon]);
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
            if(firstpoly.getBounds().contains(theMarker.getLatLng()))
                theMarker.addTo(map);
            else
                map.removeLayer(theMarker);
        });

    </script>
</div>
