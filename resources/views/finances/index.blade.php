@extends('layout')
@php
    $lib=trans('text_me.lib');
@endphp
@section('page-title')
    {{ trans('text_me.finances') }}  {{ trans('text_me.de_la') }} {{ trans('text_me.commune') }}  {{ $commune->$lib }}
@endsection
@section('page-content')
    <div class="row">
        <div class="col-lg-12">
            @if (session('successmsg') || session('errormsg'))
                <div class="alert alert-{{(session('successmsg'))?'success':'danger'}} alert-dismissible">
                    {{ session('successmsg') }}{{ session('errormsg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
            @php
                $div_aucun='display : none;';
                $div_bgd_initial='display : none;';
                $div_plus_bgd='display : none;';
                $etat_budgetvalide='display : none;';
                $etat_budgetdevalide='display : none;';
                $etat_budgetenregistre='display : block;';
            @endphp
            @if($etat_bdg == 'aucun')
                {{--@include('finances.add')--}}
                @php
                    $div_aucun='display = block;';
                @endphp
            @section('top-page-btn')
                @if(Auth::user()->hasAccess(4,2))
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="openFormAddInModal('finances')"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.add_budget_initial")}}</a>
                @endif
                @endsection
            @elseif($etat_bdg == 'bgd_initial' && $ref_etat_budget_id != 3)
                @php
                    $div_bgd_initial='display = block;';
                @endphp
                @if($ref_etat_budget_id == 1)
                    @php
                        $etat_budgetvalide='display : block;';

                    @endphp

                @endif
                @if($ref_etat_budget_id == 2)
                    @php
                        $etat_budgetdevalide='display : block;';
                        $etat_budgetenregistre='display : none;';
                        $etat_budgetvalide='display : none;';
                    @endphp
            @section('top-page-btn')
                @if(Auth::user()->hasAccess(4,2))
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="openFormAddInModal('finances')"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.add_budget")}}</a>
                @endif
                @endsection
            @endif
            @elseif($etat_bdg == 'plus_budgets' && $ref_etat_budget_id != 3)
                @php
                    $div_plus_bgd='display = block;';
                @endphp
                @if($ref_etat_budget_id == 1)
                    @php
                        $etat_budgetvalide='display : block;';

                    @endphp

                @endif
                @if($ref_etat_budget_id == 2)
                    @php
                        $etat_budgetdevalide='display : block;';
                        $etat_budgetenregistre='display : none;';
                        $etat_budgetvalide='display : none;';
                    @endphp
            @section('top-page-btn')
                @if(Auth::user()->hasAccess(4,2))
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="openFormAddInModal('finances')"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.add_budget")}}</a>
                @endif
                @endsection
            @endif
            @endif

            @php
                $div_initialcolture='display : none;';
            $msg_cloure='';
            @endphp
            @if($ref_etat_budget_id == 3)
            @section('top-page-btn')
                @if(Auth::user()->hasAccess(4,2))
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="openFormAddInModal('finances')"><i class="fas fa-plus fa-sm text-white-50"></i> {{trans("text_me.add_budget_initial")}} {{$annee+1}}</a>
                 @endif
                @endsection
            @php
                $div_initialcolture='display : block;';
                echo '<div class="alert alert-warning " role="alert" role="alert"><h4>'.trans('text_me.cloture_bdg').'</h4></div>';
            @endphp
            @endif
               @if($budgets!='')
                    @foreach($budgets as $budget)
                        @if( $budget->ref_type_budget_id ==3)
                        <div class="alert alert-warning form-row" role="alert" role="alert">
                            <div class="col-md-10 form-group m-0">{{$budget->$lib}}</div>
                        <div class="col-md-2 form-group m-0 pr-2 text-right">
                            <button type="button" class="btn btn-sm btn-dark" onclick="visualiserBudget({{$budget->id}})" data-toggle="tooltip" data-placement="top" title="{{trans('text.visualiser')}}"><i class="fa fa-fw fa-eye"></i></button>
                        </div>
                        </div>
                            @endif
                    @endforeach
                @endif

            <div id="aucun_bdg" style="{{$div_aucun}}">
                <div class="alert alert-warning " role="alert" role="alert">
                    <h4>{{trans('text_me.aucun_bdg')}}{{$annee}} </h4>
                </div>
            </div>
            <div id="bgd_initial" style="{{$div_bgd_initial}}">
                <div class="card" id="">
                    <div class="card-header">{{$libelle_budget}}
                        @if(Auth::user()->hasAccess(4,2))
                            <button  type="button" class="btn btn-link" onclick="getbudget({{$budget_id}})" ><i  class="fa fa-edit"></i></button>
                        @endif
                        @if(Auth::user()->hasAccess(4,4))
                            <div class="btn-group float-right">
                                <form role="form"  id="formbdg" name="formbdg" class=""  method="get" >
                                    {{ csrf_field() }}
                                    <button type="button" onclick="excelBudget({{$budget_id}},1)"  class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm text-white">
                                        <i class="fas fa-file-excel"></i> {{ trans('text_me.exporter')}} EXCEL
                                    </button> &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button" onclick="pdfBudget({{$budget_id}},1)" class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm text-white">
                                        <i class="fas fa-file-pdf"></i> Exopter en PDF
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    <div class="card-body" id="editForm2">
                        <form class="" action="{{ url('finances/edit') }}" method="post">
                            {{ csrf_field() }}
                            <div class="accordion m-0" id="accordion">
                                @if($etat_bdg == 'bgd_initial')
                                    {!! $html !!}
                                @endif
                            </div>
                            <input name="bidget_id" id="bidget_id" value="{{$budget_id}}" type="hidden">
                            <input name="type_budget" id="type_budget" value="1" type="hidden">
                            <div class="col-md-12 text-right" style="{{$div_bgd_initial}}">
                                @if(Auth::user()->hasAccess(4,2))
                                    <button class="btn btn-success btn-icon-split float-right m-1" onclick="updateBidget(this)" container="editForm2" style="{{ $etat_budgetenregistre }}">
                                                    <span class="icon text-white-50">
                                                        <i class="main-icon fas fa-save"></i>
                                                        <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                                        <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                    </span>
                                        <span class="text">{{ trans('text.enregistrer') }}</span>
                                    </button>&nbsp;
                                @endif
                                @if(Auth::user()->hasAccess(4,3))
                                    <button class="btn btn-dark btn-icon-split float-right m-1" id="btn_valider" onclick="valideBidget(this,{{$budget_id}})" container="editForm2" style="@php echo $etat_budgetvalide; @endphp" >
                                                        <span class="icon text-white-50">
                                                            <i class="main-icon text-white fa fa-check" id="main-icon"></i>
                                                            <span class="spinner-border  spinner-border-sm" style="display:none" id="spinner-border" role="status" aria-hidden="true"></span>
                                                            <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                        </span>
                                        <span class="text-white">{{ trans('text.validation') }}</span>
                                    </button>
                                @endif
                                @if(Auth::user()->hasAccess(4,3))
                                    <button class="btn btn-success btn-icon-split float-left  m-3" onclick="cloturerBidget(this,{{$annee}})" container="editForm2" style="{{$etat_budgetdevalide}}">
                                                            <span class="icon text-white-50">
                                                                <i class="main-icon fas fa-folder-minus"></i>
                                                                <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                                                <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                            </span>
                                        <span class="text">{{ trans('text_me.cloture') }}</span>
                                    </button>
                                @endif
                                @if(Auth::user()->hasAccess(4,3))
                                    <button class="btn btn-dark btn-icon-split float-right " onclick="devalideBidget(this,{{$budget_id}})" container="editForm2" style="@php echo $etat_budgetdevalide; @endphp">
                                                        <span class="icon text-white-50">
                                                            <i class="main-icon fas fa-arrow-alt-circle-left"></i>
                                                            <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                                            <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                        </span>
                                        <span class="text">{{ trans('text_me.devalidation') }}</span>
                                    </button>
                                @endif
                                <div id="form-errors" class="text-left"></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-2" style="{{$div_initialcolture}}">
                    <div class="card-header">

                        {{ trans('text_me.budgets') }} {{$annee}}
                    </div>
                    <div class="card-body bg-transparent">
                        @foreach($budgets as $budget)
                            @if($budget->id != $budget_id and $budget->ref_type_budget_id !=3)
                                <div class="card m-0 p-0bg-transparent">
                                    <div class="card-header  m-0 p-0 bg-transparent">
                                        <div class="form-row m-0 p-0" >
                                            <div class="col-md-10 form-group m-0 p-0">{{ $budget->$lib }}</div>
                                            <div class="col-md-2 form-group m-0 pr-2 text-right">
                                                @if(Auth::user()->hasAccess(4,1))
                                                    <button type="button" class="btn btn-sm btn-dark" onClick="visualiserBudget({{$budget->id}})" data-toggle="tooltip" data-placement="top" title="{{trans('text.visualiser')}}"><i class="fa fa-fw fa-eye"></i></button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($ref_etat_budget_id ==3)
                                <div class="card m-0 p-0bg-transparent">
                                    <div class="card-header  m-0 p-0 bg-transparent">
                                        <div class="form-row m-0 p-0" >
                                            <div class="col-md-10 form-group m-0 p-0">{{ $budget->$lib }}</div>
                                            <div class="col-md-2 form-group m-0 pr-2 text-right">
                                                @if(Auth::user()->hasAccess(4,1))
                                                    <button type="button" class="btn btn-sm btn-dark" onClick="visualiserBudget({{$budget->id}})" data-toggle="tooltip" data-placement="top" title="{{trans('text.visualiser')}}"><i class="fa fa-fw fa-eye"></i></button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div id="plus_bgd" style="{{$div_plus_bgd}}" >
                <div class="card ">
                    <div class="card-header">
                        {{ trans('text_me.budget_encours') }} {{$libelle_budget}}
                        <button  type="button" class="btn btn-link" onclick="getbudget({{$budget_id}})" ><i  class="fa fa-edit"></i></button>
                        <div class="btn-group float-right">
                            <form role="form"  id="formbdg1" name="formbdg1" class=""  method="get" >
                                {{ csrf_field() }}
                                @if(Auth::user()->hasAccess(4,4))
                                    <button type="button"  onclick="excelBudget({{$budget_id}},2)"  class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm text-white">
                                        <i class="fas fa-file-excel"></i> {{ trans('text_me.exporter')}} EXCEL
                                    </button> &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button" onclick="pdfBudget({{$budget_id}},2)" class="d-none d-sm-inline-block btn btn-sm {{$module->bg_color}} shadow-sm text-white">
                                        <i class="fas fa-file-pdf"></i> Exopter en PDF
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="card-body" id="editForm3">
                        <form class="" action="{{ url('finances/edit') }}" method="post">
                            {{ csrf_field() }}
                            <div  class="accordion m-0" id="accordion">
                                @if($etat_bdg == 'plus_budgets')
                                    {!! $html !!}
                                @endif
                            </div>
                            <input name="bidget_id" id="bidget_id" value="{{$budget_id}}" type="hidden">
                            <input name="type_budget" id="type_budget" value="2" type="hidden">
                            <div class="col-md-12 text-right" style="{{$div_plus_bgd}}">
                                @if(Auth::user()->hasAccess(4,2))
                                    <button class="btn btn-success btn-icon-split float-right m-1" onclick="updateBidget(this)" container="editForm3" style="{{ $etat_budgetenregistre }}">
                                                <span class="icon text-white-50">
                                                    <i class="main-icon fas fa-save"></i>
                                                    <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                                    <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                </span>
                                        <span class="text">{{ trans('text.enregistrer') }}</span>
                                    </button>
                                @endif
                                @if(Auth::user()->hasAccess(4,3))
                                    <button class="btn btn-dark btn-icon-split float-right m-1" id="btn_valider1" onclick="valideBidget(this,{{$budget_id}})" container="editForm3" style="{{ $etat_budgetvalide}}" >
                                                    <span class="icon text-white-50">
                                                        <i class="main-icon text-white fa fa-check" id="main-icon"></i>
                                                        <span class="spinner-border  spinner-border-sm" style="display:none" id="spinner-border" role="status" aria-hidden="true"></span>
                                                        <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                    </span>
                                        <span class="text-white">{{ trans('text.validation') }} </span>
                                    </button>
                                @endif
                                @if(Auth::user()->hasAccess(4,3))
                                    <a class="btn btn-success btn-icon-split float-left mb-3" onclick="cloturerBidget(this,{{$annee}})" container="editForm3" style="{{$etat_budgetdevalide}}">
                                                            <span class="icon text-white-50">
                                                                <i class="main-icon fas fa-folder-minus"></i>
                                                                <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                                                <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                            </span>
                                        <span class="text">{{ trans('text_me.cloture') }}</span>
                                    </a>
                                @endif
                                @if(Auth::user()->hasAccess(4,3))
                                    <a class="btn btn-dark btn-icon-split float-right mb-3" onclick="devalideBidget(this,{{$budget_id}})" container="editForm3" style="{{$etat_budgetdevalide}}">
                                                    <span class="icon text-white-50">
                                                        <i class="main-icon fas fa-arrow-alt-circle-left"></i>
                                                        <span class="spinner-border spinner-border-sm" style="display:none" role="status" aria-hidden="true"></span>
                                                        <i class="answers-well-saved text-success fa fa-check" style="display:none" aria-hidden="true"></i>
                                                    </span>
                                        <span class="text">{{ trans('text_me.devalidation') }}</span>
                                    </a>
                                @endif
                                <div id="form-errors" class="text-left"></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header">
                        <div class="form-row m-0 p-0" >
                            <div class="col-md-12 form-group m-0 p-0"> {{ trans('text_me.budgets') }} {{$annee}}</div>

                        </div>
                    </div>
                    <div class="card-body bg-transparent">
                        @foreach($budgets as $budget)
                            @if($budget->id != $budget_id and $budget->ref_type_budget_id !=3)
                                <div class="card m-0 p-0 bg-transparent">
                                    <div class="card-header  m-0 p-0 bg-transparent">
                                        <div class="form-row m-0 p-0" >
                                            <div class="col-md-10 form-group m-0 p-0">{{ $budget->$lib }}</div>
                                            <div class="col-md-2 form-group m-0 pr-2 text-right">
                                                <button type="button" class="btn btn-sm btn-dark" onclick="visualiserBudget({{$budget->id}})" data-toggle="tooltip" data-placement="top" title="{{trans('text.visualiser')}}"><i class="fa fa-fw fa-eye"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($ref_etat_budget_id ==3)
                                <div class="card m-0 p-0bg-transparent">
                                    <div class="card-header  m-0 p-0 bg-transparent">
                                        <div class="form-row m-0 p-0" >
                                            <div class="col-md-10 form-group m-0 p-0">{{ $budget->$lib }}</div>
                                            <div class="col-md-2 form-group m-0 pr-2 text-right">
                                                <button type="button" class="btn btn-sm btn-dark" onClick="visualiserBudget({{$budget->id}})" data-toggle="tooltip" data-placement="top" title="{{trans('text.visualiser')}}"><i class="fa fa-fw fa-eye"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div >
                <div class="card bg-transparent  border-0" >
                    <div class="card-header bg-transparent" id="headinghistorique">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapsehistorique">
                            <i class="fa fa-plus"></i> {{ trans('text_me.archivesbdg') }}
                        </button>
                    </div>
                    <div id="collapsehistorique" class="collapse" aria-labelledby="headinghistorique" data-parent="#collapsehistorique" class="p-0 m-0">
                        <div class="card-body pl-2 pt-0 ">
                            @foreach($anciens_bdg as $anciens_bdg)
                                <div class="card-header m-0  bg-transparent">
                                    @if($anciens_bdg->annee !=$annee )
                                        <div class="form-row m-0 " >
                                            <div class="col-md-10 form-group m-0 p-0">{{ $anciens_bdg->annee }}</div>
                                            <div class="col-md-2 form-group m-0 pr-2 text-right">
                                                @if(Auth::user()->hasAccess(4,1))
                                                    <button type="button" class="btn btn-sm btn-warning" onClick="openOldbudget({{$anciens_bdg->annee}})" data-toggle="tooltip" data-placement="top" title="{{trans('text.visualiser')}}"><i class="fa fa-fw fa-eye"></i></button>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

