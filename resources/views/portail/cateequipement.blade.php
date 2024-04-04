@extends('layout')

@php
    $lib=trans('text_me.lib');

    if (App::isLocale('ar'))
        $libelle = "libelle_ar";
    else
        $libelle = "libelle";

@endphp

@section('page-content')
    <div class="row" style="margin-top: -40px!important;">
        <div id="loading" class="loading"></div>
        <div class="col-lg-2 m-0 p-0">

            <div class="left-side-bloc-t" style="padding-left:0px;">
                <a href="{{ url('equipements') }}"
                   class="d-none d-sm-inline-block btn btn-sm bg-violet shadow-sm text-white"
                   style="width: 100%!important;">{{ trans('text_me.equipements') }}</a>
            </div>

            <div class="left-side-bloc" style="padding-left:0px;">
                <h6><i class="fa fa-filter"></i>{{ trans('text.filtre') }} </h6>
                <div id="filtre">
                    <div class="form-group">
                        <label for="libelle">Localité </label>
                        <select multiple="multiple" class="form-control selectpicker" name='localite'
                                title="{{ trans('text.all') }}" onchange="getCount()">
                        <!-- <option value="0">{{ trans('text.all') }}</option>-->
                            <?php
                            if ($localites->count() > 0) {
                                foreach ($localites as $pr) {
                                    echo "<option value='l-" . $pr->id . "'>" . $pr->$libelle . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="libelle">Type equipements </label>
                        <select multiple="multiple" class="form-control selectpicker" name='type_eq'
                                title="{{ trans('text.all') }}" onchange="getCount()">
                        <!-- <option value="0">{{ trans('text.all') }}</option>-->
                            <?php
                            if ($type_equipements->count() > 0) {
                                foreach ($type_equipements as $pr) {
                                    echo "<option value='e-" . $pr->id . "'>" . $pr->$libelle . "</option>";
                                }
                            }

                            ?>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-10 m-0 p-0">
            <div class="card shadow">
                <div class="card-body">

                    <div class="clearfix"></div>

                    <div id="res"></div>
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

                        var change_leg = 0;

                        var module = "<?php echo $module ?>";
                        var ref_deb = $("#ref_deb").val();
                        var ref_fin = $("#ref_fin").val();


                        if ($("#ap_niveau").val() != $('#niveau_geo').val()) {
                            //filtre_niveau();
                            //legend_niveau();
                            change_leg = 1;

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
                            url: racine + 'filterNiveau_patrimoin/' + selected + '*' + change_leg + '*' + module,
                            cache: false,

                            success: function (data) {
                                $('#res').empty();
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

                    $("#basicModal5").on('hidden.bs.modal', function () {
                        $(this).data('bs.modal', null);
                    });

                    //charge les moughatas du wilaya


                </script>

        </div>
    </div>
    @endsection

    </div>
    </div>
@endsection
