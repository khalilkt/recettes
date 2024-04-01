

<div style="display: none;" id="entete_carte">
    <div style="width: 100%" >
        @if(env('DCS_APP')=='0')
            <img src="{{ url('img/header.jpg')  }}">
        @else
            <img src="{{ url('img/header_medd.jpg')  }}">
        @endif
    </div>
    <h3 class="text-center">{{ trans("text.decoupage_admin") }}</h3>
    <h5>{{ trans("text.niveau") }} : {{ trans("text.Moughataas") }}</h5>
</div>
<div id="map"></div>

<div class="panel panel-default souscond_tb kk " >
    <div class="panel-heading"  role="tab" id="headingOne4">
        <form role="formexport"  id="formexport" name="formexport" class=""  method="post" >
            {{ csrf_field() }}
            <input type="hidden" name="data" value="{{ $getcolor }}" />
            <input type="hidden" name="module" value="{{ $module }}" />
            <input type="hidden" name="ref" value="{{ $ref }}" />
            <input type="hidden" name="niveau" value="2" />
        </form>
        <h4 class="panel-title">

            <a class="collapsed " role="button" data-toggle="collapse" data-parent="#tb" href="#tb" aria-expanded="false" aria-controls="tb"  onclick="plus_info_commune(this,{ <?php echo $getcolor; ?> },'{{ $module }}','{{ $ref }}',2)">
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


<table style='display: none; margin-top:20px; width: 100%!important;' class="imp" id="imp_carte_info">
    <tr>
        <td style="width: 50%;vertical-align: top;">
            <div  id="filter" class="legende1 map-legend1"></div>
        </td>
        <td style="width: 50%; margin-right: 0px!important; padding-left:40px;">
            <div  id="leg_imp" class="legende1 map-legend1"></div>
        </td>
    </tr>
</table>


