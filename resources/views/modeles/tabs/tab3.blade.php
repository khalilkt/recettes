
<div class="panel panel-default" id="editChoixElement">
    <div class="panel-body">
        <form class="" action="{{ url('modeles/editChoixElement') }}" method="post">
            {{ csrf_field() }}
            <fieldset >
                <div class="row">
                <div class="col-md-6 form-group">
                    <label >{{ trans('text_me.valeur') }}</label>
                </div>
                <div class="col-md-6 form-group">
                    <label >{{ trans('text_me.valeur_ar') }}</label>
                </div>
                        @foreach($element->ref_choix_itemes_actes as $choix)
                            <div class="col-md-6 form-group">
                                <input id="fr{{$choix->id}}" name="fr{{$choix->id}}" class="form-control" value="{{$choix->libelle}} ">
                            </div>
                        <div class="col-md-6 form-group">
                            <input id="ar{{$choix->id}}" name="ar{{$choix->id}}" class="form-control" value="{{ $choix->libelle_ar}}">
                        </div>
                        @endforeach
                <input type="hidden" value="{{$element->id}}" name="id" id="id">
                <div class="col-md-12 text-right">
                   {{-- @if(Auth::user()->hasAccess(2,2))--}}
                        <button class="btn btn-success btn-icon-split" onclick="editChoixElement(this)" container="editChoixElement">
                            <span class="icon text-white-50">
                                <i class="main-icon fas fa-save"></i>
                                <span class="spinner-border spinner-border-sm" style="display:none" role="status"
                                      aria-hidden="true"></span>
                                <i class="answers-well-saved text-success fa fa-check" style="display:none"
                                   aria-hidden="true"></i>
                            </span>
                            <span class="text">{{ trans('text.enregistrer') }}</span>
                        </button>
                   {{-- @endif--}}
                    <div id="form-errors" class="text-left"></div>
                </div>
            </div>
            </fieldset>
        </form>
    </div>
</div>
