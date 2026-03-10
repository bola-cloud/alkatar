@extends('front.layouts.newdesign_auth_layout')

@section('title', __('Verify Your Email'))

@section('content')

  <div class="macbook-pro">
    <div class="container-fluid h-100">
      <div class="row h-100">
        <!-- Left Side - same as register for consistency -->
        <div class="col-lg-6 col-md-12 left-section position-relative p-0 d-none d-lg-block">
          <img class="rectangle img-fluid w-100 h-100" src="https://c.animaapp.com/mhxjwoj8jP8UI3/img/rectangle-222.png"
            alt="Background" />
          <div class="group-2"></div>
          <p class="hungry-check-out">
            <span class="text-wrapper-8">{{ __('Verify your identity to get the freshest products delivered.') }}</span>
          </p>
        </div>

        <!-- Right Side - OTP Form -->
        <div class="col-lg-6 col-md-12 right-section d-flex align-items-center">
          <div class="content-container">
            <div class="header-section text-center mb-4">
              <h1 class="text-wrapper" style="font-size:28px;">{{ __('Verify Email') }}</h1>
              <p class="span">{{ __('Enter the 6-digit code sent to') }} <strong>{{ $email }}</strong></p>
            </div>

            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('user.verify.email.post') }}" class="signup-form">
              @csrf

              <div class="text-field mb-3">
                <label class="label" for="otp">{{ __('OTP Code') }}</label>
                <input type="text" id="otp" name="otp" 
                  class="form-control text-field-2 @error('otp') is-invalid @enderror" 
                  placeholder="000000" maxlength="6" required autofocus autocomplete="off" />
                @error('otp') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="frame mb-3 text-center">
                <button type="submit" class="sign-UP-wrapper">
                  <div class="sign-UP">{{ __('Verify') }}</div>
                </button>
              </div>

              <p class="do-you-have-an text-center mt-3">
                <span class="text-wrapper-6">{{ __('Didn\'t receive the code?') }} </span>
                <a href="{{ route('user.resend.otp') }}" class="text-wrapper-7">{{ __('Resend OTP') }}</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
