@extends('../layout')
@section('page-title')
    @if(env('DCS_APP'))
        Ministère  du Développement Durable
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
                <div id="filtre">
                    <?php
                    $id_f = '';
                    foreach($filtres as $filtre)
                    {
                    if($filtre->type != 3)
                    {
                    $id_f = $id_f . $filtre->id . ',';
                    ?>
                    <div class="form-group">
                        <label for="libelle">{{ $filtre->$libelle }}</label>
                        <select multiple="multiple" class="form-control select2" title="{{ trans('text.select') }}"
                                onchange="getCount()">
                        <!--<option value="0">{{ trans('text.all') }}</option>-->
                            <?php
                            $intervals = $filtre->get_intervals;
                            $id_ff = $filtre->id;
                            if (count($intervals) > 0) {
                                foreach ($intervals as $leg) {
                                    if ($leg->valeur_min != 0 || $leg->valeur_max != 0)
                                        echo "<option value='" . $leg->id . "'>" . $leg->$libelle . "</option>";
                                }
                            } else {
                                if ($filtre->type == 0) {
                                    $set_question = $filtre->get_question;
                                    if ($set_question->nature_question == 5) {
                                        $valeurs = explode(',', $set_question->valeurs);
                                        $i = 0;
                                        foreach ($valeurs as $val) {
                                            echo "<option value='" . htmlentities($val) . "+$id_ff'>" . htmlentities($val) . "</option>";
                                        }
                                    } elseif ($set_question->nature_question == 1) {

                                        echo "<option value='1+$id_ff'>" . trans('text.oui') . "</option>";
                                        echo "<option value='0+$id_ff'>" . trans('text.non') . "</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php } }  ?>
                    <input type='hidden' id="id_fil" ,name="id_fil" value="<?php echo $id_f?>"/>
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="axe_stategique" style="margin-top:5px!important;"></div>

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

        });
    </script>
@endsection
@endsection

