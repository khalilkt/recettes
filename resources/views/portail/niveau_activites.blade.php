

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

    <div class="card  souscond_tb kk " >
        <div class="card-header"  role="tab" id="headingOne4">
            <form role="formexport"  id="formexport" name="formexport" class=""  method="post" >
                {{ csrf_field() }}

                <input type="hidden" name="module" value="{{ $module }}" />
                <input type="hidden" name="ref" value="{{ $ref }}" />
                <input type="hidden" name="niveau" value="{{ $niveau }}" />
                <input type="hidden" name="loc"  id="loc" value="{{ $loc }}" />
                <input type="hidden" name="eq" id="eq" value="{{ $eq }}" />
            </form>
            <h4 class="card-title">

                <a class="collapsed " role="button" data-toggle="collapse" data-parent="#tb" href="#tb" aria-expanded="false" aria-controls="tb"  onclick="plus_info_equipements(this)">
                    <i class="more-less fa fa-plus " ></i>  {{ trans("text.voir_result") }}
                </a>
                <!--<button type="button" onclick="export_equipements(this)" class=" d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm exp_fiche_excel" ><i class="fa fa-file-excel-o exp_excel"></i> {{ trans("text.export") }} </button>-->

            </h4>

        </div>
        <div id="tb" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tb">
            <div class="card-body" style="padding: 3px!important;">
                <div id="resultat_detait_info">

                </div>
            </div>
        </div>
    </div>

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

        var  id_commune ='{{ $id_commune }}';

        var localite_fil = '{{ $loc }}';
        if(localite_fil.length < 1)
            localite_fil='all';

        var eq_fil = '{{ $eq }}';
        if(eq_fil.length < 1)
            eq_fil='all';

        var couche = getlayout(niveau); // charger la couche
        var baseslayout = baseLayersNiveau(niveau);
        var  layout =JSON.parse(couche);

        var couche_emplacement = getCoucheEmplacement(id_commune,localite_fil);
        var get_couche_emplacements = JSON.parse(couche_emplacement);
        //*******************************************
        var localite = getlocalite(id_commune); // charger la couche
        var  couche_localite =JSON.parse(localite);

        var setEquipements  = typeEquipementsLayers(eq_fil,localite_fil);
        var getEquipements = JSON.parse(setEquipements);

        var baseLayers ={};
        var groupedOverlaysEquipement ={};
        var couche_aglc;
        var geojson;
        var lastClickedLayer;
        var  baseslayout =JSON.parse(baseslayout)
        var popupMarker = new L.Popup({ autoPan: false });
        var couche_emp = L.layerGroup();
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

