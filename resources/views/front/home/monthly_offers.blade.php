@extends('front.layouts.new_design_layout')

@section('title', __('new_design.monthly_offers.title'))

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<style>
    /* Cairo font integration for the page container */
    .monthly-offers-page {
        font-family: 'Cairo', sans-serif;
    }

    /* Slider track CSS centering with CSS variables to ensure zero layout-shift */
    .hero-slider-track {
        display: flex;
        padding-left: var(--side-padding);
        padding-right: var(--side-padding);
        gap: var(--slide-gap);
        transform: translateX(calc(-1 * var(--current-index) * (var(--slide-width) + var(--slide-gap))));
    }

    .hero-slider-track.transition-enabled {
        transition: transform 500ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Define responsive slide widths in viewport width (vw) units to make translation math 100% viewport-relative */
    .hero-slide {
        width: var(--slide-width);
        flex-shrink: 0;
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        height: 200px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #f3f4f6;
        transition: all 500ms ease-out;
    }

    @media (min-width: 640px) {
        .hero-slide {
            border-radius: 32px;
            height: 300px;
        }
    }

    @media (min-width: 1024px) {
        .hero-slide {
            height: 360px;
        }
    }

    :root {
        --slide-width: 84vw;
        --slide-gap: 16px;
        --side-padding: 8vw; /* (100vw - 84vw) / 2 = 8vw padding on left and right to perfectly center active slide */
        --current-index: 2;  /* Initialized to 2 to center Slide 2 */
    }

    @media (min-width: 1024px) {
        :root {
            --slide-width: 80vw;
            --slide-gap: 24px;
            --side-padding: 10vw; /* (100vw - 80vw) / 2 = 10vw padding on left and right to perfectly center active slide */
        }
    }
</style>

<div class="monthly-offers-page bg-white text-[#1A4231] overflow-hidden" dir="{{ $dir }}">

    <!-- White Spacer Gap -->
    <div class="h-6 bg-white"></div>

    <!-- Hero Banner Slider Section (Peek stage padding carousel matching Figma layout) -->
    <section class="relative py-6 lg:py-10 w-full bg-white overflow-hidden">
        <!-- Slider Container with forced LTR direction to ensure consistent offset calculations -->
        <div id="hero-slider-container" class="relative w-full overflow-hidden select-none" dir="ltr">
            
            <!-- Slides Track -->
            <div id="hero-slider-track" class="hero-slider-track transition-enabled">
                
                <!-- Slide 1 (trail-box.png) -->
                <div class="hero-slide">
                    <img src="{{ asset('assets/elketar/trail-box.png') }}" class="absolute inset-0 w-full h-full object-cover" alt="Slide 1">
                </div>
                
                <!-- Slide 2 (ddd.png) -->
                <div class="hero-slide">
                    <img src="{{ asset('assets/elketar/ddd.png') }}" class="absolute inset-0 w-full h-full object-cover" alt="Slide 2">
                </div>
                
                <!-- Slide 3 (become_partner_hero.png) -->
                <div class="hero-slide">
                    <img src="{{ asset('assets/elketar/become_partner_hero.png') }}" class="absolute inset-0 w-full h-full object-cover" alt="Slide 3">
                </div>

            </div>
            
            <!-- Navigation Arrows -->
            <button id="hero-slide-prev" class="absolute top-1/2 -translate-y-1/2 right-4 sm:right-8 lg:right-16 z-20 w-12 h-12 rounded-full bg-white/30 hover:bg-white/50 active:scale-95 text-[#1A4231] flex items-center justify-center backdrop-blur-md transition-all shadow-lg border border-white/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <button id="hero-slide-next" class="absolute top-1/2 -translate-y-1/2 left-4 sm:left-8 lg:left-16 z-20 w-12 h-12 rounded-full bg-white/30 hover:bg-white/50 active:scale-95 text-[#1A4231] flex items-center justify-center backdrop-blur-md transition-all shadow-lg border border-white/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            
        </div>
    </section>

   

    <!-- Content Sections Wrapper (Background is white, cards have thin borders border-gray-200) -->
    <div class="container mx-auto px-4 lg:px-8 pb-24 max-w-6xl">
        <div class="bg-white rounded-[32px] border border-gray-200 shadow-sm p-6 lg:p-12 flex flex-col gap-12">
            
            <!-- Page Header -->
            <div class="text-start border-b border-gray-100 pb-8 flex flex-col gap-2">
                <span class="text-xs lg:text-sm font-bold text-gray-400 tracking-wider">
                    {{ __('new_design.monthly_offers.limit_time') }}
                </span>
                <h1 class="text-3xl lg:text-5xl font-black text-[#1A4231]">
                    {{ __('new_design.monthly_offers.page_title') }}
                </h1>
                <p class="text-gray-600 text-sm lg:text-base font-semibold max-w-2xl mt-2 leading-relaxed">
                    {{ __('new_design.monthly_offers.page_subtitle') }}
                </p>
            </div>

            @if($packages->isNotEmpty())
                @php
                    $featuredPackage = $packages->first();
                    $otherPackages = $packages->skip(1);
                    $featuredLines = array_filter(explode("\n", str_replace("\r", "", $featuredPackage->localized_about)));
                @endphp

                <!-- Featured Offer Card -->
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 hover:shadow-md transition-all duration-300">
                    
                    <!-- Left Column (Text & details) - rendered on the Left in RTL, Right in LTR -->
                    <div class="lg:col-span-6 p-8 lg:p-12 flex flex-col justify-between text-start order-2 lg:order-1">
                        <div>
                            <h2 class="text-2xl lg:text-3xl font-black text-[#1A4231] mb-2">
                                {{ $featuredPackage->localized_name }}
                            </h2>
                            <p class="text-gray-500 font-bold text-sm lg:text-base mb-8">
                                {{ __('new_design.monthly_offers.featured_subtitle') }}
                            </p>

                            <!-- Features Timeline List -->
                            <div class="relative flex flex-col gap-6 text-start my-6">
                                <!-- Vertical connector line -->
                                <div class="absolute right-[18px] rtl:right-[18px] ltr:right-auto ltr:left-[18px] top-4 bottom-4 w-[2px] bg-gray-200"></div>

                                @foreach($featuredLines as $line)
                                    <div class="flex items-start gap-4 relative pr-10 rtl:pr-10 ltr:pr-0 ltr:pl-10 text-start">
                                        <!-- Icon Circle -->
                                        <div class="absolute right-0 rtl:right-0 ltr:right-auto ltr:left-0 w-9 h-9 rounded-full bg-[#F3F4F6] flex items-center justify-center border-2 border-white shadow-sm z-10">
                                            <svg class="w-4 h-4 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 font-semibold leading-relaxed">
                                                {{ $line }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Price Feature -->
                                <div class="flex items-start gap-4 relative pr-10 rtl:pr-10 ltr:pr-0 ltr:pl-10 text-start">
                                    <!-- Icon Circle -->
                                    <div class="absolute right-0 rtl:right-0 ltr:right-auto ltr:left-0 w-9 h-9 rounded-full bg-[#F3F4F6] flex items-center justify-center border-2 border-white shadow-sm z-10">
                                        <svg class="w-4 h-4 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400">
                                            {{ __('new_design.monthly_offers.feat_price_lbl') }}
                                        </h4>
                                        <div class="flex items-baseline gap-3 mt-1">
                                            <span class="text-2xl font-black text-[#1A4231]">
                                                {{ function_exists('currencyConverter') ? currencyConverter($featuredPackage->Discount_Price) : number_format($featuredPackage->Discount_Price, 3) }}
                                            </span>
                                            @if($featuredPackage->Price > $featuredPackage->Discount_Price)
                                            <span class="text-sm text-gray-400 line-through">
                                                {{ function_exists('currencyConverter') ? currencyConverter($featuredPackage->Price) : number_format($featuredPackage->Price, 3) }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <button onclick="addToCart({{ $featuredPackage->id }}, {{ $featuredPackage->Discount_Price }})" class="inline-flex items-center justify-center gap-2 bg-[#1A4231] hover:bg-[#235841] text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 w-full lg:w-fit mt-6 hover:scale-[1.02] active:scale-[0.98]">
                            <span>{{ __('new_design.monthly_offers.btn_purchase') }}</span>
                            <svg class="w-4 h-4 transform rotate-180 rtl:rotate-180 ltr:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Right Column (Image with Tag) - rendered on the Right in RTL, Left in LTR -->
                    <div class="lg:col-span-6 relative h-64 lg:h-auto min-h-[380px] order-1 lg:order-2">
                        <img src="{{ resolve_product_image($featuredPackage->Primary_Image) }}" alt="{{ $featuredPackage->localized_name }}" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='{{ asset('assets/elketar/coffee.png') }}';">
                        <!-- Offer of the Month Badge -->
                        <div class="absolute bottom-6 right-6 bg-white/95 text-[#1A4231] font-black text-xs px-4 py-2.5 rounded-full shadow-md">
                            {{ __('new_design.monthly_offers.featured_tag') }}
                        </div>
                    </div>

                </div>

                @if($otherPackages->isNotEmpty())
                    <!-- Side-by-Side Small Offers Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                        @foreach($otherPackages as $other)
                            @php
                                $otherLines = array_filter(explode("\n", str_replace("\r", "", $other->localized_about)));
                            @endphp
                            <div class="bg-white rounded-[24px] border border-gray-200 shadow-sm p-6 relative hover:shadow-md transition-all duration-300 flex flex-col justify-between min-h-[320px]">
                                <!-- Image at the top of the card -->
                                <div class="w-full h-44 rounded-xl overflow-hidden mb-4">
                                    <img src="{{ resolve_product_image($other->Primary_Image) }}" alt="{{ $other->localized_name }}" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='{{ asset('assets/elketar/coffee.png') }}';">
                                </div>
                                
                                @if($other->Discount > 0)
                                <div class="absolute left-6 rtl:left-6 ltr:left-auto ltr:right-6 -top-3 bg-amber-50 text-amber-700 border border-amber-200/50 text-[10px] font-bold px-3 py-1 rounded-full">
                                    {{ round($other->Discount) }}% {{ __('new_design.monthly_offers.discount_tag') ?? 'خصم' }}
                                </div>
                                @endif

                                <!-- Content -->
                                <div class="text-start mb-4">
                                    <h3 class="text-xl font-bold text-[#1A4231] mb-2">
                                        {{ $other->localized_name }}
                                    </h3>
                                    <div class="text-gray-500 text-xs font-semibold leading-relaxed">
                                        @foreach($otherLines as $line)
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>{{ $line }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-auto">
                                    <div class="flex flex-col">
                                        @if($other->Price > $other->Discount_Price)
                                        <span class="text-xs text-gray-400 line-through">
                                            {{ function_exists('currencyConverter') ? currencyConverter($other->Price) : number_format($other->Price, 3) }}
                                        </span>
                                        @endif
                                        <span class="text-lg font-black text-[#1A4231]">
                                            {{ function_exists('currencyConverter') ? currencyConverter($other->Discount_Price) : number_format($other->Discount_Price, 3) }}
                                        </span>
                                    </div>
                                    <button onclick="addToCart({{ $other->id }}, {{ $other->Discount_Price }})" class="bg-[#1A4231] hover:bg-[#235841] text-white font-bold text-xs py-2.5 px-5 rounded-xl transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                                        {{ __('new_design.monthly_offers.btn_shop_now') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            @else
                <!-- Fallback Static Template Content if no packages are configured in admin panel -->
                <!-- Featured Offer Card -->
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 hover:shadow-md transition-all duration-300">
                    
                    <!-- Left Column (Text & details) - rendered on the Left in RTL, Right in LTR -->
                    <div class="lg:col-span-6 p-8 lg:p-12 flex flex-col justify-between text-start order-2 lg:order-1">
                        <div>
                            <h2 class="text-2xl lg:text-3xl font-black text-[#1A4231] mb-2">
                                {{ __('new_design.monthly_offers.featured_title') }}
                            </h2>
                            <p class="text-gray-500 font-bold text-sm lg:text-base mb-8">
                                {{ __('new_design.monthly_offers.featured_subtitle') }}
                            </p>

                            <!-- Features Timeline List -->
                            <div class="relative flex flex-col gap-8 text-start my-6">
                                <!-- Vertical connector line -->
                                <div class="absolute right-[18px] rtl:right-[18px] ltr:right-auto ltr:left-[18px] top-4 bottom-4 w-[2px] bg-gray-200"></div>

                                <!-- Feature 1: Origin -->
                                <div class="flex items-start gap-4 relative pr-10 rtl:pr-10 ltr:pr-0 ltr:pl-10 text-start">
                                    <!-- Icon Circle -->
                                    <div class="absolute right-0 rtl:right-0 ltr:right-auto ltr:left-0 w-9 h-9 rounded-full bg-[#F3F4F6] flex items-center justify-center border-2 border-white shadow-sm z-10">
                                        <svg class="w-4 h-4 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h2a2.5 2.5 0 002.5-2.5V10a2.5 2.5 0 00-2.5-2.5h-.5A2.5 2.5 0 0014 5V3.935M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-[#1A4231]">
                                            {{ __('new_design.monthly_offers.feat_origin_lbl') }}
                                        </h4>
                                        <p class="text-xs text-gray-500 font-semibold mt-1 leading-relaxed">
                                            {{ __('new_design.monthly_offers.feat_origin_val') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Feature 2: Roast -->
                                <div class="flex items-start gap-4 relative pr-10 rtl:pr-10 ltr:pr-0 ltr:pl-10 text-start">
                                    <!-- Icon Circle -->
                                    <div class="absolute right-0 rtl:right-0 ltr:right-auto ltr:left-0 w-9 h-9 rounded-full bg-[#F3F4F6] flex items-center justify-center border-2 border-white shadow-sm z-10">
                                        <svg class="w-4 h-4 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-[#1A4231]">
                                            {{ __('new_design.monthly_offers.feat_roast_lbl') }}
                                        </h4>
                                        <p class="text-xs text-gray-500 font-semibold mt-1 leading-relaxed">
                                            {{ __('new_design.monthly_offers.feat_roast_val') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Feature 3: Price -->
                                <div class="flex items-start gap-4 relative pr-10 rtl:pr-10 ltr:pr-0 ltr:pl-10 text-start">
                                    <!-- Icon Circle -->
                                    <div class="absolute right-0 rtl:right-0 ltr:right-auto ltr:left-0 w-9 h-9 rounded-full bg-[#F3F4F6] flex items-center justify-center border-2 border-white shadow-sm z-10">
                                        <svg class="w-4 h-4 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-[#1A4231]">
                                            {{ __('new_design.monthly_offers.feat_price_lbl') }}
                                        </h4>
                                        <div class="flex items-baseline gap-3 mt-1">
                                            <span class="text-2xl font-black text-[#1A4231]">
                                                {{ __('new_design.monthly_offers.feat_price_val') }}
                                            </span>
                                            <span class="text-sm text-gray-400 line-through">
                                                {{ __('new_design.monthly_offers.feat_price_old') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <a href="#" class="inline-flex items-center justify-center gap-2 bg-[#1A4231] hover:bg-[#235841] text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 w-full lg:w-fit mt-6 hover:scale-[1.02] active:scale-[0.98]">
                            <span>{{ __('new_design.monthly_offers.btn_purchase') }}</span>
                            <svg class="w-4 h-4 transform rotate-180 rtl:rotate-180 ltr:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Right Column (Image with Tag) - rendered on the Right in RTL, Left in LTR -->
                    <div class="lg:col-span-6 relative h-64 lg:h-auto min-h-[380px] order-1 lg:order-2">
                        <img src="{{ asset('assets/elketar/featured_coffee_crop.png') }}" alt="Featured Crop" class="w-full h-full object-cover">
                        <!-- Offer of the Month Badge -->
                        <div class="absolute bottom-6 right-6 bg-white/95 text-[#1A4231] font-black text-xs px-4 py-2.5 rounded-full shadow-md">
                            {{ __('new_design.monthly_offers.featured_tag') }}
                        </div>
                    </div>

                </div>

                <!-- Two Side-by-Side Small Offers Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                    
                    <!-- Card 1: Colombia Supremo (Right Card in RTL) -->
                    <div class="bg-white rounded-[24px] border border-gray-200 shadow-sm p-6 relative hover:shadow-md transition-all duration-300 flex flex-col justify-between min-h-[220px] order-1 md:order-2">
                        <!-- Icon badge top-right -->
                        <div class="absolute right-6 rtl:right-6 ltr:right-auto ltr:left-6 -top-4 w-9 h-9 rounded-full bg-[#F3F4F6] flex items-center justify-center border-2 border-white shadow-sm">
                            <!-- Coffee cup icon -->
                            <svg class="w-4 h-4 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <!-- Status tag top-left -->
                        <div class="absolute left-6 rtl:left-6 ltr:left-auto ltr:right-6 -top-3 bg-amber-50 text-amber-700 border border-amber-200/50 text-[10px] font-bold px-3 py-1 rounded-full">
                            {{ __('new_design.monthly_offers.card1_tag') }}
                        </div>

                        <!-- Content -->
                        <div class="mt-4 text-start">
                            <h3 class="text-xl font-bold text-[#1A4231] mb-2">
                                {{ __('new_design.monthly_offers.card1_title') }}
                            </h3>
                            <p class="text-gray-500 text-xs font-semibold leading-relaxed mb-6">
                                {{ __('new_design.monthly_offers.card1_subtitle') }}
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-2">
                            <span class="text-lg font-black text-[#1A4231]">
                                {{ __('new_design.monthly_offers.card1_price') }}
                            </span>
                            <a href="#" class="text-[#1A4231] font-bold text-xs hover:underline transition-all flex items-center gap-1">
                                <span>{{ __('new_design.monthly_offers.btn_shop_now') }}</span>
                                <svg class="w-3.5 h-3.5 transform rotate-180 rtl:rotate-180 ltr:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Tasting Box (Left Card in RTL) -->
                    <div class="bg-white rounded-[24px] border border-gray-200 shadow-sm p-6 relative hover:shadow-md transition-all duration-300 flex flex-col justify-between min-h-[220px] order-2 md:order-1">
                        <!-- Icon badge top-right -->
                        <div class="absolute right-6 rtl:right-6 ltr:right-auto ltr:left-6 -top-4 w-9 h-9 rounded-full bg-[#F3F4F6] flex items-center justify-center border-2 border-white shadow-sm">
                            <!-- Sparkles icon -->
                            <svg class="w-4 h-4 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <!-- Status tag top-left -->
                        <div class="absolute left-6 rtl:left-6 ltr:left-auto ltr:right-6 -top-3 bg-emerald-50 text-emerald-700 border border-emerald-200/50 text-[10px] font-bold px-3 py-1 rounded-full">
                            {{ __('new_design.monthly_offers.card2_tag') }}
                        </div>

                        <!-- Content -->
                        <div class="mt-4 text-start">
                            <h3 class="text-xl font-bold text-[#1A4231] mb-2">
                                {{ __('new_design.monthly_offers.card2_title') }}
                            </h3>
                            <p class="text-gray-500 text-xs font-semibold leading-relaxed mb-6">
                                {{ __('new_design.monthly_offers.card2_subtitle') }}
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-2">
                            <span class="text-lg font-black text-[#1A4231]">
                                {{ __('new_design.monthly_offers.card2_price') }}
                            </span>
                            <a href="#" class="text-[#1A4231] font-bold text-xs hover:underline transition-all flex items-center gap-1">
                                <span>{{ __('new_design.monthly_offers.btn_shop_now') }}</span>
                                <svg class="w-3.5 h-3.5 transform rotate-180 rtl:rotate-180 ltr:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            @endif

            <!-- Newsletter Subscription Banner -->
            <div class="bg-[#1A4231] rounded-[24px] p-8 lg:p-12 text-white relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8 mt-6"
                 style="background-image: url('{{ asset('assets/elketar/Background.png') }}'); background-size: cover; background-position: center; background-blend-mode: multiply; background-color: #1A4231;">
                <!-- Inner overlay for premium finish -->
                <div class="absolute inset-0 bg-[#1A4231]/80 z-0"></div>

                <!-- Text side -->
                <div class="relative z-10 text-start flex flex-col gap-2 max-w-xl">
                    <h3 class="text-xl lg:text-2xl font-black text-[#FDF9F0]">
                        {{ __('new_design.monthly_offers.newsletter_title') }}
                    </h3>
                    <p class="text-white/85 text-xs lg:text-sm font-semibold leading-relaxed">
                        {{ __('new_design.monthly_offers.newsletter_subtitle') }}
                    </p>
                </div>

                <!-- Input form side -->
                <form action="{{ route('subscribe') }}" method="POST" class="relative z-10 w-full lg:w-auto flex items-center gap-3 bg-white/10 p-2 rounded-xl border border-white/20">
                    @csrf
                    <input type="email" name="email" required placeholder="{{ __('new_design.monthly_offers.newsletter_placeholder') }}" 
                           class="bg-transparent text-white placeholder-white/50 text-xs lg:text-sm font-semibold px-4 py-3 focus:outline-none w-full lg:w-64">
                    <button type="submit" class="bg-white text-[#1A4231] hover:bg-[#FDF9F0] font-bold text-xs lg:text-sm px-6 py-3 rounded-lg transition-colors whitespace-nowrap">
                        {{ __('new_design.monthly_offers.newsletter_btn') }}
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>

<!-- JavaScript for Interactive Hero Slide -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('hero-slider-container');
        const track = document.getElementById('hero-slider-track');
        const originalSlides = Array.from(track.children);
        const totalOriginal = originalSlides.length;
        const prevBtn = document.getElementById('hero-slide-prev');
        const nextBtn = document.getElementById('hero-slide-next');
        
        if (totalOriginal === 0) return;

        // Infinite Loop setup: Clone slides dynamically
        const lastClone = originalSlides[totalOriginal - 1].cloneNode(true);
        lastClone.classList.add('slide-clone');
        track.insertBefore(lastClone, originalSlides[0]);

        const firstClone = originalSlides[0].cloneNode(true);
        firstClone.classList.add('slide-clone');
        track.appendChild(firstClone);

        let currentIndex = 2;
        let isTransitioning = false;

        function updateSliderPosition(animate = true) {
            if (animate) {
                track.classList.add('transition-enabled');
            } else {
                track.classList.remove('transition-enabled');
            }
            
            track.style.setProperty('--current-index', currentIndex);
            
            let activeVisualIndex = currentIndex;
            if (currentIndex === 0) {
                activeVisualIndex = totalOriginal;
            } else if (currentIndex === totalOriginal + 1) {
                activeVisualIndex = 1;
            }
            
            const allSlides = Array.from(track.children);
            allSlides.forEach((slide, idx) => {
                if (idx === activeVisualIndex) {
                    slide.classList.remove('opacity-50', 'scale-95');
                    slide.classList.add('opacity-100', 'scale-100');
                } else {
                    slide.classList.remove('opacity-100', 'scale-100');
                    slide.classList.add('opacity-50', 'scale-95');
                }
            });
        }

        track.addEventListener('transitionend', () => {
            isTransitioning = false;
            
            if (currentIndex === 0) {
                currentIndex = totalOriginal;
                updateSliderPosition(false);
            } else if (currentIndex === totalOriginal + 1) {
                currentIndex = 1;
                updateSliderPosition(false);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex++;
            updateSliderPosition(true);
        });

        prevBtn.addEventListener('click', () => {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex--;
            updateSliderPosition(true);
        });

        // Initialize immediately
        updateSliderPosition(false);
        
        window.addEventListener('load', () => updateSliderPosition(false));
        window.addEventListener('resize', () => updateSliderPosition(false));
    });
</script>

@endsection
