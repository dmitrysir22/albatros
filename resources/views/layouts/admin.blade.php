<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend/imgs/template/favicon.svg') }}">
    <link href="{{ asset('backend/css/style.css?version=4.1') }}" rel="stylesheet">
    <title>Admin - @yield('title')</title>
</head>
<body>
    @include('admin.partials._header')

    <main class="main">
        @include('admin.partials._sidebar')
        
        <div class="box-content">
            <div class="box-heading">
                <div class="box-title">
                    <h3 class="mb-35">@yield('title')</h3>
                </div>
                <div class="box-breadcrumb">
                    <div class="breadcrumbs">
                        <ul>
                            <li><a class="icon-home" href="{{ route('admin.dashboard') }}">Admin</a></li>
                            <li><span>@yield('title')</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            @yield('content')

            @include('admin.partials._footer')
        </div>
    </main>

    <script src="{{ asset('backend/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('backend/js/main.js?v=4.1') }}"></script>
</body>
</html>