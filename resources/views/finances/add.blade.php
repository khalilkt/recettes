
@php
    $div_bgd_ini='display : none;';
    $div_plus_bgds='display : none;';
@endphp
@if($maxOrdre == 1)
    @php
        $div_bgd_ini='display : block;';
    @endphp
@else
    @php
        $div_plus_bgds='display : block;';
    @endphp
@endif
<div class="card" style="{{ $div_bgd_ini }}">
    <div class="card-header">{{ trans('text_me.new_budget_initial') }}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button></div>
  <div class="card-body">
      <div class="row">
          <div class="col-md-12" id="addForm1">
              <form class="" action="{{ url('finances/add') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-4 form-group">
                            <label for="annee">{{ trans('text_me.exercice') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select class="selectpicker form-control" name="annee" >
                                <option value="{{$annee}}">{{$annee}}</option>

                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="libelle">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input name="libelle" type="text" value="Budget Initial {{$annee}}" class="form-control" />
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="libelleAr">{{ trans('text_me.libelleAr') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input name="libelle_ar" type="text" value="{{$annee}} الميزانية الأصلية" class="form-control" />
                        </div>
                        <div class="col-md-12 form-group" @if($budget==null) style="display: none;" @endif>
                            <div class="form-check form-check-inline" >
                                <input type="checkbox" class="form-check-input" id="identique" name="identique">
                                <label class="form-check-label" for="identique">{{ trans('text_me.identique') }}? </label>
                            </div>
                        </div>
                        <input type="submit">
                        <input name="nomenclature_id" type="hidden" value="{{$nomenclature->id}}">
                        <input name="commune_id" type="hidden" value="{{$commune->id}}">
                            <input name="ordre" type="hidden"  class="form-control" value="{{$maxOrdre}}" />
                            <input name="type_budget_id" type="hidden" value="1">
                            <input name="etat" value="0" type="hidden">
                            <input name="redirection" value="index2" type="hidden">
                        <div class="col-md-12 form-row">
                            <div class="col-md-8 form-group text-left">
                                (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                                : {{ trans('text_me.msg_asterique') }}
                            </div>
                            <div class="col-md-4 form-group text-right">
                                <button class="btn btn-success btn-icon-split" onclick="saveBidgetInitial(this)" container="addForm1">
                                    <span class="icon text-white-50">
                                        <i class="main-icon fas fa-save"></i>
                                        <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                        <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                    </span>
                                    <span class="text">{{ trans('text.ajouter') }}</span>
                                </button>
                            </div>
                            <div id="form-errors" class="col-md-12 text-left"></div>
                        </div>
                    </div>
              </form>
          </div>
      </div>
  </div>
</div>

<div class="card" style="{{ $div_plus_bgds }}">
    <div class="card-header">{{ trans('text_me.new_budget_complementaire') }}
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12" id="addForm">
                <form class="" action="{{ url('finances/add') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-4 form-group">
                            <label for="annee">{{ trans('text_me.exercice') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select class="selectpicker form-control" name="annee" >
                                    <option value="{{$annee}}">{{$annee}}</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="libelle">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input name="libelle" type="text" value="Budget complementaire ({{ $maxOrdre-1 }}) {{$annee}}" class="form-control" />
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="libelleAr">{{ trans('text_me.libelleAr') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input name="libelle_ar" type="text" value="{{$annee}} ({{ $maxOrdre-1 }}) الميزانية التكميلية" class="form-control" />
                        </div>
                        <input name="nomenclature_id" type="hidden" value="{{$nomenclature->id}}">
                        <input name="commune_id" type="hidden" value="{{$commune->id}}">
                        <input name="ordre" type="hidden"  class="form-control" value="{{$maxOrdre}}" />
                        <input name="type_budget_id" type="hidden" value="2">
                        <input name="etat" value="0" type="hidden">
                        <div class="col-md-12 form-row">
                            <div class="col-md-8 form-group text-left">
                                (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                                : {{ trans('text_me.msg_asterique') }}
                            </div>
                            <div class="col-md-4 form-group text-right">
                                <button class="btn btn-success btn-icon-split" onclick="saveBidgetInitial(this)" container="addForm">
                                    <span class="icon text-white-50">
                                        <i class="main-icon fas fa-save"></i>
                                        <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                        <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                    </span>
                                    <span class="text">{{ trans('text.ajouter') }}</span>
                                </button>
                            </div>
                            <div id="form-errors" class="col-md-12 text-left"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
