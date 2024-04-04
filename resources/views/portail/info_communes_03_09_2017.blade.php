<?php

$libelle=trans("text.libelle_base");
$grade = "";
$color="";
//echo $getcolor;
$i=0;
$len = count($legands);
foreach($legands as $l )
{
    $lb = htmlspecialchars("$l[$libelle]", ENT_QUOTES);
    $grade = $grade."'$lb'";
    $color = $color."'$l[color]'";
    if ($i != $len - 1) {
        $grade =$grade.',';
        $color =$color.',';
    }
    $i++;
    // echo '<script>console.log('.$grade.')</script>';
}

?>

<div style="display: none;" id="entete_carte">
    <div style="width: 100%" >
        <img src="{{ url('img/header.jpg')  }}">
    </div>
    <h3 class="text-center">{{ trans("text.decoupage_admin") }}</h3>
    <h5>{{ trans("text.niveau") }} : {{ trans("text.Communes") }}</h5>
</div>
<div id="map"></div>

<div>

    <div class="panel panel-default souscond_tb " >
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
                        <th> {{ trans('text.commune') }}</th>
                        <th>{{ trans('text.moughataa') }}</th>
                        <th> {{  trans('text.wilaya') }} </th>
                        <th> {{ trans('text.population') }}</th>
                        <th> {{ trans('text.nbr_village') }}</th>
                        <th>{{ trans('text.nbr_cons') }}</th>
                    </tr>
                    <?php


                    foreach($communes as $commune)
                    {$id= $commune["id"];
                        $libelle = $commune['libelle'];
                        $wilaya = $commune['wilaya'];
                        $moughataa=$commune["moughataa"];
                        $pop = $commune["pop"];
                        $nbr_village=$commune['nbr_village'];
                        $nbr_cons = $commune['nbr_cons'];
                        echo "<tr>";

                        echo "<td>$libelle</td>";
                        echo "<td>$moughataa</td>";
                        echo "<td>$wilaya</td>";
                        echo "<td>$pop</td>";
                        echo "<td>$nbr_village</td>";
                        echo "<td>$nbr_cons</td>";





                        echo "</tr>";
                    }

                    ?>
                </table>
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
                .prev('.panel-heading')
                .find(".more-less")
                .toggleClass('fa-plus fa-minus');
    }
    // variable

    // Initialisation de la map
    // variable global
    var map = L.map('map').setView([21, -10], 6).setMinZoom(6).setMaxZoom(10).setMaxBounds([[28, -21], [14, 5]]);
    // Ajout du couche Open Streets Map
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    // Declaration des Variables

    var info = L.control();
    var geojson;
    var legend = L.control({position: 'bottomright'});
    var info_selected = L.control({position: 'bottomright'});
    map.attributionControl.addAttribution('Disigned by &copy; <a href="http://dcs-sarl.com/" target="__back">DCS-sarl</a>');

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
            fillColor: getColor(feature.properties.ID)
        };
    }

    function highlightFeature(e) {

        var popup = new L.Popup({ autoPan: false });

        var layer = e.target;
        var arr = { <?php echo $getcolor; ?> }
        var ref='<?php echo $ref ?>';
        var nbr_com ='<?php echo $nbr_com ?>';
        if( arr[layer.feature.properties.ID]  != null)
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
                url: racine+'getInfoCommuneSelected/'+ e.target.feature.properties.ID+","+ref+","+nbr_com,
                cache: false,
                success: function (data) {
                    popup.setContent('<div class="marker-title">'+ data.nom + '</div>' +
                            '{{ trans("text.population") }}: ' +format(data.pp));
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
    function resetHighlight(e) {
        geojson.resetStyle(e.target);
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
        if (arr[e.target.feature.properties.ID] != null)
        {
            $('#basicModal').modal("show");

            //loading_show();
            $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

            $.ajax({
                type: 'GET',
                url: racine+'detaitInfoCommune/' + e.target.feature.properties.ID+','+ref,
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
        geojson = L.geoJson(commun, {
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
                this._div.innerHTML = '<h5><i class="glyphicon glyphicon-info-sign"></i> {{ trans("text.info") }}</h5>' + (props ?
                        '<b> {{ trans("text.commune") }}   : ' + props.nom + '</b><br />{{ trans("text.moughataa") }} : ' + props.moughataa + '<br>{{ trans("text.wilaya") }} :' + props.wilaya +
                        ' <br> {{ trans("text.population") }} : <b>' + format(props.pp) + '</b>' +props.nbr_loc + props.cons  + props.pnd  + props.date_ref
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
                    grades = [<?php echo $grade; ?>],
                    colors = [<?php echo $color ?>],
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

    $('#basicModal').on('hidden.bs.modal', function (e) {
        map.setZoom(6);
    })

    L.easyPrint({
        title: 'Imprimer la carte',
        //position: 'topleft',
        elementsToHide: '.gitButton,#left_zone,#footer,#common-header, .info,.legende',
    }).addTo(map);



    function format(x) {
        return isNaN(x)?"":x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    $("#v_tableau").on('click',function(){

        $("#result_format_tableau").html("Test");

    })

</script>
