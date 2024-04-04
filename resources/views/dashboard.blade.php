@extends('layout_without_menu')
@section('page-content')
<div class="row justify-content-md-center" id="dgctMenu">
  <div class="col-md-8 p-0 shadow">
    <div class="row justify-content-center m-2">
        @foreach($modules as $module)
            @if ($loop->iteration == 7)
              <div class="card mb-0 col-md-4 bg-secondary rounded-0 border-dash">
                <div class="card-body py-4"></div>
              </div>
            @endif
            <div class="card mb-0 col-md-4 {{$module->bg_color}} @if(!Auth::user()->hasAccess($module->sys_groupes_traitement_id)) item-disabled @endif rounded-0 border-dash">
              <div class="card-body py-4" id="module{{$module->id}}">
                  <div class="dgct-title text-center {{$module->text_color}}">
                      @if($module->is_externe)
                      <div class="card-badge"><i class="fas fa-share-square" aria-hidden></i> Lien externe</div>
                      @endif
                      <div id="img{{$module->id}}">
                        <img src="{{url('img/modules/'.$module->id.'.png')}}" class="dgct-icon" alt="">
                      </div>
                       @if(Auth::user()->hasAccess($module->sys_groupes_traitement_id))
                      <a href="{{ url('selectModule/'.$module->id) }}" @if(!$module->is_externe) onclick="$('#img{{$module->id}}').html(loading_content)" @endif @if($module->is_externe) target="_blank" @endif class="btn btn-link btn-block {{$module->text_color}} stretched-link">
                        <span>{{(App::isLocale('ar')) ? $module->libelle_ar : $module->libelle}}</span>
                      </a>
                      @else
                        <span>{{(App::isLocale('ar')) ? $module->libelle_ar : $module->libelle}}</span>
                      @endif
                  </div>
              </div>
            </div>
            @if ($loop->iteration == 7)
              <div class="card mb-0 col-md-4 bg-secondary rounded-0 border-dash">
                <div class="card-body py-4"></div>
              </div>
            @endif
        @endforeach
    </div>
  </div>
</div>
@endsection
