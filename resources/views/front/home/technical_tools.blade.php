@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<style>
    /* Cairo font integration for the page container */
    .technical-page {
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

<div class="technical-page bg-white text-[#1A4231] overflow-hidden" dir="{{ $dir }}">

    <!-- White Spacer Gap -->
    <div class="h-6 bg-white"></div>

    <!-- Hero Banner Slider Section (Peek stage padding carousel matching Figma layout) -->
    <section class="relative py-6 lg:py-10 w-full bg-white overflow-hidden">
        <!-- Slider Container with forced LTR direction to ensure consistent offset calculations -->
        <div id="hero-slider-container" class="relative w-full overflow-hidden select-none" dir="ltr">
            
            <!-- Slides Track -->
            <div id="hero-slider-track" class="hero-slider-track transition-enabled">
                @foreach($advertises as $index => $ad)
                    @php
                        $img = $ad->image;
                        $imgPublic = file_exists(public_path($img)) ? asset($img) : (isset($img) ? asset(PromotionImage() . $img) : '');
                    @endphp
                    @if($imgPublic)
                        <div class="hero-slide">
                            @if($ad->link)
                                <a href="{{ $ad->link }}" target="_blank" class="block w-full h-full">
                            @endif
                            <img src="{{ $imgPublic }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $isRtl ? $ad->ar_title : $ad->en_title }}">
                            @if($ad->link)
                                </a>
                            @endif
                        </div>
                    @endif
                @endforeach
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

    <!-- Main Content Section: Brewing Tools Grid & Filters -->
    <section class="py-12 lg:py-16 bg-white relative">
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            
            <!-- Section Header (Smart selection pill badge and main headers) -->
            <div class="max-w-3xl mx-auto text-center mb-10 flex flex-col items-center justify-center gap-3">
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-600 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <span>{{ __('new_design.technical_tools.badge_smart_selection') }}</span>
                </span>
                <h2 class="text-3xl lg:text-4xl font-black text-[#1A4231] leading-tight">
                    {{ __('new_design.technical_tools.hero_title') }}
                </h2>
                <p class="text-gray-600 text-sm lg:text-base font-semibold leading-relaxed max-w-2xl mt-1">
                    {{ __('new_design.technical_tools.hero_subtitle') }}
                </p>
            </div>

            <!-- Category Pills (Filters with dynamic click trigger) -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-12">
                <button data-filter="all" class="category-filter-btn bg-[#1A4231] text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-md hover:opacity-95 transition-all">
                    {{ __('new_design.technical_tools.filter_all') }}
                </button>
                @foreach($subcategories as $sub)
                    <button data-filter="sub-{{ $sub->id }}" class="category-filter-btn border border-gray-200 text-gray-600 hover:bg-gray-50 px-6 py-2.5 rounded-full text-sm font-bold transition-all">
                        {{ $sub->localized_name }}
                    </button>
                @endforeach
            </div>

            <!-- Products Grid: 3 Premium Cards (Responsive grid structure) -->
            <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto px-4">
                
                @foreach($products as $product)
                @php
                    $tagText = '';
                    if (strtolower($product->ItemTag) === 'beginner') {
                        $tagText = __('new_design.technical_tools.badge_beginner');
                    } elseif (strtolower($product->ItemTag) === 'professional' || strtolower($product->ItemTag) === 'pro') {
                        $tagText = __('new_design.technical_tools.badge_pro');
                    } else {
                        $tagText = $product->ItemTag;
                    }
                    
                    $descLines = array_filter(explode("\n", str_replace("\r", "", $product->localized_description)));
                    if (empty($descLines)) {
                        $descLines = [$product->localized_description];
                    }
                    
                    $prodImg = $product->Primary_Image;
                    $imgSrc = (strpos($prodImg, 'http') === 0) ? $prodImg : asset(ProductImage().$prodImg);
                @endphp
                
                <div class="product-item bg-white border border-gray-150 rounded-[32px] shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between" data-category="sub-{{ $product->subcategory_id }}">
                    <!-- Image Area -->
                    <div class="h-[260px] relative w-full overflow-hidden shrink-0">
                        <a href="{{ route('single.product.new', $product->en_Product_Slug) }}" class="block w-full h-full">
                            <img src="{{ $imgSrc }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" alt="{{ $product->localized_name }}" onerror="this.onerror=null;this.src='{{ asset(ProductImage() . 'prod.png') }}';">
                        </a>
                        @if($tagText)
                            <!-- Badge -->
                            <span class="absolute top-4 right-4 bg-white/90 backdrop-blur-md text-[#1A4231] px-4 py-1.5 rounded-full text-xs font-black border border-[#1A4231]/10">
                                {{ $tagText }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Content Details -->
                    <div class="p-6 lg:p-8 flex flex-col justify-between flex-grow gap-6">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-baseline justify-between gap-2">
                                <h3 class="text-xl lg:text-2xl font-black text-[#1A4231] leading-snug">
                                    <a href="{{ route('single.product.new', $product->en_Product_Slug) }}" class="hover:underline">
                                        {{ $product->localized_name }}
                                    </a>
                                </h3>
                                <div class="text-[#1A4231] font-extrabold flex items-baseline gap-0.5 whitespace-nowrap shrink-0">
                                    <span class="text-2xl font-black">{{ number_format($product->Price) }}</span>
                                    <span class="text-sm font-bold">{{ __('new_design.coffee_crops.currency') }}</span>
                                </div>
                            </div>
                            <p class="text-xs lg:text-sm font-semibold text-gray-500 leading-relaxed">
                                {{ $product->localized_about }}
                            </p>
                        </div>

                        <!-- Attributes List -->
                        <ul class="space-y-3 text-xs lg:text-sm leading-relaxed border-t border-gray-100 pt-4">
                            @foreach(array_slice($descLines, 0, 2) as $line)
                                <li class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-full bg-[#1A4231]/5 flex items-center justify-center shrink-0 border border-[#1A4231]/10 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span class="font-semibold text-gray-700">{{ $line }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Card Action -->
                        <div class="pt-2">
                            <a href="{{ route('single.product.new', $product->en_Product_Slug) }}" class="w-full py-4 rounded-[16px] bg-[#1A4231] hover:bg-[#2C624A] active:scale-[0.98] text-white font-extrabold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transition-all text-sm">
                                <span>{{ __('new_design.technical_tools.btn_view_in_store') }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
        </div>
    </section>

    <!-- Section 2: Complete Bundles (باقات متكاملة) -->
    <section class="py-16 bg-[#FDF9F0]/40 border-y border-gray-100">
        <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
            
            <!-- Section Header (With Left Align link matching Figma layout) -->
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-10 gap-4">
                <div class="flex flex-col gap-2">
                    <h2 class="text-3xl font-black text-[#1A4231]">
                        {{ __('new_design.technical_tools.bundles_title') }}
                    </h2>
                    <p class="text-gray-500 font-semibold text-sm lg:text-base">
                        {{ __('new_design.technical_tools.bundles_subtitle') }}
                    </p>
                </div>
                <a href="{{ route('front.store') }}" class="inline-flex items-center gap-1.5 text-[#1A4231] hover:underline font-extrabold text-sm transition-all">
                    <span>{{ __('new_design.technical_tools.bundles_view_all') }}</span>
                    <svg class="w-4 h-4 transform {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </div>

            <!-- Bundles Cards (Left and Right matching screenshots) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Left Bundle Card (Light theme) -->
                <div class="border border-gray-200 rounded-[32px] p-6 lg:p-8 bg-white flex flex-col justify-between min-h-[360px] hover:shadow-xl transition-all relative overflow-hidden group">
                    <!-- SVG Background kettle faint pattern -->
                    <svg class="absolute bottom-4 left-4 w-40 h-40 text-gray-50 opacity-40 pointer-events-none transform translate-y-6 scale-[1.3] transition-transform duration-500 group-hover:scale-[1.4] {{ $isRtl ? 'right-4 left-auto' : 'left-4' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547"/>
                    </svg>

                    <div class="relative z-10 flex flex-col gap-4">
                        <!-- Badge -->
                        <span class="inline-block bg-gray-100 text-gray-700 px-4 py-1.5 rounded-full text-xs font-black self-start">
                            {{ __('new_design.technical_tools.bundle1_badge') }}
                        </span>
                        
                        <!-- Content -->
                        <div class="flex flex-col gap-2">
                            <h3 class="text-2xl font-black text-[#1A4231]">
                                {{ __('new_design.technical_tools.bundle1_title') }}
                            </h3>
                            <p class="text-gray-500 text-sm lg:text-base font-semibold leading-relaxed max-w-md">
                                {{ __('new_design.technical_tools.bundle1_desc') }}
                            </p>
                        </div>
                    </div>

                    <!-- Price & Action Row -->
                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-8 border-t border-gray-100 pt-6">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-400 font-bold uppercase">{{ __('new_design.coffee_crops.price_exclusive') }}</span>
                            <div class="text-[#1A4231] font-black text-3xl flex items-baseline gap-1 mt-0.5">
                                <span>1,850</span>
                                <span class="text-base font-bold">{{ __('new_design.coffee_crops.currency') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('front.store') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-[16px] bg-[#1A4231] hover:bg-[#2C624A] text-white font-extrabold text-sm transition-all shadow-md active:scale-95 text-center">
                            {{ __('new_design.technical_tools.btn_order_now') }}
                        </a>
                    </div>
                </div>

                <!-- Right Bundle Card (Dark theme) -->
                <div class="rounded-[32px] p-6 lg:p-8 bg-[#1A4231] text-white flex flex-col justify-between min-h-[360px] hover:shadow-xl transition-all relative overflow-hidden group">
                    <!-- SVG Background cup/V60 faint pattern -->
                    <svg class="absolute bottom-4 left-4 w-40 h-40 text-white/5 opacity-30 pointer-events-none transform translate-y-6 scale-[1.3] transition-transform duration-500 group-hover:scale-[1.4] {{ $isRtl ? 'right-4 left-auto' : 'left-4' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 16v1M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
                    </svg>

                    <div class="relative z-10 flex flex-col gap-4">
                        <!-- Badge -->
                        <span class="inline-block bg-[#FBF0D8] text-[#1A4231] px-4 py-1.5 rounded-full text-xs font-black self-start">
                            {{ __('new_design.technical_tools.bundle2_badge') }}
                        </span>
                        
                        <!-- Content -->
                        <div class="flex flex-col gap-2">
                            <h3 class="text-2xl font-black text-[#FBF0D8]">
                                {{ __('new_design.technical_tools.bundle2_title') }}
                            </h3>
                            <p class="text-white/80 text-sm lg:text-base font-semibold leading-relaxed max-w-md">
                                {{ __('new_design.technical_tools.bundle2_desc') }}
                            </p>
                        </div>
                    </div>

                    <!-- Price & Action Row -->
                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-8 border-t border-white/10 pt-6">
                        <div class="flex flex-col">
                            <span class="text-xs text-white/50 font-bold uppercase">{{ __('new_design.coffee_crops.price_exclusive') }}</span>
                            <div class="flex items-baseline gap-2 mt-0.5">
                                <div class="text-[#FBF0D8] font-black text-3xl flex items-baseline gap-1">
                                    <span>280</span>
                                    <span class="text-base font-bold">{{ __('new_design.coffee_crops.currency') }}</span>
                                </div>
                                <span class="text-sm font-semibold text-white/40 line-through">350 {{ __('new_design.coffee_crops.currency') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('front.store') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-[16px] bg-white hover:bg-gray-50 text-[#1A4231] font-extrabold text-sm transition-all shadow-md active:scale-95 text-center">
                            {{ __('new_design.technical_tools.btn_order_now') }}
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Section 3: Premium CTA Help Section (هل تحتاج لمساعدة في الاختيار؟) -->
    <section class="py-20 relative overflow-hidden" style="background-image: url('{{ asset('assets/elketar/fff.png') }}'); background-size: cover; background-position: center;">
        <!-- Backdrop to ensure readability -->
        <div class="absolute inset-0  z-0"></div>

        <div class="container mx-auto px-4 relative z-10 text-center flex flex-col items-center gap-6">
            <!-- Book Icon Badge -->
            <div class="w-14 h-14 bg-[#1A4231] rounded-2xl flex items-center justify-center text-[#FBF0D8] shadow-lg mb-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            
            <h2 class="text-3xl lg:text-4xl font-black text-[#1A4231] max-w-2xl leading-tight">
                {{ __('new_design.technical_tools.cta_title') }}
            </h2>
            <p class="text-gray-600 text-sm lg:text-base font-semibold leading-relaxed max-w-2xl -mt-2">
                {{ __('new_design.technical_tools.cta_subtitle') }}
            </p>

            <div class="pt-4">
                <a href="#" class="inline-flex items-center justify-center bg-[#1A4231] hover:bg-[#2C624A] text-white font-extrabold rounded-full px-8 py-2.5 transition-all duration-300 shadow-md hover:scale-[1.02] active:scale-[0.98] text-sm">
                    {{ __('new_design.technical_tools.cta_btn') }}
                </a>
            </div>
        </div>
    </section>
    <div class="h-20 bg-white"></div>

</div>

<!-- JavaScript for Interactive Hero Slide & Category Filter -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- Slider Setup ---
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

        // --- Category Filter Setup ---
        const filterButtons = document.querySelectorAll('.category-filter-btn');
        const productItems = document.querySelectorAll('.product-item');

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active classes from all buttons
                filterButtons.forEach(b => {
                    b.classList.remove('bg-[#1A4231]', 'text-white', 'shadow-md');
                    b.classList.add('border', 'border-gray-200', 'text-gray-600');
                });

                // Add active classes to current button
                btn.classList.add('bg-[#1A4231]', 'text-white', 'shadow-md');
                btn.classList.remove('border', 'border-gray-200', 'text-gray-600', 'hover:bg-gray-50');

                const filterValue = btn.getAttribute('data-filter');

                productItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'flex';
                        // Add fade-in effect dynamically
                        item.classList.add('animate-fade-in');
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 350ms cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
</style>

@endsection
