@extends('layout')
@section('page-title')


@endsection
@section('top-page-btn')
    <div class="card shadow col-12">
        <div class="card-header">
            <div class="d-flex flex-row justify-content-between mb-4 ">
                <div class="d-flex flex-column flex-lg-row filters-item ">
                <div class="">
                    <div class="mb-1">
                        {{ trans('text_me.contribuables') }}
                </div>
                        <select  class="selectpicker mr-sm-2" onchange="changerAnnee()" id="annee">
                            <option value="{{$annee->id}}" selected>{{$annee->annee}}</option>
                            @foreach($annees as $an)
                                @if($an->annee != $annee->annee)
                                    <option value="{{$an->id}}">{{$an->annee}}</option>
                                @endif
                            @endforeach
                        </select>
                 </div>
    </div>
    

@endsection
@section('page-content')
    <div class="row">
        @php
           // $lib=trans('text_me.lib');--}}
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
                        <form role="form"  id="formspdf" name="formspdf" class=""  method="get" >
                            <table id="datatableshow" selected="" link="{{url("contribuables/getManageDT/")}}" colonnes="id,libelle,adresse,actions" class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th>{{ trans('text_me.nom') }}</th>
                                    <th>{{ trans('text_me.adresse') }}</th>
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
