@extends('emails.layout')

@section('content')
    <h3>Hello, {{ $name }}!</h3>
    <p>Thank you for registering. To complete your registration and start using our platform, please verify your email address by clicking the button below:</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" class="button">Verify Email Address</a>
    </div>

    <p>If you did not create an account, no further action is required.</p>
    <p>Best regards,<br>The Albatross Team</p>
@endsection