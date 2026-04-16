<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.sifec-strip-flash-query')
    <title>@yield('title', config('app.name', 'SIFEC'))</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
    <link href="{{ asset('tpl/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    @include('partials.flasher-assets-head')
    @stack('styles')
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <header class="border-bottom bg-white py-2 mb-3">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="text-decoration-none fw-semibold text-body" href="{{ url('/') }}">{{ config('app.name', 'SIFEC') }}</a>
            @if (Route::has('login'))
                <a class="small" href="{{ route('login') }}">Connexion</a>
            @endif
        </div>
    </header>

    <main class="container flex-grow-1 pb-4">
        @include('partials.session-flash')
        @yield('content')
    </main>

    <script src="{{ asset('vendor/flasher/jquery.min.js') }}"></script>
    <script src="{{ asset('tpl/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    @stack('scripts')
    @include('partials.flasher-assets-scripts')
    @include('partials.session-toastr')
    @flasher_render
</body>
</html>
