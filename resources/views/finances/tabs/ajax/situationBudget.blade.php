@php
    $lib=trans('text_me.lib');
@endphp
<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_me.situationBudgetaire') }}</h5>
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
                             {{$budget->$lib}}</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4 form-group">
                                <label for="">{{ trans('text_me.Du') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                <input class="form-control" name="date1" id="date1" type="date"  value="@php echo ''.date('Y-01-01') @endphp">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="">{{ trans('text_me.Au') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                <input class="form-control" name="date2" id="date2" type="date"  value="@php echo date('Y-m-d') @endphp">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="">{{ trans('text_me.Type') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                <select id="type"  name="type" data-live-search="true" class="selectpicker form-control"  >
                                    @foreach($type as $t)
                                        <option value="{{$t->id}}">{{$t->$lib}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="detail" name="detail">
                                <label class="form-check-label" for="detail">{{ trans('text_me.details_execution') }} </label>
                            </div>
                            <input  name="id" id="id" type="hidden" value="{{$budget->id}}">
                            <div class="col-md-4 form-group text-right">
                                <label>&nbsp;</label>
                                <form role="form"  id="formst" name="formst" class=""  method="get" >
                                    {{ csrf_field() }}
                                    <button type="button" onclick="exporterSituationBudgetaireExcel(this)" class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm  text-white">
                                        <i class="fas fa-file-excel"></i> {{ trans('text_me.exporter') }}
                                    </button>
                                    <button type="button" onclick="exporterSituationBudgetaire(this)" class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm  text-white">
                                        <i class="fas fa-file-pdf"></i> {{ trans('text_me.exporter') }}
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

        </div>
    </div>
</div>
