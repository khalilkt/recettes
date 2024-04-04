<div class="col-md-12">
    @php
        $lib=trans('text_me.lib');
    @endphp
    <div class="row">
        <div class="col-md-12">
            <form class="" action="{{ url('archives/edit') }}" method="post">
                {{ csrf_field() }}
                <fieldset @if(!Auth::user()->hasAccess([7],2)) disabled="disabled" @endif >
                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label for="libelle">{{ trans('text.libelle') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <input id="libelle" name="libelle" value="{{ $archive->libelle }}" class="form-control">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="secteur_id">{{ trans('text_archive.categorie_archive') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select id="ref_type_archive_id" disabled name="ref_type_archive_id" class="form-control selectpicker bordered"  title="Selectionner..." data-live-search="true"> 
                                @foreach($type_archives as $type_archive)
                                    <option value="{{$type_archive->id}}" @if($type_archive->id==$archive->ref_type_archive_id) selected="selected" @endif>{{$type_archive->$lib}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input id="ref_type_archive_id" name="ref_type_archive_id" type="hidden" value="{{ $archive->ref_type_archive_id }}" class="form-control">
                        <div class="form-group col-md-12">
                        <label for="fichier">{{ trans('text_archive.fichier') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span> </label>
                        <input type="file" id="fichier" name="fichier" class="form-control" required>
                    </div>
                        @if($archive->ref_type_archive_id == 1)
                            <div class="col-md-6 form-group">
                                <label for="secteur_id">{{ trans('text_archive.emplacement') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                <select id="ar_emplacement_id" name="ar_emplacement_id" class="form-control selectpicker bordered"  title="Selectionner..." data-live-search="true"> 
                                    @foreach($emplacements as $emplacement)
                                        <option value="{{$emplacement->id}}" @if($emplacement->id==$archive->ar_emplacement_id) selected="selected" @endif>{{$emplacement->$lib}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="secteur_id">{{ trans('text_archive.qualite') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                                <select id="ar_qualite_id" name="ar_qualite_id" class="form-control selectpicker bordered"  title="Selectionner..." data-live-search="true"> 
                                    @foreach($qualites as $qualite)
                                        <option value="{{$qualite->id}}" @if($qualite->id==$archive->ar_qualite_id) selected="selected" @endif>{{$qualite->$lib}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="num_dossier">{{ trans('text_archive.num_dossier') }} </label>
                                <input type="number" id="num_dossier" name="num_dossier" value="{{ $archive->num_dossier }}" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="num_archive">{{ trans('text_archive.num_archive') }} </label>
                                <input type="number" id="num_archive" name="num_archive" value="{{ $archive->num_archive }}" class="form-control" required>
                            </div>
                        @endif
                        <div class="col-md-6 form-group">
                            <label for="secteur_id">{{ trans('text_archive.service') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                            <select id="service_id" name="service_id" class="form-control selectpicker bordered"  title="Selectionner..." data-live-search="true"> 
                                @foreach($structures as $structure)
                                    <option value="{{$structure->id}}" @if($structure->id==$archive->service_id) selected="selected" @endif>{{$structure->$lib}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="libelle_ar">{{ trans('text_archive.date_archivage') }} <span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span> </label>
                            <input type="date" value="{{ $archive->date_archivage }}" id="date_archivage" name="date_archivage" class="form-control">
                        </div>
                        <div class="form-group col-md-12 valeurs" >
                            <label for="valeurs">{{ trans('text_archive.mots_cles') }} (séparer les valeurs par une virgule)</label>
                            <input type="text" id="valeurs" name="mots_cles" value="{{ $archive->mots_cles }}" class="form-control">
                        </div>
                        
                        <div class="col-md-12 form-group">
                            <label for="description">{{ trans('text_archive.description') }} </label>
                            <textarea id="desc" name="description" class="form-control" > {{ $archive->description }}</textarea>
                        </div>
                    </div>
                    <input type="hidden" value="{{ $archive->id }}" name="id">
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
                </fieldset>
                <!-- <input type="submit" value="OK"/> -->
            </form>
        </div>
    </div>
</div>