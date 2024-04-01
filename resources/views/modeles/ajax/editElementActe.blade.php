<div class="card mb-3" id="editElement">

    <div class="card-header">{{ trans('text_me.modifier_element') }} </div>
    <div class="card-body">
        <form class="" action="{{ url('modeles/updateElement') }}" method="post">
            {{ csrf_field() }}
            <fieldset @if(!(Auth::user()->hasAccess(2,2)) or $etat !=0)  disabled="true" @endif>
            <div class="row">

                <div class="col-md-12 form-group">
                    <label for="content_value">{{ trans('text_me.content_value') }}</label>
                    <textarea id="content_value" name="content_value" class="form-control" >{{$element->content_value}}</textarea>
                </div>
                <div class="col-md-12 form-group">
                    <label for="content_value_ar">{{ trans('text_me.content_value_ar') }}</label>
                    <textarea id="content_value_ar" name="content_value_ar" class="form-control" >{{$element->content_value_ar}}</textarea>
                </div>


                <div class="col-md-4 form-group">
                    <label for="position">{{ trans('text_me.type_insertion') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="positionedit" name="position" class="form-control" onchange="positionElementedit({{$element->modeles_acte_id}} , '{{ trans('text_me.premier') }}' , '{{ trans('text_me.dernier') }}' , '{{ trans('text_me.apres') }}')">
                        @if($ligne !='')
                            <option value="<br>" selected>{{ trans('text_me.nouvelle_ligne') }}</option>
                        @else
                            <option value="&nbsp;" selected>{{ trans('text_me.ligne_existante') }}</option>
                        @endif
                        <option value="&nbsp;">{{ trans('text_me.ligne_existante') }}</option>
                        <option value="<br>">{{ trans('text_me.nouvelle_ligne') }}</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="ligneedit">{{ trans('text_me.position') }}<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="ligneedit" name="ligne" class="form-control" onchange="parentLigneedit({{$element->modeles_acte_id}}, '{{ trans('text_me.premier') }}' , '{{ trans('text_me.dernier') }}' , '{{ trans('text_me.apres') }}','{{ trans('text_me.suite_elemet') }}','{{ trans('text_me.pas_de_parent') }}')">
                        @if($ligne !='')
                            <option value="0" >{{ trans('text_me.premier') }} </option>
                            @foreach($positions as $pos)
                                @if($pos->id==$position->id)
                                    <option value="{{$pos->ligne}}" selected>{{$pos->content_value}}</option>
                                @else
                                    <option value="{{$pos->ligne}}" >{{ trans('text_me.apres') }} "{{$pos->content_value}}"</option>
                                @endif
                            @endforeach
                            <option value="D" >{{ trans('text_me.dernier') }} </option>
                        @else
                            @foreach($positions as $pos)
                            @if($pos->id==$position->id)
                                <option value="{{$pos->ligne}}" selected>{{$pos->content_value}}</option>
                            @else
                                <option value="{{$pos->ligne}}" >{{ trans('text_me.apres') }} "{{$pos->content_value}}"</option>
                            @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4 form-group" id="divparentedit" @if($ligne !='') style="display: none;" @endif>
                    <label for="parentedit">{{ trans('text_me.parent') }}</label>
                    <select id="parentedit" name="parent" class="form-control" onchange="parentElementsedit({{$element->modeles_acte_id}}, '{{ trans('text_me.premier') }}' , '{{ trans('text_me.dernier') }}' , '{{ trans('text_me.apres') }}')">
                        @if($parent!='')
                            @foreach($parents as $p)
                                @if($parent->id==$p->id)
                                    <option value="{{$p->id}}" selected>{{$p->content_value}}</option>
                                @else
                                    <option value="{{$p->id}}">{{ trans('text_me.suite_elemet') }} "{{$p->content_value}}"</option>
                                @endif
                            @endforeach
                        <option value="0">{{ trans('text_me.pas_de_parent') }}</option>
                        @else
                            <option value="0">{{ trans('text_me.pas_de_parent') }}</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-4 form-group" id="divordresedit">
                    <label for="ordresedit">{{ trans('text_me.ordre_affichage') }}</label><span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span></label>
                    <select id="ordresedit" name="ordres" class="form-control">
                        @if($ligne!='<br>')
                            <option value="0" >{{ trans('text_me.premier') }} </option>
                            @foreach($ordres as $ord)
                                @if($ord->ordre == $ordre)
                                    <option value="{{$ord->id}}" selected>{{$ord->content_value }}</option>
                                @else
                                    <option value="{{$ord->id}}">{{ trans('text_me.apres') }} "{{$ord->content_value}}"</option>
                                @endif
                            @endforeach
                            <option value="D" >{{ trans('text_me.dernier') }} </option>
                        @else
                            <option value="P" >{{ trans('text_me.premier') }} </option>
                        @endif
                    </select>
                </div>
                <div class="col-md-4 form-group" id="alignementedit" @if($ligne =='') style="display: none;" @endif>
                    <label for="espaceedit">{{ trans('text_me.alignement') }}</label>
                    <select id="alignedit" name="align" class="form-control" >
                        <option value='align="left"'>A gauche</option>
                        <option value="Tabilation">Tabilation</option>
                        <option value='align="right"'>A droite</option>
                        <option value='align="center"'>Centre</option>
                    </select>
                </div>

                <div class="col-md-4 form-group" id="saut_ligneedit" @if($ligne =='') style="display: none;" @endif>
                    <label for="nbrebr">{{ trans('text_me.nbre_sauts_ligne') }}</label>
                    <select id="nbrebredit" name="nbrebr" class="form-control" >
                        @if($saut_deligne !='')
                            @if($saut_deligne==1)
                                <option value="1" selected>1</option>
                            @endif
                             @if($saut_deligne==2)
                                    <option value="2" selected>2</option>
                             @endif
                         @if($saut_deligne==3)
                                <option value="3" selected>3</option>
                            @endif
                         @if($saut_deligne==4)
                                <option value="4" selected>4</option>
                            @endif
                         @if($saut_deligne==5)
                                <option value="5" selected>5</option>
                            @endif
                          @if($saut_deligne==6)
                                    <option value="6" selected>6</option>
                          @endif

                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                         @else
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                        @endif
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label for="nature">{{ trans('text_me.nature') }}</label>
                    <select id="nature" name="nature" class="form-control" onchange="natureElementEquipement()" disabled="true">
                        @if($element->nature_content==1)
                            <option value="1" selected>Variable</option>
                        @endif
                        @if($element->nature_content==0)
                            <option value="1" selected>Fixe</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-4 form-group" id="varriable" @if($element->nature_content==1)  @else style="display:none;" @endif>
                    <label for="type">{{ trans('text_me.type') }}</label>
                    <select id="type" name="type" class="form-control" onchange="typeElementEquipement()" disabled="true">
                        @if($element->type_content==1)
                            <option value="1" selected>Text</option>
                        @endif
                        @if($element->type_content==2)
                            <option value="2" selected>Numérique</option>
                        @endif
                        @if($element->type_content==3)
                            <option value="3" selected>Choix</option>
                        @endif
                        @if($element->type_content==4)
                            <option value="4" selected>Date</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-8 form-group" id="divchoix" @if($element->type_content==3)  @else style="display:none;" @endif>
                    <label for="choix" >{{ trans('text_me.choix') }}</label>
                    <input type="text"  name="choix" id="choix" value="{{ $options }}" />
                </div>
                <input name="oldordre" value="{{ $element->ordre}}" type="hidden">
                <input id="id" name="id" type="hidden" class="form-control" value="{{ $element->modeles_acte_id }}">
                <input id="id_element" name="id_element" type="hidden" class="form-control" value="{{ $element->id }}">
                <input id="type" name="type" type="hidden" class="form-control" value="{{ $element->type_content }}">
                <input id="nature" name="nature" type="hidden" class="form-control" value="{{ $element->nature_content }}">
                <input id="choisa" name="choisa" type="hidden" class="form-control" value="{{$options}}">
                <input value="{{$element->postion}}" name="position1" type="hidden">
                <input value="{{$element->ligne}}" name="ligne1" type="hidden">
                {{--<input type="submit">--}}
                <div class="col-md-12 form-row">
                    <div class="col-md-8 form-group text-left">
                        (<span class="required_field" data-toggle="tooltip" data-placement="right" title="{{ trans('text.champ_obligatoire') }}">*</span>)
                        : {{ trans('text_me.msg_asterique') }}
                    </div>
                    <div class="col-md-4 form-group text-right">
                    <button class="btn btn-success btn-icon-split" onclick="editElementActe(this)" container="editElement">
                        <span class="icon text-white-50">
                            <i class="main-icon fas fa-save"></i>
                            <span class="spinner-border spinner-border-sm" style="display:none" role="status"
                                  aria-hidden="true"></span>
                            <i class="answers-well-saved text-success fa fa-check" style="display:none"
                               aria-hidden="true"></i>
                        </span>
                        <span class="text">{{ trans('text.enregistrer') }}</span>
                    </button>
                </div>
                <div id="form-errors" class="col-md-12 text-left"></div>
            </div>
                </fieldset>
            </div>

    </div>
</div>
