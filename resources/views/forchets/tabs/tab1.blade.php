<div class="row">
    @php
        $lib=trans('text_me.lib');
    @endphp
    <div class="col-md-12">
        <form class="" action="{{ url('forchets/edit') }}" method="post">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="categorie">{{ trans('text_me.categorie') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select class="form-control" name="categorie" id="categorieedit">
                        @foreach($categories as $categorie)
                            @if($forchet->ref_categorie_activite_id ==$categorie->id )
                                <option value="{{ $categorie->id }}" selected>{{ $categorie->$lib }}</option>
                            @else
                                <option value="{{ $categorie->id }}" >{{ $categorie->$lib }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="emplacement">{{ trans('text_me.emplacement') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select class="form-control" name="emplacement" id="emplacement">
                       @foreach($emplacements as $emplacement)
                            @if($emplacement->id == $forchet->ref_emplacement_activite_id)
                                <option value="{{ $emplacement->id }}" selected>{{ $emplacement->$lib }}</option>
                            @else
                                <option value="{{ $emplacement->id }}">{{ $emplacement->$lib }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="taille">{{ trans('text_me.taille') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select class="form-control" name="taille" id="taille">
                        @foreach($tailles as $taille)
                            @if($taille->id == $forchet->ref_taille_activite_id)
                                <option value="{{ $taille->id }}" selected>{{ $taille->$lib }}</option>
                            @else
                                <option value="{{ $taille->id }}">{{ $taille->$lib }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="montant">{{ trans('text_me.montant') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input class="form-control" name="montant" id="montantedit" value="{{$forchet->montant}}">
                </div>

                <input type="hidden"name="id" value="{{$forchet->id}}">
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
