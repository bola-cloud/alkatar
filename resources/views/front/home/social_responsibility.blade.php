@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<style>
    /* Restore normal navbar and header positioning */
    body > div[x-data] {
        position: relative !important;
        background-color: #EDEAE3 !important;
        z-index: auto !important;
        box-shadow: none !important;
    }
    body > nav {
        position: relative !important;
        top: auto !important;
        background: linear-gradient(to right, #1A4231, #387C5F) !important;
        z-index: auto !important;
        border-bottom: none !important;
    }
    
    /* Remove any margins/paddings between navbar and main hero content */
    main {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    .social-page {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    .hero-section {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    /* Premium Cairo Styling */
    .social-page {
        font-family: 'Cairo', sans-serif;
    }
    /* Responsive custom grids */
    .initiatives-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 32px;
    }
    @media (min-width: 640px) {
        .initiatives-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (min-width: 1024px) {
        .initiatives-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    /* Stats Layout Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    @media (min-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
</style>

<div class="social-page bg-white text-[#1A4231] overflow-hidden" dir="{{ $dir }}">

    <!-- White Spacer Gap between Navbar and Hero Section -->
    <div class="h-10 bg-white"></div>

    <!-- Hero Banner Section (Full-bleed edge-to-edge cover below navbar) -->
    <section class="hero-section relative min-h-[480px] lg:min-h-[728px] w-full flex items-center justify-center text-white bg-cover bg-center bg-no-repeat" 
             style="background-image: url('{{ asset('assets/elketar/hero_social.png') }}');">
        
        <!-- Darkened gradient overlay for perfect readability -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#1A4231]/95 via-[#1A4231]/40 to-[#1A4231]/10 z-0"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 py-12 lg:py-20">
            <div class="max-w-4xl mx-auto text-center flex flex-col items-center justify-center">
                <!-- Badge -->
                <span class="inline-block bg-[#FBF0D8] text-[#1A4231] font-bold text-xs lg:text-sm px-4 py-1.5 rounded-full mb-5 shadow-md uppercase tracking-wider">
                    {{ __('new_design.menu.social_responsibility') }}
                </span>
                
                <!-- Heading (Responsive sizes) -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#FBF0D8] leading-tight mb-5 drop-shadow-sm">
                    {{ __('new_design.social_responsibility.hero_title') }}
                </h1>
                
                <!-- Subheading description -->
                <p class="text-white/95 text-sm sm:text-base lg:text-lg font-semibold max-w-3xl leading-relaxed mb-8">
                    {{ __('new_design.social_responsibility.hero_subtitle') }}
                </p>

                <!-- Interactive CTAs -->
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="#initiatives" class="bg-white text-[#1A4231] hover:bg-[#FBF0D8] active:scale-[0.98] px-9 py-4 rounded-full text-sm lg:text-base font-bold transition-all shadow-lg flex items-center gap-2">
                        <span>{{ __('new_design.social_responsibility.hero_btn_explore') }}</span>
                        <svg class="w-4 h-4 transform {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="#" class="border-2 border-white/80 hover:border-white hover:bg-white/10 active:scale-[0.98] px-9 py-3.5 rounded-full text-sm lg:text-base font-bold transition-all flex items-center gap-2">
                        <!-- Play Icon -->
                        <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 fill-current text-white {{ $isRtl ? 'rotate-180' : '' }}" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                        <span>{{ __('new_design.social_responsibility.hero_btn_video') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Strategic Initiatives Section (Shares a single continuous wavy beige background) -->
    <section id="initiatives" class="py-20 lg:py-24 relative bg-[#EDEAE3]" 
             style="background-image: url('{{ asset('assets/elketar/Section - Categories Showcase.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        
        <!-- Subtle Overlay -->
        <div class="absolute inset-0 bg-[#EDEAE3]/20 z-0"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="max-w-3xl mx-auto text-center mb-16 text-[#1A4231]">
                <span class="text-sm font-bold uppercase tracking-wider bg-[#1A4231]/10 px-4 py-1.5 rounded-full inline-block mb-3">
                    {{ __('new_design.social_responsibility.initiatives_badge') }}
                </span>
                <h2 class="text-3xl lg:text-5xl font-black mb-4">
                    {{ __('new_design.social_responsibility.initiatives_title') }}
                </h2>
                <div class="w-16 h-1 bg-[#1A4231] mx-auto mt-4 rounded-full"></div>
            </div>

            <!-- Initiatives Cards Grid (RTL/LTR Aware layout) -->
            <div class="initiatives-grid">
                
                @if($isRtl)
                    <!-- CARD 1 (Right in RTL / Support Local Farmers) -->
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-xl hover:scale-[1.03] transition-all duration-300 border border-gray-100/50 flex flex-col group">
                        <div class="relative h-60 overflow-hidden">
                            <img src="{{ asset('assets/elketar/card1.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Supporting Local Farmers">
                            <span class="absolute top-4 right-4 bg-[#1A4231] text-[#FBF0D8] font-bold text-xs px-3.5 py-1.5 rounded-full shadow-md">
                                {{ __('new_design.social_responsibility.card1_badge') }}
                            </span>
                        </div>
                        <div class="p-8 flex flex-col justify-between flex-grow text-start">
                            <div>
                                <h3 class="text-xl font-bold text-[#1A4231] mb-3 group-hover:text-[#387C5F] transition-colors">{{ __('new_design.social_responsibility.card1_title') }}</h3>
                                <p class="text-[#6B7280] text-sm lg:text-base font-medium leading-relaxed mb-6">{{ __('new_design.social_responsibility.card1_desc') }}</p>
                            </div>
                            <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-[#1A4231] hover:underline self-start">
                                <span>{{ __('new_design.social_responsibility.card1_more') }}</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-300 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- CARD 2 (Middle in RTL / Water Harvesting Initiative) -->
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-xl hover:scale-[1.03] transition-all duration-300 border border-gray-100/50 flex flex-col group">
                        <div class="relative h-60 overflow-hidden">
                            <img src="{{ asset('assets/elketar/card2.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Water Harvesting Initiative">
                            <span class="absolute top-4 right-4 bg-[#0F5A8C] text-white font-bold text-xs px-3.5 py-1.5 rounded-full shadow-md">
                                {{ __('new_design.social_responsibility.card2_badge') }}
                            </span>
                        </div>
                        <div class="p-8 flex flex-col justify-between flex-grow text-start">
                            <div>
                                <h3 class="text-xl font-bold text-[#1A4231] mb-3 group-hover:text-[#387C5F] transition-colors">{{ __('new_design.social_responsibility.card2_title') }}</h3>
                                <p class="text-[#6B7280] text-sm lg:text-base font-medium leading-relaxed mb-6">{{ __('new_design.social_responsibility.card2_desc') }}</p>
                            </div>
                            <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-[#1A4231] hover:underline self-start">
                                <span>{{ __('new_design.social_responsibility.card2_more') }}</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-300 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- CARD 3 (Left in RTL / Family Empowerment Project) -->
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-xl hover:scale-[1.03] transition-all duration-300 border border-gray-100/50 flex flex-col group">
                        <div class="relative h-60 overflow-hidden">
                            <img src="{{ asset('assets/elketar/card3.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Family Empowerment Project">
                            <span class="absolute top-4 right-4 bg-[#146B6B] text-white font-bold text-xs px-3.5 py-1.5 rounded-full shadow-md">
                                {{ __('new_design.social_responsibility.card3_badge') }}
                            </span>
                        </div>
                        <div class="p-8 flex flex-col justify-between flex-grow text-start">
                            <div>
                                <h3 class="text-xl font-bold text-[#1A4231] mb-3 group-hover:text-[#387C5F] transition-colors">{{ __('new_design.social_responsibility.card3_title') }}</h3>
                                <p class="text-[#6B7280] text-sm lg:text-base font-medium leading-relaxed mb-6">{{ __('new_design.social_responsibility.card3_desc') }}</p>
                            </div>
                            <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-[#1A4231] hover:underline self-start">
                                <span>{{ __('new_design.social_responsibility.card3_more') }}</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-300 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                @else
                    <!-- CARD 3 (Left in LTR -> Family Empowerment) -->
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-xl hover:scale-[1.03] transition-all duration-300 border border-gray-100/50 flex flex-col group">
                        <div class="relative h-60 overflow-hidden">
                            <img src="{{ asset('assets/elketar/card3.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Family Empowerment Project">
                            <span class="absolute top-4 right-4 bg-[#146B6B] text-white font-bold text-xs px-3.5 py-1.5 rounded-full shadow-md">
                                {{ __('new_design.social_responsibility.card3_badge') }}
                            </span>
                        </div>
                        <div class="p-8 flex flex-col justify-between flex-grow text-start">
                            <div>
                                <h3 class="text-xl font-bold text-[#1A4231] mb-3 group-hover:text-[#387C5F] transition-colors">{{ __('new_design.social_responsibility.card3_title') }}</h3>
                                <p class="text-[#6B7280] text-sm lg:text-base font-medium leading-relaxed mb-6">{{ __('new_design.social_responsibility.card3_desc') }}</p>
                            </div>
                            <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-[#1A4231] hover:underline self-start">
                                <span>{{ __('new_design.social_responsibility.card3_more') }}</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- CARD 2 (Middle in LTR -> Water Harvesting) -->
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-xl hover:scale-[1.03] transition-all duration-300 border border-gray-100/50 flex flex-col group">
                        <div class="relative h-60 overflow-hidden">
                            <img src="{{ asset('assets/elketar/card2.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Water Harvesting Initiative">
                            <span class="absolute top-4 right-4 bg-[#0F5A8C] text-white font-bold text-xs px-3.5 py-1.5 rounded-full shadow-md">
                                {{ __('new_design.social_responsibility.card2_badge') }}
                            </span>
                        </div>
                        <div class="p-8 flex flex-col justify-between flex-grow text-start">
                            <div>
                                <h3 class="text-xl font-bold text-[#1A4231] mb-3 group-hover:text-[#387C5F] transition-colors">{{ __('new_design.social_responsibility.card2_title') }}</h3>
                                <p class="text-[#6B7280] text-sm lg:text-base font-medium leading-relaxed mb-6">{{ __('new_design.social_responsibility.card2_desc') }}</p>
                            </div>
                            <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-[#1A4231] hover:underline self-start">
                                <span>{{ __('new_design.social_responsibility.card2_more') }}</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- CARD 1 (Right in LTR -> Local Farmers) -->
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-xl hover:scale-[1.03] transition-all duration-300 border border-gray-100/50 flex flex-col group">
                        <div class="relative h-60 overflow-hidden">
                            <img src="{{ asset('assets/elketar/card1.png') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="Supporting Local Farmers">
                            <span class="absolute top-4 right-4 bg-[#1A4231] text-[#FBF0D8] font-bold text-xs px-3.5 py-1.5 rounded-full shadow-md">
                                {{ __('new_design.social_responsibility.card1_badge') }}
                            </span>
                        </div>
                        <div class="p-8 flex flex-col justify-between flex-grow text-start">
                            <div>
                                <h3 class="text-xl font-bold text-[#1A4231] mb-3 group-hover:text-[#387C5F] transition-colors">{{ __('new_design.social_responsibility.card1_title') }}</h3>
                                <p class="text-[#6B7280] text-sm lg:text-base font-medium leading-relaxed mb-6">{{ __('new_design.social_responsibility.card1_desc') }}</p>
                            </div>
                            <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-[#1A4231] hover:underline self-start">
                                <span>{{ __('new_design.social_responsibility.card1_more') }}</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </section>

    <!-- Impact Numbers Section (أثرنا بالأرقام) -->
    <section class="h-[220px] lg:h-[260px] w-full relative overflow-hidden bg-[#1A4231] flex flex-col justify-center" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/Impact Numbers Section.png') }}'); background-size: cover; background-position: center;">
        
        <!-- Floating Right Image (Loose Beans) -->
        <div class="absolute {{ $isRtl ? 'right-0' : 'left-0' }} bottom-0 w-44 md:w-60 lg:w-[340px] z-0 pointer-events-none opacity-40 lg:opacity-100 flex items-end justify-end">
            <img src="{{ asset('assets/elketar/istockphoto-1128469742-612x612 1.png') }}" class="w-full h-auto drop-shadow-2xl translate-y-8 {{ $isRtl ? 'translate-x-2 lg:translate-x-4' : '-translate-x-2 lg:-translate-x-4' }}" alt="Coffee Beans">
        </div>

        <!-- Floating Left Image (Hand with scoop) -->
        <div class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} bottom-0 w-44 md:w-60 lg:w-[340px] z-0 pointer-events-none opacity-40 lg:opacity-100 flex items-end justify-start">
            <img src="{{ asset('assets/elketar/65646 1.png') }}" class="w-full h-auto drop-shadow-2xl translate-y-6 lg:translate-y-10 {{ $isRtl ? '-translate-x-2 lg:-translate-x-4' : 'translate-x-2 lg:translate-x-4' }}" alt="Hand Scooping">
        </div>

        <div class="container mx-auto px-4 relative z-10 w-full">
            
            <!-- Section Title -->
            <div class="flex flex-col items-center mb-4 lg:mb-6">
                <span class="text-[#FBF0D8] text-[10px] lg:text-xs font-bold mb-1 opacity-90">{{ __('new_design.home.impact_subtitle') }}</span>
                <h2 class="text-white text-center text-xl lg:text-2xl font-black drop-shadow-lg">{{ __('new_design.home.impact_title') }}</h2>
            </div>
            
            <!-- 4 Stats Grid -->
            <div class="grid grid-cols-4 gap-2 lg:gap-4 text-center text-white max-w-4xl mx-auto">
                
                <!-- Stat 1 -->
                <div class="flex flex-col items-center justify-center">
                    <span class="text-2xl lg:text-4xl font-extrabold block mb-1 text-[#FBF0D8] drop-shadow-md">{{ __('new_design.home.stat_water_val') }}</span>
                    <span class="text-[9px] lg:text-xs font-bold text-white/90 leading-tight">{!! __('new_design.home.stat_water_lbl') !!}</span>
                </div>

                <!-- Stat 2 -->
                <div class="flex flex-col items-center justify-center">
                    <span class="text-2xl lg:text-4xl font-extrabold block mb-1 text-[#FBF0D8] drop-shadow-md">{{ __('new_design.home.stat_tools_val') }}</span>
                    <span class="text-[9px] lg:text-xs font-bold text-white/90 leading-tight">{!! __('new_design.home.stat_tools_lbl') !!}</span>
                </div>

                <!-- Stat 3 -->
                <div class="flex flex-col items-center justify-center">
                    <span class="text-2xl lg:text-4xl font-extrabold block mb-1 text-[#FBF0D8] drop-shadow-md">{{ __('new_design.home.stat_trees_val') }}</span>
                    <span class="text-[9px] lg:text-xs font-bold text-white/90 leading-tight">{!! __('new_design.home.stat_trees_lbl') !!}</span>
                </div>

                <!-- Stat 4 -->
                <div class="flex flex-col items-center justify-center">
                    <span class="text-2xl lg:text-4xl font-extrabold block mb-1 text-[#FBF0D8] drop-shadow-md">{{ __('new_design.home.stat_energy_val') }}</span>
                    <span class="text-[9px] lg:text-xs font-bold text-white/90 leading-tight">{!! __('new_design.home.stat_energy_lbl') !!}</span>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Project Section (مشروع مميز) -->
    <section class="py-20 lg:py-24 relative bg-[#EDEAE3]" 
             style="background-image: url('{{ asset('assets/elketar/Section - Categories Showcase.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        
        <!-- Subtle Overlay -->
        <div class="absolute inset-0 bg-[#EDEAE3]/20 z-0"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Column 1: Content Details (First in HTML -> Right in RTL, Left in LTR) -->
                <div class="lg:col-span-6 text-start flex flex-col gap-6">
                    <div>
                        <!-- Simple Green text badge, no background/border, matches Figma -->
                        <span class="text-xs lg:text-sm font-bold text-[#1A4231] block mb-3 uppercase tracking-wider">
                            {{ __('new_design.social_responsibility.featured_badge') }}
                        </span>
                        <h2 class="text-3xl lg:text-5xl font-black text-[#1A4231] leading-tight">
                            {{ __('new_design.social_responsibility.featured_title') }}
                        </h2>
                    </div>

                    <p class="text-gray-700 text-sm lg:text-base font-semibold leading-relaxed">
                        {{ __('new_design.social_responsibility.featured_desc') }}
                    </p>

                    <!-- Bullets checks with light green background circles -->
                    <ul class="space-y-4 font-bold text-[#1A4231] text-sm lg:text-base">
                        <li class="flex items-center gap-3.5">
                            <span class="w-6 h-6 rounded-full bg-[#1A4231]/10 flex items-center justify-center text-[#1A4231] shrink-0 shadow-sm border border-[#1A4231]/10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>{{ __('new_design.social_responsibility.featured_bullet1') }}</span>
                        </li>
                        <li class="flex items-center gap-3.5">
                            <span class="w-6 h-6 rounded-full bg-[#1A4231]/10 flex items-center justify-center text-[#1A4231] shrink-0 shadow-sm border border-[#1A4231]/10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>{{ __('new_design.social_responsibility.featured_bullet2') }}</span>
                        </li>
                        <li class="flex items-center gap-3.5">
                            <span class="w-6 h-6 rounded-full bg-[#1A4231]/10 flex items-center justify-center text-[#1A4231] shrink-0 shadow-sm border border-[#1A4231]/10">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>{{ __('new_design.social_responsibility.featured_bullet3') }}</span>
                        </li>
                    </ul>

                    <!-- CTA Button (Aligns to start - right in RTL, no arrow icon, matches Figma) -->
                    <a href="#" class="mt-4 bg-[#1A4231] text-white hover:opacity-90 active:scale-[0.98] px-10 py-4 rounded-full text-base font-bold shadow-lg transition-all flex items-center justify-center self-start">
                        <span>{{ __('new_design.social_responsibility.featured_btn') }}</span>
                    </a>
                </div>

                <!-- Column 2: Crop/Field image (Second in HTML -> Left in RTL, Right in LTR) -->
                <div class="lg:col-span-6 flex justify-center">
                    <div class="relative overflow-hidden rounded-[32px] shadow-2xl w-full max-w-lg aspect-square">
                        <img src="{{ asset('assets/elketar/grass.png') }}" class="w-full h-full object-cover transform hover:scale-[1.05] transition-transform duration-500" alt="Sustainability Oasis Aerial view">
                        <!-- Overlay green leaf brand asset or border accent -->
                        <div class="absolute inset-0 border-[12px] border-white/10 rounded-[32px] pointer-events-none"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- White spacer before footer -->
    <div class="w-full h-16 lg:h-24 bg-white"></div>

</div>

@endsection
