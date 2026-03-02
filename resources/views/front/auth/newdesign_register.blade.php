@extends('front.layouts.newdesign_auth_layout')

@section('title', 'Register')

@section('content')

  <div class="macbook-pro">
    <div class="container-fluid h-100">
      <div class="row h-100">
        <!-- Left Side - decorative image & text (keeps new design visuals) -->
        <div class="col-lg-6 col-md-12 left-section position-relative p-0 d-none d-lg-block">
          <img class="rectangle img-fluid w-100 h-100" src="https://c.animaapp.com/mhxjwoj8jP8UI3/img/rectangle-222.png"
            alt="Background" />
          <div class="group-2"></div>
          <p class="hungry-check-out">
            <span class="text-wrapper-8">Hungry? Check Out Fresh &amp; Healthy<br />Organic Food</span>
          </p>
        </div>

        <!-- Right Side - Form -->
        <div class="col-lg-6 col-md-12 right-section d-flex align-items-center">
          <div class="content-container">
            <div class="header-section text-center mb-4">
              <h1 class="text-wrapper" style="font-size:28px;">Sign Up</h1>
              <p class="span">Create an account to get exclusive offers</p>
            </div>

            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('user.sign.up.post') }}" class="signup-form">
              @csrf

              <div class="text-field mb-3">
                <label class="label" for="name">{{ __('Name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                  class="form-control text-field-2 @error('name') is-invalid @enderror" required />
                @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="text-field mb-3">
                <label class="label" for="email">{{ __('Email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                  class="form-control text-field-2 @error('email') is-invalid @enderror" required />
                @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="text-field mb-3">
                <label class="label" for="phone">{{ __('Mobile Number') }}</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                  class="form-control text-field-2 @error('phone') is-invalid @enderror" required />
                @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="text-field mb-3">
                <label class="label-2" for="password">{{ __('Password') }}</label>
                <input type="password" id="password" name="password"
                  class="form-control text-field-2 @error('password') is-invalid @enderror" required />
                @error('password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="text-field mb-3">
                <label class="label-2" for="confirm_password">{{ __('Confirm Password') }}</label>
                <input type="password" id="confirm_password" name="confirm_password"
                  class="form-control text-field-2 @error('confirm_password') is-invalid @enderror" required />
                @error('confirm_password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="frame mb-3 text-center">
                <button type="submit" class="sign-UP-wrapper">
                  <div class="sign-UP">{{ __('Sign Up') }}</div>
                </button>
              </div>

              <p class="do-you-have-an text-center mt-3">
                <span class="text-wrapper-6">Do you have an account? </span>
                <a href="{{ route('login') }}" class="text-wrapper-7">Sign in</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection