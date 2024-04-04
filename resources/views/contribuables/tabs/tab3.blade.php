<div class="row">
    @if(Auth::user()->hasAccess(9,2) and  $contribuale->nomenclature_element_id!='')
    <div class="col-md-12">

        @if($nbrproEchen>0)
             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mb-3" onclick="newPayement({{ $contribuale->id}},{{$contribuale->montant}})"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.new_payement")}}</a>
        @else
             <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm float-right mb-3" onclick="newPayementPv({{ $contribuale->id}},{{$contribuale->montant}})"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.new_payement")}}</a>
        @endif
     </div>
    @endif
    <div class="col-md-12">
        <div id="create1" ></div>
    </div>
    <div class="col-md-12">
        <div id="edit1" ></div>
    </div>
    <div class="col-md-12 form-row">

        <div class="card shadow col-md-12 form-group" >
            <div class="card-body">
                <div class="clearfix">Payement Par Article</div>
                <hr>
                <div class="table-responsive">
                    <table  selected="" link="{{url("contribuables/getPayementmens1/$contribuale->id")}}" colonnes="id,libelle,date,role.libelle,montant,actions" class="table table-hover table-bordered datatableshow3">
                        <thead>
                        <tr>
                            <th width="30px"></th>
                            <th>{{ trans('text_me.description') }}</th>
                            <th>{{ trans('text_me.date') }}</th>
                            <th>{{ trans('text_me.role') }}</th>
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
        <div class="card shadow col-md-12 form-group" >
            <div class="card-body">
                <div class="clearfix">Payement par protocol</div>
                <hr>
                <div class="table-responsive">
                    <table  selected="" link="{{url("contribuables/getPayements/$contribuale->id")}}" colonnes="id,libelle,date,protocol.libelle,montant,actions" class="table table-hover table-bordered datatableshow4">
                        <thead>
                        <tr>
                            <th width="30px"></th>
                            <th>{{ trans('text_me.description') }}</th>
                            <th>{{ trans('text_me.date') }}</th>
                            <th>{{ trans('text_me.protocole') }}</th>
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
