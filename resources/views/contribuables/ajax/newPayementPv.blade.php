@php
    $lib=trans('text_me.lib');
@endphp
<div class="card mb-3" id="savePayement">
    <div class="card-header">{{ trans('text_me.new_payement') }} </div>
    <div class="card-body">
        <form class="" action="{{ url('contribuables/savePayementpv') }}" method="post">
            {{ csrf_field() }}
            <div class="row">
               {{-- <input id="min_essay"  value="" type="hidden" >--}}
                <input id="max_essay" value="" type="hidden">
               {{--<input id="max_essay" value="{{count($payements)}}" type="hidden">
               --}} {{--<div class="alert alert-warning alert-dismissible fade show col-md-12" role="alert">
                    <h4>{{ trans('text_me.mois_non_payes') }}</h4>
                    @foreach($payements as $payement)
                        {{$payement->$lib}}
                    @endforeach
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>--}}

                {{--@if($mois_av!=null)
                    <div class="alert alert-danger alert-dismissible fade show col-md-12" role="alert">
                        <h4> {{ trans('text_me.mois_arriere') }} {{ $mois_av->mois_id }}
                            {{ trans('text_me.montantPaye') }} {{ $mois_av->montant }} {{ trans('text_me.montantRestant') }} {{ $mois_avmontant_arriere }}</h4>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif--}}
                <div class="col-md-12 form-group">
                <label for="">{{ trans('text_me.article') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                <select id="article"  name="article" data-live-search="true" class="selectpicker form-control" onchange="montantArticleContribuable()" >
                    <option value=""></option>
                    @foreach( $roles as $role)
                        @if($role->montant > $role->montant_paye)
                            <option value="{{$role->id}}">Article : {{$role->article}}  / {{ $role->role->libelle }} </option>
                        @endif
                    @endforeach
                </select>
                </div>
                <div class="col-md-12 form-group">
                    <label for="libelle">{{ trans('text_me.description') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle" name="libelle" class="form-control" >
                </div>

                <div class="col-md-6 form-group">
                    <label for="montant">{{ trans('text_me.montant') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="montantSaisi" name="montant" class="form-control" onchange="montantMaxMax()" data-inputmask="'alias': 'numeric', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                </div>

                <div class="col-md-6 form-group">
                    <label for="date">{{ trans('text_me.date') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="date" name="date" class="form-control" type="date">
                </div>
                <div class="col-md-6 form-group">
                    <label for="date">{{ trans('text_me.ref_quitance') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="quitance" name="quitance" class="form-control" type="">
                </div>
                 <div class="col-md-6 form-group">
                    <label for="date">{{ trans('text_me.titre') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="titre" name="titre" class="form-control" type="">
                </div>
                <div class="col-md-6 form-group">
                    <label for="date">{{ trans('text_me.type_payement') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="typePayement" name="typePayement" class="form-control" onchange="typePayementFin()">
                        <option value=""></option>
                        @foreach( $type_pay as $type)
                            <option value="{{$type->id}}">{{ $type->libelle }}</option>
                            @endforeach
                    </select>
                </div>
                <div id="cheque"  class="col-md-12 form-row">
                    <div class="col-md-3 form-group" style="display: none" id="banquediv">
                        <label for="">{{ trans('text_me.banque') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <select id="banque" name="banque" class="form-control s" style="display: none">
                            <option value=""></option>
                            @foreach( $banques as $banque)
                                <option value="{{$banque->libelle}}">{{ $banque->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group" style="display: none" id="comptediv">
                        <label for="">{{ trans('text_me.compte') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="compte" name="compte" class="form-control" style="display: none">
                    </div>
                    <div class="col-md-3 form-group" style="display: none" id="numChequediv">
                        <label for="">{{ trans('text_me.numCheque') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="numCheque" name="numCheque" class="form-control" style="display: none">
                    </div>
               <div class="col-md-3 form-group" style="display: none" id="nom_appdiv">
                        <label for="">{{ trans('text_me.nom_app') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>

                   <select id="nom_app" name="nom_app" class="form-control" style="display: none">
                       <option value=""></option>
                       @foreach( $applications as $application)
                           <option value="{{$application->libelle}}">{{ $application->libelle }}</option>
                       @endforeach
                   </select>
               </div>
                    <div class="col-md-3 form-group" style="display: none" id="teldiv">
                        <label for="">{{ trans('text_me.tel') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="tel" name="tel" class="form-control" style="display: none">
                    </div>


                    <div class="col-md-3 form-group" style="display: none" id="nom_degrevement">
                        <label for="">{{ trans('text_me.decision') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>

                        <input id="decision" name="decision" class="form-control" style="display: none">

                    </div>
                    <div class="col-md-3 form-group" style="display: none" id="aplay">
                        <label for="">{{ trans('text_me.fichier') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input type="file" id="fichier"  name="fichier[]" multiple class="form-control" required>
                    </div>

                </div>
                <input id="id" name="id" class="form-control" type="hidden" value="{{$contr->id}}">
                <input id="mt" name="mt" class="form-control" type="hidden">
                {{--<input type="submit">--}}
                <div class="col-md-12 form-row">
                    <div class="col-md-8 form-group text-left">
                        (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                        : {{ trans('text_me.msg_asterique') }}
                    </div>
                    <div class="col-md-4 form-group text-right">
                        <button class="btn btn-success btn-icon-split" onclick="savePayement(this)" container="savePayement">
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

