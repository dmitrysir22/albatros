@extends('layouts.frontend')

@section('content')
<section class="pt-100 login-register">
    <div class="container">
        <div class="row login-register-cover">
            <div class="col-lg-5 col-md-8 col-sm-12 mx-auto">
                <div class="text-center">
                    <img src="{{ asset('assets/imgs/theme/icons/mail-send.svg') }}" alt="Email" class="mb-20" style="width: 80px;">
                    <p class="font-sm text-brand-2">Verify Your Email</p>
                    <h2 class="mt-10 mb-15 text-brand-1">Check your inbox!</h2>
                    <p class="font-md color-text-paragraph-2 mb-30">
                        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success text-center mb-30" role="alert">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif

                <div class="card border-0 shadow-sm p-30 bg-light">
                    <div class="row mt-10">
                        <div class="col-12 text-center">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-brand-1 hover-up w-100">
                                    Resend Verification Email
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="row mt-20">
                        <div class="col-12 text-center">
                            <form method="POST" action="{{ route('userlogout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted text-small">
                                    Logout and try another email
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-30">
                    <a href="/" class="text-brand-2 font-sm">
                        <i class="fi-rr-arrow-small-left"></i> Back to Home Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection