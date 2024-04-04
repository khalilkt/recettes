@php
    $lib=trans('text_me.lib');
@endphp
    <div class="clearfix"></div>
    <div class="card p-0 m-0" id="presentation">
        <div class="card-header">
            <div class="form-row">
                <div class="col-md-11 form-group">
                    {{ trans('text_me.presentation')}} {{$budget->$lib}}
                </div>
                {{--@if($budget)--}}
                @if($budget->ref_type_budget_id==3 and  Auth::user()->hasAccess(4,4))
                   <div class="col-md-1 form-group text-right">
                    <button type="button" class="btn btn-sm btn-warning" onClick="situationBudget({{$budget->id}})" data-toggle="tooltip" data-placement="top" title="{{trans('text_me.situationBudgetaire')}} "><i class="fas fa-fw fa-clipboard-list"></i></button>
                   </div>
                @endif
            </div>
        </div>
        <div class="card-body p-0 m-0">
            <div  class="accordion m-0" id="accordion1">
                {!! $html !!}
            </div>
        </div>
    </div>
