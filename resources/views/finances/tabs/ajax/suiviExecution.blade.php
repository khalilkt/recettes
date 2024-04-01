@php
    $lib=trans('text_me.lib');
@endphp
<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.suivre') }} {{$nomenclature->$lib}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12" id="addForm">

                <div class="card">
                    <div class="card-header">
                        <div class="col-md-12">
                            <label for="libelle">{{ trans('text_me.compte_impitation') }}  {{$nomenclature->$lib}}</label>
                        </div>
                        <div class="col-md-12">
                            <label for="">{{ trans('text_me.classe') }} : {{$nomenclature->ref_type_nomenclature->$lib}}</label>
                        </div>
                        <div class="col-md-12">
                            <label for="">{{ trans('text_me.code') }} : {{$nomenclature->code}}</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4 form-group">
                                <label for="">{{ trans('text_me.Du') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                <input class="form-control" name="date1" id="date1" type="date"  value="@php echo date('Y-m-d') @endphp">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="">{{ trans('text_me.Au') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                <input class="form-control" name="date2" id="date2" type="date" value="@php echo date('Y-m-d') @endphp">
                            </div>
                            <div class="col-md-4 filters-item">
                                <label for="">{{ trans('text_me.niveau_affichage') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                    <select id="niveau"  name="niveau" data-live-search="true" class="selectpicker form-control"  >
                                        <option value="all">{{ trans('text_me.tous') }}</option>
                                        @if($nomenclature->niveau <=1)
                                            <option value="1">{{ trans('text_me.classes') }}</option>
                                        @endif
                                        @if($nomenclature->niveau <=2)
                                        <option value="2">{{ trans('text_me.chapitres') }}</option>
                                        @endif
                                        @if($nomenclature->niveau <=3)
                                        <option value="3">{{ trans('text_me.articles') }}</option>
                                        @endif
                                        @if($nomenclature->niveau <=4)
                                        <option value="4">{{ trans('text_me.paragraphes') }}</option>
                                        @endif
                                        @if($nomenclature->niveau <=5)
                                        <option value="5">{{ trans('text_me.sous_paragraphes') }}</option>
                                            @endif
                                    </select>

                            </div>
                            <div class="col-md-4 form-group">
                                @if($nomenclature->ref_type_nomenclature_id==1)
                                    <label for="">{{ trans('text_me.origine') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                @endif
                                    @if($nomenclature->ref_type_nomenclature_id==2)
                                    <label for="">{{ trans('text_me.beneficieur') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                @endif
                                <input class="form-control" name="texte" id="texte" type="">
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="detail" name="detail">
                                <label class="form-check-label" for="detail">{{ trans('text_me.details_execution') }} </label>
                            </div>
                            <input  name="id" id="id" type="hidden" value="{{$nomenclature->id}}">
                            <input  name="sence" id="sence" type="hidden" value="{{$nomenclature->ref_type_nomenclature_id}}">
                            <div class="col-md-4 form-group text-right">
                                <label>&nbsp;</label>
                                <form role="form"  id="formst" name="formst" class=""  method="get" >
                                    {{ csrf_field() }}
                                    <button type="button" onclick="exporterSuiviExecutionExcel(this)" class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm  text-white text-right">
                                        <i class="fas fa-file-excel"></i> {{ trans('text_me.exporter') }}
                                    </button>
                                    <button type="button" onclick="exporterSuiviExecution(this)" class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm  text-white text-right">
                                        <i class="fas fa-file-pdf"></i> {{ trans('text_me.exporter') }}
                                    </button>
                                </form>
                            </div>
                            {!! $html!!}
                        </div>
                    </div>
                </div>

        </div>
    </div>
</div>
