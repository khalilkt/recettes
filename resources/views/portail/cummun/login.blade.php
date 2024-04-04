<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('text.Authentification') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading"></div>
                            <div class="panel-body">
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('login') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email"
                                               class="col-md-4 control-label">{{ trans('text.email') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email"
                                                   value="{{ old('email') }}" required autofocus>

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                  <strong>{{ $errors->first('email') }}</strong>
                                              </span>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" name="connexion_ajax" value="1">

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password"
                                               class="col-md-4 control-label">{{ trans('text.psw') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control"
                                                   name="password" required>

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                  <strong>{{ $errors->first('password') }}</strong>
                                              </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <div class="">
                                                <label>
                                                    <input type="checkbox"
                                                           name="remember" {{ old('remember') ? 'checked' : ''}}> {{ trans('text.rester_connecte') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="clearfix"></div>
                                <div id="form-errors"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success pull-right loguser">{{ trans('text.connexion') }}</button>
                <i style="display:none" class="form-loading fa fa-refresh fa-spin fa-2x fa-fw pull-right"
                   aria-hidden="true"></i>
            </div>
        </div>
    </div>
</div>
<!-- Fin modal Connexion -->

