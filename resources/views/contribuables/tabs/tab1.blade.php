<div class="row">
    <div class="col-md-12">
        <fieldset @if(!Auth::user()->hasAccess(9,2) )  disabled="true" @endif>
        <form class="" action="{{ url('contribuables/edit') }}" method="post">
            {{ csrf_field() }}
            <div class="form-row">
                @php
                    $lib=trans('text_me.lib');
                @endphp
                @if(Auth::user()->hasAccess(9,4))
                <div class="col-md-12 form-group text-right m-0 p-0 mb-3">
                    <button type="button" class="btn btn-sm btn-warning " onClick="exportcontribuablePDF({{$contribuale->id}})" data-toggle="tooltip" data-placement="top" title="{{trans('text_me.editficheContribuable')}}"><i class="fas fa-fw fa-file-pdf"></i></button>
                </div>
                @endif

               @if($nbrproEchen>0)
                    <div class="alert alert-warning alert-dismissible fade show col-md-12" role="alert">
                        <h4>{{ trans('text_me.protocol_non_payes') }}</h4>
                        {{$nbrproEchen}}

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
               @endif
                    <div class="col-md-12 form-group">
                    <label for="libelle">{{ trans('text_me.nom') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle" name="libelle" class="form-control" value="{{$contribuale->$lib}}">
                </div>

                <div class="col-md-12 form-group">
                    <label for="representant">{{ trans('text_me.representant') }} </label>
                    <input id="representant" name="representant" class="form-control" value="{{$contribuale->representant}}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="adresse">{{ trans('text_me.adresse') }} </label><span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="adresse" name="adresse" class="form-control" value="{{$contribuale->adresse}}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="telephone">{{ trans('text_me.telephone') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="telephone" name="telephone" class="form-control" value="{{$contribuale->telephone}}">
                </div>
                {{--<div class="col-md-4 form-group">
                    <label for="date">{{ trans('text_me.date_mas') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="date_mas" name="date_mas" class="form-control" type="date" value="{{$contribuale->date_mas}}">
                </div>
                 <div class="col-md-4 form-group">
                    <label for="date">{{ trans('text_me.article') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="article" name="article" class="form-control" type="number" value="{{$contribuale->article}}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="">{{ trans('text_me.activite') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select class="form-control" name="activite_id" id="activite_idedit" onchange="montantActiviteedit()">
                        @foreach($activites as $activite)
                            @if($activite->id == $contribuale->activite_id)
                                <option value="{{ $activite->id }}" selected>{{ $activite->$lib }}</option>
                            @else
                                <option value="{{ $activite->id }}">{{ $activite->$lib }}</option>
                            @endif

                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="emplacement">{{ trans('text_me.emplacement') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select class="form-control" name="emplacement" id="emplacementedit" onchange="montantActiviteedit()">
                        <option></option>
                        @foreach($emplacements as $emplacement)
                            @if($emplacement->id == $contribuale->ref_emplacement_activite_id)
                                <option value="{{ $emplacement->id }}" selected>{{ $emplacement->$lib }}</option>
                            @else
                                <option value="{{ $emplacement->id }}">{{ $emplacement->$lib }}</option>
                            @endif

                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="taille">{{ trans('text_me.taille') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select class="form-control" name="taille" id="tailleedit" onchange="montantActiviteedit()">
                        <option></option>
                        @foreach($tailles as $taille)
                            @if($taille->id == $contribuale->ref_taille_activite_id)
                                <option value="{{ $taille->id }}" selected>{{ $taille->$lib }}</option>
                            @else
                                <option value="{{ $taille->id }}">{{ $taille->$lib }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>--}}
                {{--<div class="col-md-4 form-group">
                    <label for="compte_impitqtion">{{ trans('text_me.compte_impitation') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select class="form-control selectpicker " data-live-search="true" name="nomenclature_element_id" id="nomenclature_element_id" >
                        <option></option>
                        @foreach($nomenclatures as $nomenclature)
                            @if($nomenclature->id == $contribuale->nomenclature_element_id)
                                <option value="{{ $nomenclature->id }}" selected>{{ $nomenclature->code }} - {{ $nomenclature->$lib }}</option>
                            @else
                                <option value="{{ $nomenclature->id }}">{{ $nomenclature->code }} - {{ $nomenclature->$lib }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>--}}
                <div class="col-md-4 form-group">
                    <label for="montant">{{ trans('text_me.montant') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input class="form-control text-right" name="montant" id="montantedit" value="{{$contribuale->montant}}" >
                </div>
                <input type="hidden" value="{{ $contribuale->id }}" name="id">
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
        </fieldset>
    </div>
</div>
