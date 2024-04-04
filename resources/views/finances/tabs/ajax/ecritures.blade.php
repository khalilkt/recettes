@php
    $lib=trans('text_me.lib');
@endphp<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.suivre') }} {{$nomenclature->libelle}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12" id="addForm">
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
                            <div id="editEcriture"></div>
                            <div class="table-responsive">
                                <table  selected="" link="{{url("finances/getEcritures/$nomenclature->id")}}" colonnes="id,description,date,montant,actions" class="table table-hover table-bordered datatableshow2">
                                    <thead>
                                    <tr>
                                        <th width="30px"></th>
                                        <th>{{ trans('text_me.description') }}</th>
                                        <th>{{ trans('text_me.date') }}</th>
                                        <th>{{ trans('text_me.montant') }}</th>
                                        <th width="80px">{{ trans('text.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

        </div>
    </div>
</div>
