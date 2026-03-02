@extends('front.layouts.newdesign_auth_layout')

@section('title', 'Forgot Password')

@section('content')

    <div class="macbook-pro">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <!-- Left Side - decorative large image/text -->
                <div class="col-lg-6 col-md-12 left-section position-relative p-0 d-none d-lg-block">
                    <img class="rectangle img-fluid w-100 h-100"
                        src="https://c.animaapp.com/mhxjwoj8jP8UI3/img/rectangle-222.png" alt="Background" />
                    <div class="group-2"></div>
                    <p class="hungry-check-out">
                        <span
                            class="text-wrapper-8">{{ __('Reset Your Password') }}<br />{{ __('To Continue Shopping') }}</span>
                    </p>
                </div>

                <!-- Right Side - Form -->
                <div class="col-lg-6 col-md-12 right-section d-flex align-items-center">
                    <div class="content-container">
                        <div class="header-section text-left mb-4">
                            <h1 class="text-wrapper" style="font-size:28px;"><span
                                    style="color:#929f1a;">{{ __('Forgot') }}</span> {{ __('Password') }}</h1>
                            <p class="text-muted">
                                {{ __('Enter your email address and we\'ll send you a link to reset your password.') }}</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('forget.password.post') }}" class="signup-form">
                            @csrf

                            <div class="text-field mb-4">
                                <label class="label" for="email">{{ __('Email Address') }}</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control text-field-2 @error('email') is-invalid @enderror" required />
                                @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="frame mb-3 text-center">
                                <button type="submit" class="sign-UP-wrapper">
                                    <div class="sign-UP">{{ __('SEND RESET LINK') }}</div>
                                </button>
                            </div>

                            <p class="do-you-have-an text-center mt-3">
                                <span>{{ __('Remembered your password?') }} </span>
                                <a href="{{ route('login') }}" class="text-wrapper-7"> {{ __('sign in') }}</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection