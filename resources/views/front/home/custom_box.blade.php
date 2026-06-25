@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<style>
    .custom-box-page {
        font-family: 'Cairo', sans-serif;
    }
    .progress-bar-fill {
        transition: width 0.4s ease-in-out;
    }
</style>

<div class="custom-box-page bg-white text-[#1A4231] pb-20" dir="{{ $dir }}">

    <!-- Hero Header -->
    <section class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto">
            <div class="relative overflow-hidden rounded-[40px] shadow-2xl min-h-[260px] flex items-center p-8 lg:p-12 text-white" 
                 style="background-image: url('{{ asset('assets/elketar/Hero Section.png') }}'); background-size: cover; background-position: center;">
                
                <div class="absolute inset-0 bg-gradient-to-t from-[#1A4231]/95 via-[#1A4231]/40 to-[#1A4231]/10 z-0"></div>

                <div class="relative z-10 max-w-4xl text-start">
                    <span class="inline-block bg-[#FBF0D8] text-[#1A4231] font-bold text-xs px-4 py-1.5 rounded-full mb-3 shadow-sm uppercase">
                        {{ $isRtl ? 'صمم بوكسك الخاص' : 'Design Your Custom Box' }}
                    </span>
                    <h1 class="text-3xl sm:text-5xl font-black text-[#FBF0D8] leading-tight mb-3">
                        {{ $isRtl ? 'نظام البوكسات المخصصة' : 'Custom Box Builder' }}
                    </h1>
                    <p class="text-white/90 text-sm lg:text-base font-semibold max-w-2xl">
                        {{ $isRtl 
                            ? 'اختر تصميم البوكس المفضل لديك، حدد السعة المطلوبة، ثم املأه بما تحب من محاصيل القهوة الفاخرة أو أدوات التحضير للحصول على تجربة فريدة ومخصصة لك تماماً.' 
                            : 'Choose your box design, specify the capacity, and fill it with your favorite coffee crops or preparation tools for a completely tailored experience.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Builder Grid -->
    <section class="container mx-auto px-4 lg:px-8">
        <form id="custom-box-form" action="{{ route('add.custom.box') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            @csrf

            <!-- Left 2 Columns: Configurations & Product Selection -->
            <div class="lg:col-span-2 flex flex-col gap-8">

                <!-- Step 1: Select Template -->
                <div class="bg-white border border-gray-150 rounded-[28px] p-6 shadow-sm text-start">
                    <h3 class="text-lg lg:text-xl font-bold mb-4 flex items-center gap-2">
                        <span class="text-xl">🎨</span>
                        {{ $isRtl ? '1. اختر تصميم البوكس' : '1. Choose Box Design' }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($templates as $index => $tmpl)
                            <label class="relative border p-4 rounded-2xl cursor-pointer hover:bg-[#FAF9F5]/70 transition-all block group text-center select-box-template {{ $index === 0 ? 'border-2 border-[#1A4231] bg-[#FAF9F5]' : 'border-gray-200' }}"
                                   data-price="{{ (float) $tmpl->price }}">
                                <input type="radio" name="template" value="{{ $tmpl->name_en }}" {{ $index === 0 ? 'checked' : '' }} class="hidden">
                                <div class="w-16 h-16 mx-auto rounded-xl flex items-center justify-center text-white mb-3 shadow-md group-hover:scale-105 transition-transform"
                                     style="background-color: {{ $tmpl->color_code ?: '#1A4231' }};">
                                    <span class="text-2xl">📦</span>
                                </div>
                                <span class="block text-sm font-black text-[#1A4231]">{{ $isRtl ? $tmpl->name_ar : $tmpl->name_en }}</span>
                                <span class="block text-[11px] text-gray-400 font-semibold mt-1">{{ $isRtl ? $tmpl->description_ar : $tmpl->description_en }}</span>
                                <span class="block text-xs font-black text-[#1A4231] mt-1">{{ number_format($tmpl->price, 3) }} <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="currency-logo"></span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Step 2: Choose Capacity -->
                <div class="bg-white border border-gray-150 rounded-[28px] p-6 shadow-sm text-start">
                    <h3 class="text-lg lg:text-xl font-bold mb-4 flex items-center gap-2">
                        <span class="text-xl">📏</span>
                        {{ $isRtl ? '2. حدد حجم البوكس (السعة)' : '2. Select Box Size (Capacity)' }}
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Size 4 -->
                        <label class="relative border-2 border-[#1A4231] bg-[#FAF9F5] p-5 rounded-2xl cursor-pointer hover:bg-[#FAF9F5]/70 transition-all flex items-center justify-between select-box-capacity">
                            <input type="radio" name="capacity" value="4" checked class="hidden">
                            <div class="text-start">
                                <span class="block text-base font-black text-[#1A4231]">{{ $isRtl ? 'بوكس متوسط (4 عناصر)' : 'Medium Box (4 Items)' }}</span>
                                <span class="block text-xs text-gray-400 font-semibold mt-1">{{ $isRtl ? 'مناسب كهدية خفيفة أو للتجربة' : 'Perfect for a light gift or trial' }}</span>
                            </div>
                            <span class="bg-[#1A4231] text-white font-bold text-xs px-3 py-1.5 rounded-full">4 Items</span>
                        </label>

                        <!-- Size 6 -->
                        <label class="relative border border-gray-200 p-5 rounded-2xl cursor-pointer hover:bg-[#FAF9F5]/40 transition-all flex items-center justify-between select-box-capacity">
                            <input type="radio" name="capacity" value="6" class="hidden">
                            <div class="text-start">
                                <span class="block text-base font-black text-[#1A4231]">{{ $isRtl ? 'بوكس كبير (6 عناصر)' : 'Large Box (6 Items)' }}</span>
                                <span class="block text-xs text-gray-400 font-semibold mt-1">{{ $isRtl ? 'يتسع لتشكيلة أوسع من المحاصيل والأكواب' : 'Fits a wider assortment of items' }}</span>
                            </div>
                            <span class="bg-gray-150 text-gray-600 font-bold text-xs px-3 py-1.5 rounded-full">6 Items</span>
                        </label>

                    </div>
                </div>

                <!-- Step 3: Select Products to Fill Box -->
                <div class="bg-white border border-gray-150 rounded-[28px] p-6 shadow-sm text-start">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 pb-4 mb-6 gap-4">
                        <h3 class="text-lg lg:text-xl font-bold flex items-center gap-2">
                            <span class="text-xl">☕</span>
                            {{ $isRtl ? '3. اختر محتويات البوكس' : '3. Choose Box Items' }}
                        </h3>
                        
                        <!-- Tabs -->
                        <div class="flex bg-gray-100 p-1 rounded-xl shrink-0">
                            <button type="button" id="tab-btn-crops" onclick="switchTab('crops')" class="px-4 py-2 font-bold text-xs sm:text-sm rounded-lg bg-[#1A4231] text-white transition-all">
                                {{ $isRtl ? 'المحاصيل' : 'Crops' }}
                            </button>
                            <button type="button" id="tab-btn-tools" onclick="switchTab('tools')" class="px-4 py-2 font-bold text-xs sm:text-sm rounded-lg text-gray-500 hover:text-gray-700 transition-all">
                                {{ $isRtl ? 'أدوات وأكواب' : 'Tools & Cups' }}
                            </button>
                        </div>
                    </div>

                    <!-- Products Grid: Crops (Active) -->
                    <div id="grid-crops" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($crops as $prod)
                            <div class="border border-gray-150 rounded-2xl p-4 flex gap-4 hover:shadow-md transition-shadow relative">
                                <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                                    <img src="{{ resolve_product_image($prod->Primary_Image) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow flex flex-col justify-between text-start">
                                    <div>
                                        <h4 class="text-sm font-bold text-[#1A4231] line-clamp-1">{{ $isRtl ? $prod->fr_Product_Name : $prod->en_Product_Name }}</h4>
                                        <span class="text-xs font-black text-[#1A4231] block mt-1">{{ number_format($prod->Price, 3) }} <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="currency-logo"></span>
                                    </div>
                                    
                                    <!-- Add / Remove Quantity buttons -->
                                    <div class="flex items-center gap-2.5 mt-2">
                                        <button type="button" onclick="decrementProduct({{ $prod->id }}, {{ $prod->Price }}, '{{ addslashes($isRtl ? $prod->fr_Product_Name : $prod->en_Product_Name) }}')"
                                                class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center font-bold text-gray-500 hover:bg-gray-100 active:scale-95 transition-all">-</button>
                                        <span id="qty-display-{{ $prod->id }}" class="font-extrabold text-sm text-[#1A4231] min-w-[20px] text-center">0</span>
                                        <button type="button" onclick="incrementProduct({{ $prod->id }}, {{ $prod->Price }}, '{{ addslashes($isRtl ? $prod->fr_Product_Name : $prod->en_Product_Name) }}', {{ (int) $prod->virtual_stock }})"
                                                class="w-8 h-8 rounded-full border border-[#1A4231] text-[#1A4231] bg-[#FAF9F5] flex items-center justify-center font-bold hover:bg-[#1A4231] hover:text-white active:scale-95 transition-all">+</button>
                                    </div>
                                </div>
                                <input type="hidden" name="products[{{ $prod->id }}]" id="qty-input-{{ $prod->id }}" value="0">
                            </div>
                        @empty
                            <p class="col-span-full text-center py-6 text-gray-400 font-semibold">{{ $isRtl ? 'لا توجد محاصيل قهوة متوفرة حالياً.' : 'No coffee crops available right now.' }}</p>
                        @endforelse
                    </div>

                    <!-- Products Grid: Tools (Hidden Initially) -->
                    <div id="grid-tools" class="grid grid-cols-1 sm:grid-cols-2 gap-4 hidden">
                        @forelse($tools as $prod)
                            <div class="border border-gray-150 rounded-2xl p-4 flex gap-4 hover:shadow-md transition-shadow relative">
                                <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                                    <img src="{{ resolve_product_image($prod->Primary_Image) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow flex flex-col justify-between text-start">
                                    <div>
                                        <h4 class="text-sm font-bold text-[#1A4231] line-clamp-1">{{ $isRtl ? $prod->fr_Product_Name : $prod->en_Product_Name }}</h4>
                                        <span class="text-xs font-black text-[#1A4231] block mt-1">{{ number_format($prod->Price, 3) }} <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="currency-logo"></span>
                                    </div>
                                    
                                    <!-- Add / Remove Quantity buttons -->
                                    <div class="flex items-center gap-2.5 mt-2">
                                        <button type="button" onclick="decrementProduct({{ $prod->id }}, {{ $prod->Price }}, '{{ addslashes($isRtl ? $prod->fr_Product_Name : $prod->en_Product_Name) }}')"
                                                class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center font-bold text-gray-500 hover:bg-gray-100 active:scale-95 transition-all">-</button>
                                        <span id="qty-display-{{ $prod->id }}" class="font-extrabold text-sm text-[#1A4231] min-w-[20px] text-center">0</span>
                                        <button type="button" onclick="incrementProduct({{ $prod->id }}, {{ $prod->Price }}, '{{ addslashes($isRtl ? $prod->fr_Product_Name : $prod->en_Product_Name) }}', {{ (int) $prod->virtual_stock }})"
                                                class="w-8 h-8 rounded-full border border-[#1A4231] text-[#1A4231] bg-[#FAF9F5] flex items-center justify-center font-bold hover:bg-[#1A4231] hover:text-white active:scale-95 transition-all">+</button>
                                    </div>
                                </div>
                                <input type="hidden" name="products[{{ $prod->id }}]" id="qty-input-{{ $prod->id }}" value="0">
                            </div>
                        @empty
                            <p class="col-span-full text-center py-6 text-gray-400 font-semibold">{{ $isRtl ? 'لا توجد أدوات متوفرة حالياً.' : 'No tools available right now.' }}</p>
                        @endforelse
                    </div>

                </div>

            </div>

            <!-- Right 1 Column: Sticky Builder Status & Checkout -->
            <div class="lg:sticky lg:top-8 flex flex-col gap-6">

                <!-- Builder Status Card -->
                <div class="bg-white border-2 border-[#1A4231] rounded-[28px] overflow-hidden shadow-xl">
                    <div class="bg-[#1A4231] text-white py-4 px-6 text-center">
                        <h3 class="text-base sm:text-lg font-black tracking-wide">
                            {{ $isRtl ? 'تفاصيل البوكس المخصص' : 'Custom Box Summary' }}
                        </h3>
                    </div>

                    <div class="p-6 flex flex-col gap-6">
                        
                        <!-- Progress Level / Bar -->
                        <div class="flex flex-col gap-2 text-start">
                            <div class="flex justify-between items-center text-sm font-bold">
                                <span class="text-gray-400">{{ $isRtl ? 'حالة التعبئة' : 'Box Fill Level' }}</span>
                                <span class="text-[#1A4231] font-black"><span id="fill-count">0</span> / <span id="capacity-count">4</span></span>
                            </div>
                            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div id="progress-bar" class="progress-bar-fill h-full bg-[#1A4231]" style="width: 0%;"></div>
                            </div>
                        </div>

                        <!-- Selected Items List -->
                        <div class="flex flex-col gap-3 text-start">
                            <span class="text-xs font-bold text-gray-400 border-b border-gray-100 pb-2">{{ $isRtl ? 'العناصر المختارة:' : 'Selected Items:' }}</span>
                            <ul id="selected-items-summary" class="text-xs sm:text-sm font-semibold space-y-2 text-gray-700 min-h-[60px]">
                                <li class="text-gray-400 italic text-center py-4">{{ $isRtl ? 'لم تختر أي عناصر بعد' : 'No items selected yet' }}</li>
                            </ul>
                        </div>

                        <!-- Gifting/Personalization (Name to print & gift message) -->
                        <div class="border-t border-gray-100 pt-4 flex flex-col gap-4 text-start">
                            
                            <!-- Custom Name to print on Box -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-bold text-gray-400 flex items-center gap-1">
                                    {{ $isRtl ? 'الاسم للطباعة على البوكس (اختياري)' : 'Name to Print on Box (Optional)' }}
                                    <span class="text-red-500 font-bold" title="{{ $isRtl ? 'الطباعة المخصصة تستغرق يومين إضافيين للتحضير' : 'Custom printing requires 2 extra days for preparation' }}">⚠️</span>
                                </label>
                                <input type="text" name="print_name" id="print_name" placeholder="{{ $isRtl ? 'اسم للطباعة (يستغرق يومين للتجهيز)' : 'Name to print (takes 2 days)' }}"
                                       class="w-full px-4 py-3 text-xs font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all">
                                <span class="text-[10px] text-gray-400 font-medium leading-relaxed">{{ $isRtl ? 'تنبيه: طباعة الأسماء المخصصة تتطلب يومين إضافيين لتجهيز الطلب.' : 'Notice: printing custom names adds 2 days to order preparation.' }}</span>
                            </div>

                            <!-- Gift Message -->
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-bold text-gray-400">
                                    {{ $isRtl ? 'رسالة الإهداء (اختياري)' : 'Gift Message (Optional)' }}
                                </label>
                                <textarea name="gift_message" id="gift_message" rows="2" placeholder="{{ $isRtl ? 'اكتب رسالتك للمستلم هنا...' : 'Write message for recipient...' }}"
                                          class="w-full px-4 py-3 text-xs font-bold rounded-xl border border-gray-200 outline-none focus:ring-1 focus:ring-[#1A4231] transition-all"></textarea>
                            </div>

                        </div>

                        <!-- Pricing Breakdown -->
                        <div class="border-t border-gray-100 pt-4 flex flex-col gap-2 text-start">
                            <div class="flex justify-between items-center text-xs sm:text-sm font-semibold text-gray-500">
                                <span>{{ $isRtl ? 'سعر التغليف والتحضير' : 'Box Prep & Packaging' }}</span>
                                <span id="box-packaging-price-val">{{ number_format($templates->first() ? $templates->first()->price : 2.0, 3) }} <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="currency-logo"></span>
                            </div>
                            <div class="flex justify-between items-center text-xs sm:text-sm font-semibold text-gray-500">
                                <span>{{ $isRtl ? 'سعر المحتويات' : 'Content Price' }}</span>
                                <span id="content-price-val">0.000 <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="currency-logo"></span>
                            </div>
                            <div class="flex justify-between items-center text-[#1A4231] pt-3 border-t border-dashed border-gray-150">
                                <span class="text-base font-black">{{ $isRtl ? 'إجمالي السعر' : 'Total Price' }}</span>
                                <span id="total-price-val" class="text-lg sm:text-xl font-black">2.000 <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="currency-logo"></span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button type="submit" id="add-to-cart-btn" class="w-full bg-[#1A4231] hover:opacity-90 active:scale-[0.98] text-white py-4 rounded-full font-bold shadow-lg transition-all flex items-center justify-center gap-2">
                            <span>🛒</span>
                            <span>{{ $isRtl ? 'أضف البوكس للسلة' : 'Add Box to Cart' }}</span>
                        </button>

                    </div>
                </div>

            </div>

        </form>
    </section>

</div>

@endsection

@push('scripts')
<script>
    const currencyLogoHtml = `<img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="currency-logo">`;
    let currentCapacity = 4;
    let selectedQty = 0;
    let basePrice = {{ (float) ($templates->first() ? $templates->first()->price : 2.0) }};
    let contentPrice = 0.000;

    let selectedProductsMap = {}; // {productId: {qty: 0, price: 0, name: ''}}

    document.addEventListener('DOMContentLoaded', function() {
        
        // Listeners for Capacity changes
        document.querySelectorAll('.select-box-capacity').forEach(el => {
            el.addEventListener('click', function() {
                // Clear active states
                document.querySelectorAll('.select-box-capacity').forEach(c => {
                    c.classList.remove('border-2', 'border-[#1A4231]', 'bg-[#FAF9F5]');
                    c.classList.add('border-gray-200');
                    c.querySelector('span.bg-[#1A4231]')?.classList.replace('bg-[#1A4231]', 'bg-gray-150');
                    c.querySelector('span.text-white')?.classList.replace('text-white', 'text-gray-600');
                });

                // Set active
                this.classList.remove('border-gray-200');
                this.classList.add('border-2', 'border-[#1A4231]', 'bg-[#FAF9F5]');
                const badge = this.querySelector('span');
                if (badge) {
                    badge.className = "bg-[#1A4231] text-white font-bold text-xs px-3 py-1.5 rounded-full";
                }

                currentCapacity = parseInt(this.querySelector('input').value);
                document.getElementById('capacity-count').innerText = currentCapacity;
                updateProgressBar();
            });
        });

        // Listeners for Template changes
        document.querySelectorAll('.select-box-template').forEach(el => {
            el.addEventListener('click', function() {
                document.querySelectorAll('.select-box-template').forEach(t => {
                    t.classList.remove('border-2', 'border-[#1A4231]', 'bg-[#FAF9F5]');
                    t.classList.add('border-gray-200');
                });
                this.classList.remove('border-gray-200');
                this.classList.add('border-2', 'border-[#1A4231]', 'bg-[#FAF9F5]');
                
                // Update dynamic price
                const selectedPrice = parseFloat(this.getAttribute('data-price')) || 2.000;
                basePrice = selectedPrice;
                document.getElementById('box-packaging-price-val').innerHTML = basePrice.toFixed(3) + ' ' + currencyLogoHtml;
                updateSummary();
            });
        });

    });

    function switchTab(tab) {
        const cropsBtn = document.getElementById('tab-btn-crops');
        const toolsBtn = document.getElementById('tab-btn-tools');
        const cropsGrid = document.getElementById('grid-crops');
        const toolsGrid = document.getElementById('grid-tools');

        if (tab === 'crops') {
            cropsBtn.className = "px-4 py-2 font-bold text-xs sm:text-sm rounded-lg bg-[#1A4231] text-white transition-all";
            toolsBtn.className = "px-4 py-2 font-bold text-xs sm:text-sm rounded-lg text-gray-500 hover:text-gray-700 transition-all";
            cropsGrid.classList.remove('hidden');
            toolsGrid.classList.add('hidden');
        } else {
            toolsBtn.className = "px-4 py-2 font-bold text-xs sm:text-sm rounded-lg bg-[#1A4231] text-white transition-all";
            cropsBtn.className = "px-4 py-2 font-bold text-xs sm:text-sm rounded-lg text-gray-500 hover:text-gray-700 transition-all";
            toolsGrid.classList.remove('hidden');
            cropsGrid.classList.add('hidden');
        }
    }

    function incrementProduct(id, price, name, stock) {
        if (selectedQty >= currentCapacity) {
            alert("{{ $isRtl ? 'لقد وصلت للحد الأقصى لسعة البوكس!' : 'You have reached the maximum capacity of this box!' }}");
            return;
        }

        const currentVal = parseInt(document.getElementById('qty-input-' + id).value) || 0;
        if (currentVal >= stock) {
            alert("{{ $isRtl ? 'عذراً، الكمية المطلوبة غير متوفرة في المخزون.' : 'Sorry, requested quantity is not available in stock.' }}");
            return;
        }

        const newVal = currentVal + 1;
        document.getElementById('qty-input-' + id).value = newVal;
        document.getElementById('qty-display-' + id).innerText = newVal;

        selectedQty++;
        contentPrice += parseFloat(price);

        selectedProductsMap[id] = { qty: newVal, price: price, name: name };

        updateProgressBar();
        updateSummary();
    }

    function decrementProduct(id, price, name) {
        const currentVal = parseInt(document.getElementById('qty-input-' + id).value) || 0;
        if (currentVal <= 0) return;

        const newVal = currentVal - 1;
        document.getElementById('qty-input-' + id).value = newVal;
        document.getElementById('qty-display-' + id).innerText = newVal;

        selectedQty--;
        contentPrice -= parseFloat(price);

        if (newVal === 0) {
            delete selectedProductsMap[id];
        } else {
            selectedProductsMap[id].qty = newVal;
        }

        updateProgressBar();
        updateSummary();
    }

    function updateProgressBar() {
        document.getElementById('fill-count').innerText = selectedQty;
        const percentage = (selectedQty / currentCapacity) * 100;
        document.getElementById('progress-bar').style.width = percentage + '%';

        // Check if exceeded capacity (e.g. if user selected 6 items and then switched capacity back to 4)
        if (selectedQty > currentCapacity) {
            document.getElementById('progress-bar').className = "progress-bar-fill h-full bg-red-500 animate-pulse";
            document.getElementById('add-to-cart-btn').disabled = true;
            document.getElementById('add-to-cart-btn').style.opacity = 0.5;
        } else {
            document.getElementById('progress-bar').className = "progress-bar-fill h-full bg-[#1A4231]";
            document.getElementById('add-to-cart-btn').disabled = false;
            document.getElementById('add-to-cart-btn').style.opacity = 1;
        }
    }

    function updateSummary() {
        // Prices
        document.getElementById('content-price-val').innerHTML = contentPrice.toFixed(3) + ' ' + currencyLogoHtml;
        document.getElementById('total-price-val').innerHTML = (basePrice + contentPrice).toFixed(3) + ' ' + currencyLogoHtml;

        // Summary list
        const summaryList = document.getElementById('selected-items-summary');
        summaryList.innerHTML = '';

        const keys = Object.keys(selectedProductsMap);
        if (keys.length === 0) {
            summaryList.innerHTML = `<li class="text-gray-400 italic text-center py-4">{{ $isRtl ? 'لم تختر أي عناصر بعد' : 'No items selected yet' }}</li>`;
            return;
        }

        keys.forEach(id => {
            const item = selectedProductsMap[id];
            const li = document.createElement('li');
            li.className = "flex justify-between items-center bg-[#FAF9F5] p-2 rounded-lg border border-gray-100";
            li.innerHTML = `
                <span class="font-bold text-[#1A4231]">${item.name}</span>
                <span class="bg-[#1A4231] text-white font-extrabold px-2.5 py-0.5 rounded text-xs shrink-0">${item.qty}x</span>
            `;
            summaryList.appendChild(li);
        });
    }
</script>
@endpush