// base layout
        get_base_layers(baseslayout);

        var info = L.control({ position:'{{trans("text.topleft")}}'});
        var legend = L.control({position: '{{ trans("text.pos_legend")}}'});

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

        couche_loc = L.geoJson(couche_localite, {

            style: style1,
            onEachFeature: onEachFeature1,

        }).addTo(map);
        get_marker_emplacement(get_couche_emplacements,couche_emp)
        couche_emp.addTo(map)
        var groupedOverlays = {


            "<b style=color:rgb(179,66,165);>{{ trans('text.couches') }}</b>": {
                '{{ trans("text_me.localites") }}':   couche_loc,
                '{{ trans("text_me.emplacements") }}':   couche_emp,
            },
            "<b style=color:rgb(179,66,165);>{{ trans('text_me.type_equipement') }}</b>": groupedOverlaysEquipement
        };

        L.control.groupedLayers(baseLayers, groupedOverlays, {position: '{{trans("text.topright")}}', collapsed: false,background:'#eee'}).addTo(map);

        //L.control.layers(null, overlayMaps,,{position: 'topleft'}).addTo(map);

        map.attributionControl.addAttribution('Disigned by &copy; <a href="http://dcs-sarl.com/" target="__back">DCS-sarl</a>');

        L.control.zoom({
            position:'{{trans("text.topleft")}}'
        }).addTo(map);

        // on a production site, omit the "lc = "!
        lc = L.control.locate({
            strings: {
                title: "Montre moi où je suis !"
            },
            position:'{{trans("text.topleft")}}'
        }).addTo(map);

        //echel
        L.control.scale({position: '{{ trans("text.bottomleft") }}', maxWidth:150, metric:true}).addTo(map);

        // mini map
        //min pas
        var minis=L.tileLayer('http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'});


        var miniMap = new L.Control.MiniMap(minis, { position: '{{ trans("text.bottomleft") }}',toggleDisplay: true, width:120, height:120, zoomLevelOffset:-4.5 }).addTo(map);




 function get_groupes_layers(getEquipements)
 {

     for (var k in getEquipements) {
         var id = getEquipements[k].id;
         var icone = getEquipements[k].icone;
         var show = getEquipements[k].show;
         var libelle = getEquipements[k].libelle+"<img style='{{ trans('text.pos_icone') }}' src='"+icone+"' height=25 style= 'margin-left: 32px'/>";
         var name_el  = 'elem'+k;
         name_el = L.layerGroup();
         var set_cordinate  = coordonnee_gps_equipements(id,localite_fil);
         var get_cordinate = JSON.parse(set_cordinate);
         get_marker_type(get_cordinate,icone,name_el);
         element  = name_el;
         groupedOverlaysEquipement[libelle]=element;
         if(show)
             groupedOverlaysEquipement[libelle].addTo(map)
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


        function get_marker_emplacement(coordinates,element)
        {
            var icone_marker= L.AwesomeMarkers.icon({
                icon: 'circle',
                prefix: 'fa',
                markerColor: 'darkblue',
                iconColor: '#800026'});

            for (var k in coordinates) {
                var id_marler = coordinates[k].id;
                //alert(coordinates[k].lat)
                var  lat = parseFloat(coordinates[k].lat);
                var  log = parseFloat(coordinates[k].log);
                var  lib_mar =  coordinates[k].libelle_z;

                marker = L.marker([lat, log],{icon: icone_marker ,id:id_marler,title: lib_mar});
                element.addLayer(marker);
                marker.bindPopup('<div class="marker-title" style="display: inline">'+ lib_mar +'</div>');
                marker.on('mouseover',function(e){
                    this.openPopup();
                    //alert(this.options.id);
                    set_infoPopup(this.options.id);
                })
                marker.on('mouseout',function(){
                });
                marker.on('click', function(){
                    openObjectModal(this.options.id,'emplacements');

                } );
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
            marker.bindPopup('<div class="marker-title" style="display: inline">'+ lib_mar +'</div>');
           marker.on('mouseover',function(e){
               this.openPopup();
               //alert(this.options.id);
               set_infoPopup(this.options.id);
           })
           marker.on('mouseout',function(){
           });
           marker.on('click', function(){
               openObjectModal(this.options.id,'equipements');

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
                fillOpacity: 0.1,
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
            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine+'getInfoTypeEquipementSelected/'+id,
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
            });

            //info.update('<h2>Sidi maarouf</h2>');
        }
        function highlightFeature1(e) {

            var popup = new L.Popup({autoPan: false});

            var layer = e.target;

            var ref = '<?php echo $ref ?>';
            var module = '<?php echo $module ?>';

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
                url: racine + 'getInfoLocaliteSelected/' + e.target.feature.properties.id + "," + ref + "," + module,
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
            couche_loc.resetStyle(e.target);
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

                openObjectModal(e.target.feature.properties.id,'localites');

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
                this._div = L.DomUtil.create('div', 'info {{ trans('text.top-right') }}');
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
            position:'{{trans("text.topleft")}}',
            elementsToHide: '.gitButton,#left_zone,#footer,#common-header, .info,.legenden,.kk,#axe_stategique,#fl_p',
        }).addTo(map);


        function format(x) {
            return isNaN(x)?"":x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $("#v_tableau").on('click',function(){

            $("#result_format_tableau").html("Test");

        })



    </script>
</div>
