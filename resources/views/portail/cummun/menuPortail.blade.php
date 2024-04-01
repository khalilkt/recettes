@if(env('DCS_APP')==1)
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow  navbar-shadow">
    <div class="container-fluid">
        <div class="navbar-header">

          <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 18px;text-transform: uppercase;color: #379789;"> Système de suivi et d'échange des données environnementales</a>
        </div>
        {{--}}
        <ul class="nav navbar-top-links navbar-right">
            @if (Auth::guest())
            <li>
                <a href="#" class="top-nav-link" data-toggle="modal"
                        data-target="#loginModal">{{ trans('text.connecter') }}
                </a>
            </li>
            @else
                <div class="navbar-container content">
                    <div class="collapse navbar-collapse" id="navbar-mobile">

                        <ul class="nav navbar-nav float-right">
                            <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700">{{ (Auth::user()) ? Auth::user()->name : ''}}</span><span class="avatar avatar-online"><img src="{{ URL::asset('vendor/modern/images/portrait/small/avatar-s-17.png') }}" alt="avatar"><i></i></span></a>
                                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="#"><i class="material-icons">person_outline</i> Edit Profile</a>
                                    <div class="dropdown-divider"></div><a class="dropdown-item" href="{{ url('logout') }}"><i class="material-icons">power_settings_new</i> @lang('text.deconnexion') </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            <li class="dropdown">
                <a class="dropdown-toggle top-nav-link" href="#" id="dropdownMenu1"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    {{ Auth::user()->name }}
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    @if (Auth::user()->hasAccess(0,[1,2]))
                        <li><a href="{{ url('dashboard') }}">{{ trans('text.panneau') }}</a></li>
                    @elseif(Auth::user()->hasAccess(0,[5]))
                        <li><a href="{{ url('fichescommunales') }}">Fiches communales</a></li>
                    @elseif(Auth::user()->hasAccess([2,3,4,5],[3,8]))
                        <li><a href="{{ url('Compdec/formations') }}">Formations</a></li>
                    @elseif(Auth::user()->hasAccess(1,[8]))
                        <li><a href="{{ url('Compdec/portail/formations') }}">Formations</a></li>
                    @endif
                    <li>
                        <a href="{{ url('/logout') }}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                            {{ trans('text.deconnecter') }}
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            @endif
            <!-- /.dropdown -->
        </ul>--}}
        @php
        if(!empty($errors->all()))
        echo '<span class="help-block" style="color:red">'.trans("text.erreur_auth").'</span>';
        @endphp
    </div>
    </nav>
    @endif
