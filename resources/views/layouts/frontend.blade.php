<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/imgs/template/favicon.svg') }}">
    
    <link href="{{ asset('assets/css/style.css?version=4.1') }}" rel="stylesheet">
    
    <title>Albatross Recruit - @yield('title')</title>
	@stack('styles')
</head>
<body>
    @include('partials.mobile-menu')

    @include('partials.header')

    <main class="main">
        @yield('content')
    </main>

    
    @include('partials.footer')

@php
    $assetVersion = config('app.asset_version', '1.0');
@endphp

<!-- Modernizr (load early) -->
<script src="{{ asset('assets/js/vendor/modernizr-3.6.0.min.js') }}"></script>

<!-- Deferred scripts -->
<script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}" defer></script>
<script src="{{ asset('assets/js/vendor/jquery-migrate-3.3.0.min.js') }}" defer></script>
<script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/waypoints.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/wow.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/magnific-popup.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/select2.min.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/isotope.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/scrollup.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/swiper-bundle.min.js') }}" defer></script>
<script src="{{ asset('assets/js/plugins/counterup.js') }}" defer></script>
<script src="{{ asset('assets/js/main.js') }}?v={{ $assetVersion }}" defer></script>
<style>@import url('https://fonts.googleapis.com/css2?family=Corben:wght@400;700&display=swap');</style>
	
    @stack('scripts')	
</body>
</html>