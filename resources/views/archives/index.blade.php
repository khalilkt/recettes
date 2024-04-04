@extends('layout')
@section('page-title')
    {{ trans('text_archive.archives') }}
@endsection
@section('top-page-btn')
    <div class="text-right">
        @if(Auth::user()->hasAccess([7],2))
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="openFormAddInModal('archives')"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_archive.add_archive")}}</a>
        @endif
        <a href="{{url('courriers')}}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_archive.courriers")}} </a>
        
    </div>
@endsection
@php
    $lib=trans('text_me.lib');
@endphp
@section('page-content')
    <div class="row">
        <div class="col-lg-12">
            @if (session('successmsg') || session('errormsg'))
                <div class="alert alert-{{(session('successmsg'))?'success':'danger'}} alert-dismissible">
                    {{ session('successmsg') }}{{ session('errormsg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            <div class="card shadow">
                    <div class="card-header">
                    <form role="form" id="formst" name="formst" class="" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            
                            <div class="col-sm-6 col-md-4 filters-item">
                                <div class="filters-label">
                                    <i class="fa fa-filter"></i></i> {{ trans('text_archive.service') }}
                                </div>
                                <div class="filters-input">
                                    <select class="form-control selectpicker "  name="structure" id="structure" data-live-search="true" onchange="filterArchives(this)">
                                         <option value="all">{{ trans('text_hb.all') }}</option>
                                         @foreach($structures as $structure)
                                             <option value="{{ $structure->id }}">{{ $structure->libelle }}</option>
                                         @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 filters-item">
                                <div class="filters-label">
                                    <i class="fa fa-filter"></i> {{ trans('text_archive.date_du') }}
                                </div>
                                <div class="filters-input">
                                    <input class="form-control" type="date" id="date_debut" onchange="filterArchives(this)" value="{{ $date_min }}" name="date_debut">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 filters-item">
                                <div class="filters-label">
                                    <i class="fa fa-filter"></i> {{ trans('text_archive.au') }}
                                </div>
                                <div class="filters-input">
                                    <input class="form-control" type="date" id="date_fin" onchange="filterArchives(this)" value="{{date('Y-m-d')}}" name="date_fin">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if(Auth::user()->hasAccess([7],2))
                                <div class="col-sm-6 col-md-4 filters-item">
                                    <div class="filters-label">
                                        <i class="fa fa-filter"></i> {{ trans('text_archive.etat_archive') }}
                                    </div>
                                    <div class="filters-input">
                                        <select class="form-control selectpicker " name="etat" id="etat" data-live-search="true" onchange="filterArchives(this)">
                                            <option value="1">{{ trans('text_archive.disponible') }}</option>
                                            <option value="2">{{ trans('text_archive.non_disponible') }}</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-6 col-md-4 filters-item">
                                <div class="filters-label">
                                    <i class="fa fa-filter"></i> {{ trans('text_archive.categorie_archive') }}
                                </div>
                                <div class="filters-input">
                                    <select class="form-control selectpicker" onchange="changeType_Ar()" name="type" id="type" data-live-search="true">
                                        <option value="all">{{ trans('text_hb.all') }}</option>
                                        @foreach($type_archives as $type_archive)
                                            <option
                                                value="{{ $type_archive->id }}">{{ $type_archive->$lib }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 filters-item" id="type_arch" style="display:none">
                                <div class="filters-label">
                                    <i class="fa fa-filter"></i> {{ trans('text_archive.type_archive') }}
                                </div>
                                <div class="filters-input">
                                    <select class="form-control selectpicker " name="type_ar" id="type_ar"  onchange="filterArchives(this)">
                                        <option value="all">{{ trans('text_hb.all') }}</option>
                                        <option value="1" >{{ trans('text_archive.actif') }}</option>
                                        <option value="2" >{{ trans('text_archive.demi_actif') }}</option>
                                        <option value="3" >{{ trans('text_archive.definitif') }}</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="btn-group float-right mt-1">
                            <button type="button" onclick="getObjectPDF('archives/export_pdf')" class="mr-1 btn btn-sm btn-info float-right"> <i class="fas fa-file-pdf"></i> {{ trans('text_archive.export_pdf') }} </button>
                            <button type="button" onclick="getObjectPDF('archives/export_excel')" class="btn btn-sm btn-primary float-right mr-1"> <i class="fas fa-file-excel"></i> {{ trans('text_archive.export_excel') }} </button>
                        </div>
                    </form>
                    </div>
        <div class="card-body">
            <div class="clearfix"></div>
            <hr>
            <div id="resultat_msg"></div>
            <div class="table-responsive">
                <table id="datatableshow" selected="" link="{{url("archives/getDT/all/all/$date_min/$date_max/1/all")}}"
                       index="5,-1" hiddens='6' colonnes="id,libelle,date_archivage,service.{{$lib}},type_archive.{{$lib}},emplacement,mots_cles,actions"
                       class="table table-bordered">
                    <thead>
                    <tr>
                        <th width="30px"></th>
                        <th>{{ trans('text.libelle') }}</th>
                        <th>{{ trans('text.date') }}</th>
                        <th>{{ trans('text_archive.service') }}</th>
                        <th>{{ trans('text_archive.type_archive') }}</th>
                        <th>{{ trans('text_archive.emplacement') }}</th>
                        <th>Mots cles</th>
                        <th width="80px">{{ trans('text.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
        </div>
    </div>
@endsection
