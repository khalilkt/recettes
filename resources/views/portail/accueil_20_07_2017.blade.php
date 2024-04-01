@extends('../layout')
@section('page-title')
    {{ trans("text.header_titre2") }}
@endsection
@section('page-content')

    <script>
        $(document).ready(function () {
            $("#pop1").hide();
            $("#pop2").hide();
            loading_show();

            $( "#res" ).load( "defaut_niveau_geo", 100,function() {
                loading_hide();
            });
            $('.souscond_t').on('hidden.bs.collapse', toggleIcon);
            $('.souscond_t').on('shown.bs.collapse', toggleIcon);
            $("#block_moug").hide();
            $("#block_com").hide();
        });


        function toggleIcon(e) {
            $(e.target)
                    .prev('.panel-heading')
                    .find(".more-less")
                    .toggleClass('fa-plus fa-minus');
        }

    </script>
    <?php
    if(App::isLocale('ar'))
        $libelle="libelle_ar";
    else
        $libelle="libelle";
    ?>

    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('text.Authentification') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading"></div>
                                <div class="panel-body">
                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('users/login') }}">
                                        {{ csrf_field() }}

                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="email" class="col-md-4 control-label">{{ trans('text.email') }}</label>

                                            <div class="col-md-6">
                                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                  <strong>{{ $errors->first('email') }}</strong>
                                              </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="password" class="col-md-4 control-label">{{ trans('text.psw') }}</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password" class="form-control" name="password" required>

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                  <strong>{{ $errors->first('password') }}</strong>
                                              </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-4">
                                                <div class="">
                                                    <label>
                                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> {{ trans('text.rester_connect') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="clearfix"></div>
                                    <div id="form-errors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success pull-right loguser">{{ trans('text.connexion') }}</button>
                    <i style="display:none" class="form-loading fa fa-refresh fa-spin fa-2x fa-fw pull-right" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin modal Connexion -->
    <div class="col-lg-2 col-md-3 col-sm-12 left-side" id="left_zone">
        <div class="left-side-bloc">
            @if (Auth::guest())
                <h6><i class="glyphicon glyphicon-user"></i> {{ trans('text.Authentification') }} </h6>
                <button type="button" class="btn btn-default btn-block" data-toggle="modal" data-target="#loginModal">{{ trans('text.connecter') }}</button>
            @else
                <h6><i class="glyphicon glyphicon-user"></i> {{ trans('text.user_conect') }}</h6>
                <div class="dropdown">
                    <button class="btn btn-default btn-block dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        {{ Auth::user()->name }}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        @if(Auth::user()->hasAnyRole(['Administrateur','consultation']))
                            <li><a href="{{ url('dashboard') }}">{{ trans('text.panneau') }}</a></li>
                        @else
                            <li><a href="{{ url('fichescommunales') }}">{{ trans('text.panneau') }}</a></li>
                        @endif
                        <li>
                            <a href="{{ url('/logout') }}"
                               onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                                {{ trans('text.deconnecter') }}
                            </a>
                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>
            @endif
            @php
            if(!empty($errors->all()))
            echo '<span class="help-block" style="color:red">'.trans("text.erreur_auth").'</span>';
            @endphp
        </div>
        <form method="get" action="#">
            <div class="left-side-bloc" style="padding-left:0px;">
                <a href="{{ url('recherche') }}" class="btn btn-primary btn-block">{{ trans('text.statistique') }}</a>
            </div>
            <div class="left-side-bloc">
                <h6><i class="glyphicon glyphicon-tasks"></i> {{ trans('text.niveau') }}</h6>
                <select class="selectpicker" id="niveau_geo" name="niveau_geo"  onchange="filter(this.value,ref.value,legend.value,filtre_pop.value,filtre_notp.value,filtre_cond.value,filtre_pop1.value,filtre_pop2.value)">
                    <option value="3">{{ trans('text.vu_wilaya') }}</option>
                    <option value="2">{{ trans('text.vu_moughataa') }}</option>
                    <option value="1" selected="selected">{{ trans('text.vu_commune') }}</option>
                </select>
            </div>
            <div class="left-side-bloc">
                <div class="panel panel-default souscond_t" style="margin-bottom: 0px;!important;">
                    <div class="panel-heading"  role="tab" id="headingOne" style="padding: 10px!important;">

                        <h4 class="panel-title">
                            <a class="collapsed trouvecom"  role="button" data-toggle="collapse" data-parent="#pr" href="#fs" aria-expanded="false" aria-controls="fs" style="font-size:12px">
                                <i class="more-less fa fa-plus " ></i> {{ trans('text.trouve_commune') }}
                            </a>
                        </h4>
                    </div> <div id="fs" class="panel-collapse collapse" role="tabpanel" aria-labelledby="fs">
                        <div class="panel-body" style="padding: 3px!important;">
                            <div class="panel-group" id="fs" role="tablist" aria-multiselectable="true" style="margin-bottom: 0px;">
                                <div class="left-side-bloc" >
                                    <h6>{{ trans('text.wilaya') }}</h6>
                                    <select class="form-control" id="wilaya_t" name="wilaya_t" style="margin-bottom: 10px;"/>

                                    </select>
                                </div>
                                <div class="left-side-bloc" id="block_moug">
                                    <h6>{{ trans('text.moughataa') }}</h6>
                                    <select class="form-control" id="moughataa_t" name="moughataa_t" style="margin-bottom: 10px;"/>

                                    </select>
                                </div>
                                <div class="left-side-bloc" id="block_com">
                                    <h6>{{ trans('text.commune') }}</h6>
                                    <select class="form-control" id="commune_t" name="commune_t" style="margin-bottom: 10px;"/>

                                    </select>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="left-side-bloc">
                <h6><i class="glyphicon glyphicon-calendar"></i> {{ trans('text.annee_ref') }}</h6>
                <select class="selectpicker" name="ref" id='ref'  onchange="filter(niveau_geo.value,this.value,legend.value,filtre_pop.value,filtre_notp.value,filtre_cond.value,filtre_pop1.value,filtre_pop2.value)">
                    <?php
                    $date = date('Y');
                    for($i=2013; $i<=$date; $i++ )
                    {
                        if($i==$date)
                            echo "<option selected='selected'>".$i."</option>";
                        else
                            echo "<option>".$i."</option>";
                    }
                    ?>
                </select>
                <!-- <input type="date" class="form-control" name="ref" id='ref' value="<?php echo date('Y-m-d') ;?>" onchange="filter(niveau_geo.value,this.value,legend.value,filtre_pop.value,filtre_notp.value,filtre_cond.value,filtre_pop1.value,filtre_pop2.value)" />-->
            </div>
            <div class="left-side-bloc" id="leg">
                <h6><i class="glyphicon glyphicon-eye-open"></i> {{ trans('text.legend') }}</h6>
                <select class="selectpicker" name="legend" id="legend"  onchange="filter(niveau_geo.value,ref.value,this.value,filtre_pop.value,filtre_notp.value,filtre_cond.value,filtre_pop1.value,filtre_pop2.value)">
                    <option value="1" selected="selected">{{ trans('text.population') }}</option>
                    <option value="2" >{{ trans('text.performance') }}</option>
                    <option value="3">{{ trans('text.cond_min') }} </option>
                </select>
            </div>
            <div class="left-side-bloc" >
                <h6><i class="glyphicon glyphicon-filter"></i>{{ trans('text.filtre') }} </h6>
                <div class="form-group" id="pop">
                    <label for="libelle">{{ trans('text.group_pop') }}</label>
                    <select class="selectpicker form-control"  name="filtre_pop" id="filtre_pop"  onchange="filter(niveau_geo.value,ref.value,legend.value,this.value,filtre_notp.value,filtre_cond.value,filtre_pop1.value,filtre_pop2.value)">
                        <option value="0">{{ trans('text.all') }}</option>
                        @foreach($filter_pop as $pop)
                            @if($pop->valeur_min !=0 || $pop->valeur_max != 0)
                                <option value="{{ $pop->id }}">{{ $pop->$libelle }}</option>
                            @endif
                        @endforeach

                    </select>
                </div>
                <div class="form-group" id="pop1">
                    <label for="libelle">{{ trans('text.group_pop') }} </label>
                    <select class="selectpicker form-control"  name="filtre_pop1" id="filtre_pop1"  onchange="filter(niveau_geo.value,ref.value,legend.value,filtre_pop.value,filtre_notp.value,filtre_cond.value,this.value,filtre_pop2.value)">
                        <option value="0">{{ trans('text.all') }}</option>
                        @foreach($filter_pop_w as $pop)
                            @if($pop->valeur_min !=0 || $pop->valeur_max != 0)
                                <option value="{{ $pop->id }}">{{ $pop->$libelle }}</option>
                            @endif
                        @endforeach


                    </select>
                </div>
                <div class="form-group" id="pop2">
                    <label for="libelle">{{ trans('text.group_pop') }} </label>
                    <select class="selectpicker form-control"  name="filtre_pop2" id="filtre_pop2"  onchange="filter(niveau_geo.value,ref.value,legend.value,filtre_pop.value,filtre_notp.value,filtre_cond.value,filtre_pop1.value,this.value)">
                        <option value="0">{{ trans('text.all') }}</option>
                        @foreach($filter_pop_m as $pop)
                            @if($pop->valeur_min !=0 || $pop->valeur_max != 0)
                                <option value="{{ $pop->id }}">{{ $pop->$libelle }}</option>
                            @endif
                        @endforeach


                    </select>
                </div>
                <div class="form-group" id="perf">
                    <label for="libelle"> {{ trans('text.note_performance') }}</label>
                    <select class="selectpicker form-control"  name="filtre_notp"  id="filtre_notp" onchange="filter(niveau_geo.value,ref.value,legend.value,filtre_pop.value,this.value,filtre_cond.value,filtre_pop1.value,filtre_pop2.value)">
                        <option value="0">{{ trans('text.all') }}</option>
                        @foreach($filter_note as $n)
                            @if($n->valeur_min !=0 || $n->valeur_max != 0)
                                <option value="{{ $n->id }}">{{ $n->$libelle }}</option>
                            @endif
                        @endforeach

                    </select>
                </div>
                <div class="form-group" id="cond">
                    <label for="libelle">{{ trans('text.cond_min_remplie') }} </label>
                    <select class="selectpicker form-control"  name="filtre_cond" id="filtre_cond" onchange="filter(niveau_geo.value,ref.value,legend.value,filtre_pop.value,filtre_notp.value,this.value,filtre_pop1.value,filtre_pop2.value)">
                        <option value="0">{{ trans('text.all') }}</option>
                        @foreach($filter_cond as $n)
                            @if($n->valeur_min !=0 || $n->valeur_max != 0)
                                <option value="{{ $n->id }}">{{ $n->$libelle }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>
        </form>
    </div>
    <div id="loading" class="loading"></div>
    <div id="res" class="col-lg-10 col-md-9 col-sm-12 nopadding">

    </div>




    <!-- Resultat de popup -->


    <div id="basicModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">

                <div id="resutClick"></div>

            </div>
        </div>


    </div>


    <div style="width: 100%">
        <div id="basicModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div id="resutClick2"></div>

                </div>
            </div>

        </div>
        <!-- Resultat statistique-->
        <div id="basicModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div id="resutClick3"></div>

                </div>
            </div>

        </div>
        <!-- carte commune-->
        <div id="basicModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div id="resutClick4"></div>

                </div>
            </div>

        </div>

        <!-- Aide fiche communale  -->

        <!-- Modal -->
        <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="helpModalLabel"><i class="fa fa-question-circle"></i> Aide</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading1">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button"  class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                            Les mesures de performance (MDP)
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
                                    <div class="panel-body">
                                        <img src="{{ url('img/help1.JPG') }}" class="img-responsive">
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading2">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button"  class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                            Les conditions minimales
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                                    <div class="panel-body">
                                        <img src="{{ url('img/help2.JPG') }}" class="img-responsive">
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading3">
                                    <h4 class="panel-title" style="text-align: left;">
                                        <a role="button"  class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                            Les indicateurs de performances
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
                                    <div class="panel-body">
                                        <img src="{{ url('img/help3.JPG') }}" class="img-responsive">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('text.fermer') }}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>


        function loading_show()
        {
            $('#loading').html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p>').fadeIn('fast');
        }
        function loading_hide()
        {
            $('#loading').fadeOut('fast');
        }

        function filter(niveau_geo,ref,legend,filtre_pop,filtre_notp,filtre_cond,filtre_pop1,filtre_pop2) {
            loading_show();
            var date_ref=ref+'-12-31';
            custom_accueil(niveau_geo);
            $('#resutClick2').empty();
            $.ajax({
                type: 'get',
                url: racine+'filterNiveau/'+niveau_geo+','+date_ref+','+legend+','+filtre_pop+','+filtre_notp+','+filtre_cond+','+filtre_pop1+','+filtre_pop2,
                cache: false,
                success: function(data)
                {
                    $('#res').empty();
                    loading_hide();
                    $('#res').html(data);


                },
                error: function () {
                    loading_hide();
                    //$meg="Un problème est survenu. veuillez réessayer plus tard";
                    $.alert("{{ trans('message_erreur.request_error') }}");
                }
            });
            return false;
        }

        function custom_accueil(niveau_geo)
        {
            switch (niveau_geo) {
                case '1':
                    $("#perf").show();
                    $("#cond").show();
                    $("#leg").show();
                    $("#pop").show();
                    $("#pop1").hide();
                    $("#pop2").hide();
                    break;
                case '2':
                    $("#perf").hide();
                    $("#cond").hide();
                    $("#leg").hide();
                    $("#pop").hide();
                    $("#pop1").hide();
                    $("#pop2").show();
                    break;
                case '3':
                    $("#perf").hide();
                    $("#cond").hide();
                    $("#leg").hide();
                    $("#pop").hide();
                    $("#pop1").show();
                    $("#pop2").hide();
                    break;
                default:
                    $("#perf").show();
                    $("#cond").show();
                    $("#leg").show();
                    $("#pop").show();
                    $("#pop1").hide();
                    $("#pop2").hide();
                    break;
            }
        }
        $("a.trouvecom").on('click',function() {
            if($(this).find('.fa-plus').length)
            {

                $.ajax({
                    type: 'get',
                    url: racine+'list_wilayas',
                    cache: false,
                    success: function(data)
                    {
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

        $("#wilaya_t").on('change',function(){
            id=$("#wilaya_t").val();

            if(id != 0)
            {
                $("#block_moug").show();
                $("#block_com").hide();
                $.ajax({
                    type: 'get',
                    url: racine+'liste_moughataas/'+id,
                    cache: false,
                    success: function(data)
                    {

                        $('#moughataa_t').html(data.moughataas);
                    },
                    error: function () {
                        //loading_hide();
                        //$meg="Un problème est survenu. veuillez réessayer plus tard";
                        //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                    }
                });
            }
            else
            {
                $("#block_moug").hide();
                $("#block_com").hide();
            }

        })
        $("#moughataa_t").on('change',function(){
            id=$("#moughataa_t").val();

            if(id != 0)
            {
                $("#block_com").show();
                $.ajax({
                    type: 'get',
                    url: racine+'liste_communes/'+id,
                    cache: false,
                    success: function(data)
                    {

                        $('#commune_t').html(data.communes);
                    },
                    error: function () {
                        //loading_hide();
                        //$meg="Un problème est survenu. veuillez réessayer plus tard";
                        //$.alert("Un problème est survenu. veuillez réessayer plus tard");
                    }
                });
            }
            else
            {
                $("#block_com").hide();
            }

        })
        $("#commune_t").on('change',function(){
            id=$("#commune_t").val();
            ref =$("#ref").val();
            if(id != 0)
            {
                $('#basicModal').modal("show");

                $("#resutClick").html('<div id="loading1" class="loading1" ><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i><p>{{ trans("message_erreur.chargement") }}</p></div>').fadeIn('fast');

                $.ajax({
                    type: 'GET',
                    url: racine+'detaitInfoCommune/' + id+','+ref,
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


        })
    </script>

    </div>






@endsection
