<script>
    $("#main-modal :input").inputmask();
</script>
@php
    $lib=trans('text_me.lib');
@endphp
<div class="modal-body">
    <div class="row">
        <div class="col-md-12" id="addForm">
            <form class="" action="{{ url('finances/saveEcriture') }}" method="post">
                {{ csrf_field() }}
                    <div class="card">
                        <div class="card-header">
                            <div class="col-md-12">
                            <label for="libelle">{{ trans('text_me.compte_impitation') }}  {{$nomenclature->$lib}}</label>
                            </div>
                            <div class="col-md-12">
                                <label for="">{{ trans('text_me.classe') }} : {{$nomenclature->ref_type_nomenclature->$lib}}</label>
                              </div>
                            <div class="col-md-12">
                                <label for="">{{ trans('text_me.code') }} : {{$nomenclature->code}}</label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label for="">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                    <input class="form-control" name="description" id="description">
                                </div>
                                <div class="col-md-12 form-group">
                                    @if($nomenclature->ref_type_nomenclature_id==1)
                                        <label for="">{{ trans('text_me.origine') }} </label>
                                    @endif
                                    @if($nomenclature->ref_type_nomenclature_id==2)
                                        <label for="">{{ trans('text_me.beneficieur') }} </label>
                                    @endif
                                    <input class="form-control" name="texte" id="texte" >
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">{{ trans('text_me.type') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                    <select class="form-control" name="typeEcriture" id="typeEcriture">
                                        <option></option>
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}">{{$type->$lib}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">{{ trans('text_me.date') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                    <input type="date" id="date" class="form-control" name="date">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">{{ trans('text_me.montant') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                    <div class="float-label-control">
                                        <input type="" class="form-control"  class="form-control" id="montant" name="montant" data-inputmask="'alias': 'numeric', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                                    </div>
                                </div>

                                <input type="hidden" value="{{$nomenclature->id}}" name="id">
                                {{--<input type="submit">--}}
                                <div class="col-md-12 form-row">
                                    <div class="col-md-8 form-group text-left">
                                        (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                                        : {{ trans('text_me.msg_asterique') }}
                                    </div>
                                    <div class="col-md-4 form-group text-right">
                                        <button class="btn btn-success btn-icon-split" onclick="saveEcriture(this)" container="addForm">
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
                        </div>
                    </div>

            </form>
        </div>
    </div>
</div>
