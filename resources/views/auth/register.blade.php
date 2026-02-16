@extends('layouts.frontend')
@section('content')
<section class="pt-100 login-register">
<div class="container">
          <div class="row login-register-cover">
            <div class="col-lg-4 col-md-6 col-sm-12 mx-auto">
              <div class="text-center">
                <p class="font-sm text-brand-2">Register </p>
                <h2 class="mt-10 mb-5 text-brand-1">Start for free Today</h2>
			  </div>
<form class="login-register text-start mt-20" action="{{ route('register') }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label class="form-label" for="input-1">Full Name *</label>
        <input class="form-control @error('name') is-invalid @enderror" 
               type="text" name="name" value="{{ old('name') }}" placeholder="Steven Job" required>
        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="input-2">Email *</label>
        <input class="form-control @error('email') is-invalid @enderror" 
               type="email" name="email" value="{{ old('email') }}" placeholder="stevenjob@gmail.com" required>
        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="input-4">Password *</label>
        <input class="form-control @error('password') is-invalid @enderror" 
               type="password" name="password" placeholder="************" required>
        @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="input-5">Re-Password *</label>
        <input class="form-control" type="password" name="password_confirmation" placeholder="************" required>
    </div>

    <div class="login_footer form-group d-flex justify-content-between">
        <label class="cb-container">
            <input type="checkbox" name="terms" required><span class="text-small">Agree our terms and policy</span><span class="checkmark"></span>
        </label>
        <a class="text-muted" href="#">Learn more</a>
    </div>

    <div class="form-group">
        <button class="btn btn-brand-1 hover-up w-100" type="submit">Submit & Register</button>
    </div>
</form>
</div>
</div>
</div>
</section>

@endsection