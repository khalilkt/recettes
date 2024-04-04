<div >
    @php
        $lib=trans('text_me.lib');
    @endphp
    <form role="form"  id="formbdg2" name="formbdg2" class=""  method="get" >
        {{ csrf_field() }}
        <div class="card m-0 p-0">
            <div class="card-header ">
                {{ trans('text_me.edition') }}
            </div>
            <div class="card-body m-0 p-0">
                <div class="row">
                    <div class="col-md-4 filters-item">
                        <div class="filters-label">
                            <i class="fa fa-filter"></i>  {{ trans('text_me.niveau_affichage') }}
                        </div>
                        <div class="filters-input">
                            <select id="niveau_affichage"  name="niveau_affichage" data-live-search="true" class="selectpicker form-control" onchange="filtrebudgets({{$budget->id}})"  >
                                <option value="all">{{ trans('text_me.tous') }}</option>
                                <option value="1">{{ trans('text_me.classes') }}</option>
                                <option value="2">{{ trans('text_me.chapitres') }}</option>
                                <option value="3">{{ trans('text_me.articles') }}</option>
                                <option value="4">{{ trans('text_me.paragraphes') }}</option>
                                <option value="5">{{ trans('text_me.sous_paragraphes') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 filters-item">
                        <div class="filters-label">
                            <i class="fa fa-filter"></i> {{ trans('text_me.classe') }}
                        </div>
                        <div class="filters-input">
                            <select id="classe"  name="classe[]" data-live-search="true" class="selectpicker form-control"onchange="filtrebudgets({{$budget->id}})" multiple="multiple" >
                                <option value="all" selected>{{ trans('text_me.tous') }}</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->$lib }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="filters-label">
                            &nbsp;
                        </div>
                        <div class="filters-input">
                            @if(Auth::user()->hasAccess(4,3))
                                <button type="button"  onclick="excelBudget({{$budget->id}},3)"  class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm text-white">
                                    <i class="fas fa-file-excel"></i> {{ trans('text_me.exporter')}}
                                </button> &nbsp;
                                <button type="button" onclick="pdfBudget({{$budget->id}},3)" class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm text-white">
                                    <i class="fas fa-file-pdf"></i> {{ trans('text_me.exporter')}}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
