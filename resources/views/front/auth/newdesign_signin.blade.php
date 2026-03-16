@extends('front.layouts.newdesign_auth_layout')

@section('title', __('Sign In'))

@section('content')

  <div class="macbook-pro">
    <div class="container-fluid h-100">
      <div class="row h-100">
        <!-- Left Side - decorative large image/text -->
        <div class="col-lg-6 col-md-12 left-section position-relative p-0 d-none d-lg-block">
          <img class="rectangle img-fluid w-100 h-100" src="https://c.animaapp.com/mhxjwoj8jP8UI3/img/rectangle-222.png"
            alt="{{ __('Background') }}" />
          <div class="group-2"></div>
          <p class="hungry-check-out">
            <span class="text-wrapper-8">{{ __('Sign In To Your hi speed Account For') }}<br />{{ __('More Offers And Exclusive points') }}</span>
          </p>
        </div>

        <!-- Right Side - Form -->
        <div class="col-lg-6 col-md-12 right-section d-flex align-items-center">
          <div class="content-container">
            <div class="header-section text-left mb-4">
              <h1 class="text-wrapper" style="font-size:28px;"><span style="color:#929f1a;">{{ __('Sign In') }}</span> {{ __('To Your hi speed Account For More Offers And Exclusive points') }}</h1>
            </div>

            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('user.sign.in.post') }}" class="signup-form">
              @csrf

              <div class="text-field mb-3">
                <label class="label" for="login_id">{{ __('Email or Mobile Number') }}</label>
                <input type="text" id="login_id" name="login_id" value="{{ old('login_id') }}"
                  class="form-control text-field-2 @error('login_id') is-invalid @enderror" required />
                @error('login_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="text-field mb-3">
                <label class="label-2" for="password">{{ __('Password') }}</label>
                <div class="position-relative">
                  <input type="password" id="password" name="password"
                    class="form-control text-field-2 @error('password') is-invalid @enderror" required />
                  <div class="password-hide-see" onclick="togglePassword()">
                    <img class="img" src="https://c.animaapp.com/mhxjwoj8jP8UI3/img/icon.svg" alt="Toggle" />
                    <div class="text-wrapper-4">{{ __('Hide') }}</div>
                  </div>
                </div>
                @error('password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                <div class="mt-2 text-end">
                  <a href="{{ route('forget.password.get') }}" class="text-decoration-none"
                    style="color: #929f1a; font-size: 14px;">{{ __('Forgot Password?') }}</a>
                </div>
              </div>

              <div class="check-box mb-3">
                <input type="checkbox" id="terms" name="terms" checked
                  style="width:18px;height:18px;margin-right:10px;" />
                <p class="i-want-to-receive">{{ __('I agree to the Terms of Service and acknowledge you\'ve read our Privacy Policy.') }}</p>
              </div>

              <div class="frame mb-3 text-center">
                <button type="submit" class="sign-UP-wrapper">
                  <div class="sign-UP">{{ __('SIGN IN') }}</div>
                </button>
              </div>

              <p class="do-you-have-an text-center mt-3">
                <span>{{ __('Do not have an account?') }} </span>
                <a href="{{ route('user.sign.up') }}" class="text-wrapper-7"> {{ __('sign up') }}</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      var p = document.getElementById('password');
      if (p.type === 'password') { p.type = 'text'; } else { p.type = 'password'; }
    }
  </script>

@endsection