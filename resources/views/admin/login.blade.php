<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend/imgs/template/favicon.svg') }}">
    <link href="{{ asset('backend/css/style.css?version=4.1') }}" rel="stylesheet">
    <title>Login - Albatross Recruit Admin</title>
</head>
<body>
    <main class="main">
        
        <div class="box-content">
            <div class="box-heading">
                <div class="box-title">
                    <h3 class="mb-35">Login</h3>
                </div>
                <div class="box-breadcrumb">
                    <div class="breadcrumbs">
                        <ul>
                            <li><a class="icon-home" href="#">Admin</a></li>
                            <li><span>Login</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="section-box">
                        <div class="container">
                            <div class="panel-white mb-30">
                                <div class="box-padding">
                                    <div class="login-register">
                                        <div class="row login-register-cover pb-250">
                                            <div class="col-lg-4 col-md-6 col-sm-12 mx-auto">
                                                <div class="form-login-cover">
                                                    <div class="text-center">
                                                        <p class="font-sm text-brand-2">Welcome back!</p>
                                                        <p class="font-sm text-muted mb-30">Access to the site management.</p>
                                                    </div>

                                                    <form class="login-register text-start mt-20" method="POST" action="{{ route('login') }}">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label class="form-label" for="email">Username or Email address *</label>
                                                            <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="admin@albatross.com">
                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="form-label" for="password">Password *</label>
                                                            <input class="form-control" id="password" type="password" name="password" required placeholder="************">
                                                        </div>

                                                        <div class="login_footer form-group d-flex justify-content-between">
                                                            <label class="cb-container">
                                                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                                <span class="text-small">Remember me</span>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <a class="text-muted" href="#">Forgot Password</a>
                                                        </div>

                                                        <div class="form-group">
                                                            <button class="btn btn-brand-1 hover-up w-100" type="submit" name="login">Login</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer mt-20">
                <div class="container">
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-25 text-center text-md-start">
                                <p class="font-sm color-text-paragraph-2">© {{ date('Y') }} - <a class="color-brand-2" href="#">Albatross Recruit </a> Dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <script src="{{ asset('backend/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('backend/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/js/main.js?v=4.1') }}"></script>
</body>
</html>