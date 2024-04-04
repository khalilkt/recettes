@extends('layout')
@section('page-title')
    Saisir budget initial
@endsection

@section('module-css')

@endsection
@section('module-js')

@endsection
@section('page-content')

    <div class="row">
        <div class="col-lg-12">
            @if (session('successmsg') || session('errormsg'))
                <div class="alert alert-{{(session('successmsg'))?'success':'danger'}} alert-dismissible">
                    {{ session('successmsg') }}{{ session('errormsg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            <div class="card">
                <div class="card-header">{{ trans('text_me.new_budget') }}</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" id="addForm">
                            <form class="" action="{{ url('finances/add') }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-row">
                                    <div class="col-md-4 form-group">
                                        <label for="commune">{{ trans('text_me.commune') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                        <select class="selectpicker form-control" name="" disabled="true">
                                            <option value="">{{$commune->libelle}}</option>
                                        </select>
                                        <input name="commune_id" type="hidden" value="{{$commune->id}}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="nomenclature">{{ trans('text_me.nomenclature') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                        <select class="selectpicker form-control" name="" disabled="true">
                                            <option value="">{{$nomenclature->libelle}}</option>
                                        </select>
                                        <input name="nomenclature_id" type="hidden" value="{{$nomenclature->id}}">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="annee">{{ trans('text_me.annee') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                        <select class="selectpicker form-control" name="annee" >
                                            @php
                                                $debut=20;
                                                $fin= $debut+5;
                                            @endphp
                                            @for($i=$debut ;$i<$fin ;$i++)
                                                <option value="20{{$i}}">20{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="libelle">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                        <input name="libelle" type="text" value="" class="form-control" />
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="libelleAr">{{ trans('text_me.libelleAr') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                        <input name="libelle_ar" type="text" value="" class="form-control" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="ordre_complementaire">{{ trans('text_me.ordre') }} </label>
                                        <input name="ordre" type="text"  class="form-control" value="1" />
                                    </div>
                                    <div class="col-md-2 form-group">
                                        <label for="type_budget_id">{{ trans('text_me.type_budget_id') }} </label>
                                        <select class="selectpicker form-control" name="type_budget_id" >
                                            @foreach($type_budgets as $type_budget)
                                                @if($type_budget->id == 1)
                                                <option value="{{$type_budget->id}}" selected>{{$type_budget->libelle}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <input name="etat" value="0" type="hidden">
                                    <input name="redirection" value="index2" type="hidden">
                                    <input type="submit">
                                    <div class="col-md-12">
                                        <div class="text-right">
                                            <button class="btn btn-success btn-icon-split" onclick="saveBidgetInitial(this)" container="addForm">
                                    <span class="icon text-white-50">
                                        <i class="main-icon fas fa-save"></i>
                                        <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                        <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                    </span>
                                                <span class="text">{{ trans('text.ajouter') }}</span>
                                            </button>
                                            <div id="form-errors" class="text-left"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
