@php
    $lib=trans('text_me.lib');
@endphp
<div class="row">
    <div class="col-md-12">
        <div id="create" ></div>
    </div>
    <div class="col-md-12">
        <div id="edit" ></div>
    </div>
    <div class="col-md-12">
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mb-3" onclick="createElementType({{$type->id}})"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.add_element")}}</a>
    </div>
    <div class="col-md-12">
         <div class="card shadow" col-md-12>
            <div class="card-body">
                <div class="row">
                     <div class="col-md-12">
                        <div class="table-responsive">
                            <table  selected="" link="{{url("typesEquipements/getElts/$type->id ")}}" colonnes="id,{{$lib}},actions" class="table table-hover table-bordered datatableshow2">
                                <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th>{{ trans('text.libelle') }}</th>
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
</div>
