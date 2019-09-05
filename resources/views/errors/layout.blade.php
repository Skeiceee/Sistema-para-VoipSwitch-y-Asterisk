<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('img/favicon.png') }}" sizes="192x192">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="h-100 d-flex flex-column justify-content-center align-items-center">
        {{-- <img src="{{ asset('img/logo.png') }}" alt="Vozdigital"> --}}
        <div class="d-flex flex-column justify-content-center align-items-center bd-highlight mb-3">
                <span class="text-muted mb-3" style="font-size: 30px;">@yield('code')</span>
                <div class="bd-highlight border-bottom" style="width: 250px; border-width: 2px !important;"></div>
                <span class="text-muted mt-3" style="font-size: 30px;">@yield('message')</span>
        </div>
    </div>
</body>