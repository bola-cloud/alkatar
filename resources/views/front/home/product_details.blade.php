@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';

    // Parse product details description
    $parsed = [
        'origin' => $isRtl ? 'مرتفعات غنية بالتربة البركانية' : 'Highlands volcanic soil',
        'roast' => $isRtl ? 'تحميص متوسط متوازن' : 'Medium roast',
        'type' => $isRtl ? 'أرابيكا ١٠٠٪' : 'Arabica',
        'notes' => $isRtl ? 'شوكولاتة، مكسرات، كاكاو' : 'Chocolate, hazelnut, cocoa',
        'mainDesc' => $product->localized_description ?: $product->localized_about
    ];

    if ($product->localized_description) {
        $desc = $product->localized_description;
        if ($isRtl) {
            preg_match('/المصدر والمنشأ:\s*(.*?)(?=(قصة التحميص:|النوع:|النوع :|الإيحاءات:|الوصف:|$))/u', $desc, $m);
            if (isset($m[1]) && trim($m[1])) $parsed['origin'] = trim($m[1]);
            
            preg_match('/قصة التحميص:\s*(.*?)(?=(النوع:|النوع :|الإيحاءات:|الوصف:|$))/u', $desc, $m);
            if (isset($m[1]) && trim($m[1])) $parsed['roast'] = trim($m[1]);
            
            preg_match('/النوع\s*:\s*(.*?)(?=(الإيحاءات:|الوصف:|$))/u', $desc, $m);
            if (!isset($m[1])) {
                preg_match('/النوع:\s*(.*?)(?=(الإيحاءات:|الوصف:|$))/u', $desc, $m);
            }
            if (isset($m[1]) && trim($m[1])) $parsed['type'] = trim($m[1]);
            
            preg_match('/الإيحاءات:\s*(.*?)(?=(الوصف:|$))/u', $desc, $m);
            if (isset($m[1]) && trim($m[1])) $parsed['notes'] = trim($m[1]);
            
            preg_match('/الوصف:\s*(.*)/u', $desc, $m);
            if (isset($m[1]) && trim($m[1])) $parsed['mainDesc'] = trim($m[1]);
        } else {
            preg_match('/Source\/Origin:\s*(.*?)(?=(Roast story:|Type:|Notes:|Description:|$))/i', $desc, $m);
            if (isset($m[1]) && trim($m[1])) $parsed['origin'] = trim($m[1]);
            
            preg_match('/Roast story:\s*(.*?)(?=(Type:|Notes:|Description:|$))/i', $desc, $m);
            if (isset($m[1]) && trim($m[1])) $parsed['roast'] = trim($m[1]);
            
            preg_match('/Type:\s*(.*?)(?=(Notes:|Description:|$))/i', $desc, $m);
            if (isset($m[1]) && trim($m[1])) $parsed['type'] = trim($m[1]);
            
            preg_match('/Notes:\s*(.*?)(?=(Description:|$))/i', $desc, $m);
            if (isset($m[1]) && trim($m[1])) $parsed['notes'] = trim($m[1]);
            
            preg_match('/Description:\s*(.*)/i', $desc, $m);
            if (isset($m[1]) && trim($m[1])) $parsed['mainDesc'] = trim($m[1]);
        }
    }

    // Compile product images
    $images = [];
    if ($product->Primary_Image) $images[] = $product->Primary_Image;
    if ($product->Image2) $images[] = $product->Image2;
    if ($product->Image3) $images[] = $product->Image3;
    if ($product->Image4) $images[] = $product->Image4;
    if ($product->Image5) $images[] = $product->Image5;
    if (empty($images)) $images[] = 'prod.png';

    // Calculate base price
    $basePrice = $product->Price;
    if ($product->Discount > 0) {
        $basePrice = $product->Price - ($product->Price * $product->Discount / 100);
    }
    if ($basePrice <= 0) {
        if ($product->weights && $product->weights->count() > 0) {
            $basePrice = $product->weights->first()->price;
        } elseif ($product->sizes && $product->sizes->count() > 0) {
            $firstSize = $product->sizes->first();
            $basePrice = $firstSize->pivot->price;
        }
    }
