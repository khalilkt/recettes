@extends('layout')
@section('page-title')
    {{ trans('text_me.categories') }}
@endsection
@section('top-page-btn')
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="openFormAddInModal('categories')"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.add_categorie")}}</a>
@endsection
@section('page-content')
    <div class="row">
        @php
            $lib=trans('text_me.lib');
        @endphp
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
                        <table id="datatableshow" selected="" link="{{url("categories/getDT")}}" colonnes="id,{{$lib}},libelle_ar,montant,actions" class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th>{{ trans('text.libelle') }}</th>
                                    <th>{{ trans('text.libelle_ar') }}</th>
                                    <th>{{ trans('text_me.montant') }}</th>
                                    <th width="80px">{{ trans('text.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
