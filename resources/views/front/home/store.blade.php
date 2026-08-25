@extends('front.layouts.new_design_layout')

@section('title', __('new_design.store_page.title'))

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<!-- Main Wrapper with White Background -->
<div class="store-page bg-white text-[#1A4231] pb-24" dir="{{ $dir }}" style="font-family: 'Cairo', sans-serif;">

    <!-- Top Spacer -->
    <div class="h-6 bg-white"></div>

    <!-- Full Page Width Banner with Right and Left Margins (Not constrained inside the main product container) -->
    <div class="px-4 md:px-8 lg:px-12 w-full mb-10">
        <section class="rounded-[24px] lg:rounded-[32px] text-white relative overflow-hidden flex flex-col items-center justify-center text-center gap-4 h-[200px] sm:h-[300px] lg:h-[360px] w-full"
                 style="background-image: url('{{ asset('assets/elketar/become_partner_hero.png') }}'); background-size: cover; background-position: center;">
            
            <!-- Dark gradient overlay for text readability (neutral black, not green) -->
            <div class="absolute inset-0 bg-black/35 z-0"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center gap-3 max-w-4xl px-4">
                <h1 class="text-2xl sm:text-4xl lg:text-6xl font-black text-[#FDF9F0] tracking-wide leading-tight">
                    {{ __('new_design.store_page.hero_title') }}
                </h1>
                <p class="text-white/90 text-xs sm:text-base lg:text-lg font-bold max-w-3xl leading-relaxed">
                    {{ __('new_design.store_page.hero_subtitle') }}
                </p>
                <a href="#products-list" class="mt-2 bg-white text-[#1A4231] font-black px-8 sm:px-10 py-2 sm:py-3.5 rounded-full text-xs sm:text-sm hover:scale-[1.03] active:scale-[0.98] transition-all shadow-lg">
                    {{ __('new_design.store_page.hero_btn') }}
                </a>
            </div>
        </section>
    </div>

    <!-- Products & Filters Container (Max-Width 1360px) -->
    <div class="container mx-auto px-4 lg:px-8 flex flex-col gap-10 max-w-[1360px]">

        <!-- Filters & Category Navigation Bar -->
        <section id="products-list" class="flex flex-col gap-6">
            
            <!-- Category Pills Container -->
            <div class="flex overflow-x-auto items-center gap-3 w-full pb-2 scrollbar-hide snap-x">
                <button data-category-slug="all" class="category-pill shrink-0 snap-start bg-[#1A4231] text-white px-6 py-2.5 rounded-full font-bold text-xs lg:text-sm shadow-sm transition-all border border-[#1A4231]">
                    {{ __('new_design.store_page.filter_all') }}
                </button>
                @foreach($categories as $cat)
                <button data-category-slug="{{ $cat->en_Category_Slug }}" class="category-pill shrink-0 snap-start bg-white hover:bg-gray-50 text-gray-500 hover:text-[#1A4231] border border-gray-200 px-6 py-2.5 rounded-full font-bold text-xs lg:text-sm transition-all">
                    {{ $cat->localized_name }}
                </button>
                @endforeach
            </div>

            <!-- Filters & Sort Container -->
            <div class="flex flex-wrap items-center justify-between gap-4 w-full bg-white rounded-2xl border border-gray-150 p-4 shadow-sm">
                
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Live Search Input -->
                    <div class="relative bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm text-[#1A4231] flex items-center min-w-[200px] lg:min-w-[250px] focus-within:ring-1 focus-within:ring-[#1A4231] focus-within:border-[#1A4231] transition-all">
                        <svg class="w-4 h-4 text-gray-400 absolute {{ $isRtl ? 'right-3' : 'left-3' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" id="liveSearchInput" placeholder="{{ $isRtl ? 'بحث ذكي...' : 'Smart Search...' }}" class="bg-transparent border-none focus:outline-none focus:ring-0 w-full {{ $isRtl ? 'pr-6' : 'pl-6' }} py-0 text-[#1A4231] placeholder-gray-400">
                    </div>

                    <!-- Subcategory Filter -->
                    <div class="relative bg-[#F9FAFB] border border-gray-200 rounded-xl px-3 py-2 text-xs lg:text-sm font-bold text-[#1A4231] cursor-pointer hover:bg-white transition-colors">
                        <select id="subcategoryFilter" class="bg-transparent border-none focus:outline-none focus:ring-0 cursor-pointer pr-6 py-0 text-[#1A4231] w-full min-w-[110px]">
                            <option value="all">{{ __('All Types') }}</option>
                            @foreach($subcategories as $subcat)
                                <option value="{{ $subcat->id }}">{{ app()->getLocale() == 'fr' ? $subcat->name_ar : $subcat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Size/Weight Filter -->
                    <div class="relative bg-[#F9FAFB] border border-gray-200 rounded-xl px-3 py-2 text-xs lg:text-sm font-bold text-[#1A4231] cursor-pointer hover:bg-white transition-colors">
                        <select id="sizeFilter" class="bg-transparent border-none focus:outline-none focus:ring-0 cursor-pointer pr-6 py-0 text-[#1A4231] w-full min-w-[100px]">
                            <option value="all">{{ __('Weight/Size') }}</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ app()->getLocale() == 'fr' ? ($size->Size_ar ?? $size->Size) : $size->Size }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sort Dropdown -->
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-gray-500 hidden sm:inline-block">{{ $isRtl ? 'ترتيب حسب:' : 'Sort by:' }}</span>
                    <div class="relative bg-[#F9FAFB] border border-gray-200 rounded-xl px-3 py-2 text-xs lg:text-sm font-bold text-[#1A4231] cursor-pointer hover:bg-white transition-colors">
                        <select id="sortFilter" class="bg-transparent border-none focus:outline-none focus:ring-0 cursor-pointer pr-6 py-0 text-[#1A4231] w-full min-w-[120px]">
                            <option value="latest">{{ __('new_design.store_page.sort_latest') }}</option>
                            <option value="low-high">{{ __('new_design.store_page.sort_low_high') }}</option>
                            <option value="high-low">{{ __('new_design.store_page.sort_high_low') }}</option>
                        </select>
                    </div>
                </div>

            </div>
        </section>

        <!-- Product Cards Grid (3 Columns) -->
        <section class="products-grid grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6 lg:gap-8">
            
            @foreach($products as $product)
                @include('front.components.product_card', ['product' => $product, 'isRtl' => $isRtl])
            @endforeach

            @if($products->isEmpty())
                <div class="col-span-1 md:col-span-3 flex flex-col items-center justify-center py-20 text-center">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-xl font-bold text-gray-400">{{ __('new_design.coffee_crops.no_products_found') ?? 'عفواً، لا يوجد منتجات مطابقة للبحث' }}</h3>
                    @if(request('search'))
                        <a href="{{ route('front.store.catalog') }}" class="mt-4 text-[#1A4231] font-bold underline">{{ $isRtl ? 'العودة للمتجر' : 'Back to Store' }}</a>
                    @endif
                </div>
            @endif
            
            <!-- JavaScript Empty State (Hidden initially) -->
            <div id="js-empty-state" class="col-span-1 md:col-span-3 flex-col items-center justify-center py-20 text-center" style="display: none;">
                <svg class="w-16 h-16 text-gray-300 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-xl font-bold text-gray-400">{{ __('new_design.coffee_crops.no_products_found') ?? 'عفواً، لا يوجد منتجات مطابقة للبحث' }}</h3>
            </div>

        </section>

    </div>

    <!-- Join the Katar Community Section -->
    <div class="px-4 md:px-8 lg:px-12 w-full mt-16">
        <section class="rounded-[24px] lg:rounded-[32px] text-white relative overflow-hidden flex flex-col items-center justify-center text-center gap-4 py-16 lg:py-20 w-full"
                 style="background-image: url('{{ asset('assets/elketar/Section - Why Partner With Us.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            
            <!-- Dark overlay for readability -->
            <div class="absolute inset-0 bg-[#1A4231]/75 z-0"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center gap-4 max-w-4xl px-4">
                <h2 class="text-3xl lg:text-[44px] font-black text-[#FDF9F0] leading-tight">
                    {{ __('new_design.store_page.join_community_title') }}
                </h2>
                <p class="text-white/90 text-sm lg:text-base font-semibold max-w-2xl leading-relaxed">
                    {{ __('new_design.store_page.join_community_subtitle') }}
                </p>
                
                <!-- Buttons -->
                <div class="flex flex-wrap items-center justify-center gap-4 mt-4">
                    <a href="#" class="bg-white text-[#1A4231] hover:bg-[#FDF9F0] active:scale-[0.98] px-8 py-3.5 rounded-full text-sm font-bold transition-all shadow-md flex items-center gap-2">
                        <!-- Group/Community Icon -->
                        <svg class="w-4 h-4 text-[#1A4231] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>{{ __('new_design.store_page.btn_goto_community') }}</span>
                    </a>
                    
                    <a href="{{ route('experts') }}" class="border border-white/40 hover:border-white hover:bg-white/10 active:scale-[0.98] px-8 py-3.5 rounded-full text-sm font-bold transition-all flex items-center justify-center">
                        <span>{{ __('new_design.store_page.btn_contact_expert') }}</span>
                    </a>
                </div>
            </div>
        </section>
    </div>

</div>

<style>
    /* Premium Cairo Styling */
    .store-page {
        font-family: 'Cairo', sans-serif;
    }
    
    /* Scrollbar hide utility */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .category-pill.active {
        background-color: #1A4231 !important;
        color: white !important;
        border-color: #1A4231 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pills = document.querySelectorAll('.category-pill');
    const cards = document.querySelectorAll('.product-card');
    const cardsContainer = document.querySelector('.products-grid');
    const sortSelect = document.getElementById('sortFilter');
    const subcategorySelect = document.getElementById('subcategoryFilter');
    const sizeSelect = document.getElementById('sizeFilter');
    const searchInput = document.getElementById('liveSearchInput');
    const jsEmptyState = document.getElementById('js-empty-state');
    
    let currentCategory = 'all';

    // Category Filtering
    pills.forEach(pill => {
        pill.addEventListener('click', function() {
            // Update active styling
            document.querySelectorAll('.category-pill').forEach(b => {
                b.classList.remove('active', 'bg-[#1A4231]', 'text-white', 'border-[#1A4231]');
                b.classList.add('bg-white', 'text-gray-500', 'border-gray-200');
            });
            this.classList.add('active', 'bg-[#1A4231]', 'text-white', 'border-[#1A4231]');
            this.classList.remove('bg-white', 'text-gray-500', 'border-gray-200');

            currentCategory = this.getAttribute('data-category-slug');
            
            // Optionally, reset subcategory when changing main category to avoid empty states
            if (subcategorySelect) subcategorySelect.value = 'all';
            
            // Update URL without reloading
            const newUrl = new URL(window.location);
            if (currentCategory === 'all') {
                newUrl.searchParams.delete('category');
            } else {
                newUrl.searchParams.set('category', currentCategory);
            }
            window.history.pushState({}, '', newUrl);

            filterAndSort();
        });
    });

    // Listeners for new filters
    if (sortSelect) sortSelect.addEventListener('change', filterAndSort);
    if (subcategorySelect) subcategorySelect.addEventListener('change', filterAndSort);
    if (sizeSelect) sizeSelect.addEventListener('change', filterAndSort);
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Add a small debounce
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(filterAndSort, 200);
        });
    }

    function filterAndSort() {
        const selectedSubcategory = subcategorySelect ? subcategorySelect.value : 'all';
        const selectedSize = sizeSelect ? sizeSelect.value : 'all';
        let hasVisibleCards = false;

        // Filter
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            const cardSubcat = card.getAttribute('data-subcategory');
            const cardSizes = card.getAttribute('data-sizes'); // e.g. "1,2,12"

            // Main Category match
            const matchesCat = (currentCategory === 'all' || cardCat === currentCategory);
            
            // Subcategory match
            const matchesSubcat = (selectedSubcategory === 'all' || cardSubcat === selectedSubcategory);
            
            // Size match
            let matchesSize = true;
            if (selectedSize !== 'all') {
                const sizesArray = cardSizes ? cardSizes.split(',') : [];
                matchesSize = sizesArray.includes(selectedSize);
            }

            // Search match
            let matchesSearch = true;
            if (searchInput && searchInput.value.trim() !== '') {
                const query = searchInput.value.toLowerCase().trim();
                const cardSearchText = card.getAttribute('data-search-text');
                matchesSearch = cardSearchText.includes(query);
            }

            if (matchesCat && matchesSubcat && matchesSize && matchesSearch) {
                card.style.display = 'flex';
                hasVisibleCards = true;
            } else {
                card.style.display = 'none';
            }
        });

        if (jsEmptyState) {
            if (!hasVisibleCards && cards.length > 0) {
                jsEmptyState.style.display = 'flex';
            } else {
                jsEmptyState.style.display = 'none';
            }
        }

        // Sort
        const visibleCards = Array.from(cards).filter(c => c.style.display !== 'none');
        const hiddenCards = Array.from(cards).filter(c => c.style.display === 'none');
        
        const sortVal = sortSelect ? sortSelect.value : 'latest';

        visibleCards.sort((a, b) => {
            const priceA = parseFloat(a.getAttribute('data-price')) || 0;
            const priceB = parseFloat(b.getAttribute('data-price')) || 0;
            const dateA = new Date(a.getAttribute('data-created-at'));
            const dateB = new Date(b.getAttribute('data-created-at'));

            if (sortVal === 'low-high') {
                return priceA - priceB;
            } else if (sortVal === 'high-low') {
                return priceB - priceA;
            } else {
                // Latest
                return dateB - dateA;
            }
        });

        // Re-append to container in sorted order
        const allSorted = [...visibleCards, ...hiddenCards];
        allSorted.forEach(card => cardsContainer.appendChild(card));
    }

    // Auto-filter based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const initialCategory = urlParams.get('category');
    if (initialCategory && initialCategory !== 'all') {
        const initialPill = document.querySelector(`.category-pill[data-category-slug="${initialCategory}"]`);
        if (initialPill) {
            initialPill.click();
        }
    }
});

