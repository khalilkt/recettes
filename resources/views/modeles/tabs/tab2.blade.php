<div class="row">
    <div class="col-md-12">
        @if($elementss !='[]')
        <form role="form"  id="formspdf" name="formspdf" class=""  method="get" >
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-left mb-3" onclick="imprimerModele({{$acte->id}})"><i class="fas fa-eye fa-sm text-white-50"></i> {{trans("text.visualiser")}}</a>
        </form>
        @endif
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mb-3" onclick="createElementActeMDL({{$acte->id}})"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.add_element")}}</a>
    </div>
    <div class="col-md-12">
        <div id="createElmtActe" ></div>
    </div>
    <div class="col-md-12">
        <div id="editElmtActe" ></div>
    </div>
    <div class="col-md-12">
        <div class="card shadow" >
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table  selected="" link="{{url("modeles/getElts/$acte->id")}}" colonnes="id,content_value,ordre,type_content,actions" class="table table-hover table-bordered datatableshow2" >
                                <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th>{{ trans('text_me.content_value') }}</th>
                                    <th>{{ trans('text_me.ordre') }}</th>
                                    <th>{{ trans('text_me.nature') }}</th>
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
