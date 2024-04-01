<div class="modal-header">
    <h5 class="modal-title">{{ trans('text_archive.new_archive') }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>@php
    $lib=trans('text_me.lib');
@endphp
<div class="modal-body">
    <div class="row">
        <div class="col-md-12" id="addForm">
            <form class="" action="{{ url('archives/add') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-row">
                    <div class="col-md-6 form-group">
                        <label for="libelle">{{ trans('text.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <input id="libelle" name="libelle" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="secteur_id">{{ trans('text_archive.categorie_archive') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <select id="ref_type_archive" name="ref_type_archive_id" onchange="changeType()" class="form-control selectpicker bordered"  title="{{ trans('text_archive.selectionner') }}" data-live-search="true"> 
                            @foreach($type_archives as $type_archive)
                                <option value="{{$type_archive->id}}" >{{$type_archive->$lib}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="fichier">{{ trans('text_archive.fichier') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span> </label>
                        <input type="file" id="fichier" name="fichier" class="form-control" required>
                    </div>
                    <div class="row col-lg-12 emplm" style="display:none">
                        <div class="col-md-6 form-group">
                            <label for="secteur_id">{{ trans('text_archive.emplacement') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select id="ar_emplacement_id" name="ar_emplacement_id" class="form-control selectpicker bordered"  title="{{ trans('text_archive.selectionner') }}" data-live-search="true"> 
                                @foreach($emplacements as $emplacement)
                                    <option value="{{$emplacement->id}}" >{{$emplacement->$lib}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="secteur_id">{{ trans('text_archive.qualite') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select id="ar_qualite_id" name="ar_qualite_id" class="form-control selectpicker bordered"  title="{{ trans('text_archive.selectionner') }}" data-live-search="true"> 
                                @foreach($qualites as $qualites)
                                    <option value="{{$qualites->id}}" >{{$qualites->$lib}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="num_dossier">{{ trans('text_archive.num_dossier') }} </label>
                            <input type="number" id="num_dossier" name="num_dossier" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="num_archive">{{ trans('text_archive.num_archive') }} </label>
                            <input type="number" id="num_archive" name="num_archive" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label for="secteur_id">{{ trans('text_archive.service') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                        <select id="service_id" name="service_id" class="form-control selectpicker bordered"  title="{{ trans('text_archive.selectionner') }}" data-live-search="true"> 
                            @foreach($structures as $structure)
                                <option value="{{$structure->id}}" >{{$structure->$lib}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label for="libelle_ar">{{ trans('text_archive.date_archivage') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span> </label>
                        <input type="date" value="{{date('Y-m-d')}}" id="date_archivage" name="date_archivage" class="form-control">
                    
                    </div>
                    <div class="col-md-12 form-group valeurs" >
                        <label for="valeurs">{{ trans('text_archive.mots_cles') }} (séparer les valeurs par une virgule)</label>
                        <input type="text" id="valeurs" name="mots_cles" class="form-control">
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="description">{{ trans('text_archive.description') }} </label>
                        <textarea id="desc" name="description" class="form-control" ></textarea>
                    </div>
                        

                    <div class="col-md-12">
                        <div class="text-right">
                            <button class="btn btn-success btn-icon-split" onclick="addObject(this,'archives')"
                                    container="addForm">
                                <span class="icon text-white-50">
                                    <i class="main-icon fas fa-save"></i>
                                    <span class="spinner-border spinner-border-sm" style="display:none" role="status"
                                          aria-hidden="true"></span>
                                    <i class="answers-well-saved text-success fa fa-check" style="display:none"
                                       aria-hidden="true"></i>
                                </span>
                                <span class="text">{{ trans('text.ajouter') }}</span>
                            </button>
                            <div id="form-errors" class="text-left"></div>
                        </div>
                    </div>

                    <!--  <input type="submit" value="Valider" /> -->
            </form>
        </div>
    </div>
</div>
