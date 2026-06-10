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
        <section id="products-list" class="bg-white rounded-2xl border border-gray-150 p-4 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
            
            <!-- Category Pills (Right side in RTL) -->
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                <button data-category-slug="all" class="category-pill bg-[#1A4231] text-white px-6 py-2.5 rounded-full font-bold text-xs lg:text-sm shadow-sm transition-all">
                    {{ __('new_design.store_page.filter_all') }}
                </button>
                @foreach($categories as $cat)
                <button data-category-slug="{{ $cat->en_Category_Slug }}" class="category-pill bg-[#F9FAFB] hover:bg-gray-100 text-[#1A4231] border border-gray-200/40 px-6 py-2.5 rounded-full font-bold text-xs lg:text-sm transition-all">
                    {{ $cat->localized_name }}
                </button>
                @endforeach
            </div>

            <!-- Sort Dropdown (Left side in RTL) -->
            <div class="flex items-center gap-3 shrink-0 self-end md:self-auto">
                <span class="text-xs lg:text-sm font-bold text-[#1A4231]/60">
                    {{ __('new_design.store_page.sort_by') }}
                </span>
                <div class="relative bg-[#F9FAFB] border border-gray-200 rounded-xl px-4 py-2 text-xs lg:text-sm font-bold text-[#1A4231] cursor-pointer">
                    <select class="bg-transparent border-none focus:outline-none focus:ring-0 cursor-pointer pr-6 py-0 text-[#1A4231] w-full">
                        <option value="latest">{{ __('new_design.store_page.sort_latest') }}</option>
                        <option value="low-high">{{ __('new_design.store_page.sort_low_high') }}</option>
                        <option value="high-low">{{ __('new_design.store_page.sort_high_low') }}</option>
                    </select>
                </div>
            </div>

        </section>

        <!-- Product Cards Grid (3 Columns) -->
        <section class="products-grid grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            
            @foreach($products as $product)
            @php
                $catSlug = $product->category ? $product->category->en_Category_Slug : '';
            @endphp
            <!-- Product Card -->
            <div class="product-card bg-white rounded-[32px] border border-[#1A4231] overflow-hidden flex flex-col justify-between hover:shadow-lg transition-all duration-300"
                 data-category="{{ $catSlug }}"
                 data-price="{{ $product->Price }}"
                 data-created-at="{{ $product->created_at }}">
                
                <a href="{{ route('single.product.new', $product->en_Product_Slug) }}" class="block hover:opacity-95 transition-opacity">
                    <!-- Product Image Container -->
                    <div class="relative w-full h-[260px] overflow-hidden">
                        <!-- Tag -->
                        @if($product->ItemTag)
                        <span class="absolute top-4 {{ $isRtl ? 'right-4' : 'left-4' }} bg-white/95 text-[#1A4231] text-[11px] font-black px-4 py-1.5 rounded-full border border-[#1A4231]/10 shadow-sm backdrop-blur-md z-10">
                            {{ $product->ItemTag == 'Beginner' ? __('new_design.store_page.tag_beginner') : __('new_design.store_page.tag_pro') }}
                        </span>
                        @endif
                        <!-- Product image -->
                        @php
                            $prodImg = $product->Primary_Image;
                            $imgSrc = (strpos($prodImg, 'http') === 0) ? $prodImg : asset(ProductImage().$prodImg);
                        @endphp
                        <img src="{{ $imgSrc }}" alt="{{ $product->localized_name }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Product Info -->
                    <div class="p-6 lg:p-8 flex flex-col text-start gap-4">
                        <div class="flex items-start justify-between gap-4">
                            <h3 class="text-lg lg:text-xl font-black text-[#1A4231] leading-tight">
                                {{ $product->localized_name }}
                            </h3>
                            <span class="text-base lg:text-lg font-black text-[#1A4231] whitespace-nowrap">
                                {{ floatval($product->Price) }} {{ __('new_design.coffee_crops.currency') }}
                            </span>
                        </div>
                        
                        <p class="text-xs text-gray-500 font-semibold leading-relaxed">
                            {{ $product->localized_about }}
                        </p>

                        <!-- Product Specs List -->
                        <ul class="flex flex-col gap-3 text-xs font-semibold text-gray-700 leading-normal border-t border-gray-100 pt-4">
                            @php
                                $descLines = array_filter(array_map('trim', explode('.', strip_tags($product->localized_description))));
                            @endphp
                            @if(count($descLines) > 0)
                                @foreach(array_slice($descLines, 0, 2) as $line)
                                @if(!empty($line))
                                <li class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-[#1A4231]/5 flex items-center justify-center shrink-0 border border-[#1A4231]/10 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span>{{ $line }}</span>
                                </li>
                                @endif
                                @endforeach
                            @else
                                <li class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-[#1A4231]/5 flex items-center justify-center shrink-0 border border-[#1A4231]/10 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span>{{ __('new_design.store_page.hario_feat1') }}</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-[#1A4231]/5 flex items-center justify-center shrink-0 border border-[#1A4231]/10 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span>{{ __('new_design.store_page.hario_feat2') }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </a>

                <!-- Add to Cart Button -->
                <div class="px-6 lg:px-8 pb-6 lg:pb-8">
                    <button onclick="addToCart({{ $product->id }}, {{ $product->Discount > 0 ? ($product->Price - ($product->Price * $product->Discount / 100)) : $product->Price }})" class="w-full bg-[#1A4231] hover:bg-[#2C624A] text-white py-4 rounded-full text-sm font-extrabold flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99] transition-all shadow-md">
                        <span>{{ __('new_design.store_page.add_to_cart') }}</span>
                        <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </button>
                </div>

            </div>
            @endforeach

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pills = document.querySelectorAll('.category-pill');
    const cards = document.querySelectorAll('.product-card');
    const cardsContainer = document.querySelector('.products-grid');
    const sortSelect = document.querySelector('select');
    
    let currentCategory = 'all';

    // Category Filtering
    pills.forEach(pill => {
        pill.addEventListener('click', function() {
            // Update active styling
            pills.forEach(p => {
                p.classList.remove('bg-[#1A4231]', 'text-white', 'shadow-sm');
                p.classList.add('bg-[#F9FAFB]', 'text-[#1A4231]', 'border', 'border-gray-200/40');
            });
            this.classList.remove('bg-[#F9FAFB]', 'text-[#1A4231]', 'border', 'border-gray-200/40');
            this.classList.add('bg-[#1A4231]', 'text-white', 'shadow-sm');

            currentCategory = this.getAttribute('data-category-slug');
            filterAndSort();
        });
    });

    // Sort Selection Change
    if (sortSelect) {
        sortSelect.addEventListener('change', filterAndSort);
    }

    function filterAndSort() {
        // Filter
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (currentCategory === 'all' || cardCat === currentCategory) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });

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
});

function addToCart(productId, price) {
    $.ajax({
        url: "{{ route('add.to.cart') }}",
        type: "POST",
        data: {
            product_id: productId,
            quantity: 1,
            price: price,
            _token: "{{ csrf_token() }}"
        },
        success: function(data) {
            window.showCartSuccess(data);
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
