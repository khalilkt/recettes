<script>
    $("#main-modal :input").inputmask();
</script>
<div class="row">
    <div class="col-md-12">
        @php
            $lib=trans('text_me.lib');
        @endphp
        <form class="" action="{{ url('categories/edit') }}" method="post">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="libelle">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle" name="libelle" class="form-control" value="{{$categorie->$lib}}">
                </div>
                <div class="col-md-12 form-group">
                    <label for="libelle_ar">{{ trans('text_me.libelle_ar') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle_ar" name="libelle_ar" class="form-control" value="{{$categorie->libelle_ar}}">
                </div>
                <div class="col-md-12 form-group">
                    <label for="categorie">{{ trans('text_me.categorie') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="montant" name="montant" class="form-control" value="{{$categorie->montant}}" data-inputmask="'alias': 'numeric', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">

                </div>
                <input type="hidden" value="{{ $categorie->id }}" name="id">
                <div class="col-md-12 text-right">
                    <button class="btn btn-success btn-icon-split" onclick="saveform(this)" container="tab1">
                        <span class="icon text-white-50">
                            <i class="main-icon fas fa-save"></i>
                            <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                            <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                        </span>
                        <span class="text">{{ trans('text.enregistrer') }}</span>
                    </button>
                    <div id="form-errors" class="text-left"></div>
                </div>
            </div>
        </form>
    </div>
</div>
