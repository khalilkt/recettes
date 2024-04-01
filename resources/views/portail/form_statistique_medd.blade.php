@php
//$d_module = Crypt::encrypt($module);
@endphp
<script>
    $(document).ready(function () {
        //niveau commune
        $("#commune").hide();

        var id_cat_st = "<?php echo $id_cat_st; ?>";
        $.ajax({
            type: 'get',
            url: racine + 'list_wilayas',
            cache: false,
            success: function (data) {
                $("#wilayas").empty();
                $('#wilayas').html(data.wilayas);

            },
            error: function () {

                //loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                //$.alert("Un problème est survenu. veuillez réessayer plus tard");
            }
        });
        ulr_select = racine + 'groupe_donnees_mauritanie/' + id_cat_st;
        $.ajax({
            type: 'get',
            url: racine + 'categrie_donnees',
            cache: false,
            success: function (data) {
                $('#categorie_donnee').html(data);
            },
            error: function () {
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });

        $.ajax({
            type: 'get',
            url: ulr_select,
            cache: false,
            success: function (data) {
                $('#grp_donnee').html(data.grp_donnee);
                $("#type_pr").html(data.charts)
            },
            error: function () {
                //loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });
    });

</script>

<div class="col-lg-2 col-md-3 col-sm-12 left-side">
    @include('gestion.cummun.login')
    @if(!env('DCS_APP'))
        <div class="left-side-bloc" style="padding-left:0px;">
            <a href="{{ url('/') }}" class="btn btn-primary btn-block">{{ trans("text.mdp") }}</a>
        </div>
        <div class="left-side-bloc" style="padding-left:0px;">
            <a href="{{ url('compdec') }}" class="btn btn-success btn-block">{{ trans('text.compdec') }}</a>
        </div>
    @else
        <div class="left-side-bloc" style="padding-left:0px;">
            <a href="{{ url('/') }}" class="btn btn-primary btn-block">Carte</a>
        </div>
    @endif

</div>
<div id="loading" class="loading"></div>
<div id="res" class="col-lg-10 col-md-9 col-sm-12 nopadding">
    <br>

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ trans('text.statistique') }}
            </div>
            <div class="panel-body">
                <div class="row">
                    <form role="form" id="formst" class="" action="{{ url('get_statistique') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="module" value="{{ $module}}"/>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>* {{ trans('text.annee_ref') }} </label>

                                <!--<input type="date" class="form-control" value="<?php echo date('Y-m-d');?>" name="date_ref" id="date_ref">-->
                                <select class="form-control selectpicker" name="date_ref" id="date_ref">
                                    <?php
                                    $date = date('Y');
                                    for ($i = 2012; $i <= $date; $i++) {
                                        if ($i == $date)
                                            echo "<option selected='selected'>" . $i . "</option>";
                                        else
                                            echo "<option>" . $i . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label> {{ trans('text.niveau_s') }} </label>
                                <select class="form-control" id="niveau_geo" name="niveau_geo">
                                    <option value="4">{{ trans('text.national') }}</option>
                                    <option value="3">{{ trans('text.wilaya') }}</option>
                                    <option value="2">{{ trans('text.moughataa') }}</option>
                                    <option value="1">{{ trans('text.commune') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('text.categorie') }}</label>
                                <select class="form-control" name="categorie_donnee" id="categorie_donnee">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>* {{ trans('text.type_st') }}</label>
                                <select class="form-control" name="grp_donnee" id="grp_donnee">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ trans('text.type_visaluation') }} </label>
                                <select class="form-control" name="type_pr" id="type_pr">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">

                                <div class="radio">
                                    <label><input type="radio" name="chiff"  value="true" checked>avec chiffre</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="chiff" value="false">Sans chiffre</label>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="commune">
                                <div class="form-group" id="wil">
                                    <label>{{ trans('text.Wilayas') }}</label>
                                    <select class="form-control" id="wilayas" name="wilayas">

                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="mg">
                                <label>{{ trans('text.Moughataas') }}</label>
                                <select class="form-control" id="moughataas" name="moughataas">

                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="com">
                            </div>


                        </div>


                <div id="form-errors" style="display: none"></div>

                <button type="button" id="get_statistique"
                        class="btn btn-success pull-right addfiche">{{ trans("text.generer") }}</button>

                </form>

                <!-- /.col-lg-6 (nested) -->

                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>


<!-- Resultat de popup -->

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">

            <div id="resutClick" style="direction: ltr!important;"></div>

        </div>
    </div>


</div>


<script>
    $('#get_statistique').on('click', function () {

        var data = $('#formst').serialize();
        $('#basicModal').modal('show');
        //loading_show();
        $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

        $.ajax({
            type: $('#formst').attr("method"),
            url: $('#formst').attr("action"),
            data: data,
            success: function (data) {

                $("#resutClick").html(data);
                if (data.error) {

                }
            },
            error: function (data) {
                $('#basicModal').modal('hide');
                if (data.status == 422) {


                    if (data.responseJSON.communes)
                        $('.multiselect').css('border-color', 'red');
                    if (data.responseJSON.date_ref)
                        $('#date_ref').css('border-color', 'red');
                    if (data.responseJSON.grp_donnee)
                        $('#grp_donnee').css('border-color', 'red');
                    errorsHtml = '<div class="alert alert-danger"> 1- {{ trans("message_erreur.champs_obligatoire_st") }} <br> 2- {{ trans("message_erreur.prametre_st") }}     !';
                    errorsHtml += '</div>';
                    $('#form-errors').show().html(errorsHtml);

                }
                else {
                    $.alert("{{ trans('message_erreur.validate_error') }}");
                }
            }

        });
    })


    $("#niveau_geo").on('change', function () {
        niveau_geo = $("#niveau_geo").val();
        if (niveau_geo <= 3)
            $('#commune').show();
        switch (niveau_geo) {
            case '1':
                $("#wil").show();
                $("#mg").show();
                $("#com").show();
                $.ajax({
                    type: 'get',
                    url: racine + 'list_wilayas',
                    cache: false,
                    success: function (data) {
                        $("#wilayas").empty();
                        $('#wilayas').html(data.wilayas);
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });
                // charger les communes
                $.ajax({
                    type: 'get',
                    url: racine + 'multSelect_communes',
                    cache: false,
                    success: function (data) {
                        $('#com').html(data);
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });

                id_cat = $("#categorie_donnee").val();
                //charger le groupe de donnees
                $.ajax({
                    type: 'get',
                    url: racine + 'groupe_donnees_commune/' + id_cat,
                    cache: false,
                    success: function (data) {
                        $('#grp_donnee').html(data.grp_donnee);
                        $("#type_pr").html(data.charts)
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });
                break;
            case '2':
                $("#wil").show();
                $("#mg").hide();
                $("#com").show();
                // $("#mg").hide();
                // charger les wilaya
                $.ajax({
                    type: 'get',
                    url: racine + 'list_wilayas',
                    cache: false,
                    success: function (data) {
                        $("#wilayas").empty();
                        $('#wilayas').html(data.wilayas);
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });
                $.ajax({
                    type: 'get',
                    url: racine + 'multSelect_moughataas',
                    cache: false,
                    success: function (data) {
                        $('#com').html(data);
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });
                id_cat = $("#categorie_donnee").val();
                // Groupe de donnée moughataa
                $.ajax({
                    type: 'get',
                    url: racine + 'groupe_donnees_moughataas/' + id_cat,
                    cache: false,
                    success: function (data) {
                        $('#grp_donnee').html(data.grp_donnee);
                        $("#type_pr").html(data.charts)
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });
                break;
            case '3':
                $("#wil").hide();
                $("#mg").hide();
                $("#com").show();
                $.ajax({
                    type: 'get',
                    url: racine + 'multSelect_wilayas',
                    cache: false,
                    success: function (data) {
                        $('#com').html(data);
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });
                id_cat = $("#categorie_donnee").val();
                $.ajax({
                    type: 'get',
                    url: racine + 'groupe_donnees_wilayas/' + id_cat,
                    cache: false,
                    success: function (data) {
                        $('#grp_donnee').html(data.grp_donnee);
                        $("#type_pr").html(data.charts)
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });
                break;

            case '4':
                $("#wil").hide();
                $("#mg").hide();
                $("#com").hide();
                id_cat = $("#categorie_donnee").val();
                $.ajax({
                    type: 'get',
                    url: racine + 'groupe_donnees_mauritanie/' + id_cat,
                    cache: false,
                    success: function (data) {
                        $('#grp_donnee').html(data.grp_donnee);
                        $("#type_pr").html(data.charts)
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });
                break;

            case '5':
                $("#wil").hide();
                $("#mg").hide();
                $("#com").hide();
                id_cat = $("#categorie_donnee").val();
                source = $("#type_source").val();
                $.ajax({
                    type: 'get',
                    url: racine + 'groupe_donnees_categorie/' + id_cat,
                    cache: false,
                    success: function (data) {
                        $('#grp_donnee').html(data.grp_donnee);
                        $("#type_pr").html(data.charts)
                    },
                    error: function () {
                        $.alert("{{ trans('message_erreur.request_error') }}");
                    }
                });
                break;
            default:

                break;
        }

    })

    $("#wilayas").on('change', function () {
        id = $("#wilayas").val();
        niveau_geo = $("#niveau_geo").val();

        if (id != 0) {
            switch (niveau_geo) {
                case '1':
                    ulr_select = racine + 'multSelect_communes_byWilaya/';
                    $("#mg").show('slow');
                    //charger la liste des moughataas
                    $.ajax({
                        type: 'get',
                        url: racine + 'liste_moughataas/' + id,
                        cache: false,
                        success: function (data) {

                            $('#moughataas').html(data.moughataas);
                        },
                        error: function () {
                            //loading_hide();
                            //$meg="Un problème est survenu. veuillez réessayer plus tard";
                            $.alert("{{ trans('message_erreur.request_error') }}");
                        }
                    });
                    break;
                case '2':
                    ulr_select = racine + 'multSelect_moughataas_byWilaya/';
                    $("#mg").hide('slow');
                    break;
                default:

                    break;
            }
            //charger la liste des moughataas de la wilaya
            urr = ulr_select + id;
            //alert(urr)
            $.ajax({
                type: 'get',
                url: ulr_select + id,
                cache: false,
                success: function (data) {
                    $('#com').html(data);
                },
                error: function () {

                    //loading_hide();
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                }
            });
            // charger la liste des communes de la wilayas

        }
        else {
            $("#mg").hide('slow');
            niveau_geo = $("#niveau_geo").val();
            switch (niveau_geo) {
                case '1':
                    ulr_select = racine + 'multSelect_communes';
                    //charger la liste des moughataas
                    break;
                case '2':
                    ulr_select = racine + 'multSelect_moughataas';
                    break;
                default:

                    break;
            }
            $.ajax({
                type: 'get',
                url: ulr_select,
                cache: false,
                success: function (data) {
                    $('#com').html(data);
                },
                error: function () {

                    //loading_hide();
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
        }

    })
    $("#moughataas").on('change', function () {
        id = $("#moughataas").val();
        id_w = $("#wilayas").val();
        if (id != 0) {
            $.ajax({
                type: 'get',
                url: racine + 'multSelect_communes_byMoughataas/' + id,
                cache: false,
                success: function (data) {
                    $('#com').html(data);
                },
                error: function () {
                    //loading_hide();
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
        }
        else {
            $.ajax({
                type: 'get',
                url: racine + 'multSelect_communes_byWilaya/' + id_w,
                cache: false,
                success: function (data) {

                    $('#com').html(data);

                },
                error: function () {

                    //loading_hide();
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
        }
    })


    $("#categorie_donnee").on('change', function () {
        id_cat = $(this).val();

        niveau_geo = $("#niveau_geo").val();
        switch (niveau_geo) {
            case '1':
                ulr_select = racine + 'groupe_donnees_commune/' + id_cat;
                //charger la liste des moughataas
                break;
            case '2':
                ulr_select = racine + 'groupe_donnees_moughataas/' + id_cat;
                break;
            case '3':
                ulr_select = racine + 'groupe_donnees_wilayas/' + id_cat;
                break;
            case '4':
                ulr_select = racine + 'groupe_donnees_mauritanie/' + id_cat;
                break;
            case '5':
                ulr_select = racine + 'groupe_donnees_categorie/' + id_cat;
                break;
            default:

                break;
        }
        $.ajax({
            type: 'get',
            url: ulr_select,
            cache: false,
            success: function (data) {
                $('#grp_donnee').html(data.grp_donnee);
                $("#type_pr").html(data.charts)
            },
            error: function () {
                //loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });


    })

    $("#grp_donnee").on('change', function () {
        id_st = $(this).val();

        $.ajax({
            type: 'get',
            url: racine + 'type_charts/' + id_st,
            cache: false,
            success: function (data) {
                $("#type_pr").html(data)
            },
            error: function () {
                //loading_hide();
                //$meg="Un problème est survenu. veuillez réessayer plus tard";
                $.alert("{{ trans('message_erreur.request_error') }}");
            }
        });

    })

</script>


</div>
