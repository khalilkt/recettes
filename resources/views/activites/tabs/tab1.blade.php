<div class="row">
    <div class="col-md-12">
        @php
            $lib=trans('text_me.lib');
        @endphp
        <form class="" action="{{ url('activites/edit') }}" method="post">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="libelle">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle" name="libelle" class="form-control" value="{{$activite->$lib}}">
                </div>
                <div class="col-md-12 form-group">
                    <label for="libelle_ar">{{ trans('text_me.libelle_ar') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle_ar" name="libelle_ar" class="form-control" value="{{$activite->libelle_ar}}">
                </div>
                <div class="col-md-12 form-group">
                    <label for="categorie">{{ trans('text_me.categorie') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select class="form-control" name="categorie" id="categorie">
                        @foreach($categories as $categorie)
                            @if($categorie->id == $activite->ref_categorie_activite_id)
                                <option value="{{ $categorie->id }}" selected>{{ $categorie->$lib }}</option>
                            @else
                                <option value="{{ $categorie->id }}">{{ $categorie->$lib }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <input type="hidden" value="{{ $activite->id }}" name="id">
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