@endphp

<!-- Breadcrumbs -->
<div class="bg-[#FBF0D8] py-4" dir="{{ $dir }}">
    <div class="container mx-auto px-4 lg:px-8">
        <nav class="flex text-sm text-slate-500 font-bold">
            <a href="{{ route('front') }}" class="hover:text-[#1A4231]">{{ $isRtl ? 'الرئيسية' : 'Home' }}</a>
            <span class="mx-2">/</span>
            <a href="{{ route('front.store') }}" class="hover:text-[#1A4231]">{{ $isRtl ? 'المتجر' : 'Store' }}</a>
            <span class="mx-2">/</span>
            <span class="text-[#1A4231]">{{ $product->localized_name }}</span>
        </nav>
    </div>
</div>

<!-- Product Details Section -->
<section class="py-16 bg-white" dir="{{ $dir }}">
    <div class="container mx-auto px-4 lg:px-8 max-w-[1360px]">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            
            <!-- Product Image Gallery -->
            <div class="space-y-4">
                <div class="aspect-square bg-[#FBF0D8] rounded-[32px] overflow-hidden border border-gray-100 flex items-center justify-center p-8 lg:p-12">
                    @php
                        $mainImg = $images[0];
                        $mainImgSrc = (strpos($mainImg, 'http') === 0) ? $mainImg : asset(ProductImage().$mainImg);
                    @endphp
                    <img id="main-product-image" src="{{ $mainImgSrc }}" class="w-full h-full object-contain mix-blend-multiply hover:scale-105 transition-transform duration-500" onerror="this.onerror=null;this.src='{{ asset(ProductImage() . 'prod.png') }}';">
                </div>
                @if(count($images) > 1)
                    <div class="grid grid-cols-5 gap-4">
                        @foreach($images as $img)
                            @php
                                $thumbSrc = (strpos($img, 'http') === 0) ? $img : asset(ProductImage().$img);
                            @endphp
                            <div onclick="changeMainImage('{{ $thumbSrc }}')" class="aspect-square bg-[#FBF0D8] rounded-2xl border-2 border-transparent hover:border-[#1A4231] cursor-pointer p-3 transition-all flex items-center justify-center">
                                <img src="{{ $thumbSrc }}" class="w-full h-full object-contain mix-blend-multiply" onerror="this.onerror=null;this.src='{{ asset(ProductImage() . 'prod.png') }}';">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="flex flex-col text-start justify-start">
                @if($product->ItemTag)
                    <div class="mb-2">
                        <span class="bg-[#1A4231] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $product->ItemTag == 'Beginner' ? __('new_design.store_page.tag_beginner') : __('new_design.store_page.tag_pro') }}
                        </span>
                    </div>
                @endif
                <h1 class="text-3xl lg:text-4xl font-extrabold text-[#1A4231] mb-4">{{ $product->localized_name }}</h1>
                
                <div class="flex items-center gap-4 mb-8">
                    @if($product->Discount > 0)
                        @php
                            $discountPrice = $product->Price - ($product->Price * $product->Discount / 100);
                        @endphp
                        <span id="product-display-price" class="text-3xl font-extrabold text-[#1A4231]">{{ floatval($discountPrice) }} {{ __('new_design.coffee_crops.currency') }}</span>
                        <span class="text-slate-400 line-through text-lg">{{ floatval($product->Price) }} {{ __('new_design.coffee_crops.currency') }}</span>
                        <span class="bg-red-50 text-red-600 px-3 py-1 rounded-lg text-xs font-bold">{{ __('Discount') }} {{ floatval($product->Discount) }}%</span>
                    @else
                        <span id="product-display-price" class="text-3xl font-extrabold text-[#1A4231]">{{ floatval($product->Price) }} {{ __('new_design.coffee_crops.currency') }}</span>
                    @endif
                </div>

                <!-- Product Meta Spec Grid -->
                <div class="grid grid-cols-2 gap-6 mb-10 bg-[#FBF0D8]/50 p-6 rounded-2xl border border-[#FBF0D8]">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-slate-500 font-bold">{{ $isRtl ? 'المنطقة:' : 'Region:' }}</span>
                        <span class="text-[#1A4231] font-extrabold text-sm lg:text-base">{{ $parsed['origin'] }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-slate-500 font-bold">{{ $isRtl ? 'المعالجة / قصة التحميص:' : 'Processing / Roast Story:' }}</span>
                        <span class="text-[#1A4231] font-extrabold text-sm lg:text-base">{{ $parsed['roast'] }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-slate-500 font-bold">{{ $isRtl ? 'الإيحاءات:' : 'Notes:' }}</span>
                        <span class="text-[#1A4231] font-extrabold text-sm lg:text-base">{{ $parsed['notes'] }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-slate-500 font-bold">{{ $isRtl ? 'النوع:' : 'Type:' }}</span>
                        <span class="text-[#1A4231] font-extrabold text-sm lg:text-base">{{ $parsed['type'] }}</span>
                    </div>
                </div>

                <!-- Sizes Selector -->
                @if($product->sizes && $product->sizes->count() > 0)
                    <div class="mb-8">
                        <span class="block text-sm font-bold text-[#1A4231] mb-4">
                            {{ $isRtl ? 'اختر المقاس:' : 'Select Size:' }}
                        </span>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->sizes as $idx => $sz)
                                <button type="button" 
                                        onclick="selectSize({{ $sz->id }}, {{ floatval($sz->pivot->price) }}, this)" 
                                        class="size-pill-btn px-6 py-2.5 rounded-xl border-2 font-bold text-sm transition-all {{ $idx === 0 ? 'border-[#1A4231] bg-[#1A4231] text-white shadow-sm' : 'border-gray-100 bg-gray-50 text-slate-600 hover:border-gray-200' }}">
                                    {{ $sz->Size }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Weights Selector -->
                @if($product->weights && $product->weights->count() > 0)
                    <div class="mb-8">
                        <span class="block text-sm font-bold text-[#1A4231] mb-4">
                            {{ $isRtl ? 'اختر الوزن:' : 'Select Weight:' }}
                        </span>
                        <div class="flex flex-wrap gap-3">
                            @foreach($product->weights as $idx => $wt)
                                <button type="button" 
                                        onclick="selectWeight({{ $wt->id }}, {{ floatval($wt->price) }}, this)" 
                                        class="weight-pill-btn px-6 py-2.5 rounded-xl border-2 font-bold text-sm transition-all {{ $idx === 0 ? 'border-[#1A4231] bg-[#1A4231] text-white shadow-sm' : 'border-gray-100 bg-gray-50 text-slate-600 hover:border-gray-200' }}">
                                    {{ $wt->weight }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Additions Option Grid -->
                @if($product->additions && $product->additions->count() > 0)
                    <div class="mb-8 border-t border-gray-100 pt-6">
                        <span class="block text-sm font-bold text-[#1A4231] mb-4">
                            {{ $isRtl ? 'الإضافات:' : 'Additions:' }}
                        </span>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($product->additions as $addition)
                                <label class="flex items-center gap-3 bg-gray-50 hover:bg-gray-100 border border-gray-150 rounded-xl p-4 cursor-pointer transition-all">
                                    <input type="checkbox" 
                                           value="{{ $addition->id }}" 
                                           data-price="{{ floatval($addition->price) }}"
                                           onchange="updateTotalPrice()"
                                           class="addition-checkbox rounded text-[#1A4231] focus:ring-[#1A4231] w-5 h-5 border-gray-300">
                                    <span class="flex-grow text-sm font-bold text-[#1A4231]">
                                        {{ $isRtl ? ($addition->title_ar ?: $addition->title) : $addition->title }}
                                    </span>
                                    <span class="text-xs font-black text-[#1A4231] bg-[#1A4231]/5 px-2 py-1 rounded">
                                        + {{ floatval($addition->price) }} {{ __('new_design.coffee_crops.currency') }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Action Bar -->
                <div class="flex items-center gap-4 mt-8">
                    <!-- Quantity Counter -->
                    <div class="flex items-center border-2 border-gray-150 rounded-xl overflow-hidden bg-gray-50 h-14">
                        <button type="button" onclick="adjustQty(-1)" class="px-5 text-[#1A4231] hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        </button>
                        <span id="product-qty-val" class="px-4 font-black text-lg select-none">1</span>
                        <button type="button" onclick="adjustQty(1)" class="px-5 text-[#1A4231] hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>
                    
                    <!-- Add to Cart Button -->
                    <button type="button" onclick="submitAddToCart()" class="flex-grow bg-[#1A4231] hover:bg-[#2C624A] text-white h-14 rounded-xl font-bold text-lg shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span>{{ $isRtl ? 'إضافة للسلة' : 'Add to Cart' }}</span>
                    </button>
                </div>

                <!-- Shipping / Quality Benefits -->
                <div class="mt-10 grid grid-cols-2 gap-4 border-t border-gray-100 pt-6">
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-[#1A4231]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span>{{ $isRtl ? 'توصيل سريع خلال 48 ساعة' : 'Fast Delivery in 48 Hours' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-[#1A4231]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <span>{{ $isRtl ? 'ضمان جودة المحصول' : 'Crop Quality Guaranteed' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Description Details Tabs -->
        <div class="mt-24 text-start">
            <div class="border-b border-gray-100 flex gap-8 mb-10">
                <button class="pb-4 border-b-4 border-[#1A4231] text-[#1A4231] font-extrabold text-xl">
                    {{ $isRtl ? 'وصف المنتج' : 'Product Description' }}
                </button>
            </div>
            <div class="max-w-4xl prose prose-slate">
                <p class="text-base lg:text-lg text-slate-600 leading-loose">
                    {!! nl2br(e($parsed['mainDesc'])) !!}
                </p>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($related && $related->count() > 0)
            <div class="mt-24 border-t border-gray-100 pt-16">
                <h2 class="text-3xl font-black text-[#1A4231] mb-10 text-center">
                    {{ $isRtl ? 'منتجات قد تعجبك' : 'Related Products' }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($related as $rel)
                        @php
                            $relImg = $rel->Primary_Image ?: 'prod.png';
                            $relImgSrc = (strpos($relImg, 'http') === 0) ? $relImg : asset(ProductImage().$relImg);
                        @endphp
                        <div class="bg-white rounded-[24px] border border-gray-150 overflow-hidden flex flex-col justify-between hover:shadow-lg transition-all duration-300">
                            <a href="{{ route('single.product.new', $rel->en_Product_Slug) }}" class="block">
                                <div class="aspect-square bg-gray-50 flex items-center justify-center p-6 relative overflow-hidden">
                                    <img src="{{ $relImgSrc }}" alt="{{ $rel->localized_name }}" class="max-h-full max-w-full object-contain mix-blend-multiply hover:scale-[1.03] transition-all">
                                </div>
                                <div class="p-5 flex flex-col text-start gap-2">
                                    <h3 class="font-bold text-[#1A4231] text-base line-clamp-1">
                                        {{ $rel->localized_name }}
                                    </h3>
                                    <span class="text-sm font-black text-[#1A4231]">
                                        {{ floatval($rel->Price) }} {{ __('new_design.coffee_crops.currency') }}
                                    </span>
                                </div>
                            </a>
                            <div class="px-5 pb-5">
                                <button onclick="addToCart({{ $rel->id }}, {{ $rel->Discount > 0 ? ($rel->Price - ($rel->Price * $rel->Discount / 100)) : $rel->Price }})" class="w-full bg-[#1A4231] hover:bg-[#2C624A] text-white py-3 rounded-xl text-xs font-bold transition-all shadow-sm">
                                    {{ __('new_design.store_page.add_to_cart') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

<!-- Page Operations JS Logic -->
<script>
    function changeMainImage(src) {
        document.getElementById('main-product-image').src = src;
    }

    let basePrice = {{ floatval($basePrice) }};
    let selectedSizeId = null;
    let selectedWeightId = null;
    let selectedSizePrice = 0;
    let selectedWeightPrice = 0;
    let quantity = 1;

    // Preselect option defaults on document ready
    document.addEventListener('DOMContentLoaded', () => {
        const firstSize = document.querySelector('.size-pill-btn');
        if (firstSize) {
            firstSize.click();
        }
        const firstWeight = document.querySelector('.weight-pill-btn');
        if (firstWeight) {
            firstWeight.click();
        }
        updateTotalPrice();
    });

    function selectSize(sizeId, price, btn) {
        selectedSizeId = sizeId;
        selectedSizePrice = price;

        document.querySelectorAll('.size-pill-btn').forEach(b => {
            b.classList.remove('border-[#1A4231]', 'bg-[#1A4231]', 'text-white', 'shadow-sm');
            b.classList.add('border-gray-100', 'bg-gray-50', 'text-slate-600');
        });
        btn.classList.add('border-[#1A4231]', 'bg-[#1A4231]', 'text-white', 'shadow-sm');
        btn.classList.remove('border-gray-100', 'bg-gray-50', 'text-slate-600');

        updateTotalPrice();
    }

    function selectWeight(weightId, price, btn) {
        selectedWeightId = weightId;
        selectedWeightPrice = price;

        document.querySelectorAll('.weight-pill-btn').forEach(b => {
            b.classList.remove('border-[#1A4231]', 'bg-[#1A4231]', 'text-white', 'shadow-sm');
            b.classList.add('border-gray-100', 'bg-gray-50', 'text-slate-600');
        });
        btn.classList.add('border-[#1A4231]', 'bg-[#1A4231]', 'text-white', 'shadow-sm');
        btn.classList.remove('border-gray-100', 'bg-gray-50', 'text-slate-600');

        updateTotalPrice();
    }

    function adjustQty(amount) {
        quantity = Math.max(1, quantity + amount);
        document.getElementById('product-qty-val').innerText = quantity;
        updateTotalPrice();
    }

    function updateTotalPrice() {
        let activePrice = basePrice;
        if (selectedSizeId) {
            activePrice = selectedSizePrice;
        } else if (selectedWeightId) {
            activePrice = selectedWeightPrice;
        }

        let additionsSum = 0;
        document.querySelectorAll('.addition-checkbox:checked').forEach(cb => {
            additionsSum += parseFloat(cb.getAttribute('data-price')) || 0;
        });

        let singlePrice = activePrice + additionsSum;
        let finalPrice = singlePrice * quantity;

        const priceEl = document.getElementById('product-display-price');
        if (priceEl) {
            priceEl.innerText = finalPrice.toFixed(2) + ' ' + '{{ __('new_design.coffee_crops.currency') }}';
        }
    }

    function submitAddToCart() {
        let activePrice = basePrice;
        if (selectedSizeId) {
            activePrice = selectedSizePrice;
        } else if (selectedWeightId) {
            activePrice = selectedWeightPrice;
        }

        let additionsSum = 0;
        let additions = [];
        document.querySelectorAll('.addition-checkbox:checked').forEach(cb => {
            additionsSum += parseFloat(cb.getAttribute('data-price')) || 0;
            additions.push(cb.value);
        });

        let singlePrice = activePrice + additionsSum;

        $.ajax({
            url: "{{ route('add.to.cart') }}",
            type: "POST",
            data: {
                product_id: {{ $product->id }},
                quantity: quantity,
                price: singlePrice,
                size_id: selectedSizeId,
                selectedSize: selectedSizeId,
                weight_id: selectedWeightId,
                additions: additions,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if (typeof window.showCartSuccess === 'function') {
                    window.showCartSuccess(data);
                } else {
                    toastr.success("{{ __('Product Added to Cart Successfully') }}");
                    $(".totalCountItem").html(data[0]);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.error) {
                    toastr.error(xhr.responseJSON.error);
                } else {
                    toastr.error("{{ __('Failed to add product to cart') }}");
                }
            }
        });
    }
</script>

@endsection
