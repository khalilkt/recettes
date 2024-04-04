<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.new_contribuale') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@php
    $lib=trans('text_me.lib');
@endphp
  <div class="modal-body">
      <div class="row">
          <div class="col-md-12" id="addForm">
              <form class="" action="{{ url('contribuables/add') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-12 form-group">
                            <label for="libelle">{{ trans('text_me.nom') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="libelle" name="libelle" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="representant">{{ trans('text_me.representant') }} </label>
                            <input id="representant" name="representant" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="adresse">{{ trans('text_me.adresse') }} </label><span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="adresse" name="adresse" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="telephone">{{ trans('text_me.telephone') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="telephone" name="telephone" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="date">{{ trans('text_me.date_mas') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="date_mas" name="date_mas" class="form-control" type="date">
                        </div>
                        {{-- <div class="col-md-4 form-group">
                            <label for="date">{{ trans('text_me.article') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                           --}} <input id="article" name="article" class="form-control" type="hidden" value="{{$maxOrdre}}" >
                        {{--</div>
                        <div class="col-md-4 form-group">
                            <label for="activite_id">{{ trans('text_me.activite') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select class="form-control" name="activite_id" id="activite_id" onchange="montantActivite()">
                                <option></option>
                                @foreach($activites as $activite)
                                    <option value="{{ $activite->id }}">{{ $activite->$lib }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="emplacement">{{ trans('text_me.emplacement') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select class="form-control" name="emplacement" id="emplacement" onchange="montantActivite()">
                                <option></option>
                                @foreach($emplacements as $emplacement)
                                    <option value="{{ $emplacement->id }}">{{ $emplacement->$lib }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="taille">{{ trans('text_me.taille') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select class="form-control" name="taille" id="taille" onchange="montantActivite()">
                                <option></option>
                                @foreach($tailles as $taille)
                                    <option value="{{ $taille->id }}">{{ $taille->$lib }}</option>
                                @endforeach
                            </select>
                        </div>--}}
                        <div class="col-md-6 form-group">
                            <label for="montant">{{ trans('text_me.montant') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input class="form-control" name="montant" id="montant" {{--data-inputmask="'alias': 'numeric', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 0, 'digitsOptional': true, 'prefix': '', 'placeholder': '0'"--}}>
                        </div>
                        {{--<input type="submit">--}}
                        <div class="col-md-12">
                            <div class="text-right">
                                <button class="btn btn-success btn-icon-split" onclick="addObject(this,'contribuables')" container="addForm">
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
