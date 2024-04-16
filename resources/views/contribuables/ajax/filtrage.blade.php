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
                        <div class="row col-md-8" id = "div_contr_created_at"style="display: none;"
                        >
                            <div class=" filters-item mt-3" >
                                <div class="filters-label mb-1">
                                    <i class="fa fa-filter"></i>Contribuables Créer au
                                </div>
                                <select class="selectpicker form-control" id="contr_created_at_select" name="contr_created_at_select" onchange="onDecouvrementMonthChange({{$annee}})">
                                   @foreach (["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"] as $key => $month)
                                    <option value="{{$key + 1}}" >{{$month}}</option>
                                   @endforeach
                                
                                </select>
                                <span>total : <span class="bold" id="contr_total_resut">0</span></span>
                            </div>
                            <div id = "div_contr" class="ml-3 filters-item mt-3" >
                                <div class="filters-label mb-1">
                                    <i class="fa fa-filter"></i>parties
                                </div>
                                <select id="contr_split_select" class="selectpicker form-control" style="display: block" onchange="">
                                    <option value="1" >1 - 500</option>
                                </select>
                            </div>
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
