<?php

$libelle=trans("text.libelle_base");
$grade = "";
$color="";
//echo $getcolor;
$i=0;
$len = count($legands);
foreach($legands as $l )
{
    $grade = $grade."'$l[$libelle]'";
    $color = $color."'$l[color]'";
    if ($i != $len - 1) {
        $grade =$grade.',';
        $color =$color.',';
    }
    $i++;

}


?>

<div style="display: none;" id="entete_carte">
    <div style="width: 100%" >
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

<div>

    <div class="panel panel-default souscond_tb kk" >
        <div class="panel-heading"  role="tab" id="headingOne4">

            <h4 class="panel-title">
                <a class="collapsed "  role="button" data-toggle="collapse" data-parent="#tb" href="#tb" aria-expanded="false" aria-controls="tb" >
                    <i class="more-less fa fa-plus " ></i> Voir le resultat
                </a>
            </h4>
        </div> <div id="tb" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tb">
            <div class="panel-body" style="padding: 3px!important;">

                <table class="table">
                    <tr>
                        <th> {{ trans('text.wilaya') }}</th>
                        <th> {{  trans('text.population') }} </th>
                        <th> {{ trans('text.nbr_moughataa') }}</th>
                        <th> {{ trans('text.nbr_commune') }}</th>
                    </tr>
                    <?php
                    foreach($wilayas as $m)
                    {
                        $libelle=$m["libelle"];
                        $pop=$m['pop'];
                        $nbr_moug=$m['nbr_moughataas'];
                        $nbr_communes=$m['nbr_communes'];
                        echo "<tr>";
                        echo "<td>$libelle</td>";
                        echo "<td>$pop</td>";
                        echo "<td>$nbr_moug</td>";
                        echo "<td>$nbr_communes</td>";

                    }


                    ?>
                </table>
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
    var couche_aglc;
    var geojson;
    var map = L.map('map').setView([21, -10], 6).setMinZoom(5.8).setMaxZoom(18).setMaxBounds([[28, -21], [14, 5]]);
    /*// Ajout du couche Open Streets Map
     L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
     attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
     }).addTo(map);
    // Declaration des Variables*/
	
	var osmUrl='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var osmAttrib='© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors';

	var token = 'pk.eyJ1IjoiZG9tb3JpdHoiLCJhIjoiY2o0OHZuY3MwMGo1cTMybGM4MTFrM2dxbCJ9.yCQe43DMRqobazKewlhi9w';
    var mapboxUrl = 'https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}@2x?access_token=' + token;
    var mapboxAttrib = 'Map data © <a href="http://osm.org/copyright">OpenStreetMap</a> contributors. Tiles from <a href="https://www.mapbox.com">Mapbox</a>.';


    var osmLayer = L.tileLayer(osmUrl, {
        attribution: osmAttrib,
        maxZoom: 19
    });

    // la couche "osmLayer" est ajoutée à la carte
    map.addLayer(osmLayer);

    // création d'une couche "watercolorLayer"
    var watercolorLayer = L.tileLayer('http://{s}.tile.stamen.com/watercolor/{z}/{x}/{y}.jpg', {
        attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    });
   /* var transport = L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
        attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    });*/

    // la couche "watercolorLayer" est ajoutée à la carte
   // map.addLayer(watercolorLayer);

    // création d'un contrôle des couches pour modifier les couches de fond de plan
    var baseLayers = {
        "Watercolor" : watercolorLayer,
        "OpenStreetMap": osmLayer

    };

    var littleton = L.marker([19.61, -10.02]).bindPopup('This is Littleton, CO.'),
        denver    = L.marker([18.74, -10.99]).bindPopup('This is Denver, CO.'),
        aurora    = L.marker([17.73, -10.8]).bindPopup('This is Aurora, CO.'),
        golden    = L.marker([16.77, -10.23]).bindPopup('This is Golden, CO.');

    var cities = L.layerGroup([littleton, denver, aurora, golden]);

	
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
    geojson = L.geoJson(wilaya, {
        style: style,
        onEachFeature: onEachFeature
    }).addTo(map)

