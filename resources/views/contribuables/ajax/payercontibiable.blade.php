<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.nouveaupayement') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@php

@endphp
<div class="card">

                    <div class="card-body">
                     <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="">{{ trans('text_me.contribuable') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select id="contribuable"  name="contribuable" data-live-search="true" class="selectpicker form-control" onchange="selectionnerContribuable({{$annee}})"  >
                            <option value="" >{{ trans('text_me.tous') }}</option>
                            @foreach($contribuables as $contribuable)
                                <option value="{{ $contribuable->contribuable_id }}">{{ $contribuable->article }} / {{ $contribuable->contribuable->libelle }}</option>
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-12 form-group" style="display: none" id="create1">

                         </div>
                         <div class="col-md-12 form-group" style="display: none" id="edit1">

                         </div>
                            <div class="col-md-6 form-group" style="display: none" id="divprotocole">
                                <label for="">{{ trans('text_me.protocole') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                <select id="protocole"  name="protocole" data-live-search="true" class="selectpicker form-control" onchange="filterContribuableff({{$annee}})"  >

                                </select>
                            </div>

                    </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>
