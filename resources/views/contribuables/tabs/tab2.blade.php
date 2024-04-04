<div class="row">
    @if(Auth::user()->hasAccess(9,2) and $nbrproEchen==0)
    <div class="col-md-12">
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mb-3" onclick="newprotpcol({{ $contribuale->id}},{{$contribuale->montant}})"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.new_pratocol")}}</a>
    </div>
    @endif
    <div class="col-md-12">
        <div id="create" ></div>
    </div>
    <div class="col-md-12">
        <div id="edit" ></div>
    </div>
    <div class="col-md-12">
        <div class="card shadow" col-md-12>
            <div class="card-body">
                <div class="clearfix"></div>
                <hr>
                <div class="table-responsive">
                    <table  selected="" link="{{url("contribuables/getProtocoles/$contribuale->id")}}" colonnes="id,libelle,dateEch,montant,etat,actions" class="table table-hover table-bordered datatableshow2">
                        <thead>
                        <tr>
                            <th width="30px"></th>
                            <th>{{ trans('text_me.description') }}</th>
                            <th>{{ trans('text_me.date') }}</th>
                            <th>{{ trans('text_me.montant') }}</th>
                            <th>{{ trans('text_me.etat') }}</th>
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
