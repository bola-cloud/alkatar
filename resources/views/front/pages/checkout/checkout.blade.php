@extends('front.layouts.master')
@section('title', isset($title) ? $title : 'Home')
@section('description', isset($description) ? $description : '')
@section('keywords', isset($keywords) ? $keywords : '')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">

<!-- checkout page area start here  -->
<section class="page-content section">
    <h1 class="text-5xl md:text-7xl font-bold text-center text-primary-red mb-8">{{ __("Checkout Page") }}</h1>
    <div class="checkout">
        <div class="mx-auto lg:px-4">
            <div class="grid lg:grid-cols-2 lg:gap-8 checkout-form bg-white p-6 rounded-lg shadow-md lg:h-full">
                <form method="post" action="{{ route('checkout.order') }}" id="paymentForm">
                    @csrf
                    <div class="space-y-6">
                        @if (!auth()->check())
                            <div class="mb-6">
                                <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
                                    <h2 class="text-3xl xl:text-4xl text-black font-bold mb-2 lg:mb-0">
                                        {{ __("Returning buyer? Please login:") }}
                                    </h2>
                                    <a class="primary-btn text-center bg-primary-red text-white px-5 py-3 lg:py-4 rounded text-lg xl:text-xl"
                                        href="{{ route("login") }}">{{ __("Login") }}</a>
                                </div>
                            </div>
                        @endif

                        <div>
                            <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('Billing Address') }}</h2>
                        </div>
                        <div class="space-y-4">
                            <label for="billing_name"
                                class="block text-lg lg:text-2xl font-medium text-gray-700">{{ __('Name') }}</label>
                            <input type="text"
                                class="w-11/12 lg:w-full p-3 lg:p-4 border rounded h-14 lg:h-16 text-lg lg:text-xl"
                                id="billing_name" name="billing_name" placeholder="{{ __('Name') }}"
                                value="{{ isset($billing) ? $billing->Name ?? $billing->name : '' }}" required />

                            <label for="billing_phone"
                                class="block text-lg lg:text-2xl font-medium text-gray-700">{{ __('Phone Number') }}</label>
                            <!-- <input type="text"
                                class="w-11/12 lg:w-full p-3 lg:p-4 border rounded h-14 lg:h-16 text-lg lg:text-xl"
                                id="billing_phone" name="billing_phone" placeholder="{{ __('Phone Number') }}"
                                value="{{ isset($user) ? $user->Number ?? $user->Number : '' }}" required /> -->

                            <div class="space-y-4">
                                <input type="tel" id="phone_number" name="phone_number"
                                    class="h-14 lg:h-16 lg:text-xl w-11/12 lg:w-full lg:p-4 p-2.5 text-lg rounded-lg border border-gray-300 focus:ring-primary-red focus:border-primary-red dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-primary-red"
                                    required value="{{ isset($user) ? $user->Number ?? $user->Number : '' }}">
                            </div>
                            <input type="hidden" name="billing_phone" id="billing_phone"
                                value="{{ isset($user) ? $user->Number ?? $user->Number : '' }}" />
                            <input type="hidden" name="country_code" id="country_code" />

                            <label for="billing_email"
                                class="block text-lg lg:text-2xl font-medium text-gray-700">{{ __('Email Address (Optional)') }}</label>
                            <input type="email"
                                class="w-11/12 lg:w-full p-3 lg:p-4 border rounded h-14 lg:h-16 text-lg lg:text-xl"
                                id="billing_email" name="billing_email" placeholder="{{ __('Email Address') }}"
                                value="{{ isset($billing) ? $billing->Email ?? $billing->email : '' }}" />

                            <div class="relative">
                                <label for="billing_country"
                                    class="block text-lg lg:text-2xl font-medium text-gray-700 mb-2">{{ __('State') }}</label>
                                <select
                                    class="w-11/12 lg:w-full p-3 lg:p-4 border rounded  h-14 lg:h-16 text-lg lg:text-xl !mb-0"
                                    id="billing_country" name="billing_state" required>
                                    <option>{{ __('State') }}</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">
                                            {{ langConverter($state->name_en, $state->name_ar) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="billing_country" value="1" class="!h-0" />

                            <div class="flex flex-col sm:flex-row gap-4">

                                <div class="relative w-full">
                                    <label for="city"
                                        class="block text-lg lg:text-2xl font-medium text-gray-700 mb-3">{{ __('City') }}</label>
                                    <select
                                        class="w-11/12 lg:w-full p-3 lg:p-4 border rounded h-14 !mb-0 lg:h-16 text-lg lg:text-xl"
                                        id="city" name="billing_city" required>
                                        <option value="">{{__('City')}}</option>

                                    </select>
                                </div>

                                <input type="hidden"
                                    class="w-11/12 lg:w-full p-3 lg:p-4 border rounded h-14 lg:h-16 text-lg lg:text-xl"
                                    id="billing_zipcode" name="billing_zipcode"
                                    placeholder="{{ __('Zip/Postal Code') }}" value="00000" />
                            </div>

                            <label for="billing_street_address mb-2"
                                class="block text-lg lg:text-2xl font-medium text-gray-700">{{ __('Street Address (Optional)') }}</label>
                            <input type="text"
                                class="w-11/12 lg:w-full p-3 lg:p-4 border rounded h-14 lg:h-16 text-lg lg:text-xl"
                                id="billing_street_address" name="billing_street_address"
                                placeholder="{{ __('Street Address') }}"
                                value="{{ isset($billing) ? $billing->Street : '' }}" />



                        </div>

                        <div class="mt-8">
                            <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('Payment Method') }}</h2>
                            <div class="space-y-4">
                                @foreach ($paymentPlatforms as $payment)
                                    @if ($payment->slug == 'paypal')
                                        <div class="flex items-center justify-between p-3 lg:p-0 border rounded">
                                            <div class="flex items-center gap-4 mt-4">
                                                <div class="relative flex items-center">
                                                    <input
                                                        class="appearance-none w-6 h-6 border-2 border-blue-500 rounded-full checked:border-blue-500 checked:bg-blue-500 transition-all duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 !important"
                                                        type="radio" name="payment" id="paypal" value="paypal" checked />
                                                    <div
                                                        class="absolute w-4 h-4 rounded-full bg-white left-1 top-1 transition-transform scale-0 origin-center checked:scale-100 pointer-events-none !important">
                                                    </div>
                                                </div>
                                                <label
                                                    class="text-lg lg:text-2xl text-gray-700 cursor-pointer select-none !important"
                                                    for="paypal">
                                                    {{ __('Credit Card Payment') }}
                                                </label>
                                            </div>
                                            <img src="{{ asset(IMG_PAYMENT_GATEWAY . $payment->image) }}" alt="paypal"
                                                class="size-48" />
                                        </div>
                                    @endif
                                @endforeach

                                @if (env('COD_STATUS') == '1')
                                    <div class="flex items-center justify-between p-3 lg:p-4 border rounded">
                                        <div class="flex gap-2 mt-2">
                                            <input class="form-check-input" type="radio" name="payment" id="COD"
                                                value="COD" />
                                            <label class="form-check-label text-lg lg:text-2xl"
                                                for="COD">{{ langConverter('Cash on delivery', 'دفع عند التوصيل') }}</label>
                                        </div>
                                        <img src="{{ asset(IMG_PAYMENT_GATEWAY . env('COD_IMAGE')) }}"
                                            alt="{{ env('COD_NAME') }}" class="size-48" />
                                    </div>
                                @endif

                                <div class="flex gap-3 space-x-2 mt-4">
                                    <input type="checkbox" class="form-check-input !accent-primary-red" id="agree"
                                        required />
                                    <label class="form-check-label text-lg lg:text-2xl" for="agree">
                                        {{ __('By clicking the button you agree to our') }}
                                        <a href="{{ route('terms.conditions') }}"
                                            class="text-primary-red">{{ __('Terms & Conditions') }}</a>
                                    </label>
                                </div>

                                <button type="submit" id="payButton"
                                    class="w-full bg-primary-red text-white py-3 lg:py-6 px-4 rounded hover:bg-red-600 transition duration-300 text-lg lg:text-xl">{{ __('Place Order') }}</button>
                                <button type="button" id="payButtonN"
                                    class="w-full bg-primary-red text-white py-3 lg:!py-4 px-4 rounded hover:bg-red-600 transition duration-300 d-none buy_now text-lg lg:text-xl">{{ __('Place Order') }}</button>

                            </div>
                        </div>
                </form>
            </div>

            <div class="cart-summary bg-white !p-8 rounded-lg shadow-md lg:h-full">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl lg:text-4xl font-bold">{{ __('Cart Summary') }}</h2>
                    <a class="text-primary-red hover:underline text-lg lg:text-xl"
                        href="{{ route('cart.content') }}">{{ __('Edit') }}</a>
                </div>
                <ul class="space-y-4">
                    @php $total = 0; @endphp
                    @foreach ($content as $item)

                        <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b pb-4">
                            <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                                <img src="{{ asset(ProductImage() . $item->options->image) }}" alt="{{ $item->name }}"
                                    class="w-20 h-20 lg:w-32 lg:h-32 object-cover rounded">
                                <div class="!ms-10">
                                    <h3 class="font-bold text-2xl lg:text-3xl text-black">
                                        {{langConverter($item->name, $item->options->name_ar)}}
                                    </h3>


                                    @if($item->options->size)
                                        <p class="text-2xl lg:text-3xl text-gray-600 my-4">
                                            <span class="font-bold underline">{{__('Option') }}</span>:
                                            {{ is_null($item->options->size) ? __('Free Size') : langConverter($item->options->size, $item->options->size_ar) }}
                                        </p>
                                    @endif


                                    <p class="text-2xl lg:text-3xl text-gray-600 my-4">
                                        <span class="font-bold ">{{__('Size') }}</span>:
                                        {{ is_null($item->options->weight) ? __('Free Size') : $item->options->weight->weight . " " . __("Grams") }}
                                    </p>

                                    @if ($item->options->additions)
                                        <div class="mb-4">
                                            <p class="text-2xl lg:text-3xl text-gray-600 font-bold underline">
                                                {{ __('Additions') }}:
                                            </p>
                                            @foreach ($item->options->additions as $addition)
                                                <p class="text-2xl lg:text-3xl text-gray-600">
                                                    {{ langConverter($addition->name, $addition->name_ar) }}
                                                </p>
                                            @endforeach
                                        </div>
                                    @endif
                                    <p class="text-2xl lg:text-3xl text-gray-600">{{ __('Quantity') }}: {{ $item->qty }}</p>
                                </div>
                            </div>
                            <div class="text-right mt-2 sm:mt-0">
                                <h3 class="font-bold text-black text-2xl lg:text-3xl">
                                    {{ currencyConverter($item->price * $item->qty) }}
                                </h3>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-6">
                    <h2 class="text-2xl lg:text-3xl font-bold mb-3">{{ __('Discount Codes') }}</h2>
                    <form action="{{ route('apply.coupon') }}" method="post" class="flex space-x-2">
                        @csrf
                        <input type="text" class="flex-grow p-2 lg:p-3 border rounded h-14 lg:h-16 text-lg lg:text-2xl"
                            name="coupon_code" placeholder="{{ __('Enter your coupon code') }}" required />
                        <button type="submit"
                            class="bg-primary-red text-white px-4 py-3 lg:py-4 rounded hover:bg-red-600 transition duration-300 text-lg lg:text-2xl">{{ __('Apply Coupon') }}</button>
                    </form>
                </div>
                <ul class="mt-10 lg:mt-20 space-y-2 text-2xl lg:text-3xl">
                    <li class="flex justify-between">
                        <span>{{ __('Subtotal') }}</span>
                        <span id="subtotal">{{ currencyConverter(\Cart::subtotal()) }}</span>
                    </li>


                    <li class="flex justify-between d-none" id="offer-Discount-li">
                        <span>{{ __('offer Discount (-)') }}</span>
                        <span id="offer-Discount"></span>
                    </li>
                    <li class="flex justify-between d-none" id="total-After-offer-Discount-li">
                        <span>{{ __('Total After Discount') }}</span>
                        <span id="total-After-offer-Discount"></span>
                    </li>

                    <li class="flex justify-between">
                        <span>{{ __('Shipping Cost') }}</span>
                        <span id="delivery-charge-curr"></span>
                    </li>
                    <li class="flex justify-between">
                        <span>{{ __('Weight Handling Cost') }}</span>
                        <span id="weight-charge-curr">{{currencyConverter($extraWeightFees)}}</span>
                    </li>
                    <li class="flex justify-between">
                        <span>{{ __('VAT/Tax') }}</span>
                        <span id="tax-show-curr">{{ currencyConverter(tax_amount(\Cart::subtotal())) }}</span>
                    </li>
                    @if (!empty(Session::get('CouponAmount')))
                        <li class="flex justify-between">
                            <span>{{ __('Coupon Discount (-)') }}</span>
                            <span>{{ currencyConverter(Session::get('CouponAmount')) }}</span>
                        </li>
                    @endif

                </ul>
                <div class="mt-6 pt-4 border-t">
                    <h3 class="text-2xl lg:text-3xl font-bold flex justify-between">
                        <span>{{ __('Total Cost') }}</span>
                        <span id="total-cost-curr">
                            {{ currencyConverter(\Cart::subtotal() + allsetting()['shipping_charge'] + tax_amount(\Cart::subtotal()) - Session::get('CouponAmount') + $extraWeightFees) }}
                        </span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="stripe-collapse" data-stripe="{{ route('stripe_collapse') }}"></div>
