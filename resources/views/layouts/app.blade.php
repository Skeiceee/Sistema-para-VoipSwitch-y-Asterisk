<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Vozdigital') }}</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('img/favicon.png') }}" sizes="192x192">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.extend.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/hamburgers.css') }}" rel="stylesheet">
    @stack('css')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm py-0" style="height: 56px">
            <div class="container">
                @auth
                    <ul class="navbar-nav mr-4">
                        <div id="menu_toggle" class="tray" style="width: 40px; height: 40px;">
                            <div id="hamburger" class="hamburger hamburger--squeeze p-0 pt-2 ">
                                <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                                </div>
                            </div>
                        </div>
                    </ul>
                @endauth
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Vozdigital" style="height: 40px;">
                </a>
                @guest
                    <div class="navbar">
                        <ul class="navbar-nav" style="flex-direction: row !important;">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}" style="padding-right: .5em; padding-left: .5em;">Iniciar sesión</a>
                            </li>
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}" style="padding-right: .5em; padding-left: .5em;">Registrarse</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                @endguest
                @auth
                    <div class="d-flex align-items-center pr-0">
                        @if (is_null(Auth::user()->picture))
                        <div class="no-avatar d-sm-block d-md-none ml-2 align-self-start order-3">
                            <span class="initials">{{ Auth::user()->initials() }}</span>
                        </div>
                        @else
                            <div class="avatar d-sm-block d-md-none ml-2 align-self-start order-3">
                                <img src="{{ url(Auth::user()->picture) }}">
                            </div>
                        @endif
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</span>
                                    @if (is_null(Auth::user()->picture))
                                        <div class="no-avatar d-none d-md-inline ml-2 align-self-start order-3">
                                            <span class="initials">{{ Auth::user()->initials() }}</span>
                                        </div>
                                    @else
                                        <div class="avatar d-none d-md-inline ml-2 align-self-start order-3">
                                            <img src="{{ url(Auth::user()->picture) }}">
                                        </div>
                                    @endif
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('configuration.index') }}">
                                        Configuración
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </nav>
        <main>
            @include('layouts.menu')
            @yield('content')
        </main>
    </div>
    @stack('scripts')
    <script src="{{ asset('js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
</body>
</html>
