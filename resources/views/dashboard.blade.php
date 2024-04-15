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
  <h2 class="mt-4 col-12 text-dark ml-4">Statistiques</h2>
  <div class="mt-2 container col-12">
    <div class="row">
      <div class="col-4">
        <div class="list-group" id="list-tab" role="tablist">
          
          <a onclick="changeSelectedRole({{null}});" style="cursor: pointer;"  class="list-group-item list-group-item-action{{ null == $roleId ? ' active text-white ' : '' }}" id="list-home-list"  aria-controls="home">Tout</a>
          @foreach ($roles as $role)
            {{-- <a class=list-group-item list-group-item-action {{ $role->id == $roleId ? ' active bg-danger' : ' bg-warning ' }} id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">{{$role->libelle}}</a> --}}
            <a onclick="changeSelectedRole({{$role->id}});" style="cursor: pointer;"  class="list-group-item list-group-item-action{{ $role->id == $roleId ? ' active text-white ' : '' }}" id="list-home-list"  aria-controls="home">{{$role->libelle}}</a>
            @endforeach
        </div>
      </div>
      <div class="d-flex flex-column col-4">
        <div class="d-flex flex-row"> 
          <div class="col">
            <div class="card border rounded d-flex justify-content-center align-items-center">
              <div class="card-body ">
                <h6 class="card-title">Total Dégrèvements</h6>
                <p id="reste_a_payer_montant_id" class="card-text h3">{{number_format($total_degrevement , 0, '', ' ')}} MRU</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card border rounded d-flex justify-content-center align-items-center">
              <div class="card-body ">
                <h6 class="card-title">Total Payment</h6>
                <p id = "total_payment_montant_id" class="card-text h3">{{number_format($total_payments , 0, '', ' ')}} MRU</p>
              </div>
            </div>
          </div>
        </div>
      
  <div class="row"> 
        <div class="col mt-3">
          <div class="card border rounded d-flex justify-content-center align-items-center">
            <div class="card-body ">
              <h6 class="card-title">Total</h6>
              <p id = "total_payment_montant_id" class="card-text h3">{{number_format($total_roles_montant  , 0, '', ' ')}} MRU</p>
            </div>
          </div>
        </div>
        <div class="col mt-3">
          <div class="card border rounded d-flex justify-content-center align-items-center">
            <div class="card-body ">
              <h6 class="card-title">Total Rest a payer</h6>
              <p id = "total_payment_montant_id" class="card-text h3">{{number_format($rest , 0, '', ' ')}} MRU</p>
            </div>
          </div>
        </div>
      </div>
    </div>
     

      
      <div class="col col-3 d-flex flex-column">
        <div class="mb-1">
          Année
  </div>
        <select class="selectpicker mr-sm-2" onchange="changeStatsYear()" id="stats_year_select">
          <option value="{{$year->annee}}" selected>{{$year->annee}}</option>
          @foreach($years as $an)
              @if($an->annee != $year->annee)
                  <option value="{{$an->annee}}">{{$an->annee}}</option>
              @endif
          @endforeach
       </select>
       {{-- <span class="bg-warning"> {{cal_days_in_month(CAL_GREGORIAN, $month, $year)}}</span> --}}
       <div class="mb-1 mt-3">
        Mois
</div>
        <select class="selectpicker mr-sm-2" onchange="changeSelectedStatsDate()" id="stats_month_select">
          <option value="{{null}}" @if(null == $month) selected @endif>Tout</option>
          @for ($i = 1; $i <= 12; $i++)
            <option value="{{$i}}" @if($i == $month) selected @endif>{{$i}}</option>
          @endfor
       </select>
       <div class="mb-1 mt-3">
        Jour
</div>
       <select class="selectpicker mr-sm-2" onchange="changeSelectedStatsDate()" id="stats_day_select">
          <option value="{{null}}" @if(null == $day) selected @endif>Tout</option>
       @if($month != null)
       @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year->annee); $i++)
       <option value="{{$i}}" @if($i == $day) selected @endif>{{$i}}</option>
     @endfor
      @endif
        </select>
{{--         
           @if ($month != null && $day != null)
           <div class="d-flex flex-row">
            <button class="btn text-primary mt-3 mr-4" onclick="nextDayOrMonth(false)">&larr;  Jour précédant </button>
            <button class="btn  text-primary mt-3" onclick="nextDayOrMonth()">Jour suivant  &rarr;</button>
            </div>
           @endif
            @if ($month != null && $day == null)
            <div class="d-flex flex-row">
              <button class="btn text-primary mt-3 mr-4" onclick="nextDayOrMonth(false)">&larr;  Mois précédant </button>
              <button class="btn  text-primary mt-3" onclick="nextDayOrMonth()">Mois suivant  &rarr;</button>
              </div>
            @endif --}}
    </div>
    </div>
</div>
@endsection
