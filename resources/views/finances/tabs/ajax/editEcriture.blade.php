@php
    $lib=trans('text_me.lib');
@endphp<div class="col-md-12" id="editEcriture">
    <form class="" action="{{ url('finances/updateEcriture') }}" method="post">
        {{ csrf_field() }}
                <div class="form-row">
                    <div class="col-md-12 form-group">
                        <label for="">{{ trans('text_me.description') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input class="form-control" name="description" id="description" value="{{$ecriture->description}}">
                    </div>
                    <div class="col-md-12 form-group">
                        <label for=""> {{ $libelleTexte }} </label>
                        <input class="form-control" name="texte" id="texte" value="{{ $texte }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">{{ trans('text_me.type') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <select class="form-control" name="typeEcriture" id="typeEcriture">
                            @foreach($types as $type)
                                @if($type->id == $ecriture->ref_type_depenses)
                                    <option value="{{$type->id}}" selected>{{$type->$lib}}</option>
                                @else
                                    <option value="{{$type->id}}">{{$type->$lib}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">{{ trans('text_me.date') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input type="date" id="date" class="form-control" name="date" value="{{$ecriture->date}}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="">{{ trans('text_me.montant') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <div class="float-label-control">
                            <input type="" class="form-control" value="{{$ecriture->montant}}" class="form-control" id="montant" name="montant" data-inputmask="'alias': 'numeric', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="">{{ trans('text_me.ged') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input class="form-control" name="ged" id="ged" type="file">
                    </div>
                    <input type="hidden" value="{{$sens}}" name="sense">
                    <input type="hidden" value="{{$ecriture->id}}" name="id">
                    <input type="hidden" value="{{$ecriture->nomenclature_element_id}}" name="nomenclature_element_id">
                    <div class="col-md-12 form-row">
                        <div class="col-md-8 form-group text-left">
                            (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                            : {{ trans('text_me.msg_asterique') }}
                        </div>
                        <div class="col-md-4 form-group text-right">
                            <button class="btn btn-success btn-icon-split" onclick="updateEcriture(this)" container="editEcriture">
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
