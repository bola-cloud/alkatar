@extends('admin.master', ['menu' => 'order'])
@section('title', __('Create Order'))
@push('post_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
<style>
    .iti { width: 100%; }
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="breadcrumb__content">
            <div class="breadcrumb__content__left">
                <div class="breadcrumb__title">
                    <h2>{{ __('Create Order for Customer') }}</h2>
                </div>
            </div>
            <div class="breadcrumb__content__right">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders', 'all') }}">{{ __('Orders') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-vertical__item bg-style">
            <form action="{{ route('admin.orders.store') }}" method="POST" id="order_form">
                @csrf
                
                <!-- Section 1: Customer & Payment -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title mb-20">
                            <h4><i class="fas fa-user-circle me-2"></i>{{ __('Customer Information') }}</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input__group mb-25">
                            <label for="user_id">{{ __('Select Customer') }} <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control select2">
                                <option value="guest">{{ __('Guest Customer') }}</option>
                                @foreach($users as $user)
                                    @php
                                        $displayPhone = $user->Number;
                                        if (str_starts_with($displayPhone, '968')) {
                                            $displayPhone = substr($displayPhone, 3);
                                        }
                                    @endphp
                                    <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-phone="{{ $displayPhone }}" data-email="{{ $user->email }}">
                                        {{ $user->name }} ({{ $displayPhone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input__group mb-25">
                            <label for="payment_method">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="COD">{{ __('Cash on Delivery (COD)') }}</option>
                                <option value="BANK_TRANSFER">{{ __('Bank Transfer') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input__group mb-25">
                            <label for="collection_method">{{ __('Collection Method') }} <span class="text-danger">*</span></label>
                            <select name="collection_method" id="collection_method" class="form-control" required>
                                <option value="delivery">{{ __('Delivery') }}</option>
                                <option value="store_pickup">{{ __('In-Store Pickup') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Guest Fields (Toggled via JS) -->
                <div class="row guest-fields" style="display:none;">
                    <div class="col-md-4">
                        <div class="input__group mb-25">
                            <label for="guest_name">{{ __('Customer Name') }}</label>
                            <input type="text" name="guest_name" id="guest_name" class="form-control" placeholder="{{ __('Name') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input__group mb-25">
                            <label for="guest_phone_display">{{ __('Customer Phone (WhatsApp)') }}</label>
                            <input type="tel" id="guest_phone_display" class="form-control" placeholder="XXXXXXXX">
                            <input type="hidden" name="guest_phone" id="guest_phone">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input__group mb-25">
                            <label for="guest_email">{{ __('Customer Email (Optional)') }}</label>
                            <input type="email" name="guest_email" id="guest_email" class="form-control" placeholder="{{ __('Email') }}">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Delivery Address -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="section-title mb-20">
                            <h4><i class="fas fa-truck me-2"></i>{{ __('Delivery Details') }}</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input__group mb-25">
                            <label for="state_id">{{ __('State') }} <span class="text-danger">*</span></label>
                            <select name="state_id" id="state_id" class="form-control select2" required>
                                <option value="">{{ __('Select State') }}</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name_ar }} / {{ $state->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input__group mb-25">
                            <label for="city_id">{{ __('City') }} <span class="text-danger">*</span></label>
                            <select name="city_id" id="city_id" class="form-control select2" required>
                                <option value="">{{ __('Select City') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input__group mb-25">
                            <label for="area_id">{{ __('Area') }} <span class="text-danger">*</span></label>
                            <select name="area_id" id="area_id" class="form-control select2" required>
                                <option value="">{{ __('Select Area') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input__group mb-25">
                            <label for="street_address">{{ __('Street Address') }} <span class="text-danger">*</span></label>
                            <input type="text" name="street_address" id="street_address" class="form-control" placeholder="{{ __('Street, Building, Apartment...') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Products & Summary -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="section-title mb-20">
                            <h4><i class="fas fa-shopping-basket me-2"></i>{{ __('Order Products') }}</h4>
                            <hr>
                        </div>
                    </div>
                    
                    <div class="col-lg-8">
                        <div class="input__group mb-20">
                            <label for="add_product">{{ __('Add Product to Order') }}</label>
                            <select id="add_product" class="form-control select2">
                                <option value="">{{ __('Search or select product...') }}</option>
                                @foreach($products as $product)
                                    @php
                                        $price = $product->Price;
                                        if ($product->Discount) {
                                            $price -= ($product->Discount / 100) * $price;
                                        }
                                    @endphp
                                    <option value="{{ $product->id }}" data-name="{{ $product->en_Product_Name }}" data-price="{{ $price }}">
                                        {{ $product->en_Product_Name }} 
                                        @if($product->Status != 1)
                                            <span class="text-danger">({{ __('Deactivated') }})</span>
                                        @endif
                                        ({{ number_format($price, 3) }} OMR)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle" id="products_table">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Product') }}</th>
                                        <th style="width: 120px;">{{ __('Price') }}</th>
                                        <th style="width: 100px;">{{ __('Qty') }}</th>
                                        <th style="width: 120px;">{{ __('Total') }}</th>
                                        <th style="width: 50px;">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="products_list">
                                    <!-- Products will be added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="p-4 rounded shadow-sm border" style="background-color: #f8f9fa;">
                            <h5 class="mb-4 border-bottom pb-2"><i class="fas fa-file-invoice-dollar me-2"></i>{{ __('Order Summary') }}</h5>
                            
                            <div class="input__group mb-25">
                                <label for="shipping_charge">{{ __('Shipping Charge (OMR)') }}</label>
                                <input type="number" step="0.001" min="0" name="shipping_charge" id="shipping_charge" class="form-control" value="0.000">
                            </div>
                            
                            <div class="input__group mb-25">
                                <label for="discount">{{ __('Discount (OMR)') }}</label>
                                <input type="number" step="0.001" min="0" name="discount" id="discount" class="form-control" value="0.000">
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">{{ __('Subtotal') }}:</span>
                                    <span id="subtotal_display" class="font-weight-bold">0.000</span>
                                </div>
                                <div class="d-flex justify-content-between mb-4">
                                    <h5 class="mb-0">{{ __('Grand Total:') }}</h5>
                                    <h5 class="mb-0 text-primary font-weight-bold"><span id="grand_total_display">0.000</span> <small>OMR</small></h5>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow-sm">
                                    <i class="fas fa-check-circle me-2"></i>{{ __('Create Order') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('post_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>
    $(document).ready(function() {
        const phoneInput = document.querySelector("#guest_phone_display");
        const iti = window.intlTelInput(phoneInput, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            initialCountry: "om",
            separateDialCode: true,
            preferredCountries: ["om", "ae", "sa", "kw", "bh", "qa"]
        });

        const hiddenPhone = document.querySelector("#guest_phone");

        function updateHiddenPhone() {
            if (iti.isValidNumber() || iti.isPossibleNumber()) {
                hiddenPhone.value = iti.getNumber();
            } else {
                hiddenPhone.value = phoneInput.value;
            }
        }

        phoneInput.addEventListener('input', updateHiddenPhone);
        phoneInput.addEventListener('countrychange', updateHiddenPhone);
        if ($('.select2').length) {
            $('.select2').select2();
        }

        let productIndex = 0;

        function updateTotals() {
            let subtotal = 0;
            $('.product-row').each(function() {
                let qty = parseFloat($(this).find('.product-qty').val()) || 0;
                let price = parseFloat($(this).find('.product-price').data('price')) || 0;
                let total = qty * price;
                $(this).find('.product-total').text(total.toFixed(3));
                subtotal += total;
            });

            let shipping = parseFloat($('#shipping_charge').val()) || 0;
            let discount = parseFloat($('#discount').val()) || 0;

            let grandTotal = subtotal + shipping - discount;
            $('#subtotal_display').text(subtotal.toFixed(3));
            $('#grand_total_display').text(grandTotal.toFixed(3));
        }

        // Toggle required fields and shipping charge based on collection method
        $('#collection_method').on('change', function() {
            const isPickup = $(this).val() === 'store_pickup';
            const locationFields = ['#state_id', '#city_id', '#area_id', '#street_address'];
            
            if (isPickup) {
                $('#shipping_charge').val('0.000').prop('readonly', true);
                locationFields.forEach(selector => {
                    $(selector).removeAttr('required');
                    $(selector).prev('label').find('.text-danger').hide();
                });
            } else {
                $('#shipping_charge').prop('readonly', false);
                locationFields.forEach(selector => {
                    $(selector).attr('required', 'required');
                    $(selector).prev('label').find('.text-danger').show();
                });
            }
            updateTotals();
        });

        // Trigger on load
        $('#collection_method').trigger('change');

        // Handle User Selection
        $('#user_id').on('change', function() {
            let val = $(this).val();
            if (val === 'guest') {
                $('.guest-fields').show();
                $('#guest_name, #guest_phone').prop('required', true);
            } else {
                $('.guest-fields').hide();
                $('#guest_name, #guest_phone').prop('required', false);
                
                // Pre-fill if needed, but the user said "can be guest"
                let selected = $(this).find('option:selected');
                $('#guest_name').val(selected.data('name'));
                
                // Set value for intl-tel-input
                let phone = selected.data('phone') || '';
                iti.setNumber(phone.startsWith('+') ? phone : '+' + (phone.startsWith('968') ? '' : '968') + phone);
                updateHiddenPhone();
                
                $('#guest_email').val(selected.data('email'));
            }
        });

        // Trigger on load
        $('#user_id').trigger('change');

        // State -> City
        $('#state_id').on('change', function() {
            let stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: '/get-cities-by-state/' + stateId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#city_id').empty().append('<option value="">{{ __("Select City") }}</option>');
                        $.each(data, function(key, value) {
                            $('#city_id').append('<option value="' + value.id + '">' + value.name_en + '</option>');
                        });
                        $('#area_id').empty().append('<option value="">{{ __("Select Area") }}</option>');
                    }
                });
            }
        });

        // City -> Area
        $('#city_id').on('change', function() {
            let cityId = $(this).val();
            if (cityId) {
                $.ajax({
                    url: '/get-areas-by-city/' + cityId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#area_id').empty().append('<option value="">{{ __("Select Area") }}</option>');
                        $.each(data, function(key, value) {
                            $('#area_id').append('<option value="' + value.id + '">' + value.name_en + '</option>');
                        });
                    }
                });
            }
        });

        // Area Change -> Get Shipping Charge
        $('#area_id').on('change', function() {
            let areaId = $(this).val();
            if (areaId) {
                $.ajax({
                    url: '/get-area-charge/' + areaId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.delivery_charge !== undefined) {
                            $('#shipping_charge').val(data.delivery_charge).trigger('input');
                        }
                    }
                });
            }
        });

        $('#add_product').on('change', function() {
            let selected = $(this).find('option:selected');
            if (!selected.val()) return;

            let id = selected.val();
            let name = selected.data('name');
            let price = selected.data('price');

            // Check if already added
            if ($(`.product-row[data-id="${id}"]`).length > 0) {
                let qtyInput = $(`.product-row[data-id="${id}"]`).find('.product-qty');
                qtyInput.val(parseInt(qtyInput.val()) + 1);
            } else {
                let row = `
                    <tr class="product-row" data-id="${id}">
                        <td>
                            ${name}
                            <input type="hidden" name="products[${productIndex}][id]" value="${id}">
                        </td>
                        <td class="product-price" data-price="${price}">${parseFloat(price).toFixed(3)}</td>
                        <td>
                            <input type="number" name="products[${productIndex}][quantity]" class="form-control product-qty" value="1" min="1" style="width: 80px;">
                        </td>
                        <td class="product-total">${parseFloat(price).toFixed(3)}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-product"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `;
                $('#products_list').append(row);
                productIndex++;
            }

            $(this).val('').trigger('change');
            updateTotals();
        });

        $(document).on('input', '.product-qty', updateTotals);
        $(document).on('click', '.remove-product', function() {
            $(this).closest('tr').remove();
            updateTotals();
        });
        $('#shipping_charge, #discount').on('input', updateTotals);
    });
</script>
@endpush
