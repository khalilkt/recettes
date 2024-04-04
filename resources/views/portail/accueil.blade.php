@extends('../layout')
@section('page-title')
    @if(env('DCS_APP'))
        Ministère du Développement Rural
    @else
        {{ trans("text.header_titre2") }}
    @endif

@endsection
@section('page-content')
    @php
    $d_module = Crypt::encrypt($module);

    @endphp
    <div class="col-md-12">
    @include('portail.cummun.menu_top2')
    </div>
    <?php

    if (App::isLocale('ar'))
        $libelle = "libelle_ar";
    else
        $libelle = "libelle";
    ?>


    <?php
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(stripos($ua, 'android') !== false && env("DCS_APP") == '0') { // && stripos($ua,'mobile') !== false) {
    ?>
    {{--}}<div class="alert alert-success" role="alert">
        <strong>Information </strong> <a href="{{ url('data/app-release.apk')  }}" download target="_blank"> <i
                    class="fa fa-download"></i> Cliquez ici pour télécharger la version mobile de système d'information
        </a></h4>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>--}}
    <?php } ?>

    <div class="col-lg-12 left-side" id="left_zone">
        @include('portail.cummun.login')
        @include('portail.cummun.filtres')
    </div>

    <div id="loading" class="loading"></div>
    <!---<select ></select>-->
    <div class="row px-1" >
        <div class="col-lg-3 " style="/*max-height: 680px; overflow: auto;*/">
        <div class="left-side-bloc" id="fl_p">
            <h6><i class="fa fa-filter"></i>{{ trans('text.filtre') }} </h6>
            <button type="button"  style="    top: 0;position: absolute;right: 0;margin-right: 16px;margin-top: 2px;" class="btn btn-success btn-xs pull-right"  title="Requete personnalisée" onclick="costumRequet()"><i class="fa fa-plus"></i></button>
             <div id="filtre">

            </div>
            <div class="clearfix"></div>
        </div>


    </div>
        <div id="res" class="col-lg-9 nopadding">
    </div>
    </div>

    <!-- Resultat de popup -->

    <div id="basicModal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">

                <div id="resutClick_form"></div>

            </div>
        </div>


    </div>

    <div id="basicModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">

                <div id="resutClick"></div>

            </div>
        </div>


    </div>


    <div style="width: 100%">
        <div id="basicModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div id="resutClick2"></div>

                </div>
            </div>

        </div>
        <!-- Resultat statistique-->
        <div id="basicModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div id="resutClick3"></div>

                </div>
            </div>

        </div>
        <!-- carte commune-->
        <div id="basicModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div id="resutClick4"></div>

                </div>
            </div>

        </div>

        <!-- Personnalisation -->
        <!-- carte commune-->


        <div id="basicModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div id="resutClick5"></div>

                </div>
            </div>

        </div>

        <!-- Aide fiche communale  -->

        <!-- Modal help -->
        <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" >
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="text-align: left;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="helpModalLabel"><i class="fa fa-question-circle"></i> Aide</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading1">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button" class="collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapse1" aria-expanded="false"
                                           aria-controls="collapse1">
                                            Evolution de l'indice du couvert végétal (ICV)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse1" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading1">
                                    <div class="panel-body" >
                                            <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">ICV</h4>
                                            <p>Elle représente l’ensemble des végétaux recouvrant le sol de façon permanente ou temporaire dans un espace donnée. Elle est composée des trois paramètres L (couvert ligneux), H (couvert herbacé) et E (diversité des essences ligneuses) qui la définissent</p>

                                        </div>
                                      </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading2">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button" class="collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapse2" aria-expanded="false"
                                           aria-controls="collapse2">
                                            Couvert arboré (A)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse2" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading2">
                                    <div class="panel-body">
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">A</h4>
                                            <p>Il représente la valeur moyenne de la projection verticale au sol des cimes des arbres comptés à l’aide d’un dendromètre au niveau des placettes d’observation d’une AGLC, en m²/ha</p>
                                        </div>
                                       </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading3">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button" class="collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapse3" aria-expanded="false"
                                           aria-controls="collapse3">
                                            Couvert régénération arboré (R)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse3" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading3">
                                    <div class="panel-body">
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">R</h4>
                                        <p>Il représente la valeur moyenne de la projection verticale au sol des cimes des rejets des arbres ne dépassant pas 2 mètres, comptés à l’aide d’un dendromètre au niveau des placettes d’observation d’une AGLC, en m²/ha</p>
                                    </div>
                                        </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading4">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button" class="collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapse4" aria-expanded="false"
                                           aria-controls="collapse4">
                                            Couvert arbustif (B)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse4" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading4">
                                    <div class="panel-body">
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">B</h4>
                                            <p>Il représente la valeur moyenne de la projection verticale au sol des cimes des arbustes comptés à l’aide d’un dendromètre sur des placettes d’observation d’une AGLC, en m²/ha</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading5">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button" class="collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapse5" aria-expanded="false"
                                           aria-controls="collapse5">
                                            Couvert ligneux (L)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse5" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading5">
                                    <div class="panel-body">
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">L</h4>
                                            <p>Il regroupe les couverts arboré, arbustif et de régénération arborée (A + R + B) et donne la surface moyenne d’occupation du sol en couvert ligneux au niveau d’une AGLC en m²/ha</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading6">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button" class="collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapse6" aria-expanded="false"
                                           aria-controls="collapse6">
                                            Nombre d'essences rencontrées (Ne)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse6" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading6">
                                    <div class="panel-body">
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">NE</h4>
                                            <p>Il représente le nombre moyen des types d’essences végétales rencontrées au niveau de l’AGLC</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading7">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button" class="collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapse7" aria-expanded="false"
                                           aria-controls="collapse7">
                                            Couvert herbacé (H)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse7" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading7">
                                    <div class="panel-body">
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">H</h4>
                                            <p>Il représente le pourcentage moyen de la surface occupée par des étendues « non-stériles » c'est-à-dire contenant un couvert végétal au niveau d’une AGLC.
                                                Diversité des essences ligneuses (E) : la diversité des essences ligneuses est prise en compte sous forme d’un indicateur de diversité dont la valeur est obtenue à partir de (Ne) à travers la formule E=(Ne-1) + (Min / Max) <br>
                                                <b>Min :</b>  nombre de pieds d’arbres trouvés pour l’essence la plus rare <br>
                                                <b>Max : </b> nombre de pieds d’arbres trouvés pour l’essence la plus fréquente
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading8">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button" class="collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapse8" aria-expanded="false"
                                           aria-controls="collapse8">
                                            Evolution relative de l'indice du couvert végétal (Evol.rel)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse8" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading8">
                                    <div class="panel-body">
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">Evol.rel</h4>
                                            <p>C’est une mesure comparative entre l’évolution du couvert végétal au niveau des AGLC et celle de la zone témoin où il n’y a aucun effort de gestion des ressources pendant une période de temps déterminée</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading9">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button" class="collapsed" data-toggle="collapse"
                                           data-parent="#accordion" href="#collapse9" aria-expanded="false"
                                           aria-controls="collapse9">
                                            Zone témoin (ZT)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse9" class="panel-collapse collapse" role="tabpanel"
                                     aria-labelledby="heading9">
                                    <div class="panel-body">
                                        <div class="alert alert-info" role="alert">
                                            <h4 class="alert-heading">ZT</h4>
                                            <p>C’est une zone qui représente l’état écologique d’un espace brousse qui n’est pas soumis à une gestion locale collective des ressources naturelles et est composée de 4 zones écologiques suivantes : <br>
                                                	Savane à croûtes/cuirasses/glacis (C)<br>
                                                	Savane sablonneuse (S)<br>
                                                	Montagne (M)<br>
                                                	Galerie ou zone humide (G)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">{{ trans('text.fermer') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- fin modal help -->

    </div>



    </div>


 @section('module-js')
     <script>
         $(document).ready(function () {

             $("#ap_niveau").val(1);
             var niveau_act = 1;
             var module = '<?php echo $module ?>';

             loading_show();
             /*$( "#res" ).load( "defaut_niveau_geo/"+module, 100,function() {
              loading_hide();
              });*/
             getCount();

             $('.souscond_t').on('hidden.bs.collapse', toggleIcon);
             $('.souscond_t').on('shown.bs.collapse', toggleIcon);


             $("#block_moug").hide();
             $("#block_com").hide();



         });

         function toggleIcon(e) {
             $(e.target)
                 .prev('.card-header')
                 .find(".more-less")
                 .toggleClass('fa-plus fa-minus');
         }

         function loading_show() {
             $('#loading').html('<i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
         }
         function loading_hide() {
             $('#loading').fadeOut('fast');
         }


         function getCount() {
               // $("#map").html('');
             var change_leg = 0;
             var date_ref = ref + '-12-31';
             var module = "<?php echo $module ?>";
             if ($("#ap_niveau").val() != $('#niveau_geo').val()) {
                 filtre_niveau();
                 legend_niveau();
                 change_leg = 1;
                 dcs_app ={{ env('DCS_APP') }}

                 if (dcs_app = '1') {
                     get_axes($('#niveau_geo').val(), module);
                 }
             }

             var comboBoxes = document.querySelectorAll("div.left-side-bloc select");
             var selected = [];

             var mult_select = [];
             for (var i = 0, len = comboBoxes.length; i < len; i++) {
                 var combo = comboBoxes[i];
                 var op = '';
                 var options = combo.children;
                 var k = 0;
                 for (var j = 0, length = options.length; j < length; j++) {
                     var option = options[j];
                     if (option.selected) {
                         if (k > 0)
                             op += ';' + option.value;
                         else {
                             op += option.value;
                         }
                         k++;
                     }
                 }
                 if (op.length > 0)
                     selected.push(op);
             }
             loading_show();


             $.ajax({
                 type: 'get',
                 url: racine + 'filterNiveau/' + selected + '*' + change_leg + '*' + module,
                 cache: false,
                 success: function (data) {
                     $('#res').html('');
                     loading_hide();
                     $('#res').html(data);
                     resetInit();
                 },
                 error: function () {
                     loading_hide();
                     //$meg="Un problème est survenu. veuillez réessayer plus tard";
                     $.alert("{{ trans('message_erreur.request_error') }}");
                 }
             });

             $("#ap_niveau").val($('#niveau_geo').val());
             return false;
             //alert("Annee '" + selected[1] + "' Total Count "+ selected.length);
         }

         function filtre_niveau() {
             var niveau_geo = $("#niveau_geo").val();
             var module = '<?php echo $module ?>';
             $.ajax({
                 type: 'get',
                 url: racine + 'liste_filtre_niveau/' + niveau_geo + '/' + module,
                 cache: false,
                 success: function (k) {
                     $("#filtre").empty();
                     $("#filtre").html(k);
                     //ajout legend du filtre
                 },
                 error: function () {

                     //loading_hide();
                     //$meg="Un problème est survenu. veuillez réessayer plus tard";
                     //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                 }
             });
         }
         function legend_niveau() {
             var niveau_geo = $("#niveau_geo").val();
             var module = '<?php echo $module ?>';
             $.ajax({
                 type: 'get',
                 url: racine + 'liste_legend_niveau/' + niveau_geo + '/' + module,
                 cache: false,
                 success: function (d) {
                     $("#legend_pers").empty();
                     $("#legend_pers").html(d);
                     // apel getCount pour actualiser la carte apres led cangement de legande
                 },
                 error: function () {
                     //loading_hide();
                     //$meg="Un problème est survenu. veuillez réessayer plus tard";
                     //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                 }
             });

         }

         function costumRequet()  {

             var id_f = $("#id_fil").val();

             var niveau_geo = $("#niveau_geo").val();
             var module = "<?php echo $module ?>";
             $("#resutClick5").html('');
             $('#basicModal5').modal("show");
             $.ajax({
                 type: 'get',
                 url: racine + 'liste_filtre/' + id_f + "+" + niveau_geo + '/' + module,
                 cache: false,
                 success: function (data) {

                     $("#resutClick5").empty();
                     $('#resutClick5').html(data);
                      resetInit();


                 },
                 error: function () {

                     //loading_hide();
                     //$meg="Un problème est survenu. veuillez réessayer plus tard";
                     //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                 }
             });

         }

         $("#basicModal5").on('hidden.bs.modal', function () {
             $(this).data('bs.modal', null);
         });
         $("a.trouvecom").on('click', function () {
             if ($(this).find('.fa-plus').length) {

                 $.ajax({
                     type: 'get',
                     url: racine + 'list_wilayas',
                     cache: false,
                     success: function (data) {
                         $("#wilaya_t").empty();
                         $('#wilaya_t').html(data.wilayas);

                     },
                     error: function () {

                         //loading_hide();
                         //$meg="Un problème est survenu. veuillez réessayer plus tard";
                         //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                     }
                 });
             }
             else {
                 $("#block_moug").hide();
                 $("#block_com").hide();
             }

         });

         //charge les moughatas du wilaya

         $("#wilaya_t").on('change', function () {
             id = $("#wilaya_t").val();

             if (id != 0) {
                 $("#block_moug").show();
                 $("#block_com").hide();
                 $.ajax({
                     type: 'get',
                     url: racine + 'liste_moughataas/' + id,
                     cache: false,
                     success: function (data) {

                         $('#moughataa_t').html(data.moughataas);
                     },
                     error: function () {
                         //loading_hide();
                         //$meg="Un problème est survenu. veuillez réessayer plus tard";
                         //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                     }
                 });
             }
             else {
                 $("#block_moug").hide();
                 $("#block_com").hide();
             }

         })
         $("#moughataa_t").on('change', function () {
             id = $("#moughataa_t").val();

             if (id != 0) {
                 $("#block_com").show();
                 $.ajax({
                     type: 'get',
                     url: racine + 'liste_communes/' + id,
                     cache: false,
                     success: function (data) {

                         $('#commune_t').html(data.communes);
                     },
                     error: function () {
                         //loading_hide();
                         //$meg="Un problème est survenu. veuillez réessayer plus tard";
                         //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                     }
                 });
             }
             else {
                 $("#block_com").hide();
             }

         })
         $("#commune_t").on('change', function () {
             id = $("#commune_t").val();
             ref = $("#ref").val() + '-12-31';

             var module = "<?php echo $module ?>";

             if (id != 0) {
                 $('#basicModal').modal("show");

                 $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

                 $.ajax({
                     type: 'GET',
                     url: racine + 'detaitInfoCommune/' + id + ',' + ref + ',' + module,
                     cache: false,
                     success: function (data) {

                         //$('#example').html(data.msg + 'popilation' + data.pp);
                         //loading_hide();
                         $("#resutClick").html(data);

                         $('#commune_t option:eq(0)').prop('selected', true);

                     },
                     error: function () {
                         //alert('La requête n\'a pas abouti');
                         console.log('La requête n\'a pas abouti');
                     }
                 });
                 return false;
             }


         })
     </script>
 @endsection
@endsection