/* Quick View Modal Logic */
let qvProductId = null;
let qvSelectedSizeId = null;
let qvSelectedWeightId = null;
let qvSelectedPrice = 0;

function openQuickViewModal(productId, productName, productImg, sizes, weights, basePrice, discount) {
    qvProductId = productId;
    qvSelectedSizeId = null;
    qvSelectedWeightId = null;
    qvSelectedPrice = basePrice;
    
    // Calculate initial price (apply discount if any)
    if (discount > 0) {
        qvSelectedPrice = basePrice - (basePrice * discount / 100);
    }

    const modal = document.getElementById('quickViewModal');
    document.getElementById('qvProductImage').src = productImg;
    document.getElementById('qvProductName').innerText = productName;
    updateQvPriceDisplay();

    const sizesContainer = document.getElementById('qvSizesContainer');
    const weightsContainer = document.getElementById('qvWeightsContainer');
    
    // Populate Sizes
    if (sizes && sizes.length > 0) {
        let sizesHtml = `<p class="font-bold text-sm mb-2 text-[#1A4231]">{{ $isRtl ? 'اختر الحجم:' : 'Select Size:' }}</p><div class="flex flex-wrap gap-2">`;
        sizes.forEach((sz, idx) => {
            const priceVal = discount > 0 ? (sz.price - (sz.price * discount / 100)) : sz.price;
            sizesHtml += `<button type="button" onclick="selectQvSize(${sz.id}, ${priceVal}, this)" class="qv-size-btn px-4 py-2 rounded-xl border-2 font-bold text-xs transition-all ${idx === 0 ? 'border-[#1A4231] bg-[#1A4231] text-white shadow-sm' : 'border-gray-100 bg-gray-50 text-slate-600 hover:border-gray-200'}">${sz.name_ar}</button>`;
            if(idx === 0) {
                qvSelectedSizeId = sz.id;
                qvSelectedPrice = priceVal;
            }
        });
        sizesHtml += `</div>`;
        sizesContainer.innerHTML = sizesHtml;
        sizesContainer.style.display = 'block';
    } else {
        sizesContainer.innerHTML = '';
        sizesContainer.style.display = 'none';
    }

    // Populate Weights
    if (weights && weights.length > 0) {
        let weightsHtml = `<p class="font-bold text-sm mb-2 text-[#1A4231]">{{ $isRtl ? 'اختر الوزن:' : 'Select Weight:' }}</p><div class="flex flex-wrap gap-2">`;
        weights.forEach((wt, idx) => {
            const priceVal = discount > 0 ? (wt.price - (wt.price * discount / 100)) : wt.price;
            weightsHtml += `<button type="button" onclick="selectQvWeight(${wt.id}, ${priceVal}, this)" class="qv-weight-btn px-4 py-2 rounded-xl border-2 font-bold text-xs transition-all ${(!qvSelectedSizeId && idx === 0) ? 'border-[#1A4231] bg-[#1A4231] text-white shadow-sm' : 'border-gray-100 bg-gray-50 text-slate-600 hover:border-gray-200'}">${wt.name_ar}</button>`;
            if(!qvSelectedSizeId && idx === 0) {
                qvSelectedWeightId = wt.id;
                qvSelectedPrice = priceVal;
            }
        });
        weightsHtml += `</div>`;
        weightsContainer.innerHTML = weightsHtml;
        weightsContainer.style.display = 'block';
    } else {
        weightsContainer.innerHTML = '';
        weightsContainer.style.display = 'none';
    }

    updateQvPriceDisplay();
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeQuickViewModal() {
    const modal = document.getElementById('quickViewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function selectQvSize(id, price, btn) {
    qvSelectedSizeId = id;
    qvSelectedPrice = price;
    
    // Reset sizes UI
    document.querySelectorAll('.qv-size-btn').forEach(b => {
        b.className = "qv-size-btn px-4 py-2 rounded-xl border-2 font-bold text-xs transition-all border-gray-100 bg-gray-50 text-slate-600 hover:border-gray-200";
    });
    btn.className = "qv-size-btn px-4 py-2 rounded-xl border-2 font-bold text-xs transition-all border-[#1A4231] bg-[#1A4231] text-white shadow-sm";
    
    updateQvPriceDisplay();
}

function selectQvWeight(id, price, btn) {
    qvSelectedWeightId = id;
    qvSelectedPrice = price;
    
    // Reset weights UI
    document.querySelectorAll('.qv-weight-btn').forEach(b => {
        b.className = "qv-weight-btn px-4 py-2 rounded-xl border-2 font-bold text-xs transition-all border-gray-100 bg-gray-50 text-slate-600 hover:border-gray-200";
    });
    btn.className = "qv-weight-btn px-4 py-2 rounded-xl border-2 font-bold text-xs transition-all border-[#1A4231] bg-[#1A4231] text-white shadow-sm";
    
    updateQvPriceDisplay();
}

function updateQvPriceDisplay() {
    document.getElementById('qvProductPrice').innerText = qvSelectedPrice.toFixed(2) + " {{ __('new_design.coffee_crops.currency') }}";
}

function submitQuickViewAddToCart() {
    if(!qvProductId) return;
    
    const btn = document.getElementById('qvSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>`;

    $.ajax({
        type: "POST",
        url: "{{ route('add.to.cart') }}",
        data: {
            product_id: qvProductId,
            price: qvSelectedPrice,
            quantity: 1,
            size_id: qvSelectedSizeId,
            selectedSize: qvSelectedSizeId,
            weight_id: qvSelectedWeightId,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            closeQuickViewModal();
            if (typeof window.showCartSuccess === 'function') {
                window.showCartSuccess(response);
            } else {
                toastr.success("{{ __('Product Added to Cart Successfully') }}");
                $(".totalCountItem").html(response[0]);
            }
        },
        error: function(xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.error) {
                toastr.error(xhr.responseJSON.error);
            } else {
                toastr.error("{{ __('Failed to add product to cart') }}");
            }
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = `<span>{{ __('new_design.store_page.add_to_cart') }}</span> <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>`;
        }
    });
}
</script>

<!-- Quick View Modal Structure -->
<div id="quickViewModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity" dir="{{ $dir }}">
    <div class="bg-white rounded-3xl w-[90%] max-w-md overflow-hidden shadow-2xl relative animate-fadeInUp">
        <!-- Close Button -->
        <button onclick="closeQuickViewModal()" class="absolute top-4 {{ $isRtl ? 'left-4' : 'right-4' }} bg-white/80 backdrop-blur text-gray-500 hover:text-red-500 rounded-full p-2 z-10 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <!-- Header Image -->
        <div class="w-full h-48 bg-gray-50 relative">
            <img id="qvProductImage" src="" alt="Product" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            <h3 id="qvProductName" class="absolute bottom-4 {{ $isRtl ? 'right-4' : 'left-4' }} text-white font-black text-xl w-[80%] leading-tight drop-shadow-md"></h3>
        </div>
        
        <!-- Body Content -->
        <div class="p-6">
            <!-- Price Display -->
            <div class="mb-4 pb-4 border-b border-gray-100 flex items-center justify-between">
                <span class="text-sm font-bold text-gray-500">{{ $isRtl ? 'السعر' : 'Price' }}:</span>
                <span id="qvProductPrice" class="text-xl font-black text-[#1A4231]"></span>
            </div>

            <!-- Options Containers -->
            <div id="qvSizesContainer" class="mb-4"></div>
            <div id="qvWeightsContainer" class="mb-6"></div>
            
            <!-- Submit Button -->
            <button id="qvSubmitBtn" onclick="submitQuickViewAddToCart()" class="w-full bg-[#1A4231] hover:bg-[#2C624A] text-white py-3.5 rounded-2xl text-base font-extrabold flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99] transition-all shadow-md">
                <span>{{ __('new_design.store_page.add_to_cart') }}</span>
                <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeInUp {
    animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

@endsection
