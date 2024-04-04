<div class="card shadow mb-3" id="divGED">
    <div class="card-header">
        {{ trans('text_my.new_document')." ".$tilte }}
        <button type="button" onclick="closeDivGED()" id="close" class="close"  aria-label="Close"><span aria-hidden="true">&times;
    </div>
    <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="" id="addpiece" action="{{ url('documents/add') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                              <label for="libelle">{{ trans('text.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                              <input type="text" id="libelle" name="libelle" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="libelle">{{ trans('text_my.type_document') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select id="ref_types_documents_id" name="ref_types_documents_id" class="form-control selectpicker bordered"  title="Selectionner..." data-live-search="true">
                                @foreach($types_docum as $type_docum)
                                    <option value="{{$type_docum->id}}" >{{$type_docum->libelle}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                              <label for="fichier">Fichier <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                              <input type="file" id="fichier" name="fichier" class="form-control" required>
                        </div>
                        <input type="hidden" id="type" name="type" value="{{$type_objet}}">
                        <input type="hidden" id="objet_id" name="objet_id" value="{{$objet->id}}">
                        <input type="hidden" id="destination" name="destination" value="{{$destination}}">
                        <!-- <input type="submit"  value="ok"> -->
                    </form>
                    <div class="col-md-12">
                        <div class="text-right">
                            <button class="btn btn-success btn-icon-split" onclick="addDocument()" container="addForm">
                                <span class="icon text-white-50">
                                    <i class="main-icon fas fa-save"></i>
                                    <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                    <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                </span>
                                <span class="text">{{ trans('text.ajouter') }}</span>
                            </button>
                            <div id="form-errors" class="text-left"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
