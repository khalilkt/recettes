
<div class="row">
    <div class="col-md-12">
        @if(Auth::user()->hasAccess(4,2) )
        <div class="text-right mb-3">
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="openDocumentModal({{$ecriture->id}},3)"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_my.new_document")}}</a>
        </div>
@endif
        <div id="document_div" >
        </div>
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table  selected="" id="datatableshow_ged" colonnes="libelle,extension,taille,ref_types_document.libelle,actions" class="table table-hover table-bordered " link="{{url("documents/getDocuments/$ecriture->id/3")}}" >
                        <thead>
                        <tr>
                            <th>{{ trans('text.libelle') }}</th>
                            <th >{{ trans('text_my.extension') }}</th>
                            <th class="text-right">{{ trans('text_my.taille') }} en Octet</th>
                            <th >{{ trans('text_my.type_document') }} </th>
                            <th style="width:100px;">{{ trans('text.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

