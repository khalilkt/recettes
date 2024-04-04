@php
    $lib=trans('text_me.lib');
@endphp
<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.supension') }}  {{$contribuable->$lib}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="card mb-3 col-md-12" id="saveSuspension">
    <div class="card-body col-md-12">
        <form class="" action="{{ url('contribuables/saveSuspension') }}" method="post">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="mois1">{{ trans('text_me.Du') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="mois1" name="mois1" class="form-control" >
                        <option value=""></option>
                        @foreach($mois as $m)
                            <option value="{{$m->id}}">{{$m->libelle}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="mois2">{{ trans('text_me.Au') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="mois2" name="mois2" class="form-control" >
                        <option value=""></option>
                        @foreach($mois as $m)
                            <option value="{{$m->id}}">{{$m->$lib}}</option>
                        @endforeach
                    </select>
                </div>
                <input id="id" name="id" class="form-control" type="hidden" value="{{$contribuable->id}}">
               <input type="submit">
                <div class="col-md-12 form-row">
                    <div class="col-md-8 form-group text-left">
                        (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                        : {{ trans('text_me.msg_asterique') }}
                    </div>
                    <div class="col-md-4 form-group text-right">
                        <button class="btn btn-success btn-icon-split" onclick="saveSuspension(this)" container="saveSuspension">
                        <span class="icon text-white-50">
                            <i class="main-icon fas fa-save"></i>
                            <span class="spinner-border spinner-border-sm" style="display:none" role="status"
                                  aria-hidden="true"></span>
                            <i class="answers-well-saved text-success fa fa-check" style="display:none"
                               aria-hidden="true"></i>
                        </span>
                            <span class="text">{{ trans('text.enregistrer') }}</span>
                        </button>
                    </div>
                    <div id="form-errors" class="text-left col-md-12"></div>
                </div>
            </div>
        </form>
    </div>
</div>

