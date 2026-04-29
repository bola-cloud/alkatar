@extends('front.layouts.new_design_layout')
@section('title', isset($title) ? $title : 'Checkout')
@section('content')

    {{-- include the shared category/banner area to match site design --}}
    @include('front.partials.category_banner', ['title' => 'Checkout'])

    {{-- intl-tel-input CSS (required for styled country dropdown) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">

    {{-- Small CSS fixes so the phone input and country list display correctly inside new design layout --}}
    <style>
        /* ensure the intl input uses full width and the flag is placed correctly */
        .iti {
            width: 100%;
            /* Force LTR for phone numbers regardless of site language */
            direction: ltr !important;
        }

        .iti .iti__flag-container {
            top: 5px;
            left: 10px;
        }

        #phone_number {
            direction: ltr !important;
            text-align: left !important;
        }

        /* ensure country list is scrollable and stays above other elements */
        .iti__country-list {
            max-height: 300px;
            overflow-y: auto;
            z-index: 999999 !important;
            direction: ltr !important;
            text-align: left !important;
        }

        /* when using separate dial code, ensure enough padding so text never overlaps */
        .iti--separate-dial-code input {
            padding-left: 100px !important;
        }

        #phone_number {
            padding-left: 100px !important;
        }

        /* More generous padding for desktop where dial code might be wider */
        @media (min-width: 769px) {
            .iti--separate-dial-code input, #phone_number {
                padding-left: 130px !important;
            }
        }

        /* Slightly reduce padding on very narrow screens but keep enough room */
        @media (max-width: 480px) {
            .iti--separate-dial-code input, #phone_number {
                padding-left: 95px !important;
            }
        }
        
        /* Fix dial code positioning if it was shifting too much */
        .iti--separate-dial-code .iti__selected-dial-code {
            margin-left: 5px;
        }
    </style>

    <section class="checkout-new-design section py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <form method="post" action="{{ route('checkout.order') }}" id="paymentForm">
                        @csrf
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <h4 class="card-title mb-3">{{ __('Billing Address') }}</h4>

                                @if (!auth()->check())
                                    <div class="mb-4 flex items-center justify-between">
                                        <div class="text-lg font-medium">{{ __('Returning buyer? Please login:') }}</div>
                                        <a class="btn btn-primary" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </div>
                                @endif

                                <div class="">
                                    @if (auth()->user() && auth()->user()->is_admin == 1)
                                        <label class="font-medium">{{ __('Select User To Buy For') }}</label>
                                        <select id="user_id" name="user_id" class="form-control select2 p-3">
                                            <option value="">{{ __('New User') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" data-name="{{ $user->name }}"
                                                    data-number="{{ $user->Number }}" data-email="{{ $user->email }}"
                                                    data-code="{{ $user->code }}">{{ $user->name }} ({{ $user->Number }})</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Name') }}</label>
                                        <input type="text" id="billing_name" name="billing_name"
                                            class="form-control {{ $errors->has('billing_name') ? 'is-invalid' : '' }}"
                                            value="{{ old('billing_name', isset($billing) ? ($billing->Name ?? $billing->name) : '') }}"
                                            required>
                                        @error('billing_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Phone Number') }}</label>
                                        <input type="tel" id="phone_number" name="phone_number"
                                            class="form-control {{ $errors->has('billing_phone') ? 'is-invalid' : '' }}"
                                            value="{{ old('billing_phone', isset($user) ? ($user->Number ?? $user->Number) : '') }}"
                                            required>
                                        <input type="hidden" name="billing_phone" id="billing_phone"
                                            value="{{ old('billing_phone', isset($user) ? ($user->Number ?? $user->Number) : '') }}">
                                        <input type="hidden" name="country_code" id="country_code">
                                        @error('billing_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Email Address (Optional)') }}</label>
                                        <input type="email" id="billing_email" name="billing_email"
                                            class="form-control {{ $errors->has('billing_email') ? 'is-invalid' : '' }}"
                                            value="{{ old('billing_email', isset($billing) ? ($billing->Email ?? $billing->email) : '') }}">
                                        @error('billing_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('State') }}</label>
                                        <select id="billing_country" name="billing_state"
                                            class="form-select {{ $errors->has('billing_state') ? 'is-invalid' : '' }}"
                                            required>
                                            <option value="">{{ __('State') }}</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" {{ (old('billing_state') == $state->id) ? 'selected' : '' }}>{{ langConverter($state->name_en, $state->name_ar) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('billing_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <input type="hidden" name="billing_country" value="1">

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('City') }}</label>
                                            <select id="city" name="billing_city"
                                                class="form-select {{ $errors->has('billing_city') ? 'is-invalid' : '' }}"
                                                required>
                                                <option value="">{{ __('City') }}</option>
                                                @if(old('billing_city'))
                                                    <option value="{{ old('billing_city') }}" selected>{{ __('Selected City') }}
                                                    </option>
                                                @endif
                                            </select>
                                            @error('billing_city')<div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('Area') }}</label>
                                            <select id="area" name="billing_area"
                                                class="form-select {{ $errors->has('billing_area') ? 'is-invalid' : '' }}"
                                                required>
                                                <option value="">{{ __('Area') }}</option>
                                                @if(old('billing_area'))
                                                    <option value="{{ old('billing_area') }}" selected>{{ __('Selected Area') }}
                                                    </option>
                                                @endif
                                            </select>
                                            @error('billing_area')<div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">{{ __('Zip/Postal Code') }}</label>
                                            <input type="text" id="billing_zipcode" name="billing_zipcode"
                                                class="form-control {{ $errors->has('billing_zipcode') ? 'is-invalid' : '' }}"
                                                value="{{ old('billing_zipcode', '00000') }}" required>
                                            @error('billing_zipcode')<div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Street Address (Optional)') }}</label>
                                        <input type="text" id="billing_street_address" name="billing_street_address"
                                            class="form-control {{ $errors->has('billing_street_address') ? 'is-invalid' : '' }}"
                                            value="{{ old('billing_street_address', isset($billing) ? $billing->Street : '') }}">
                                        @error('billing_street_address')<div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3">{{ langConverter('Collection Method', 'طريقة الاستلام') }}</h5>
                                <div class="mb-3">
                                    <div class="flex items-center justify-between p-3 border rounded mb-3">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="collection_method" id="delivery" value="delivery" checked>
                                            <label for="delivery">{{ langConverter('Delivery', 'توصيل') }}</label>
                                        </div>
                                        <i class="fas fa-truck fa-2x text-secondary"></i>
                                    </div>
                                    <div class="flex items-center justify-between p-3 border rounded mb-3">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="collection_method" id="store_pickup" value="store_pickup">
                                            <label for="store_pickup">{{ langConverter('Warehouse Pickup', 'استلام من المخزن') }}</label>
                                        </div>
                                        <i class="fas fa-store fa-2x text-primary"></i>
                                    </div>
                                    @error('collection_method')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-3">{{ __('Payment Method') }}</h5>
                                <div class="mb-3">
                                    @foreach ($paymentPlatforms as $payment)
                                        @if ($payment->slug == 'paypal')
                                            <div class="flex items-center justify-between p-3 border rounded">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="payment" id="paypal" value="paypal" checked>
                                                    <label for="paypal">{{ __('Credit Card Payment') }}</label>
                                                </div>
                                                <img src="{{ asset(IMG_PAYMENT_GATEWAY . $payment->image) }}"
                                                    alt="{{ $payment->name }}" class="h-10" style="height: 120px;">
                                            </div>
                                        @endif
                                    @endforeach

                                    @if (env('COD_STATUS') == '1')
                                        <div class="flex items-center justify-between p-3 border rounded mb-3">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="payment" id="COD" value="COD">
                                                <label
                                                    for="COD">{{ langConverter('Cash on delivery', 'دفع عند التوصيل') }}</label>
                                            </div>
                                            <img src="{{ asset(IMG_PAYMENT_GATEWAY . env('COD_IMAGE')) }}" alt="COD"
                                                class="h-10">
                                        </div>
                                    @endif


                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="agree" required>
                                        <label class="form-check-label"
                                            for="agree">{{ __('By clicking the button you agree to our') }} <a
                                                href="{{ route('terms.conditions') }}"
                                                class="text-primary-red">{{ __('Terms & Conditions') }}</a></label>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">{{ __('Place Order') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">{{ __('Cart Summary') }}</h5>
                                @if(auth()->check() && !auth()->user()->is_admin)
                                    <div class="text-info mt-1">
                                        <small>{{ __('Wallet Balance') }}: <strong>{{ currencyConverter(auth()->user()->balance) }}</strong></small>
                                    </div>
                                @endif
                                <a href="{{ route('cart.content') }}" class="text-primary-red">{{ __('Edit') }}</a>
                            </div>
                            <ul class="list-unstyled">
                                @foreach ($content as $item)
                                    <li class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-start">
                                            <img src="{{ asset(ProductImage() . $item->options->image) }}" class="rounded me-3"
                                                style="width:64px; height:64px; object-fit:cover;" alt="{{ $item->name }}">
                                            <div>
                                                <div class="fw-bold">{{ langConverter($item->name, $item->options->name_ar) }}
                                                </div>
                                                <div class="text-muted small">{{ __('Quantity') }}: {{ $item->qty }}</div>
                                            </div>
                                        </div>
                                        <div class="fw-bold">{{ currencyConverter($item->price * $item->qty) }}</div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-3 border-top pt-3">
                                <div class="d-flex justify-content-between"><span>{{ __('Subtotal') }}</span><span
                                        id="subtotal">{{ currencyConverter(subtotal()) }}</span></div>
                                <div class="d-flex justify-content-between align-items-start">
                                    <span>{{ __('Shipping Cost') }}</span>
                                    <span class="text-end">
                                        <span id="delivery-charge-curr"
                                            data-zero="{{ currencyConverter(0) }}">{{ currencyConverter(0) }}</span>
                                        {{-- <small class="text-muted d-block">(+ {{ __('Tax') }}: <span
                                                id="tax-next-to-shipping" data-zero="{{ currencyConverter(0) }}">{{
                                                currencyConverter(0) }}</span>)</small> --}}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between"><span>{{ __('VAT/Tax') }}</span><span
                                        id="tax-show-curr"
                                        data-zero="{{ currencyConverter(0) }}">{{ currencyConverter(0) }}</span></div>
                                @if (!empty(Session::get('CouponAmount')))
                                    <div class="d-flex justify-content-between text-danger">
                                        <span>{{ __('Coupon Discount (-)') }}</span><span>-{{ currencyConverter(Session::get('CouponAmount')) }}</span>
                                    </div>
                                @endif
                                @if (!empty(Session::get('subscription_discount_amount')))
                                    <div class="d-flex justify-content-between text-success">
                                        <span>{{ __('Subscription Discount (-)') }}</span>
                                        <span>-{{ currencyConverter(Session::get('subscription_discount_amount')) }}</span>
                                    </div>
                                @endif
                                    <div class="d-flex justify-content-between text-info" id="wallet-line" style="display:none;">
                                        <span>{{ __('Wallet Used (-)') }}</span>
                                        <span id="wallet-used-curr">{{ currencyConverter(0) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-betweenfw-bold mt-2" id="net-payable-line" style="display:none;">
                                        <span>{{ __('Net Payable') }}</span>
                                        <span id="net-payable-curr">{{ currencyConverter(0) }}</span>
                                    </div>
                                @if (!empty(Session::get('free_shipping_applied')))
                                    <div class="d-flex justify-content-between text-success">
                                        <small>{{ __('🎉 Free Shipping from Subscription') }}</small>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between fw-bold mt-3">
                                    <span>{{ __('Total Cost') }}</span><span id="total-cost-curr"
                                        data-initial="{{ currencyConverter(subtotal() - Session::get('CouponAmount') + $extraWeightFees) }}">{{ currencyConverter(subtotal() - Session::get('CouponAmount') + $extraWeightFees) }}</span>
                                </div>
                            </div>
                        </div>
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



@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.querySelector("#phone_number");
            if (input) {
                var iti = window.intlTelInput(input, {
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    initialCountry: "om",
                    separateDialCode: true,
                    preferredCountries: ["om", "ae", "sa", "kw", "bh", "qa"],
                    dropdownContainer: document.body
                });

                var form = document.querySelector("#paymentForm");
                var fullPhoneInput = document.querySelector("#billing_phone");
                var countryCodeInput = document.querySelector("#country_code");

                if (form) {
                    form.addEventListener('submit', function (e) {
                        if (iti && !iti.isPossibleNumber()) {
                            e.preventDefault();
                            alert("Please enter a valid phone number.");
                            return false;
                        }
                        if (iti) {
                            countryCodeInput.value = iti.getSelectedCountryData().dialCode;
                            fullPhoneInput.value = iti.getNumber();
                        }
                    });
                }
            }
        });
    </script>

    <script src="{{ asset('frontend/assets/js/pages/checkout.js') }}"></script>
    <script>
        $(document).ready(function () {
            // If there were old values (validation failed), repopulate state and city selects
            var oldState = "{{ old('billing_state') }}";
            var oldCity = "{{ old('billing_city') }}";
            if (oldState) {
                $('#billing_country').val(oldState).trigger('change');
                // Wait for cities to be populated then select old city
                var cityTry = setInterval(function () {
                    if (oldCity && $('#city option[value="' + oldCity + '"]').length) {
                        $('#city').val(oldCity);
                        clearInterval(cityTry);
                    }
                }, 200);
            }
            $('#billing_country').on('change', function () {
                var stateId = $(this).val();
                if (stateId) {
                    $.ajax({
                        url: '/get-cities-by-state/' + stateId,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#city').empty();
                            $('#city').append('<option value="">{{ __('City') }}</option>');
                            $.each(data, function (key, value) {
                                $('#city').append('<option value="' + value.id + '">' + value.name_en + '</option>');
                            });
                        }
                    });
                } else {
                    $('#city').empty();
                    $('#city').append('<option value="">{{ __('City') }}</option>');
                }
            });

            $('#city').on('change', function () {
                var cityId = $(this).val();
                if (cityId) {
                    $.ajax({
                        url: '/get-areas-by-city/' + cityId,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#area').empty();
                            $('#area').append('<option value="">{{ __('Area') }}</option>');
                            $.each(data, function (key, value) {
                                $('#area').append('<option value="' + value.id + '">' + value.name_en + '</option>');
                            });
                        }
                    });
                } else {
                    $('#area').empty();
                    $('#area').append('<option value="">{{ __('Area') }}</option>');
                }
            });

            $('#area').on('change', function () {
                var areaId = $(this).val();
                if (areaId) {
                    $.ajax({
                        url: '/get-area-charge/' + areaId,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#delivery-charge-curr').text(data.formatted_charge);
                            // Do not overwrite tax here — tax is fixed per country (set on state change).
                            $('#total-cost-curr').text(data.total_cost);
                            $('#subtotal').text(data.subtotal);
                            if (data.is_offer) {
                                toastr.success("{{ __('Offer applied') }}: " + data.offer_Discount);
                            }

                            // Wallet & Net Payable
                            if (data.wallet_used && parseFloat(data.wallet_used.replace(/[^\d.-]/g, '')) > 0) {
                                $('#wallet-used-curr').text(data.wallet_used);
                                $('#wallet-line').show();
                                
                                $('#net-payable-curr').text(data.net_payable);
                                $('#net-payable-line').show();
                            } else {
                                $('#wallet-line').hide();
                                $('#net-payable-line').hide();
                            }
                        }
                    });
                }
            });

            // Toggle required fields based on collection method
            $('input[name="collection_method"]').on('change', function() {
                const isPickup = $(this).val() === 'store_pickup';
                const fields = ['#billing_country', '#city', '#area'];
                
                fields.forEach(selector => {
                    if (isPickup) {
                        $(selector).removeAttr('required');
                        $(selector).prev('label').find('.text-danger').hide();
                    } else {
                        $(selector).attr('required', 'required');
                        $(selector).prev('label').find('.text-danger').show();
                    }
                });
                
                // Toggle delivery charge
                if (isPickup) {
                    $('#delivery-charge-curr').text($('#delivery-charge-curr').data('zero') || '0.00');
                } else {
                    if ($('#area').val()) {
                        $('#area').trigger('change');
                    }
                }
            });
            // Trigger once on load
            $('input[name="collection_method"]:checked').trigger('change');
        });
    </script>
@endpush

{{-- order error modal (shows on checkout when session flag set) --}}
@include('front.partials.order_error_modal')