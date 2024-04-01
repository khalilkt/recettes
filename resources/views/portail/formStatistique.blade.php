@extends('../layout')
@section('page-title')
    Accueil
@endsection
@section('page-content')

    @php
        $d_module = Crypt::encrypt($module);
    @endphp
    @include('portail.cummun.menu_top2')
    <script>
        $(document).ready(function () {
            var id_cat_st = "{{ $id_cat_st }}";
            //niveau commune

            default_niveau(id_cat_st);
        });

    </script>
<div class="form-row">
    <div class="col-lg-2 col-md-3 left-side form-group">
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
    <div id="res" class="col-lg-10 col-md-9  form-group nopadding">
        <br>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header" style="background-color:#F3F3F3 ">
                    {{ trans('text.statistique') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form role="form" id="formst" class="" action="{{ url('get_statistique') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="module" value="{{ $module}}"/>
                                <div class="form-group">
                                    <label>* {{ trans('text.annee_ref') }} </label>

                                <!--<input type="date" class="form-control" value="<?php echo date('Y-m-d');?>" name="date_ref" id="date_ref">-->
                                    <select class="form-control select2" name="date_ref" id="date_ref">
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
                                <div class="form-group">
                                    <label> {{ trans('text.niveau_s') }} </label>
                                    <select class="form-control" id="niveau_geo" onchange="get_niveau_gea()"
                                            name="niveau_geo">
                                        <option value="4">{{ trans('text.national') }}</option>
                                        <option value="3">{{ trans('text.wilaya') }}</option>
                                        <option value="2">{{ trans('text.moughataa') }}</option>
                                        <option value="1">{{ trans('text.commune') }}</option>
                                        @if(env('DCS_APP')=='1')
                                            <option value="6">{{ trans('text.obj_geo') }}</option>
                                        @endif
                                        @if(env('DCS_APP')=='0')
                                            <option value="5">{{ trans('text.categorie_p') }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group" id="type_objets" style="display: none">
                                    <label>{{ trans('text.type_obj') }}</label>
                                    <select class="form-control" id="type_objet" name="type_objet">

                                    </select>

                                </div>

                            <!--   <div class="form-group" style="display:none">
                                    <label>{{ trans('text.type_source') }}</label>
                                    <select class="form-control" name="type_source" id="type_source">
                                        <option value="0">{{ trans('text.base') }}</option>
                                        <option value="1">{{ trans('text.libre') }}</option>
                                    </select>
                                </div>-->

                                <div class="form-group">
                                    <label>{{ trans('text.categorie') }}</label>
                                    <select class="form-control" id="categorie_donnee" onchange="get_categorie_donnee()" name="categorie_donnee" >

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>* {{ trans('text.type_st') }}</label>
                                    <select class="form-control" name="grp_donnee" id="grp_donnee" onchange="get_grp_donnee()">

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('text.type_visaluation') }} </label>
                                    <select class="form-control" name="type_pr" id="type_pr">

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('text.chiffre') }}</label>
                                    <select class="form-control" name="chiff" id="chiff">
                                        <option value="true">{{ trans('text.oui') }}</option>
                                        <option value="false">{{ trans('text.non') }}</option>
                                    </select>
                                </div>

                                <div id="commune">
                                    <div class="form-group" id="wil">
                                        <label>{{ trans('text.Wilayas') }}</label>
                                        <select class="form-control" id="wilayas" name="wilayas" onchange="get_wilayas()">

                                        </select>

                                    </div>
                                    <div class="form-group" id="mg">
                                        <label>{{ trans('text.Moughataas') }}</label>
                                        <select class="form-control" id="moughataas" name="moughataas" onchange="get_moughataas()">

                                        </select>

                                    </div>
                                    <div class="form-group" id="com">

                                    </div>

                                </div>

                                <div id="form-errors-st" style="display: none"></div>

                                <!--<input type="submit" value="Valider"/> -->

                            </form>
                            <button type="button" onclick="get_statistique()"
                                    class="btn btn-success pull-right addfiche">{{ trans("text.generer") }}</button>

                        </div>
                        <!-- /.col-lg-6 (nested) -->

                        <!-- /.row (nested) -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        </div>


        <!-- Resultat de popup -->

        <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
             aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div id="resutClick" style="direction: ltr!important;"></div>

                </div>
            </div>


        </div>


        <script>

        </script>

    </div>
</div>
@endsection
