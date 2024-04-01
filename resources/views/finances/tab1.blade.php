<div class="row">
    <div class="col-md-12">
            {{ csrf_field() }}
            <div class="form-row">
                @include('finances.tabs.ajax.budgets')
            </div>
    </div>
</div>