<script type="text/javascript">
    // variable

    // Initialisation de la map
    // variable global
    //var map = L.map('map').setView([21, -10], 5.8).setMinZoom(5.8).setMaxZoom(18).setMaxBounds([[28, -21], [14, 5]]);
    var couche = getlayout(2); // charger la couche
    var  layout =JSON.parse(couche);

    var map = L.map('map').setView([20.7, -13], 5.8).setMinZoom(6).setMaxZoom(18).setMaxBounds([[27.6, -29], [13.4, 2.4]]);
    // Ajout du couche Open Streets Map
    var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmAttrib='Map data © <a href="http://osm.org/copyright">OpenStreetMap</a> contributors';

    var token = 'pk.eyJ1IjoiZG9tb3JpdHoiLCJhIjoiY2o0OHZuY3MwMGo1cTMybGM4MTFrM2dxbCJ9.yCQe43DMRqobazKewlhi9w';
    var mapboxUrl = 'https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}@2x?access_token=' + token;
    var mapboxAttrib = 'Map data © <a href="http://osm.org/copyright">OpenStreetMap</a> contributors. Tiles from <a href="https://www.mapbox.com">Mapbox</a>.';
    var lastClickedLayer;

    var osmLayer = L.tileLayer(osmUrl, {
        attribution: osmAttrib,
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
    map.addLayer(sat);


    // création d'une couche "watercolorLayer"
    var watercolorLayer = L.tileLayer('http://{s}.tile.stamen.com/watercolor/{z}/{x}/{y}.jpg', {
        attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    });
    // la couche "watercolorLayer" est ajoutée à la carte
    //map.addLayer(watercolorLayer);

    // création d'un contrôle des couches pour modifier les couches de fond de plan
    var baseLayers = {
        'Satellite':sat,
        "Watercolor" : watercolorLayer,
        "OpenStreetMap": osmLayer
    };
    // Declaration des Variables

   /// var info = L.control();
    var geojson;
   // var legend = L.control({position: 'bottomright'});
    var info = L.control({position: 'topleft'});
    var legend = L.control({position: 'topright'});

    L.control.layers(baseLayers, null, {position: 'topright', collapsed: false}).addTo(map);
    map.attributionControl.addAttribution('Disigned by &copy; <a href="http://dcs-sarl.com/" target="__back">DCS-sarl</a>');


	// on a production site, omit the "lc = "!
lc = L.control.locate({
    strings: {
        title: "Montre moi où je suis !"
    }
}).addTo(map);
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
            color: '#555',
            dashArray: '2',
            fillOpacity: 0.6,
            fillColor: getColor(feature.properties.id)
        };
    }

    function highlightFeature(e) {

        var popup = new L.Popup({ autoPan: false });


        var layer = e.target;
        var arr = { <?php echo $getcolor; ?> }
        var ref='<?php echo $ref ?>';
        var module ='<?php echo $module ?>';

        if( arr[layer.feature.properties.id]  != null)
        {
            popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
            info.update(1);
            layer.setStyle({
                weight: 2,
                dashArray: '',
            });
            map_zoom_start(layer);
            popup.setLatLng(layer.getBounds().getCenter());

            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine+'getInfoMoughataaSelected/'+ e.target.feature.properties.id+','+ref+','+module,
                success: function (data) {
                    popup.setContent('<div class="marker-title" style="display: inline">'+ data.nom +'</div>'+ data.info_hover);
                    info.update(data);

                    // map.removeLayer(info);
                    //$("#resultat").html("sidi maarouf");
                },

                error: function () {
                    //alert('La requête n\'a pas abouti');
                    console.log('La requête n\'a pas abouti');
                }
            });
            if (!popup._map) popup.openOn(map);
            window.clearTimeout(closeTooltip);

            if (!L.Browser.ie && !L.Browser.opera) {
                layer.bringToFront();
            }
            layer.bindLabel("str").addTo(map);

        }

    }
    function reset_style_map_zoom(layer,color='#666')
    {
        /*map.on("zoomstart", function() {
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
        });*/
    }

    function map_zoom_start(layer,color='#666')
    {
        /*map.on("zoomstart", function() {
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
        reset_style_map_zoom(e.target);
        closeTooltip = window.setTimeout(function() {
            map.closePopup();
        }, 100);
        info.update();
    }

    function zoomToFeature(e) {
        map.fitBounds(e.target.getBounds());
        /* appele ajax pour recuperer l'information au click*/
        var arr = {<?php echo $getcolor; ?> }
        var ref ='<?php echo $ref ?>';
        var module ='<?php echo $module ?>';
        if (arr[e.target.feature.properties.id] != null)
        {
            var basicModal = jQuery('.modal.in').attr('id'); // modalID or undefined

            $('#basicModal').modal('show');
            $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

            $.ajax({
                type: 'GET',
                url: racine+'detaitInfoMoughataa/'+e.target.feature.properties.id+','+ref+','+module,
                success: function (data) {
                    $("#resutClick").html(data);

                },
                error: function () {
                    $meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert($msg);
                    console.log('La requête n\'a pas abouti');
                }
            });

        }
    }
    function onEachFeature(feature, layer) {
        // layer.bindLabel('kk ',{noHide:false});
        lastClickedLayer = layer;
        layer.on({
            mouseover: highlightFeature,
            mouseout: resetHighlight,
            click: zoomToFeature
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
        geojson = L.geoJson(layout, {
            style: style,
            onEachFeature: onEachFeature
        }).addTo(map);
        info_right();
        legand();
    }

    init_cart();

    function info_right()
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
                this._div.innerHTML = '<h5><i class="glyphicon glyphicon-info-sign"></i> {{ trans('text.info') }} <span style="color:#337ab7; font-weight:bold">{{ $bd_model }}</span> </h5>' + (props ?
                                props.info_right
                                : '{{ trans('text.survole') }}');
            }
        };
        info.addTo(map);

    }

    function legand()
    {
        /*
         *  name      : legand
         * parametres :
         * return     :
         * Descrption : permet de l'affichage du legede a droit
         */

        legend.onAdd = function (map) {
            var nbr_mg='<?php echo $nbr_mg?>';
            var div = L.DomUtil.create('div', 'legende map-legend'),
                    grades = [<?php echo $legands[0]; ?>],
                    colors = [<?php echo $legands[1] ?>],
            //grades = ''
            //colors = ''
                    labels = [],
                    from, to;

            for (var i = 0; i < grades.length; i++) {

                labels.push(
                        '<li><span class="swatch" style="background:' + colors[i] + '!important"></span> ' +grades[i]+ '</li>');
            }
            div.innerHTML = '<h5><?php echo $titre_legande ?></h5><ul>' + labels.join('') + '</ul><hr>{{ trans("text.nbr_moughataa") }} : <span class="badge app_bgcolor">'+nbr_mg+'</span>';
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
    /*$('#basicModal').on('hidden.bs.modal', function (e) {

        //zoomToFeature;
        lastClickedLayer.setStyle({
            fillOpacity: 0.6
        });
        //geojson.resetStyle(lastClickedLayer);
        map.setZoom(6);

    });*/

    L.easyPrint({
        title: 'Imprimer la carte',
        elementsToHide: '.gitButton,#left_zone,#footer,#common-header, .info,.legende,.kk,#axe_stategique,#fl_p'
    }).addTo(map);

    function format(x) {
        return isNaN(x)?"":x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>
