<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.new_activite') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="row">
          <div class="col-md-12" id="addForm">
              <form class="" action="{{ url('activites/add') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-12 form-group">
                            <label for="libelle">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="libelle" name="libelle" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="libelle_ar">{{ trans('text_me.libelle_ar') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="libelle_ar" name="libelle_ar" class="form-control">
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="categorie">{{ trans('text_me.categorie') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                           <select class="form-control" name="categorie" id="categorie">
                               <option></option>
                                @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->libelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="text-right">
                                <button class="btn btn-success btn-icon-split" onclick="addObject(this,'activites')" container="addForm">
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
