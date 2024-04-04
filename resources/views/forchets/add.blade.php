@php
    $lib=trans('text_me.lib');
@endphp
<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.new_forchet') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="row">
          <div class="col-md-12" id="addForm">
              <form class="" action="{{ url('forchets/add') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label for="categorie">{{ trans('text_me.categorie') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select class="form-control" name="categorie" id="categorie" onchange="getMotantCategorie()">
                                <option></option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->$lib }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="emplacement">{{ trans('text_me.emplacement') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select class="form-control" name="emplacement" id="emplacement">
                                <option></option>
                                @foreach($emplacements as $emplacement)
                                    <option value="{{ $emplacement->id }}">{{ $emplacement->$lib }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="taille">{{ trans('text_me.taille') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select class="form-control" name="taille" id="taille">
                                <option></option>
                                @foreach($tailles as $taille)
                                    <option value="{{ $taille->id }}">{{ $taille->$lib }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="montant">{{ trans('text_me.montant') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input class="form-control" name="montant" id="montant" >
                        </div>
                        <div class="col-md-12">
                            <div class="text-right">
                                <button class="btn btn-success btn-icon-split" onclick="addObject(this,'forchets')" container="addForm">
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
