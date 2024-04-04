<div class="row">
    <div class="col-md-12">
        <form class="" action="{{ url('modeles/edit') }}" method="post">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="titre">{{ trans('text_me.titre') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="titre" name="titre" value="{{$acte->titre}}" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                    <label for="titre_ar">{{ trans('text_me.titre_ar') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="titre_ar" name="titre_ar" value="{{$acte->titre_ar}}" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                    <label for="libelle">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle" name="libelle" value="{{$acte->libelle}}" class="form-control">
                </div>
                <div class="col-md-6 form-group">
                    <label for="libelle_ar">{{ trans('text_me.libelle_ar') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle_ar" name="libelle_ar" value="{{$acte->libelle_ar}}" class="form-control">
                </div>
                <input type="hidden" value="{{ $acte->id }}" name="id">
                <div class="col-md-12 form-row">
                    <div class="col-md-8 form-group text-left">
                        (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                        : {{ trans('text_me.msg_asterique') }}
                    </div>
                    <div class="col-md-4 form-group text-right">
                    <button class="btn btn-success btn-icon-split" onclick="saveform(this)" container="tab1">
                        <span class="icon text-white-50">
                            <i class="main-icon fas fa-save"></i>
                            <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                            <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                        </span>
                        <span class="text">{{ trans('text.enregistrer') }}</span>
                    </button>
                </div>
                <div id="form-errors" class="col-md-12 text-left"></div>
            </div>
            </div>
        </form>
    </div>
</div>
