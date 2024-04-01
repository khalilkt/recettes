@php
    $lib=trans('text_me.lib');
@endphp
<div class="card mb-3" id="saveProtocol">
    <div class="card-header">{{ trans('text_me.new_protocol') }}</div>
    <div class="card-body">
        <form class="" action="{{ url('contribuables/saveProtocol') }}" method="post">
            {{ csrf_field() }}
            <div class="row">
                <input id="id" name="id" value="{{$id}}" type="hidden">
                <input id="max_essay" value="{{$montantRest}}" type="hidden">
                <div class="alert alert-warning alert-dismissible fade show col-md-12" role="alert">
                    <h4>{{ trans('text_me.montantMax') }}</h4>
                   {{$montantRest}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="col-md-12 form-group">
                    <label for="libelle">{{ trans('text_me.description') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle" name="libelle" class="form-control" >
                </div>

                <div class="col-md-6 form-group">
                    <label for="montant">{{ trans('text_me.montant') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="montantSaisi"  value="{{$montantRest}}" name="montant" class="form-control" onchange="montantMaxDoit()" data-inputmask="'alias': 'numeric', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                </div>
                <div class="col-md-6 form-group">
                    <label for="">{{ trans('text_me.nbreEch') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
              <select class="form-control" name="nbreEch" id="nbreEch"  onchange="desactivernbre()">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
              </select>
                 </div>
                <div class="col-md-6 form-group row " >
                    <div class="col-md-7">
                    <label for="date">{{ trans('text_me.dateEch') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="date" name="date" class="form-control" type="date">
                    </div>
                    <div class="col-md-5">
                        <label for="date">{{ trans('text_me.montant') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="montant1" name="montant1" class="form-control" type="">
                    </div>
                </div>
                <div class="col-md-6 form-group row " style="display: none" id="div2ech">
                    <div class="col-md-7">
                    <label for="date">{{ trans('text_me.dateEch2') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="date2" name="date2" class="form-control" type="date">
                    </div>
                    <div class="col-md-5">
                        <label for="date">{{ trans('text_me.montant') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="montant2" name="montant2" class="form-control" type="">
                    </div>
                </div>
                <div class="col-md-6 form-group row " style="display: none" id="div3ech">
                    <div class="col-md-7">
                    <label for="date">{{ trans('text_me.dateEch3') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="date3" name="date3" class="form-control" type="date">
                    </div>
                    <div class="col-md-5">
                        <label for="date">{{ trans('text_me.montant') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="montant3" name="montant3" class="form-control" type="">
                    </div>
                </div>
                <div class="col-md-6 form-group row " style="display: none" id="div4ech">

                    <div class="col-md-7">
                        <label for="date">{{ trans('text_me.dateEch4') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="date4" name="date4" class="form-control" type="date">
                    </div>
                    <div class="col-md-5">
                        <label for="date">{{ trans('text_me.montant') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="montant4" name="montant4" class="form-control" type="">
                    </div>
                </div>
                <div class="col-md-6 form-group row " style="display: none" id="div5ech">
                    <div class="col-md-7">
                    <label for="date">{{ trans('text_me.dateEch5') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="date5" name="date5" class="form-control" type="date">
                    </div>
                    <div class="col-md-5">
                        <label for="date">{{ trans('text_me.montant') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="montant5" name="montant5" class="form-control" type="">
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label for="date">{{ trans('text_me.observation') }}</label>
                    <textarea id="remarque" name="remarque" class="form-control" ></textarea>
                </div>

                {{--<input type="submit">--}}
                <div class="col-md-12 form-row">
                    <div class="col-md-8 form-group text-left">
                        (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                        : {{ trans('text_me.msg_asterique') }}
                    </div>
                    <div class="col-md-4 form-group text-right">
                        <button class="btn btn-success btn-icon-split" onclick="saveProtocol(this)" container="saveProtocol">
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

