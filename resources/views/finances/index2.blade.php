@extends('layout')
@section('page-title')
    {{ trans('text_me.finances') }}
@endsection

@section('module-css')

@endsection
@section('module-js')

@endsection
@section('page-content')

    <div class="row" id="editForm">
        <div class="col-lg-12">
            @if (session('successmsg') || session('errormsg'))
                <div class="alert alert-{{(session('successmsg'))?'success':'danger'}} alert-dismissible">
                    {{ session('successmsg') }}{{ session('errormsg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            @endif
                <div class="card p-0 m-0" >
                    <div class="card-header p-0 m-0">
                        <div class="form-row p-0 m-0" >
                            <div class="col-md-8 float-label-control p-0 m-0">
                                <h5>{{ $bidgetinitial->libelle }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="" action="{{ url('finances/edit') }}" method="post">
                            {{ csrf_field() }}
                           <div class="bs-example" >
                               <div  class="accordion" id="accordion">
                                    {!! $html  !!}
                                </div>
                            </div>

                            <input name="bidget_id" id="bidget_id" value="{{$bidgetinitial->id}}" type="hidden">
                            <div class="col-md-12">
                                <div class="text-right">
                                    <button class="btn btn-success btn-icon-split" onclick="updateBidgetInitial(this)" container="editForm">
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
                        </form>

                   </div>
                </div>
    </div>
@endsection