<div id="stripe-key" data-key="{{ config('services.stripe.key') }}"></div>
<div id="user-name" data-key="{{ auth()->check() ? auth()->user()->name : 'Guest User' }}"></div>
<div id="user-email" data-key="{{ auth()->check() ? auth()->user()->email : 'guest@gmail.com' }}"></div>
<div id="get-tax-amount" data-url="{{ route('checkout.get_tax_amount') }}"></div>

@push('post_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.querySelector("#phone_number");
            var iti = window.intlTelInput(input, {
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                initialCountry: "om",
                excludeCountries: ["il"],
                separateDialCode: true,
                preferredCountries: ["om", "ae", "sa", "kw", "bh", "qa"]
            });

            var form = document.querySelector("form");
            var fullPhoneInput = document.querySelector("#billing_phone");
            var countryCodeInput = document.querySelector("#country_code");

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (iti.isValidNumber()) {
                    var fullNumber = iti.getNumber();
                    var countryCode = iti.getSelectedCountryData().dialCode;
                    countryCodeInput.value = countryCode;
                    fullPhoneInput.value = fullNumber;
                    form.submit();
                } else {
                    console.error("Invalid phone number");
                    alert("Please enter a valid phone number.");
                }
            });

            input.addEventListener('input', function () {
                var fullNumber = iti.getNumber();
                var countryCode = iti.getSelectedCountryData().dialCode;
                countryCodeInput.value = countryCode;
                fullPhoneInput.value = fullNumber;
            });
        });
    </script>

    <script src="{{ asset('frontend/assets/js/pages/checkout.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#billing_country').on('change', function () {
                var stateId = $(this).val();
                if (stateId) {
                    $.ajax({
                        url: '/get-cities-by-state/' + stateId,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#city').empty();
                            $('#city').append('<option value="">---اختيار المدينة ---</option>');
                            $.each(data, function (key, value) {
                                $('#city').append('<option value="' + value.id + '" > ' + value.name_en + '</option > ');
                            });
                        }
                    });
                } else {
                    $('#city').empty();
                    $('#city').append('<option value="">---اختيار المدينة ---</option>');
                }
            });
        });

        $('#city').on('change', function () {
            var cityId = $(this).val();
            if (cityId) {
                $.ajax({
                    url: '/get-city-charge/' + cityId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        console.log("city change data", data);
                        $('#delivery-charge-curr').text(data.formatted_charge);
                        $('#total-cost-curr').text(data.total_cost);
                        $('#subtotal').text(data.subtotal);
                        if (data.is_offer) {
                            $('#offer-Discount-li').removeClass('d-none');
                            $('#total-After-offer-Discount-li').removeClass('d-none');
                            $('#total-After-offer-Discount').text(data.subtotal_After_offer);
                            $('#offer-Discount').text(data.offer_Discount);
                            toastr.success("تم تطبيق العرض! لقد وفرت:" + data.offer_Discount);
                        }

                    }
                });
            }
        });
    </script>
@endpush
@endsection