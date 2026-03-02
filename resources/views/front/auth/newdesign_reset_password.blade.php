@extends('front.layouts.newdesign_auth_layout')

@section('title', 'Reset Password')

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
                            class="text-wrapper-8">{{ __('Update Your Password') }}<br />{{ __('To Secure Your Account') }}</span>
                    </p>
                </div>

                <!-- Right Side - Form -->
                <div class="col-lg-6 col-md-12 right-section d-flex align-items-center">
                    <div class="content-container">
                        <div class="header-section text-left mb-4">
                            <h1 class="text-wrapper" style="font-size:28px;"><span
                                    style="color:#929f1a;">{{ __('Reset') }}</span> {{ __('Password') }}</h1>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('reset.password.post') }}" class="signup-form">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="text-field mb-3">
                                <label class="label" for="email">{{ __('Email Address') }}</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control text-field-2 @error('email') is-invalid @enderror" required />
                                @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="text-field mb-3">
                                <label class="label-2" for="password">{{ __('New Password') }}</label>
                                <div class="position-relative">
                                    <input type="password" id="password" name="password"
                                        class="form-control text-field-2 @error('password') is-invalid @enderror"
                                        required />
                                    <div class="password-hide-see" onclick="togglePassword('password')">
                                        <img class="img" src="https://c.animaapp.com/mhxjwoj8jP8UI3/img/icon.svg"
                                            alt="Toggle" />
                                        <div class="text-wrapper-4">Hide</div>
                                    </div>
                                </div>
                                @error('password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="text-field mb-4">
                                <label class="label-2" for="password_confirmation">{{ __('Confirm New Password') }}</label>
                                <div class="position-relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control text-field-2" required />
                                    <div class="password-hide-see" onclick="togglePassword('password_confirmation')">
                                        <img class="img" src="https://c.animaapp.com/mhxjwoj8jP8UI3/img/icon.svg"
                                            alt="Toggle" />
                                        <div class="text-wrapper-4">Hide</div>
                                    </div>
                                </div>
                            </div>

                            <div class="frame mb-3 text-center">
                                <button type="submit" class="sign-UP-wrapper">
                                    <div class="sign-UP">{{ __('RESET PASSWORD') }}</div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            var p = document.getElementById(id);
            if (p.type === 'password') { p.type = 'text'; } else { p.type = 'password'; }
        }
    </script>

@endsection