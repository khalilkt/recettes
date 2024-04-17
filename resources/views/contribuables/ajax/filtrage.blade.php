<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.suiviContribuable') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@php
    $lib=trans('text_me.lib');
@endphp
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div id="create" ></div>
            </div>
            <div class="col-md-12">
                <div id="edit" ></div>
            </div>
            <div class="col-md-12">
                <div class="card shadow" >
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4 filters-item">
                                <div class="filters-label">
                                    <i class="fa fa-filter"></i> Filtrage
                                </div>
                                <div class="filters-input">
                                    <select id="filtrage"  name="filtrage" data-live-search="true" class="selectpicker form-control" onchange="filterContribuable({{$annee}})"  >
                                        <option value="1" >Payements</option>
                                        <option value="2" >Degrevement</option>
                                        <option value="3" >Recouvrement</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 filters-item">
                                <div class="filters-label">
                                    <i class="fa fa-filter"></i> {{ trans('text_me.Du') }}
                                </div>
                                <div class="filters-input">
                                    <input id="date1"  name="date1"  class="form-control" type="date" onchange="filterContribuableDate({{$annee}})" >
                                </div>
                            </div>
                            <div class="col-md-4 filters-item">
                                <div class="filters-label">
                                    <i class="fa fa-filter"></i> {{ trans('text_me.Au') }}
                                </div>
                                <div class="filters-input">
                                    <input id="date2"  name="date2"  class="form-control" type="date" onchange="filterContribuableDate({{$annee}})" >
                                </div>
                            </div>
                        <div class="row col-md-8"
                        >
                        <div class="ml-3 filters-item mt-3" >
                            <div class="filters-label mb-1">
                                <i class="fa fa-filter"></i>Role
                            </div>
                            <select id="suivi_payment_role_select" class="selectpicker form-control" style="display: block" onchange="">
                                <option value="all" >Tout</option>
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}" >{{$role->libelle}}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                        </div>
                         

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group float-right">
                                    <form role="form"  id="formst" name="formst" class=""  method="get" >
                                        {{ csrf_field() }}
                                        <button type="button" onclick="excelSuiviPayementCtb({{$annee}})" class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm  text-white">
                                            <i class="fas fa-file-excel"></i> {{ trans('text_me.exporter') }} EXCEL
                                        </button>
                                            <button type="button" onclick="pdfSuiviPayementCtb({{$annee}})" class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm  text-white">
                                                <i class="fas fa-file-pdf"></i> {{ trans('text_me.exporter') }} PDF
                                            </button>
                                    </form>
                                </div>
                                <div class="clearfix"></div>
                                <hr>
                               {{-- <div class="table-responsive">
                                    <table  id="datatableshow2" selected="" index="0" link="{{url("contribuables/getPayementAnnne/$annee/all/all/all")}}" colonnes="contribuable,date,montants" class="table table-hover table-bordered datatableshow2">
                                        <thead>
                                        <tr>
                                            <th>{{ trans('text_me.contribuable') }}</th>
                                            <th>{{ trans('text_me.date') }}</th>
                                            <th>{{ trans('text_me.montant') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>--}}
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
