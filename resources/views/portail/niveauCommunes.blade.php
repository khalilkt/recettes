

<div style="display: none;" id="entete_carte">
<div style="width: 100%" >
    @if(env('DCS_APP')=='0')
        <img src="{{ url('img/header.jpg')  }}">
    @else
        <img src="{{ url('img/header_medd.jpg')  }}">
    @endif

</div>
    <h3 class="text-center">{{ trans("text.decoupage_admin") }}</h3>
    <h5>{{ trans("text.niveau") }} : {{ trans("text.Communes") }}</h5>
</div>
<button class="btn-group btn-success" onclick="baseLayersNiveau(1)">Test </button>;
<div id="map" class="sidebar-map"></div>

<div>
    <div id="resultat"></div>

    <div class="card  souscond_tb kk " >
        <div class="card-header"  role="tab" id="headingOne4">
            <form role="formexport"  id="formexport" name="formexport" class=""  method="post" >
                {{ csrf_field() }}
                <input type="hidden" name="data" value="{{ $getcolor }}" />
                <input type="hidden" name="module" value="{{ $module }}" />
                <input type="hidden" name="ref" value="{{ $ref }}" />
                <input type="hidden" name="niveau" value="1" />
            </form>
            <h4 class="card-title">

                <a class="collapsed " role="button" data-toggle="collapse" data-parent="#tb" href="#tb" aria-expanded="false" aria-controls="tb"  onclick="plus_info_commune(this,{ <?php echo $getcolor; ?> },'{{ $module }}','{{ $ref }}',1)">
                    <i class="more-less fa fa-plus " ></i>  {{ trans("text.voir_result") }}
                 </a>
                <button type="button" onclick="export_tab_commune(this)" class="btn btn-success  {{ trans('text.pul') }} exp_fiche_excel" style="padding: 3px 6px;font-size: 12px;margin-top: -5px;"><i class="fa fa-file-excel-o exp_excel"></i> {{ trans("text.export") }} </button>
            </h4>

        </div>
            <div id="tb" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tb">
            <div class="card-body" style="padding: 3px!important;">
                <div id="resultat_detait_info">

                </div>
            </div>
        </div>

  </div>

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


    var couche = getlayout(1); // charger la couche
    var baseslayout = baseLayersNiveau(1);
    var  layout =JSON.parse(couche);
    var baseLayers ={};
    var couche_aglc;
    var geojson;
    var lastClickedLayer;
    var  baseslayout =JSON.parse(baseslayout)


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


    geojson = L.geoJson(layout, {
         style: style,
         onEachFeature: onEachFeature
    }).addTo(map);

    var info = L.control({position: 'topleft'});
    var legend = L.control({position: '{{ trans("text.pos_legend")}}'});
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
            "activites ": lacs
        }
    }

    L.control.groupedLayers(baseLayers, groupedOverlays, {position: 'topright', collapsed: false}).addTo(map);

    //L.control.layers(null, overlayMaps,,{position: 'topleft'}).addTo(map);

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

    var layer = e.target;
    var arr = { <?php echo $getcolor; ?> }
    var ref='<?php echo $ref ?>';
    var nbr_com ='<?php echo $nbr_com ?>';
    var module ='<?php echo $module ?>';
    //layer.bringToFront();
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
            url: racine+'getInfoCommuneSelected/'+ e.target.feature.properties.id+","+ref+","+nbr_com+","+module,
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
        layer.bringToBack();

    }

}

    function highlightFeature1(e) {
        var popup = new L.Popup({ autoPan: false });

        var layer = e.target;
        var arr = { <?php echo $getcolor; ?> }
        var ref='<?php echo $ref ?>';
        var nbr_com ='<?php echo $nbr_com ?>';
        var module ='<?php echo $module ?>';
        //layer.bringToFront();
        if( arr[layer.feature.properties.id]  != null)
        {
            popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
            info.update(1);
            layer.setStyle({
                weight: 3,
                color: '#666',
                dashArray: '',
                fillOpacity: 0.7,
                zIndex: 1
            });
            popup.setLatLng(layer.getBounds().getCenter());

            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine+'getInfoCommuneSelected/'+ e.target.feature.properties.id+","+ref+","+nbr_com+","+module,
                cache: false,
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

            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                layer.bringToFront();
            }
            if (!popup._map) popup.openOn(map);
            window.clearTimeout(closeTooltip);


            // layer.bindLabel("str").addTo(map);
        }

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
    legand();
}

init_cart();
geojson.bringToBack();


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
            this._div.innerHTML = '<h5><i class="glyphicon glyphicon-info-sign"></i> {{ trans("text.info") }} <span style="color:#337ab7; font-weight:bold">{{ $bd_model }}</span></h5>' + (props ?
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
function legand()
{
    /*
     *  name      : legand
     * parametres :
     * return     :
     * Descrption : permet de l'affichage du legede a droit
     */

    legend.onAdd = function (map)
    {
        var nbr_com='<?php echo $nbr_com?>';
        var div = L.DomUtil.create('div', 'legende map-legend'),
                grades = [<?php echo $legands[0]; ?>],
                colors = [<?php echo $legands[1] ?>],
                labels = [],
                labels_imp=[],
                from, to;

        for (var i = 0; i < grades.length; i++) {
            labels.push(
                    '<li><span class="swatch" style="background:' + colors[i] + '!important; background-color: '+colors[i]+'!important; opacity: 0.6;"></span> ' +grades[i]+ '</li>');
            labels_imp.push(
                    '<li style="color:'+colors[i]+'!important;"><span class="swatch" style="background:' + colors[i] + '!important; background-color: '+colors[i]+';opacity: 0.6;"></span> ' +grades[i]+ '</li>');
        }
        div.innerHTML = '<h5><?php echo $titre_legande ?></h5><ul>' + labels.join('') + '</ul><hr>{{ trans("text.nbr_commune") }} : <span class="badge app_bgcolor">'+nbr_com+'</span>';
        $('#leg_imp').html('<h5><?php echo $titre_legande ?> :</h5><ul>' + labels_imp.join('') + '</ul>');
        $('#filter').html('<?php echo $filter ?>');
        return div;
    }
    legend.addTo(map);
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


</script>
