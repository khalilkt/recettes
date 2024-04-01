<div class="row">
    <div class="col-md-12">
        @php
            $lib=trans('text_me.lib');
        @endphp
        <form class="" action="{{ url('role_annees/edit') }}" method="post">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="libelle">{{ trans('text_me.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <input id="libelle" name="libelle" class="form-control" value="{{$role->$lib}}">
                </div>
                <div class="col-md-12 form-group">
                    <label for="compte_impitqtion">{{ trans('text_me.compte_impitation') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select class="form-control selectpicker " data-live-search="true" name="nomenclature_element_id" id="nomenclature_element_id" >
                        <option></option>
                        @foreach($nomenclatures as $nomenclature)
                            @if($nomenclature->id == $role->nomenclature_element_id)
                                <option value="{{ $nomenclature->id }}" selected>{{ $nomenclature->code }} - {{ $nomenclature->$lib }}</option>
                            @else
                                <option value="{{ $nomenclature->id }}">{{ $nomenclature->code }} - {{ $nomenclature->$lib }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <input type="hidden" value="{{ $role->id }}" name="id">
                <div class="col-md-12 text-right">
                    <button class="btn btn-success btn-icon-split" onclick="saveform(this)" container="tab1">
                        <span class="icon text-white-50">
                            <i class="main-icon fas fa-save"></i>
                            <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                            <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                        </span>
                        <span class="text">{{ trans('text.enregistrer') }}</span>
                    </button>
                    <div id="form-errors" class="text-left"></div>
                </div>
            </div>
        </form>
    </div>
</div>
