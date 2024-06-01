@extends('front.layouts.master')
@section('title', isset($title) ? $title : 'OTP Login')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')

<div class="section">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 col-lg-5">
                <div class="login-wrap h-[470px]">
                    <h1 class="text-center mb-0">{{ __('Verify Phone Number') }}</h1>
                    <p id="helper-text-explanation" class="mt-10 text-3xl font-medium text-gray-500 text-center ">
                        {{__("We sent a verification code to number to", ['phone_number' => $phone_number])}}</p>
                    <form class="login-form" method="post" action="{{ route('user.sign.otp') }}">
                        @csrf
                        <div class="otpForm my-20">
                            <input type="text" maxlength="1" class="otp-input" id="digit1">
                            <input type="text" maxlength="1" class="otp-input" id="digit2">
                            <input type="text" maxlength="1" class="otp-input" id="digit3">
                            <input type="text" maxlength="1" class="otp-input" id="digit4" autofocus>
                        </div>
                        <div class="form-group mt-24">
                            <button type="submit"
                                class="form-control btn btn-primary rounded submit px-3 primary-btn auth-btn">{{
                                __('Submit Code') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="/assets/js/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Automatically move to the next input field after entering a digit
        $('.otp-input').keyup(function(e) {
            if (this.value.length == this.maxLength) {
                $(this).prev('.otp-input').focus();
            }


            if (e.which == 8 || e.which == 46) { // Check for backspace or delete key
                $(this).next('.otp-input').focus();
            }
        });
    });
</script>