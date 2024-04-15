@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-4 d-none d-flex justify-content-center align-items-center">
                 {{-- @if(file_exists('img/'.env('LOGO_COMMUNE')))
                    <img class="" style="width: 100%;height: 100%" src="{{ URL::asset('img/'.env('LOGO_COMMUNE')) }}">
                  @else--}}
                      <img  style="width: 240px;height: 240px" src="{{ URL::asset('img/logo_rim.jpeg') }}" >
                  {{--@endif--}}


              </div>

              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">{{ trans('text.authentification') }}</h1>
                  </div>
                  <form class="user" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="control-label">{{ trans('text.login') }}</label>
                        <div class="">
                            <input id="email" type="text" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="control-label">{{ trans('text.mot_passe') }}</label>
                        <div class="">
                            <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-user btn-primary btn-block">
                            {{ trans('text.connexion') }}
                        </button>
                    </div>
                  </form>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