var parc = L.layerGroup([couche_parc, limit_parc]);

    var overlayMaps = {
        //'Wilaya':geojson,
        "Parc": parc,
       
        'AGLC' : couche_aglc
    };


    //baselayers.OrthoRM.addTo(map);

    // Add a layer control element to the map
    //layerControl = L.control.layers(baseLayers,overlayMaps, {position: 'topleft'});
    //layerControl.addTo(map);

    var info = L.control();
    var legend = L.control({position: 'bottomright'});
    var info_selected = L.control({position: 'bottomright'});

    //geojson.bringToBack();
    //couche_aglc.bringToFront();
    L.control.layers(null,overlayMaps,{position: 'topleft',collapsed: false}).addTo(map);
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
            zindex:12000,
            fillColor:'#eee'
        };
    }
	function style2(feature) {

        return {
            weight: 1,
            opacity: 1,
            color: 'blue',
            dashArray: '2',
            fillOpacity: 0.5,
            zindex:12000,
            fillColor:'#eee'
        };
    }
	function style_limit_parc(feature) {

        return {
            weight: 1,
            opacity: 1,
            color: 'blue',
            dashArray: '2',
            fillOpacity: 0.5,
            zindex:12000,
            fillColor:'#eee'
        };
    }
    function highlightFeature(e) {

        var popup = new L.Popup({ autoPan: false });


        var layer = e.target;
        var arr = { <?php echo $getcolor; ?> }
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';

        if( arr[layer.feature.properties.code]  != null)
        {
            popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
            info.update(1);
            layer.setStyle({
                weight: 3,
                color: '#666',
                dashArray: '',
                fillOpacity: 0.7
            });
            popup.setLatLng(layer.getBounds().getCenter());

            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine+'getInfoWilayaSelected/'+ e.target.feature.properties.code+','+ref+','+module,
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
                layer.bringToBack();
            }
            if (!popup._map) popup.openOn(map);
            window.clearTimeout(closeTooltip);
            layer.bringToBack();
        }

    }

    function highlightFeature1(e) {
        var popup = new L.Popup({ autoPan: false });

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
                color: 'red',
                dashArray: '',
                fillOpacity: 0.7,
                zIndex: 1
            });
            popup.setLatLng(layer.getBounds().getCenter());

            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine+'getInfoObjetSelected/'+ e.target.feature.properties.ID+","+ref+","+module,
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

            layer.bringToFront();
            // layer.bindLabel("str").addTo(map);
        //}

    }
	
	    function highlightFeature2(e) {
        var popup = new L.Popup({ autoPan: false });

        var layer = e.target;

        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        //layer.bringToFront();
		
        if( layer.feature.properties.Code  != 'null')
        {
            popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
            info.update(1);
            layer.setStyle({
                weight: 2,
                color: 'blue',
                dashArray: '',
                fillOpacity: 0.7,
                zIndex: 1
            });
            popup.setLatLng(layer.getBounds().getCenter());

            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine+'getInfoObjetSelected/'+ e.target.feature.properties.ID+","+ref+","+module,
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
		 layer.bringToFront();

    }
	
	 function highlightFeature_limit_parc(e) {
        var popup = new L.Popup({ autoPan: false });

        var layer = e.target;

        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        //layer.bringToFront();
		
        if( layer.feature.properties.Code  != 'null')
        {
            popup.setContent('<div class="text-center"><i class="fa fa-refresh fa-spin fa-fw"></i></div>');
            info.update(1);
            layer.setStyle({
                weight: 2,
                color: 'blue',
                dashArray: '',
                fillOpacity: 0.7,
                zIndex: 1
            });
            popup.setLatLng(layer.getBounds().getCenter());

            /* appel ajax pour recuperer le resultat au serveur */

            $.ajax({
                type: 'GET',
                //data:{id:layer.feature.properties.ID},
                url: racine+'getInfoObjetSelected/'+ e.target.feature.properties.ID+","+ref+","+module,
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
                layer.bringToBack();
            }
            if (!popup._map) popup.openOn(map);
            window.clearTimeout(closeTooltip);
             layer.bringToBack();
          
            // layer.bindLabel("str").addTo(map);
        }
		 //layer.bringToFront();

    }
    function resetHighlight(e) {
        geojson.resetStyle(e.target);
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
	
	  function resetHighlight2(e) {
        couche_parc.resetStyle(e.target);
        closeTooltip = window.setTimeout(function() {
            map.closePopup();
        }, 100);

        info.update();
    }
	 function resetHighlight_limit_parc(e) {
        limit_parc.resetStyle(e.target);
        closeTooltip = window.setTimeout(function() {
            map.closePopup();
        }, 100);

        info.update();
    }


    function zoomToFeature(e) {
        map.fitBounds(e.target.getBounds());
        var couche=0;
        if(map.hasLayer(couche_aglc))
            couche=1;
        /* appele ajax pour recuperer l'information au click*/
        var arr = {<?php echo $getcolor; ?> }
        var ref = '<?php echo $ref ?>';
        var module = '<?php echo $module ?>';
        if (arr[e.target.feature.properties.code] != null)
        {
            $('#basicModal').modal('show');
            $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>chargement en cours</p></div>').fadeIn('fast');


            $.ajax({
                type: 'GET',
                url: racine+'detaitInfoWilaya/' + e.target.feature.properties.code+','+ref+','+module+','+couche,
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
        var ref='<?php echo $ref ?>';
        var module ='<?php echo $module ?>';
	
       /* if (arr[e.target.feature.properties.ID] != null)
        {*/
      // alert(e.target.feature.properties.ID);

            $('#basicModal').modal("show");

            //loading_show();
            $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

            $.ajax({
                type: 'GET',
                url: racine+'detaitInfoObjet/' + e.target.feature.properties.ID+','+ref+','+module,
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
        var ref='<?php echo $ref ?>';
        var module ='<?php echo $module ?>';
       /* if (arr[e.target.feature.properties.ID] != null)
        {*/
      // alert(e.target.feature.properties.ID);

            $('#basicModal').modal("show");

            //loading_show();
            $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

            $.ajax({
                type: 'GET',
                url: racine+'detaitInfoObjet/' + e.target.feature.properties.ID+','+ref+','+module,
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
        var ref='<?php echo $ref ?>';
        var module ='<?php echo $module ?>';
       /* if (arr[e.target.feature.properties.ID] != null)
        {*/
      // alert(e.target.feature.properties.ID);

            $('#basicModal').modal("show");

            //loading_show();
            $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

            $.ajax({
                type: 'GET',
                url: racine+'detaitInfoObjet/' + e.target.feature.properties.ID+','+ref+','+module,
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

    function onEachFeature(feature, layer)
    {
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
    function init_cart()
    {
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
    geojson.bringToBack();
	limit_parc.bringToFront();
    couche_aglc.bringToFront();

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
            var nbr_wil ='<?php echo $nbr_wil ?>';
            var div = L.DomUtil.create('div', 'legende map-legend'),
                    grades = [<?php echo $grade; ?>],
                    colors = [<?php echo $color ?>],
            //grades = ''
            //colors = ''
                    labels = [],
                    from, to;

            for (var i = 0; i < grades.length; i++) {

                labels.push(
                        '<li><span class="swatch" style="background:' + colors[i] + '!important;"></span> ' +grades[i]+ '</li>');
            }
            div.innerHTML = '<h5><?php echo $titre_legande ?></h5><ul>' + labels.join('') + '</ul><hr>{{ trans("text.nbr_wilaya") }} : <span class="badge app_bgcolor">'+nbr_wil+'</span>';
            $('#leg_imp').html('<h5><?php echo $titre_legande ?> :</h5><ul>' + labels.join('') + '</ul>');
            $('#filter').html('<?php echo $filter ?>');
            return div;
        };
        legend.addTo(map);
    }

    L.easyPrint({
        title: 'Imprimer la carte',
        elementsToHide: '.gitButton,#left_zone,#footer,#common-header, .info,.legende,.kk'
    }).addTo(map);

    function format(x) {
        return isNaN(x)?"":x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>
