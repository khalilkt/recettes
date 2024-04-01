@extends('layout')
@section('page-title')


@endsection
@section('top-page-btn')
    <div class="card shadow">
        <div class="card-header">
            <div class="row col-md-12">
                <div class="col-md-2 filters-item">

                        {{ trans('text_me.contribuables') }}
                        <select class="selectpicker mr-sm-2" onchange="changerAnnee()" id="annee">
                            <option value="{{$annee->id}}" selected>{{$annee->annee}}</option>
                            @foreach($annees as $an)
                                @if($an->annee != $annee->annee)
                                    <option value="{{$an->id}}">{{$an->annee}}</option>
                                @endif
                            @endforeach
                        </select>

                </div>
    @if(Auth::user()->hasAccess(9,4))
        <div class="col-md-2 filters-item">
            <div class="filters-label">
                <i class="fa fa-filter"></i> {{ trans('text_me.type_payement') }}
            </div>
            <div class="filters-input">
                <select id="type_payement"  name="type_payement" data-live-search="true" class="selectpicker form-control" onchange="filterTypePayement(this)"  >
                    <option value="all" >{{ trans('text_me.tous') }}</option>
                    <option value="f" >contribuable fermé</option>
                    <option value="1" >{{ trans('text_me.Spontanee') }}</option>
                    <option value="0" >{{ trans('text_me.Sans_delclaration') }}</option>
                    @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->libelle}}</option>
                        @endforeach
                </select>
            </div>
        </div>
    @endif
    @if(Auth::user()->hasAccess(9,4))
       <div class="col-md-4 filters-item">

        <form role="form" id="formstpdf" name="formstpdf" class=""  method="post" enctype="multipart/form-data" action="{{ url('contribuables/openficherexcel') }}">
            {{ csrf_field() }}
            <label for="fichier" class="col-md-12 group">Fichier <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
            <div class="col-md-12 row">
           <div class="col-md-6 group">
            <input type="file" id="fichier" name="fichier" class="form-control" disabled=true />
           </div>

            <div class="col-md-6 group">
                <input type="checkbox" id="rolecf" name="rolecf" class="" onclick="desactivepa()" >Role CF <br>
                <input type="checkbox" id="rolePATENTE" name="rolePATENTE" class="" onclick="desactivecf()">Role patente
            </div>
            </div>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm text-right" onclick="importerEXCELEMP()"><i class="fas fa-fw fa-file-excel text-white-50"></i> {{trans("text_me.importerFichier")}}</a>
        </form>

       </div>
    @endif
   @if(Auth::user()->hasAccess(9,4))
            <div class="col-md-2 filters-item">
                <a href="{{ url('programmes') }}"  target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm text-right" {{--onclick="suiviprogrammes()"--}}><i class="fas fa-fw fa-clipboard-list text-white-50"></i> {{trans("text_me.suiviprogrammes")}}</a>
                <br><br>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm text-right" onclick="suiviContibuable({{$annee->annee}})"><i class="fas fa-fw fa-clipboard-list text-white-50"></i> {{trans("text_me.suiviContribuable")}}</a>
            </div>
    @endif
    @if(Auth::user()->hasAccess(9,2))
            <div class="col-md-1 filters-item">
                 <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm text-right" onclick="openFormAddInModal('contribuables')"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.add_contribuable")}}</a>
            </div>
    @endif
                @if(Auth::user()->hasAccess(9,2))
                    <div class="col-md-1 filters-item">
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm text-right" onclick="payercontibiable({{$annee->annee}})"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.add_payement")}}</a>
                    </div>
                @endif
            </div>

@endsection
@section('page-content')
    <div class="row">
        @php
           // $lib=trans('text_me.lib');--}}
        @endphp
        @if($nbrproEchen>0)
            <div class="alert alert-warning alert-dismissible fade show col-md-12" role="alert">
                <h5>{{ trans('text_me.protocol_non_payes') }} sont : {{$nbrproEchen}} </h5>
                <form id="form1l" name="form1l" method="get">
                <a href="#"   class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm text-white" onclick="exporterListeprotocolEch()"><i class="fas fa-map-marker-alt fa-sm text-white"></i> {{trans("text_me.voir_la_liste")}}</a>
                </form>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="col-lg-12">
            @if (session('successmsg') || session('errormsg'))
                <div class="alert alert-{{(session('successmsg'))?'success':'danger'}} alert-dismissible">
                    {{ session('successmsg') }}{{ session('errormsg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <form role="form"  id="formspdf" name="formspdf" class=""  method="get" >
                            <table id="datatableshow" selected="" link="{{url("contribuables/getDT/all")}}" colonnes="id,libelle,adresse,nbreRole,nbrearticle,article,Roles,montant,actions" class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th>{{ trans('text_me.nom') }}</th>
                                    <th>{{ trans('text_me.adresse') }}</th>
                                    <th>{{ trans('text_me.nbreRole') }}</th>
                                    <th>{{ trans('text_me.nbrearticle') }}</th>
                                    <th>{{ trans('text_me.article') }}</th>
                                    <th>{{ trans('text_me.Roles') }}</th>

                                    <th>{{ trans('text_me.montanttoto') }}</th>
                                    <th width="80px">{{ trans('text.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
