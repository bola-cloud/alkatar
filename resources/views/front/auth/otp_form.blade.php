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
                        {{__("We sent a verification code to number to", ['phone_number' => $phone_number])}}
                    </p>
                    <form class="login-form" method="post" action="{{ route('user.otp.verify') }}">
                        @csrf
                        <div class="otpForm my-20">
                            @if(app()->getLocale() == 'en')
                                <input type="text" maxlength="1" class="otp-input" id="digit1" autofocus>
                                <input type="text" maxlength="1" class="otp-input" id="digit2">
                                <input type="text" maxlength="1" class="otp-input" id="digit3">
                                <input type="text" maxlength="1" class="otp-input" id="digit4">
                                <input type="text" maxlength="1" class="otp-input" id="digit5">
                            @elseif(app()->getLocale() == 'fr')
                                <input type="text" maxlength="1" class="otp-input" id="digit5">
                                <input type="text" maxlength="1" class="otp-input" id="digit4">
                                <input type="text" maxlength="1" class="otp-input" id="digit3">
                                <input type="text" maxlength="1" class="otp-input" id="digit2">
                                <input type="text" maxlength="1" class="otp-input" id="digit1" autofocus>
                            @endif
                        </div>

                        <input type="hidden" name="phone_number" value="{{ $phone_number }}">
                        <input type="hidden" name="country_code" value="{{ $country_code }}">
                        <input type="hidden" name="name" value="{{ $name }}">
                        <input type="hidden" name="otp" id="otp" value="">
                        <div class="form-group mt-24">
                            <button type="submit"
                                class="form-control btn btn-primary rounded submit px-3 primary-btn auth-btn">{{
    __('Submit Code') }}</button>
                        </div>
                    </form>

                    {{-- resend button --}}
                    <form class="login-form" method="post" action="{{ route('user.sign.otp') }}">
                        @csrf
                        <input type="hidden" name="phone_number" value="{{ $phone_number }}">
                        <div class="form-group">
                            <button type="submit"
                                class="form-control rounded submit px-3 primary-btn !text-primary-red !bg-transparent hover:!bg-transparent">{{
    __('Resend Code') }}</button>
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
    $(document).ready(function () {
        const isRTL = '{{ app()->getLocale() }}' === 'fr';

        $('.otp-input').keyup(function (e) {
            if (this.value.length == this.maxLength) {
                if (isRTL) {
                    $(this).prev('.otp-input').focus();
                } else {
                    $(this).next('.otp-input').focus();
                }
            }

            if (e.which == 8 || e.which == 46) { // Check for backspace or delete key
                if (isRTL) {
                    $(this).next('.otp-input').focus();
                } else {
                    $(this).prev('.otp-input').focus();
                }
            }
        });

        // Get the OTP value
        $('.otp-input').keyup(function () {
            let otp = '';
            if (isRTL) {
                $('.otp-input').each(function () {
                    otp = $(this).val() + otp; // Prepend for RTL
                });
            } else {
                $('.otp-input').each(function () {
                    otp += $(this).val(); // Append for LTR
                });
            }
            $('#otp').val(otp);
        });
    });
</script>