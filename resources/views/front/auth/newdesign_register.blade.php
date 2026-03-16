@extends('front.layouts.newdesign_auth_layout')

@section('title', __('Register'))

@section('content')

  <div class="macbook-pro">
    <div class="container-fluid h-100">
      <div class="row h-100">
        <!-- Left Side - decorative image & text (keeps new design visuals) -->
        <div class="col-lg-6 col-md-12 left-section position-relative p-0 d-none d-lg-block">
          <img class="rectangle img-fluid w-100 h-100" src="https://c.animaapp.com/mhxjwoj8jP8UI3/img/rectangle-222.png"
            alt="{{ __('Background') }}" />
          <div class="group-2"></div>
          <p class="hungry-check-out">
            <span class="text-wrapper-8">{{ __('Hungry? Check Out Fresh & Healthy') }}<br />{{ __('Organic Food') }}</span>
          </p>
        </div>

        <!-- Right Side - Form -->
        <div class="col-lg-6 col-md-12 right-section d-flex align-items-center">
          <div class="content-container">
            <div class="header-section text-center mb-4">
              <h1 class="text-wrapper" style="font-size:28px;">{{ __('Sign Up') }}</h1>
              <p class="span">{{ __('Create an account to get exclusive offers') }}</p>
            </div>

            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('user.sign.up.post') }}" class="signup-form" id="registration-form">
              @csrf

              <div class="text-field mb-3">
                <label class="label" for="name">{{ __('Name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                  class="form-control text-field-2 @error('name') is-invalid @enderror" required />
                @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="text-field mb-3" id="email-container">
                <label class="label" for="email">
                    <span class="label-text">{{ __('Email') }}</span>
                    <span class="optional-text text-muted" style="display:none;">({{ __('Optional') }})</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                  class="form-control text-field-2 @error('email') is-invalid @enderror" required />
                @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="text-field mb-3" id="phone-container">
                <label class="label" for="phone">
                    <span class="label-text">{{ __('Mobile Number') }}</span>
                    <span class="optional-text text-muted" style="display:none;">({{ __('Optional') }})</span>
                </label>
                <div class="row g-2">
                  <div class="col-4">
                    <select name="country_code" class="form-control text-field-2 @error('country_code') is-invalid @enderror" required>
                      <option value="968" {{ old('country_code') == '968' ? 'selected' : '' }}>OM (+968)</option>
                      <option value="20" {{ old('country_code') == '20' ? 'selected' : '' }}>EG (+20)</option>
                      <option value="966" {{ old('country_code') == '966' ? 'selected' : '' }}>SA (+966)</option>
                      <option value="971" {{ old('country_code') == '971' ? 'selected' : '' }}>AE (+971)</option>
                      <option value="974" {{ old('country_code') == '974' ? 'selected' : '' }}>QA (+974)</option>
                      <option value="973" {{ old('country_code') == '973' ? 'selected' : '' }}>BH (+973)</option>
                      <option value="965" {{ old('country_code') == '965' ? 'selected' : '' }}>KW (+965)</option>
                    </select>
                  </div>
                  <div class="col-8">
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                      class="form-control text-field-2 @error('phone') is-invalid @enderror" placeholder="71234567" required />
                  </div>
                </div>
                @error('country_code') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <div class="text-field mb-3">
                <label class="label">{{ __('Verify Your Account Via') }}</label>
                <div class="d-flex gap-4 mt-1">
                  <div class="form-check">
                    <input class="form-check-input verification-method" type="radio" name="verification_method" id="verify_email" value="email" {{ old('verification_method', 'email') == 'email' ? 'checked' : '' }}>
                    <label class="form-check-label" for="verify_email">{{ __('Email') }}</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input verification-method" type="radio" name="verification_method" id="verify_whatsapp" value="whatsapp" {{ old('verification_method') == 'whatsapp' ? 'checked' : '' }}>
                    <label class="form-check-label" for="verify_whatsapp">{{ __('WhatsApp') }}</label>
                  </div>
                </div>
                @error('verification_method') <div class="text-danger mt-1">{{ $message }}</div> @enderror
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
                <span class="text-wrapper-6">{{ __('Do you have an account?') }} </span>
                <a href="{{ route('login') }}" class="text-wrapper-7">{{ __('Sign in') }}</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const countrySelect = document.querySelector('select[name="country_code"]');
        const emailOptional = document.querySelector('#email-container .optional-text');
        const phoneOptional = document.querySelector('#phone-container .optional-text');
        const verificationMethods = document.querySelectorAll('.verification-method');

        function updateRequirements() {
            const selectedMethod = document.querySelector('.verification-method:checked').value;
            
            if (selectedMethod === 'email') {
                emailInput.required = true;
                emailOptional.style.display = 'none';
                
                phoneInput.required = false;
                countrySelect.required = false;
                phoneOptional.style.display = 'inline';
            } else {
                phoneInput.required = true;
                countrySelect.required = true;
                phoneOptional.style.display = 'none';
                
                emailInput.required = false;
                emailOptional.style.display = 'inline';
            }
        }

        verificationMethods.forEach(method => {
            method.addEventListener('change', updateRequirements);
        });

        // Initialize on load
        updateRequirements();
    });
  </script>

@endsection