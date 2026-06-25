@extends('front.layouts.new_design_layout')

@section('title', __('new_design.checkout_page.title'))

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
    $subtotalVal = subtotal();
    // Default tax rate or calculation based on Oman
    $taxVal = tax_amount($subtotalVal, 'Oman');
    $grandTotalVal = $subtotalVal + $taxVal;
@endphp

<!-- Main Wrapper with White Background -->
<div class="checkout-page bg-white text-[#1A4231] pb-24" dir="{{ $dir }}" style="font-family: 'Cairo', sans-serif;">

    <!-- Top Spacer -->
    <div class="h-6 bg-white"></div>

    <!-- Wide Container (Max-Width 1360px matching standard layout) -->
    <div class="container mx-auto px-4 lg:px-8 flex flex-col gap-8 max-w-[1360px]">

        <!-- Page Header -->
        <div class="text-start">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-[#1A4231] tracking-wide">
                {{ __('new_design.checkout_page.page_title') }}
            </h1>
            <p class="text-xs sm:text-sm font-semibold text-gray-400 mt-1">
                {{ __('new_design.checkout_page.subtitle') }}
            </p>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-xs sm:text-sm text-start">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Checkout Form & Grid -->
        <form action="{{ route('checkout.order') }}" method="POST" id="checkout-form">
            @csrf

            <!-- Hidden Fields for Backend Compatibility -->
            <input type="hidden" name="billing_country" value="Oman">
            <input type="hidden" name="billing_zipcode" value="00000">
            <input type="hidden" id="payment_method_input" name="payment" value="paypal">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Right Side: Main Checkout Form (2 columns) -->
                <div class="lg:col-span-2 flex flex-col gap-6">
                    
                    <!-- Box 1: Shipping Information -->
                    <div class="bg-white border border-gray-150 rounded-[24px] p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-6 text-start">
                            <span class="text-xl">🚚</span>
                            <h2 class="text-base sm:text-lg lg:text-xl font-bold text-[#1A4231]">
                                {{ __('new_design.checkout_page.shipping_info') }}
                            </h2>
                        </div>

                        <div class="flex flex-col gap-4">
                            <!-- Full Name -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs sm:text-sm font-bold text-gray-400 text-start">
                                    {{ __('new_design.checkout_page.full_name') }}
                                </label>
                                <input type="text" name="billing_name" value="{{ old('billing_name', $billing->Name ?? $user->name ?? '') }}" required
                                       class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all text-start placeholder:text-gray-300">
                            </div>

                            <!-- State & City in 2 Columns -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Governorate (State) -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs sm:text-sm font-bold text-gray-400 text-start">
                                        المنطقة / المحافظة
                                    </label>
                                    <select name="billing_state" id="billing_state_select" required
                                            class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all text-start bg-white">
                                        <option value="">اختر المحافظة</option>
                                        @foreach($states as $state)
                                            @php
                                                $stateName = $state->name_en;
                                                if ($isRtl) {
                                                    $translations = [
                                                        'Muscat' => 'مسقط',
                                                        'Dhofar' => 'ظفار',
                                                        'Musandam' => 'مسندم',
                                                        'Al Buraimi' => 'البريمي',
                                                        'Ad Dakhiliyah' => 'الداخلية',
                                                        'Al Dakhiliyah' => 'الداخلية',
                                                        'Al Batinah North' => 'شمال الباطنة',
                                                        'Al Batinah South' => 'جنوب الباطنة',
                                                        'Ash Sharqiyah North' => 'شمال الشرقية',
                                                        'Al Sharqiya North' => 'شمال الشرقية',
                                                        'Ash Sharqiyah South' => 'جنوب الشرقية',
                                                        'Al Sharqiya South' => 'جنوب الشرقية',
                                                        'Ad Dhahirah' => 'الظاهرة',
                                                        'Al Dhahirah' => 'الظاهرة',
                                                        'Al Wusta' => 'الوسطى',
                                                    ];
                                                    $stateName = $translations[$stateName] ?? (!empty($state->name_ar) ? $state->name_ar : $state->name_en);
                                                }
                                            @endphp
                                            <option value="{{ $state->id }}" {{ (old('billing_state', $billing->State ?? '') == $state->id) ? 'selected' : '' }}>
                                                {{ $stateName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Wilayat (City) -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs sm:text-sm font-bold text-gray-400 text-start">
                                        المدينة / الولاية
                                    </label>
                                    <select name="billing_city" id="billing_city_select" required
                                            class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all text-start bg-white">
                                        <option value="">اختر الولاية</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Area & Phone in 2 Columns -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Area (District) -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs sm:text-sm font-bold text-gray-400 text-start">
                                        الحي / المنطقة
                                    </label>
                                    <select name="billing_area" id="billing_area_select" required
                                            class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all text-start bg-white">
                                        <option value="">اختر الحي</option>
                                    </select>
                                </div>

                                <!-- Phone Number -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs sm:text-sm font-bold text-gray-400 text-start">
                                        {{ __('new_design.checkout_page.phone') }}
                                    </label>
                                    <input type="text" name="billing_phone" value="{{ old('billing_phone', $billing->phone_number ?? $user->Number ?? '') }}" required placeholder="{{ __('new_design.checkout_page.phone_placeholder') }}"
                                           class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all text-start placeholder:text-gray-300">
                                </div>
                            </div>

                            <!-- Detailed Address -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs sm:text-sm font-bold text-gray-400 text-start">
                                    {{ __('new_design.checkout_page.detailed_address') }}
                                </label>
                                <input type="text" name="billing_street_address" value="{{ old('billing_street_address', $billing->Street ?? '') }}" required placeholder="{{ __('new_design.checkout_page.detailed_address_placeholder') }}"
                                       class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all text-start placeholder:text-gray-300">
                            </div>
                        </div>
                    </div>

                    <!-- Box 2: Shipping Method -->
                    <div class="bg-white border border-gray-150 rounded-[24px] p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-6 text-start">
                            <span class="text-xl">📦</span>
                            <h2 class="text-base sm:text-lg lg:text-xl font-bold text-[#1A4231]">
                                {{ __('new_design.checkout_page.shipping_method') }}
                            </h2>
                        </div>

                        <div class="flex flex-col gap-4">
                            <!-- Option 1: Fast Shipping -->
                            <label class="shipping-method-card flex items-center justify-between p-4 border border-[#1A4231] bg-[#FAF9F5] rounded-2xl cursor-pointer hover:bg-[#FAF9F5]/70 transition-all">
                                <div class="flex items-center gap-3 text-start">
                                    <input type="radio" name="collection_method" value="delivery" checked
                                           class="w-4 h-4 text-[#1A4231] focus:ring-[#1A4231]">
                                    <div>
                                        <span class="block text-xs sm:text-sm font-black text-[#1A4231]">
                                            {{ __('new_design.checkout_page.fast_shipping') }}
                                        </span>
                                        <span class="block text-[11px] sm:text-xs text-gray-400 font-semibold mt-0.5">
                                            {{ __('new_design.checkout_page.fast_shipping_sub') }}
                                        </span>
                                    </div>
                                </div>
                                <span class="text-xs sm:text-sm font-black text-[#1A4231] whitespace-nowrap dynamic-shipping-fee">
                                    -- <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="inline-block align-middle" style="height: 1.2em; width: auto; margin-inline: 2px;">
                                </span>
                            </label>

                            <!-- Option 2: Store Pickup -->
                            <label class="shipping-method-card flex items-center justify-between p-4 border border-gray-200 bg-white rounded-2xl cursor-pointer hover:bg-[#FAF9F5]/40 transition-all">
                                <div class="flex items-center gap-3 text-start">
                                    <input type="radio" name="collection_method" value="store_pickup"
                                           class="w-4 h-4 text-[#1A4231] focus:ring-[#1A4231]">
                                    <div>
                                        <span class="block text-xs sm:text-sm font-black text-[#1A4231]">
                                            الاستلام من الفرع (Store Pickup)
                                        </span>
                                        <span class="block text-[11px] sm:text-xs text-gray-400 font-semibold mt-0.5">
                                            استلم طلبك مجاناً من أقرب فرع لك
                                        </span>
                                    </div>
                                </div>
                                <span class="text-xs sm:text-sm font-black text-[#1A4231] whitespace-nowrap">
                                    0.00 <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="inline-block align-middle" style="height: 1.2em; width: auto; margin-inline: 2px;">
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Box 2.5: Gifting Options -->
                    <div class="bg-white border border-gray-150 rounded-[24px] p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4 text-start">
                            <span class="text-xl">🎁</span>
                            <h2 class="text-base sm:text-lg lg:text-xl font-bold text-[#1A4231]">
                                {{ $isRtl ? 'خيارات الإهداء' : 'Gifting Options' }}
                            </h2>
                        </div>

                        <!-- Checkbox to toggle gifting details -->
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-200 rounded-xl hover:bg-[#FAF9F5]/40 transition-all text-start">
                            <input type="checkbox" id="is_gift" name="is_gift" value="1" onchange="toggleGiftFields(this)"
                                   class="w-4 h-4 text-[#1A4231] focus:ring-[#1A4231] rounded">
                            <div>
                                <span class="block text-xs sm:text-sm font-black text-[#1A4231]">
                                    {{ $isRtl ? 'شراء هذا الطلب كهدية لشخص آخر' : 'Purchase this order as a gift' }}
                                </span>
                                <span class="block text-[10px] sm:text-xs text-gray-400 font-semibold mt-0.5">
                                    {{ $isRtl ? 'سيتم تغليف الطلب وإرساله إلى المستلم مع رسالتك الخاصة' : 'The order will be wrapped and sent to the recipient with your custom message' }}
                                </span>
                            </div>
                        </label>

                        <!-- Gifting Input Fields (Hidden by default) -->
                        <div id="gift-fields-wrapper" class="hidden flex flex-col gap-4 mt-5 pt-4 border-t border-gray-100 text-start">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Recipient Name -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs sm:text-sm font-bold text-gray-400">
                                        {{ $isRtl ? 'اسم المستلم' : 'Recipient Name' }}
                                    </label>
                                    <input type="text" id="gift_recipient_name" name="gift_recipient_name" placeholder="{{ $isRtl ? 'أدخل اسم مستلم الهدية' : 'Enter recipient name' }}"
                                           class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all placeholder:text-gray-300">
                                </div>

                                <!-- Recipient Phone -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs sm:text-sm font-bold text-gray-400">
                                        {{ $isRtl ? 'رقم هاتف المستلم' : 'Recipient Phone Number' }}
                                    </label>
                                    <input type="tel" id="gift_recipient_phone" name="gift_recipient_phone" placeholder="968xxxxxxx"
                                           class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all placeholder:text-gray-300 text-start" dir="ltr">
                                </div>
                            </div>

                            <!-- Gift Message -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs sm:text-sm font-bold text-gray-400">
                                    {{ $isRtl ? 'رسالة الإهداء (تظهر على كرت الإهداء)' : 'Gift Message (will print on gift card)' }}
                                </label>
                                <textarea id="gift_message" name="gift_message" rows="3" placeholder="{{ $isRtl ? 'اكتب رسالتك هنا للمستلم...' : 'Write your message to the recipient here...' }}"
                                          class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all placeholder:text-gray-300"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Box 3: Payment Method -->
                    <div class="bg-white border border-gray-150 rounded-[24px] p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-6 text-start">
                            <span class="text-xl">💵</span>
                            <h2 class="text-base sm:text-lg lg:text-xl font-bold text-[#1A4231]">
                                {{ __('new_design.checkout_page.payment_method') }}
                            </h2>
                        </div>

                        <!-- Horizontal Tabs -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                            <!-- Tab 1: Mada / Card -->
                            <button type="button" onclick="selectPayment('paypal', this)"
                                    class="payment-tab-btn flex flex-col items-center justify-center p-4 border-2 border-[#1A4231] bg-[#FAF9F5] rounded-2xl transition-all gap-2 text-[#1A4231]">
                                <span class="text-lg">💳</span>
                                <span class="text-xs font-bold text-center leading-tight">
                                    {{ __('new_design.checkout_page.mada_card') }}
                                </span>
                            </button>

                            <!-- Tab 2: Apple Pay -->
                            <button type="button" onclick="selectPayment('paypal', this)"
                                    class="payment-tab-btn flex flex-col items-center justify-center p-4 border border-gray-200 bg-white rounded-2xl transition-all gap-2 text-gray-500 hover:border-[#1A4231] hover:bg-[#FAF9F5]/40">
                                <span class="text-lg">📱</span>
                                <span class="text-xs font-bold text-center leading-tight">
                                    Apple Pay
                                </span>
                            </button>

                            <!-- Tab 3: STC Pay -->
                            <button type="button" onclick="selectPayment('paypal', this)"
                                    class="payment-tab-btn flex flex-col items-center justify-center p-4 border border-gray-200 bg-white rounded-2xl transition-all gap-2 text-gray-500 hover:border-[#1A4231] hover:bg-[#FAF9F5]/40">
                                <span class="text-lg">💼</span>
                                <span class="text-xs font-bold text-center leading-tight">
                                    STC Pay
                                </span>
                            </button>

                            <!-- Tab 4: Cash on Delivery -->
                            <button type="button" onclick="selectPayment('COD', this)"
                                    class="payment-tab-btn flex flex-col items-center justify-center p-4 border border-gray-200 bg-white rounded-2xl transition-all gap-2 text-gray-500 hover:border-[#1A4231] hover:bg-[#FAF9F5]/40">
                                <span class="text-lg">🤝</span>
                                <span class="text-xs font-bold text-center leading-tight">
                                    {{ __('new_design.checkout_page.cod') }}
                                </span>
                            </button>
                        </div>

                        <!-- Card Fields (Visible only for Online Cards) -->
                        <div id="card-fields-box" class="flex flex-col gap-4 border-t border-gray-100 pt-5 text-start">
                            <!-- Card Number -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs sm:text-sm font-bold text-gray-400">
                                    {{ __('new_design.checkout_page.card_number') }}
                                </label>
                                <input type="text" placeholder="0000 0000 0000 0000" id="mada_card_number"
                                       class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all placeholder:text-gray-300">
                            </div>

                            <!-- Expiry & CVV in 2 columns -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Expiry Date -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs sm:text-sm font-bold text-gray-400">
                                        {{ __('new_design.checkout_page.expiry_date') }}
                                    </label>
                                    <input type="text" placeholder="MM/YY" id="mada_card_expiry"
                                           class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all placeholder:text-gray-300">
                                </div>

                                <!-- CVV -->
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs sm:text-sm font-bold text-gray-400">
                                        {{ __('new_design.checkout_page.cvv') }}
                                    </label>
                                    <input type="text" placeholder="123" id="mada_card_cvv"
                                           class="w-full px-4 py-3 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all placeholder:text-gray-300">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Left Side: Order Summary (1 column - Sticky) -->
                <div class="lg:sticky lg:top-8 flex flex-col gap-4">
                    
                    <!-- Summary Card -->
                    <div class="bg-white border-2 border-[#1A4231] rounded-[24px] overflow-hidden shadow-sm">
                        <!-- Card Header -->
                        <div class="bg-[#1A4231] text-white py-4 px-6 text-center">
                            <h3 class="text-lg font-black tracking-wide">
                                {{ __('new_design.cart_page.summary_title') }}
                            </h3>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex flex-col gap-5">
                            
                            <!-- Product Items List -->
                            <div class="flex flex-col max-h-[280px] overflow-y-auto pr-1">
                                @foreach($content as $item)
                                    <!-- Dynamic Product Item Row -->
                                    <div class="flex items-center gap-4 py-4 border-b border-gray-100 last:border-0">
                                        <!-- Image -->
                                        <div class="w-[60px] h-[60px] rounded-xl overflow-hidden shrink-0 bg-gray-50 border border-gray-100">
                                            @if(!empty($item->options->is_custom_box) && $item->options->image === 'trail-box.png')
                                                <img src="{{ asset('assets/elketar/trail-box.png') }}" 
                                                     alt="{{ $isRtl ? ($item->options->name_ar ?? $item->name) : $item->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <img src="{{ resolve_product_image($item->options->image) }}" 
                                                     alt="{{ $isRtl ? ($item->options->name_ar ?? $item->name) : $item->name }}" 
                                                     class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <!-- Info -->
                                        <div class="flex-grow flex flex-col justify-between self-stretch py-0.5 text-start">
                                            <div>
                                                <h4 class="text-xs sm:text-sm font-bold text-[#1A4231] line-clamp-1 leading-tight">
                                                    {{ $isRtl ? ($item->options->name_ar ?? $item->name) : $item->name }}
                                                </h4>
                                                <p class="text-[10px] font-semibold text-gray-400 mt-0.5">
                                                    @if($item->options->size)
                                                        {{ $isRtl ? ($item->options->size_ar ?? $item->options->size) : $item->options->size }}
                                                    @endif
                                                </p>
                                                @if(!empty($item->options->is_custom_box))
                                                    <div class="mt-1 bg-gray-50 p-2 rounded text-[10px] text-gray-500 font-semibold leading-relaxed border border-gray-100">
                                                        <div>
                                                            <span class="opacity-75">{{ $isRtl ? 'القالب:' : 'Template:' }}</span> {{ $item->options->template }}
                                                        </div>
                                                        <div>
                                                            <span class="opacity-75">{{ $isRtl ? 'السعة:' : 'Capacity:' }}</span> {{ $item->options->capacity }} {{ $isRtl ? 'محاصيل' : 'Crops' }}
                                                        </div>
                                                        @if(!empty($item->options->print_name))
                                                            <div>
                                                                <span class="opacity-75">{{ $isRtl ? 'الاسم المطبوع:' : 'Printed Name:' }}</span> {{ $item->options->print_name }}
                                                            </div>
                                                        @endif
                                                        @if(!empty($item->options->gift_message))
                                                            <div>
                                                                <span class="opacity-75">{{ $isRtl ? 'الرسالة:' : 'Message:' }}</span> {{ $item->options->gift_message }}
                                                            </div>
                                                        @endif
                                                        <div class="mt-1 border-t border-gray-200/50 pt-1 text-[9px]">
                                                            <span class="opacity-75 block">{{ $isRtl ? 'المحتويات:' : 'Contents:' }}</span>
                                                            <span class="font-bold text-gray-600">{{ $item->options->custom_box_details }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="text-xs sm:text-sm font-black text-[#1A4231] mt-0.5">
                                                {{ number_format($item->price, 2) }} <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="inline-block align-middle" style="height: 1.2em; width: auto; margin-inline: 2px;">
                                            </span>
                                        </div>
                                        <!-- Qty Badge -->
                                        <span class="bg-[#FAF9F5] border border-gray-150 px-2 py-1 rounded-lg text-[10px] sm:text-xs font-black text-[#1A4231] whitespace-nowrap">
                                            {{ $item->qty }}x
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Coupon Code Input -->
                            <div class="flex flex-col gap-2 border-t border-gray-100 pt-4">
                                <span class="text-xs sm:text-sm font-bold text-gray-400 text-start">
                                    هل لديك كود خصم؟
                                </span>
                                <div class="flex gap-2 items-center">
                                    <input type="text" id="coupon_code_input" placeholder="أدخل الكود" 
                                           class="flex-grow w-full px-4 py-2.5 text-xs sm:text-sm font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all text-start placeholder:text-gray-300">
                                    <button type="button" onclick="applyCoupon()"
                                            class="bg-[#E5E0D8] hover:bg-[#D5D0C8] text-[#1A4231] font-extrabold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition-all whitespace-nowrap">
                                        تطبيق
                                    </button>
                                </div>
                                <p id="coupon-message" class="text-[11px] font-bold text-start hidden"></p>
                            </div>

                            <!-- Pricing Breakdowns -->
                            <div class="flex flex-col gap-3 pt-2 border-t border-gray-100">
                                <!-- Subtotal -->
                                <div class="flex justify-between items-center text-xs sm:text-sm font-bold text-gray-400">
                                    <span>{{ __('new_design.cart_page.summary_subtotal') }}</span>
                                    <span id="summary-subtotal-val">{{ number_format($subtotalVal, 2) }} <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="inline-block align-middle" style="height: 1.2em; width: auto; margin-inline: 2px;"></span>
                                </div>

                                <!-- Shipping -->
                                <div class="flex justify-between items-center text-xs sm:text-sm font-bold text-gray-400">
                                    <span>الفرعي رسوم الشحن</span>
                                    <span id="summary-shipping-val">0.00 <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="inline-block align-middle" style="height: 1.2em; width: auto; margin-inline: 2px;"></span>
                                </div>

                                <!-- Tax -->
                                <div class="flex justify-between items-center text-xs sm:text-sm font-bold text-gray-400">
                                    <span>{{ __('new_design.cart_page.summary_tax') }}</span>
                                    <span id="summary-tax-val">{{ number_format($taxVal, 2) }} <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="inline-block align-middle" style="height: 1.2em; width: auto; margin-inline: 2px;"></span>
                                </div>

                                <!-- Coupon Discount (Hidden initially) -->
                                <div id="summary-discount-row" class="flex justify-between items-center text-xs sm:text-sm font-bold text-red-500 hidden">
                                    <span>خصم الكوبون</span>
                                    <span id="summary-discount-val">-0.00 <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="inline-block align-middle" style="height: 1.2em; width: auto; margin-inline: 2px;"></span>
                                </div>

                                <!-- Grand Total -->
                                <div class="pt-4 border-t border-gray-100 flex justify-between items-center text-[#1A4231]">
                                    <span class="text-base sm:text-lg font-black">{{ __('new_design.cart_page.summary_total') }}</span>
                                    <span id="summary-total-val" class="text-lg sm:text-xl font-black">{{ number_format($grandTotalVal, 2) }} <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="inline-block align-middle" style="height: 1.2em; width: auto; margin-inline: 2px;"></span>
                                </div>
                            </div>

                            <!-- Submit Checkout Button -->
                            <button type="submit" class="w-full bg-[#1A4231] hover:bg-[#2C624A] text-white py-3.5 rounded-[16px] text-sm lg:text-base font-black transition-all shadow-md mt-2 flex items-center justify-center gap-2">
                                <span>🔒</span>
                                <span>{{ __('new_design.checkout_page.place_order') }}</span>
                            </button>

                            <!-- Terms Notice -->
                            <p class="text-[10px] text-gray-400 font-semibold leading-normal text-center px-2">
                                {{ __('new_design.checkout_page.checkout_agree') }}
                            </p>
                        </div>
                    </div>

                    <!-- Support Card -->
                    <div class="bg-[#FAF9F5] border border-gray-150 rounded-[20px] p-4 flex items-center justify-between gap-4 shadow-sm">
                        <div class="text-start">
                            <h4 class="text-xs sm:text-sm font-bold text-[#1A4231]">
                                {{ __('new_design.checkout_page.need_help') }}
                            </h4>
                            <p class="text-[11px] sm:text-xs text-gray-400 font-semibold mt-0.5">
                                {{ __('new_design.checkout_page.whatsapp_support') }}
                            </p>
                        </div>
                        <!-- Whatsapp Button -->
                        <a href="https://wa.me/96812345678" target="_blank"
                           class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center shadow-sm text-green-500 hover:scale-105 transition-transform shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                <path d="M12.012 2C6.48 2 2 6.48 2 12.012c0 1.767.46 3.427 1.258 4.896L2 22l5.244-1.222a9.96 9.96 0 0 0 4.768 1.234c5.532 0 10.012-4.48 10.012-10.012S17.544 2 12.012 2zm6.36 14.3c-.26.732-1.314 1.348-1.802 1.414-.476.064-.954.128-2.934-.683-2.528-1.037-4.136-3.622-4.263-3.792-.12-.17-1.015-1.348-1.015-2.567 0-1.22.637-1.819.865-2.062.228-.244.496-.305.662-.305.166 0 .332.008.476.014.148.006.348-.053.546.425.2.482.68 1.66.738 1.777.06.117.1.255.02.414-.08.16-.12.26-.24.398-.12.138-.255.31-.365.414-.12.112-.246.234-.106.474.14.24.624 1.025 1.338 1.66.917.818 1.69 1.072 1.93 1.19.24.118.38.096.52-.064.14-.16.6-1.002.76-1.348.16-.346.32-.288.54-.207.22.08 1.398.66 1.637.779.24.119.398.18.457.278.06.1.06.578-.2 1.31z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Premium CTA Banner Section -->
<div class="w-full relative overflow-hidden bg-[#FAF9F5] border-t border-gray-100 py-16" dir="{{ $dir }}">
    <div class="container mx-auto px-4 lg:px-8 max-w-[1140px] relative z-10">
        <!-- Floating Coffee Beans Mockup Styling (Decorative background shapes) -->
        <div class="absolute -left-20 top-0 w-40 h-40 bg-[url('/assets/elketar/coffee.png')] bg-cover opacity-10 pointer-events-none"></div>
        <div class="absolute -right-20 bottom-0 w-40 h-40 bg-[url('/assets/elketar/coffee.png')] bg-cover opacity-10 pointer-events-none"></div>

        <div class="bg-[#1A4231] rounded-[32px] p-8 sm:p-12 text-center text-white relative overflow-hidden shadow-xl">
            <!-- Decorative inner shadow overlay -->
            <div class="absolute inset-0 bg-black/10 pointer-events-none"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto flex flex-col items-center gap-6">
                <!-- Icon -->
                <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-2xl">
                    ✉️
                </div>
                
                <!-- Headings -->
                <div class="flex flex-col gap-2">
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-wide">
                        كن أول من يعرف عنا
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-300 font-semibold">
                        سجل وسياتيك كل ما هو جديد
                    </p>
                </div>
                
                <!-- Form Row -->
                <form action="{{ route('subscribe') }}" method="POST" class="w-full flex flex-col sm:flex-row gap-3 max-w-lg mt-2">
                    @csrf
                    <input type="email" name="email" required placeholder="بريدك الإلكتروني" 
                           class="flex-grow w-full px-6 py-4 rounded-full bg-white/10 text-white font-bold text-xs sm:text-sm outline-none border border-white/20 focus:border-white focus:bg-white/20 placeholder:text-gray-300/80 transition-all text-center sm:text-start">
                    <button type="submit" 
                            class="bg-white hover:bg-gray-100 text-[#1A4231] font-black px-8 py-4 rounded-full text-xs sm:text-sm transition-all whitespace-nowrap shadow-md hover:shadow-lg shrink-0">
                        ارسل الان
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Toggle active state for payment tabs
    function selectPayment(methodVal, buttonEl) {
        // Set hidden input value
        document.getElementById('payment_method_input').value = methodVal;
        
        // Remove active styling from all buttons
        document.querySelectorAll('.payment-tab-btn').forEach(btn => {
            btn.classList.remove('border-2', 'border-[#1A4231]', 'bg-[#FAF9F5]', 'text-[#1A4231]');
            btn.classList.add('border-gray-200', 'bg-white', 'text-gray-500');
        });

        // Add active styling to clicked button
        buttonEl.classList.remove('border-gray-200', 'bg-white', 'text-gray-500');
        buttonEl.classList.add('border-2', 'border-[#1A4231]', 'bg-[#FAF9F5]', 'text-[#1A4231]');

        // Show/hide Card details box based on selected method
        const cardBox = document.getElementById('card-fields-box');
        if (methodVal === 'paypal') {
            cardBox.style.display = 'flex';
            document.getElementById('mada_card_number').required = true;
            document.getElementById('mada_card_expiry').required = true;
            document.getElementById('mada_card_cvv').required = true;
        } else {
            cardBox.style.display = 'none';
            document.getElementById('mada_card_number').required = false;
            document.getElementById('mada_card_expiry').required = false;
            document.getElementById('mada_card_cvv').required = false;
        }
    }

    // Dynamic state, city and area loader with shipping rate recalculation
    document.addEventListener('DOMContentLoaded', function() {
        const stateSelect = document.getElementById('billing_state_select');
        const citySelect = document.getElementById('billing_city_select');
        const areaSelect = document.getElementById('billing_area_select');
        
        const isRtl = "{{ $isRtl }}";

        // Load saved address configurations if they exist
        const savedState = "{{ old('billing_state', $billing->State ?? '') }}";
        const savedCity = "{{ old('billing_city', $billing->City ?? '') }}";
        const savedArea = "{{ old('billing_area', $billing->area ?? '') }}";

        if (savedState) {
            loadCities(savedState, savedCity, () => {
                if (savedCity) {
                    loadAreas(savedCity, savedArea, () => {
                        if (savedArea) {
                            updateDeliveryChargeByArea(savedArea);
                        }
                    });
                }
            });
        }

        // State Change Event
        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            citySelect.innerHTML = '<option value="">اختر الولاية</option>';
            areaSelect.innerHTML = '<option value="">اختر الحي</option>';
            if (stateId) {
                loadCities(stateId);
            }
        });

        // City Change Event
        citySelect.addEventListener('change', function() {
            const cityId = this.value;
            areaSelect.innerHTML = '<option value="">اختر الحي</option>';
            if (cityId) {
                loadAreas(cityId);
            }
        });

        // Area Change Event
        areaSelect.addEventListener('change', function() {
            const areaId = this.value;
            if (areaId) {
                updateDeliveryChargeByArea(areaId);
            }
        });

        function loadCities(stateId, selectedCityId = '', callback = null) {
            fetch(`/get-cities-by-state/${stateId}`)
                .then(res => res.json())
                .then(cities => {
                    citySelect.innerHTML = '<option value="">اختر الولاية</option>';
                    cities.forEach(city => {
                        const name = isRtl ? (city.name_ar || city.name_en) : (city.name_en || city.name_ar);
                        const selected = (city.id == selectedCityId) ? 'selected' : '';
                        citySelect.innerHTML += `<option value="${city.id}" ${selected}>${name}</option>`;
                    });
                    if (callback) callback();
                })
                .catch(err => console.error('Error fetching cities:', err));
        }

        function loadAreas(cityId, selectedAreaId = '', callback = null) {
            fetch(`/get-areas-by-city/${cityId}`)
                .then(res => res.json())
                .then(areas => {
                    areaSelect.innerHTML = '<option value="">اختر الحي</option>';
                    areas.forEach(area => {
                        const name = isRtl ? (area.name_ar || area.name_en) : (area.name_en || area.name_ar);
                        const selected = (area.id == selectedAreaId) ? 'selected' : '';
                        areaSelect.innerHTML += `<option value="${area.id}" ${selected}>${name}</option>`;
                    });
                    if (callback) callback();
                })
                .catch(err => console.error('Error fetching areas:', err));
        }

        function updateDeliveryChargeByArea(areaId) {
            fetch(`/get-area-charge/${areaId}`)
                .then(res => res.json())
                .then(data => {
                    // Update Shipping elements
                    const formattedCharge = data.formatted_charge || `${data.delivery_charge.toFixed(2)} ر.س`;
                    document.getElementById('summary-shipping-val').innerText = formattedCharge;
                    
                    // Update dynamic badge in fast shipping option
                    document.querySelectorAll('.dynamic-shipping-fee').forEach(el => {
                        el.innerText = formattedCharge;
                    });

                    // Update Tax and Totals
                    if (data.tax_show) {
                        document.getElementById('summary-tax-val').innerText = data.tax_show;
                    }
                    if (data.total_cost) {
                        document.getElementById('summary-total-val').innerText = data.total_cost;
                    }

                    // Handle coupon discount if active
                    const discountRow = document.getElementById('summary-discount-row');
                    if (data.coupon_amount && data.coupon_amount > 0) {
                        discountRow.classList.remove('hidden');
                        document.getElementById('summary-discount-val').innerText = `-${data.coupon_amount.toFixed(2)} ر.س`;
                    }
                })
                .catch(err => console.error('Error fetching area charge:', err));
        }

        // Listener for collection method (Store pickup toggler)
        document.querySelectorAll('input[name="collection_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const isPickup = this.value === 'store_pickup';
                const shippingFields = ['billing_state_select', 'billing_city_select', 'billing_area_select'];
                
                shippingFields.forEach(fieldId => {
                    const el = document.getElementById(fieldId);
                    if (isPickup) {
                        el.removeAttribute('required');
                        el.disabled = true;
                    } else {
                        el.setAttribute('required', 'required');
                        el.disabled = false;
                    }
                });

                if (isPickup) {
                    // Force shipping to 0 and recalculate total
                    document.getElementById('summary-shipping-val').innerText = '0.00 ر.س';
                    const subtotal = parseFloat("{{ $subtotalVal }}");
                    const tax = parseFloat("{{ $taxVal }}");
                    document.getElementById('summary-total-val').innerText = (subtotal + tax).toFixed(2) + ' ر.س';
                } else {
                    // Restore area selection charge
                    const selectedArea = areaSelect.value;
                    if (selectedArea) {
                        updateDeliveryChargeByArea(selectedArea);
                    }
                }
            });
        });
    });

    // Apply Coupon via AJAX
    function applyCoupon() {
        const code = document.getElementById('coupon_code_input').value.trim();
        const msgEl = document.getElementById('coupon-message');
        if (!code) {
            msgEl.className = "text-[11px] font-bold text-start text-red-500 mt-1";
            msgEl.innerText = "الرجاء إدخال كود الخصم";
            msgEl.classList.remove('hidden');
            return;
        }

        fetch("{{ route('apply.coupon') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ coupon_code: code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                msgEl.className = "text-[11px] font-bold text-start text-green-500 mt-1";
                msgEl.innerText = "تم تطبيق الكوبون بنجاح!";
                msgEl.classList.remove('hidden');

                // Refresh pricing elements dynamically (re-fetch current area charge to update sums)
                const selectedArea = document.getElementById('billing_area_select').value;
                if (selectedArea) {
                    updateDeliveryChargeByArea(selectedArea);
                } else {
                    location.reload(); // Fallback reload
                }
            } else {
                msgEl.className = "text-[11px] font-bold text-start text-red-500 mt-1";
                msgEl.innerText = data.message || "كود الخصم غير صالح";
                msgEl.classList.remove('hidden');
            }
        })
        .catch(err => {
            msgEl.className = "text-[11px] font-bold text-start text-red-500 mt-1";
            msgEl.innerText = "حدث خطأ أثناء تطبيق كود الخصم";
            msgEl.classList.remove('hidden');
        });
    }

    function toggleGiftFields(checkbox) {
        const wrapper = document.getElementById('gift-fields-wrapper');
        const nameInput = document.getElementById('gift_recipient_name');
        const phoneInput = document.getElementById('gift_recipient_phone');
        
        if (checkbox.checked) {
            wrapper.classList.remove('hidden');
            nameInput.required = true;
            phoneInput.required = true;
        } else {
            wrapper.classList.add('hidden');
            nameInput.required = false;
            phoneInput.required = false;
            nameInput.value = '';
            phoneInput.value = '';
            document.getElementById('gift_message').value = '';
        }
    }
</script>
@endpush
