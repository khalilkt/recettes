<?php
$text_right = trans("text.text_right");
$position = trans("text.position_right");
$direction = trans("text.direction");
$libelle = trans("text.libelle_base");
?>
<style>
    .datatable {
        width: 100% !important;
    }

</style>
<script>
    $(document).ready(function () {
        $("#verif_ens").hide();
        var date_ref = "<?php echo $date_ref?>";

        var id_com = "<?php echo $commune->id ?>";
        var module = "<?php echo $module ?>";
        $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
        //charger le fiche communale de la commune
        $.ajax({
            type: 'get',
            url: racine + 'get_fiche_communale_ByDate_ref/' + id_com + ',' + date_ref + ',' + module + ',' + 1,
            cache: false,
            success: function (data) {
                $('#result_fiche').empty();
                //loading_hide();
                $('#result_fiche').html(data);


            },
            error: function () {
                //  loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });

    })

    $("#date_ref_com").on('change', function () {
        $(".imp_fiche").attr('disabled', 'disabled');
        $(".exp_fiche_excel").attr('disabled', 'disabled');
        var date_ref = $("#date_ref_com").val() + '-12-31';
        var id_com = "<?php echo $commune->id ?>";
        var module = "<?php echo $module ?>";
        $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
        $.ajax({
            type: 'get',
            url: racine + 'get_fiche_communale_ByDate_ref/' + id_com + ',' + date_ref + ',' + module + ',' + 1,
            cache: false,
            success: function (data) {
                $('#result_fiche').empty();
                //loading_hide();
                $('#result_fiche').html(data);
                $('#loading').empty();


            },
            error: function () {
                //  loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });
    });

    // les actions a faire selecon l'export choisie excel ou pdf

    function OnButton1() {
        document.formst.action = "export_pdf"
        document.formst.target = "_blank";    // Open in a new window

        document.formst.submit();             // Submit the page

        return true;
    }

    function OnButton2() {
        document.formst.action = "export_excel"
        document.formst.target = "_blank";    // Open in a new window

        document.formst.submit();             // Submit the page

        return true;
    }

    $('#comp').change(function () {

        $("#ens_date_evaluations").html('');
        if ($(this).is(':checked')) {
            $('#date_evaluations').show();
            var date_ev = $("#date_eva").val();
            var id_com = "<?php echo $commune->id ?>";
            var module = "<?php echo $module ?>";
            $.ajax({
                type: 'get',
                url: racine + 'ens_evaluations/' + id_com + ',' + date_ev + ',' + module + ',' + 1,
                //('tyru')->result,
                cache: false,
                success: function (data) {

                    $("#ens_date_evaluat").html(data);

                },
                error: function () {
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
        }
        else {
            $("#fiche_com").val('');
            $("#show_st").hide();
            $("#ens_date_evaluat").html('');
            $('#date_evaluations').hide();

            $(".imp_fiche").attr('disabled', 'disabled');
            $(".exp_fiche_excel").attr('disabled', 'disabled');
            var date_ref = $("#date_ref_com").val() + '-12-31';
            var id_com = "<?php echo $commune->id ?>";
            var module = "<?php echo $module ?>";
            $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
            $.ajax({
                type: 'get',
                url: racine + 'get_fiche_communale_ByDate_ref/' + id_com + ',' + date_ref + ',' + module + ',' + 1,
                cache: false,
                success: function (data) {
                    $('#result_fiche').empty();
                    //loading_hide();
                    $('#result_fiche').html(data);
                    $('#loading').empty();


                },
                error: function () {
                    //  loading_hide();
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
        }
    });
    $('#ens_date_evaluations').change(function () {
        var nb = $("#ens_date_evaluations :selected").length;
        if (nb > 0) {
            $(".valid_com").removeAttr('disabled');
        }
        else {
            $(".valid_com").attr('disabled', 'disabled');
            $("#resultat_com").html('');
            var date_ref = $("#date_ref_com").val() + '-12-31';
            var id_com = "<?php echo $commune->id ?>";
            var module = "<?php echo $module ?>";
            $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
            $.ajax({
                type: 'get',
                url: racine + 'get_fiche_communale_ByDate_ref/' + id_com + ',' + date_ref + ',' + module + ',' + 1,
                cache: false,
                success: function (data) {
                    $('#result_fiche').empty();
                    //loading_hide();
                    $('#result_fiche').html(data);
                    $('#loading').empty();


                },
                error: function () {
                    //  loading_hide();
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
        }
    });

    //charger la carte
    $("a#voir_cart").on('click', function () {

        var path = $(this).attr('rel');

        $("#resutClick4").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine + 'affiche_carte/' + path + ',' + 1,
            cache: false,
            success: function (data) {
                $('#basicModal4').modal('show');
                //$('#example').html(data.msg + 'popilation' + data.pp);
                //loading_hide();
                $("#resutClick4").html(data);

            },
            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }
        });

    })
    function result_eval() {
        var val = $("#ens_date_evaluations").val();
        var id_fiche = $('#id_fiche_com').val();

        var date_ref = $("#date_ref_com").val() + '-12-31';
        var module = "<?php echo $module ?>";
        var id_c = "<?php echo $commune->id ?>";
        var date_eval = $('#date_eva').val();

        var items = [];
        items.push(date_eval);
        items.push(val);
        $("#fiche_com").val(items);
        $('#result_fiche').html('<i style=" color:#009688;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');

        $.ajax({
            type: 'get',
            url: racine + 'get_resultat_com/' + id_fiche + '+' + items + '+' + date_ref + '+' + module + '+' + 1 + '+' + id_c,
            cache: false,
            success: function (data) {
                $('#result_fiche').empty();
                //loading_hide();
                $('#result_fiche').html(data);
                $('#loading').empty();

            },
            error: function () {
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });
    }


    function showStatistique(element) {


        var id_f = $(element).attr('id_fiches');
        var id_g = $(element).attr('id_groupe');
        var type_st = $(element).attr('type_st');
        var module = $(element).attr('module');
        var niveau = $(element).attr('niveau');
        var id_objet_niveau = $(element).attr('id_objet_niveau');


        $("#resutClick3").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: 'GET',
            url: racine + 'get_statistique_com/' + id_g + '+' + id_f + '+' + id_objet_niveau + '+' + type_st + '+' + module + '+' + niveau,
            cache: false,
            success: function (data) {
                $('#basicModal3').modal('show');
                //$('#example').html(data.msg + 'popilation' + data.pp);
                //loading_hide();
                $("#resutClick3").html(data);

            },
            error: function () {
                //alert('La requête n\'a pas abouti');
                console.log('La requête n\'a pas abouti');
            }
        });

    }
</script>


<div class="modal-header">


    </button>
    <h4 class="modal-title" style="{{ $position }}" id="myModalLabel"><i
                class="fa fa-list-alt"></i> {{ trans("text.fiche") }} : <a>{{ $commune->$libelle }} </a>
        <!-- <a href="{{ url('/download/manuel.docx') }}" target="_blank"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Plus d'information" ></i></a>-->
    </h4>
    <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
</div>

<div class="modal-body">

    <div class="well well-lg">
        <div class="row" style="{{ $text_right }}">
            @if(env('DCS_APP') == '0')
                <div class="col-md-6">
                    <table>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.commune") }} : </b></td>
                            <td>{{ $commune->$libelle }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.moughataa") }} : </b></td>
                            <td> {{ $moughataa }}</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.wilaya") }} : </b></td>
                            <td>{{ $wilaya }}</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-users"></i> <b> {{ trans("text.population") }} : </b></td>
                            <td>
                                <span class="direction"><?php echo strrev(wordwrap(strrev($commune->nbr_habitans), 3, ' ', true));  ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.surface") }} :</b></td>
                            <td>
                                <span class="direction"><?php echo strrev(wordwrap(strrev($commune->surface), 3, ' ', true));  ?>
                                    km&sup2; </span></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.nbr_village") }} :</b></td>
                            <td>{{ $commune->nbr_villages_localites }}</td>
                        </tr>
                    </table>

                </div>

                <div class="col-md-6">
                    <table>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.nbr_cons") }} : </b></td>
                            <td>{{ $commune->nbr_conseillers_municipaux }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.nbr_emp_per") }} : </b></td>
                            <td> {{  $commune->nbr_employes_municipaux_permanents }}</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.pndidlle") }} : </b></td>
                            <td> @if($commune->pnidelle==1)  @php echo
                                trans("text.oui"); @endphp @elseif($commune->pnidelle==0) @php echo
                                trans("text.non") @endphp @endif </td>

                        </tr>
                    </table>
                    <div>
                        <i class="fa fa-users"></i> <b> {{ trans("text.decret") }} : </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{ $commune->decret_de_creation }}
                    </div>

                    <div>
                        <i class="fa fa-eye"></i> <b><a id="voir_cart" rel="<?php echo $commune->id;?>"
                                                        href="#">{{ trans("text.v_carte") }}</a> </b>

                    </div>

                </div>
            @else
                <div class="col-md-6">
                    <table>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.commune") }} : </b></td>
                            <td>{{ $commune->$libelle }}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.moughataa") }} : </b></td>
                            <td> {{ $moughataa }}</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.wilaya") }} : </b></td>
                            <td>{{ $wilaya }}</td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-users"></i> <b> {{ trans("text.population") }} : </b></td>
                            <td>
                                <span class="direction"><?php echo strrev(wordwrap(strrev($commune->nbr_habitans), 3, ' ', true));  ?></span>
                            </td>
                        </tr>

                    </table>

                </div>

                <div class="col-md-6">
                    <table>

                        <tr>
                            <td><i class="fa fa-globe"></i> <b>{{ trans("text.surface") }} :</b></td>
                            <td>
                                <span class="direction"><?php echo strrev(wordwrap(strrev($commune->surface), 3, ' ', true));  ?>
                                    km&sup2; </span></td>
                        </tr>

                    </table>
                    <div>
                        <i class="fa fa-users"></i> <b> {{ trans("text.decret") }} : </b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{ $commune->decret_de_creation }}
                    </div>

                    <div>
                        <i class="fa fa-eye"></i> <b><a id="voir_cart" rel="<?php echo $commune->id;?>"
                                                        href="#">{{ trans("text.v_carte") }}</a> </b>

                    </div>

                </div>
                @endif
                        <!--  <div class="col-md-4">
                /div>

             </div>
            <!--<div class="col-md-6">
                <div ><i class="fa fa-globe"></i> <b>Décret de création : {{ $commune->decret_de_creation }} </b> </div>
                 </div>-->


        </div>
    </div>
    <div class="row" style="{{ $text_right }}">
        <input type="hidden" value="" id="id_fiche_com"/>

        <div class="form-group col-md-6 col-xs-6" style="color:red">
            <label for="date_ref">{{ trans("text.annee_ref") }}</label>
            <!--<h6><i class="glyphicon glyphicon-calendar"></i> Année de référence</h6>-->
            <!-- <input type="date" class="form-control" value="{{ $date_ref }}" id="date_ref_com"  name="date_ref_com" placeholder="">-->
            <select class="form-control" name="date_ref_com" id='date_ref_com'>
                <?php
                $exp = explode('-', $date_ref);
                $annee = $exp[0];
                $date = date('Y');
                for ($i = 2013; $i <= $date; $i++) {
                    if ($i == $annee)
                        echo "<option selected='selected'>" . $i . "</option>";
                    else
                        echo "<option>" . $i . "</option>";
                }
                ?>
            </select>
        </div>
        @if($module==2)
            <div class="form-group col-md-6 col-xs-6 pull-right">
                <label for="date_eva"> {{ trans("text.date_ev") }}</label>
                <input type="date" class="form-control" disabled="disabled" value="{{ $date_ev }}" name="date_eva"
                       id="date_eva">
            </div>
        @endif

    </div>
    @if($module==2)
        <div class="panel panel-default" id="verif_ens">
            <div class="panel-heading" style="background-color:#337ab7; text-align: left;">
                <h3 class="panel-title">
                    <div id="comp_cl" class="checkbox disabled" style="margin:0px; padding:0px">
                        <label style="margin:0px; padding:0px">
                            <input name="comp" id="comp" type="checkbox" disabled="disabled">
                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                            <span style="color:white;">{{ trans("text.comp") }}</span>

                        </label>
                    </div>
                </h3>


            </div>

            <div class="panel-body" id="date_evaluations">

                <div id="ens_date_evaluat"></div>

            </div>
        </div>

        @if(env('DCS_APP')=='0')
            <div style="display: block; height: 40px;">
                <button type="button" class="btn btn-success pull-left" style="{{ $position }}" data-toggle="modal"
                        data-target="#helpModal">
                    <i class="fa fa-question-circle-o"></i> {{ trans("text.aide_fiche") }}
                </button>
            </div>
        @endif
    @endif
    <div id="loading"></div>
    <div id="result_fiche"></div>


</div>
<div class="modal-footer">
    <form role="form" id="formst" name="formst" class="" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="exp_fiche" id="exp_fiche"/>
        <input type="hidden" name="exp_eval" id="exp_eval"/>
        <input type="hidden" name="id_fiche" value="{{ $commune->id }}"/>
        <input type="hidden" name="fiche_com" id="fiche_com"/>
        <input type="hidden" name="date_ref" value="{{ $date_ref }}"/>
        <input type="hidden" name="module" value="{{ $module }}"/>
        <input type="hidden" name="id_groupe[]" id="id_groupe[]"/>
        <input type="hidden" name="niveau_exp" id="niveau_exp" value="1"/>


        <button type="button" class="btn btn-dark pull-left text-white" data-dismiss="modal"><i
                    class="fa fa-times"></i> {{ trans("text.fermer") }}</button>
       {{-- <button type="button" disabled="disabled" class="btn btn-success pull-right exp_fiche_excel text-white"><i
                    class="fa fa-file-excel-o exp_excel "></i> {{ trans("text.export") }}</button>--}}
        <button type="button" onclick="OnButton1();" disabled="disabled" class="btn btn-primary pull-right imp_fiche text-white"><i
                    class="fa fa-print"></i> {{ trans("text.print") }} </button>

    </form>
</div>
