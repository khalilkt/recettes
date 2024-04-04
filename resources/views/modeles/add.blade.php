<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.new_modele_acte') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="row">
          <div class="col-md-12" id="addForm">
              <form class="" action="{{ url('modeles/add') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label for="titre">{{ trans('text_me.titre') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="titre" name="titre" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="titre_ar">{{ trans('text_me.titre_ar') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="titre_ar" name="titre_ar" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="libelle">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="libelle" name="libelle" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="libelle_ar">{{ trans('text_me.libelle_ar') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="libelle_ar" name="libelle_ar" class="form-control">
                        </div>

                        <div class="col-md-12 form-row">
                            <div class="col-md-8 form-group text-left">
                                (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                                : {{ trans('text_me.msg_asterique') }}
                            </div>
                            <div class="col-md-4 form-group text-right">
                                <button class="btn btn-success btn-icon-split" onclick="addObject(this,'modeles')" container="addForm">
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
