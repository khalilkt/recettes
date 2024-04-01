<div class="card mb-3" id="saveElement">
    <div class="card-header">{{ trans('text_me.add_element') }}</div>
    <div class="card-body">
        <form class="" action="{{ url('modeles/saveElement') }}" method="post">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="content_value">{{ trans('text_me.content_value') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <textarea id="content_value" name="content_value" class="form-control" ></textarea>
                </div>
                <div class="col-md-12 form-group">
                    <label for="content_value_ar">{{ trans('text_me.content_value_ar') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <textarea id="content_value_ar" name="content_value_ar" class="form-control" ></textarea>
                </div>
                <div class="col-md-4 form-group">
                    <label for="position">{{ trans('text_me.type_insertion') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="position" name="position" class="form-control" onchange="positionElement({{$acte->id}} , '{{ trans('text_me.premier') }}' , '{{ trans('text_me.dernier') }}' , '{{ trans('text_me.apres') }}')">
                        <option value=""></option>
                        @if($lignes !=0)
                            <option value="&nbsp;">{{ trans('text_me.ligne_existante') }}</option>
                        @endif
                        <option value="<br>">{{ trans('text_me.nouvelle_ligne') }}</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="ligne">{{ trans('text_me.position') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="ligne" name="ligne" class="form-control" onchange="parentLigne({{$acte->id}}, '{{ trans('text_me.premier') }}' , '{{ trans('text_me.dernier') }}' , '{{ trans('text_me.apres') }}','{{ trans('text_me.suite_elemet') }}','{{ trans('text_me.pas_de_parent') }}')">
                        {{--<option value="{{$lignes->last()->ligne+1}}">{{$lignes->last()->ligne+1}}</option>--}}
                    </select>
                </div>
                <div class="col-md-4 form-group" id="divparent">
                    <label for="parent">{{ trans('text_me.parent') }}</label>
                    <select id="parent" name="parent" class="form-control" onchange="parentElements({{$acte->id}}, '{{ trans('text_me.premier') }}' , '{{ trans('text_me.dernier') }}' , '{{ trans('text_me.apres') }}')">
                    </select>
                </div>
                <div class="col-md-4 form-group" id="divordres">
                    <label for="ordres">{{ trans('text_me.ordre_affichage') }}</label><span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="ordres" name="ordres" class="form-control">
                    </select>
                </div>
                <div class="col-md-4 form-group" id="alignement">
                    <label for="espace">{{ trans('text_me.alignement') }}</label>
                    <select id="align" name="align" class="form-control" >
                        <option value='align="left"'>A gauche</option>
                        <option value="Tabilation">Tabilation</option>
                        <option value='align="right"'>A droite</option>
                        <option value='align="center"'>Centre</option>
                    </select>
                </div>
                <div class="col-md-4 form-group" id="saut_ligne" style="display: none;">
                    <label for="nbrebr">{{ trans('text_me.nbre_sauts_ligne') }}</label>
                    <select id="nbrebr" name="nbrebr" class="form-control" >
                        <option value="">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="nature">{{ trans('text_me.nature') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="nature" name="nature" class="form-control" onchange="natureElementEquipement()">
                        <option value="0">Fixe</option>
                        <option value="1">Variable</option>
                    </select>
                </div>
                <div id="varriable" class="col-md-4 form-group"style="display:none;">
                   <label for="type">{{ trans('text_me.type_contenu') }}</label>
                        <select id="type" name="type" class="form-control" onchange="typeElementEquipement()">
                            <option value=""></option>
                            <option value="1">Text</option>
                            <option value="2">Numérique</option>
                            <option value="3">Choix</option>
                            <option value="4">Date</option>
                        </select>
                </div>
                <div class="col-md-12 form-group" id="divchoix" style="display:none;">
                    <label for="choix" >{{ trans('text_me.choix') }}</label>
                    <input type="text" value="" name="choix" id="choix"/>
                </div>
                <input id="id" name="id" type="hidden" class="form-control" value="{{ $acte->id }}">
                {{-- <input type="submit">--}}
                <div class="col-md-12 form-row">
                    <div class="col-md-8 form-group text-left">
                        (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                        : {{ trans('text_me.msg_asterique') }}
                    </div>
                    <div class="col-md-4 form-group text-right">
                        <button class="btn btn-success btn-icon-split" onclick="saveElementActe(this)" container="saveElement">
                        <span class="icon text-white-50">
                            <i class="main-icon fas fa-save"></i>
                            <span class="spinner-border spinner-border-sm" style="display:none" role="status"
                                  aria-hidden="true"></span>
                            <i class="answers-well-saved text-success fa fa-check" style="display:none"
                               aria-hidden="true"></i>
                        </span>
                            <span class="text">{{ trans('text.ajouter') }}</span>
                        </button>
                    </div>
                    <div id="form-errors" class="col-md-12 text-left"></div>
                </div>
            </div>
    </div>
</div>
