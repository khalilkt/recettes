@php
    $lib=trans('text_me.lib');
@endphp<div class="card">
<div class="modal-header">
    <h5 class="modal-title">{{ $budget->$lib }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
<div class="row">
    <div class="col-md-12">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-12">
                    <hr>
                        @include('finances.tabs.ajax.filtre')
                        @include('finances.tabs.ajax.budgets',['budget'=>$budget])

                </div>
            </div>
    </div>
</div>
</div>
</div>

