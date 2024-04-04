<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow navbar-semi-light bg-white navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item"><a class="navbar-brand" href="{{ url('/') }}">
                        <h3 class="brand-text">Système de Suivi-Evaluation de la DSCSE</h3>
                    </a></li>
                <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="material-icons mt-50">more_vert</i></a></li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">


                </ul>
                <ul class="nav navbar-nav float-right">
                    <li class="nav-item"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>


                    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" @if(Auth::user()) style="background: rebeccapurple;" @endif href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700">{{ (Auth::user()) ? Auth::user()->name : ''}}</span><span class="avatar"><img src="{{ URL::asset('img/avatar_2x.png') }}" alt="avatar"><i></i></span></a>
                        <div class="dropdown-menu dropdown-menu-right" style="z-index: 4000!important;">
                            @if (Auth::user())
                                  <a  class="dropdown-item" href="{{ url('dashboard') }}"><i class="material-icons">settings</i>{{ trans('text.panneau') }}</a>
                                <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ url('logout') }}"><i class="material-icons">power_settings_new</i> @lang('text.deconnecter') </a>

                              @else
                                <a class="dropdown-item" href="{{ url('login') }}"><i class="material-icons">power_settings_new</i> @lang('text.connecter') </a>

                            @endif
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</nav>
