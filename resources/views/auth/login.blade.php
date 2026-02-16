@extends('layouts.frontend')
@section('content')
<section class="pt-100 login-register">
<div class="container">
          <div class="row login-register-cover">
            <div class="col-lg-4 col-md-6 col-sm-12 mx-auto">
              <div class="text-center">
                <p class="font-sm text-brand-2">Sig In </p>
                <h2 class="mt-10 mb-5 text-brand-1">Sign In to continue</h2>
			  </div>

<form class="login-register text-start mt-20" action="{{ route('userlogin') }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label class="form-label" for="input-email">Email address *</label>
        <input class="form-control @error('email') is-invalid @enderror" 
               id="input-email" type="email" name="email" 
               value="{{ old('email') }}" required placeholder="stevenjob@gmail.com">
        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="input-password">Password *</label>
        <input class="form-control @error('password') is-invalid @enderror" 
               id="input-password" type="password" name="password" 
               required placeholder="************">
    </div>

    <div class="login_footer form-group d-flex justify-content-between">
        <label class="cb-container">
            <input type="checkbox" name="remember"><span class="text-small">Remember me</span><span class="checkmark"></span>
        </label>
        <a class="text-muted" href="#">Forgot password?</a>
    </div>

    <div class="form-group">
        <button class="btn btn-brand-1 hover-up w-100" type="submit" name="login">Login</button>
    </div>

    <div class="text-muted text-center">Don't have an Account? <a href="{{ route('register') }}">Sign up</a></div>
</form>

</div>
</div>
</div>
</section>

@endsection