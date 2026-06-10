@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<style>
    /* Cairo font integration for the page container */
    .trial-page {
        font-family: 'Cairo', sans-serif;
    }
</style>

<div class="trial-page bg-white text-[#1A4231] overflow-hidden" dir="{{ $dir }}">

    <!-- White Spacer Gap between Navbar and Hero Section -->
    <div class="h-10 bg-white"></div>

    <!-- Hero Banner Section (Full-bleed edge-to-edge cover of the whole screen) -->
    <section class="relative py-16 lg:py-24 w-full flex flex-col items-center justify-center text-white bg-cover bg-center bg-no-repeat overflow-hidden" 
             style="background-image: url('{{ asset('assets/elketar/Section - Why Partner With Us.png') }}');">
        
        <!-- Darkened gradient overlay for perfect readability -->
        <div class="absolute inset-0  via-[#1A4231]/50 to-[#1A4231]/10 z-0"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center flex flex-col items-center justify-center">
            
            <!-- Heading -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black leading-tight mb-5 drop-shadow-sm max-w-4xl">
                {{ __('new_design.trial_boxes.hero_title') }}
            </h1>
            
            <!-- Subheading description -->
            <p class="text-white/95 text-sm sm:text-base lg:text-lg font-semibold max-w-3xl leading-relaxed mb-10">
                {{ __('new_design.trial_boxes.hero_subtitle') }}
            </p>

            <!-- Main Feature Showcase Card (trail-box.png) -->
            <div class="w-full max-w-4xl mx-auto flex justify-center hover:scale-[1.01] transition-transform duration-500">
                <div class="relative rounded-[32px] overflow-hidden shadow-2xl border border-white/10 w-full bg-transparent flex justify-center">
                    <img src="{{ asset('assets/elketar/trail-box.png') }}" class="w-full h-auto object-cover rounded-[32px]" alt="Trial Coffee Box Showcase">
                </div>
            </div>

        </div>
    </section>

    <!-- Three Features Section (Cards with light cream wavy pattern background) -->
    <section class="py-20 lg:py-24 relative bg-[#EDEAE3]" 
             style="background-image: url('{{ asset('assets/elketar/Section - Categories Showcase.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        
        <!-- Subtle Overlay -->
        <div class="absolute inset-0 bg-[#EDEAE3]/20 z-0"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Card 1: Multi-Origin (تعدد المصادر) -->
                <div class="bg-white rounded-[32px] p-8 lg:p-10 shadow-xl hover:scale-[1.03] hover:shadow-2xl transition-all duration-300 border border-gray-100/50 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-2xl bg-[#1A4231]/5 flex items-center justify-center mb-6 group-hover:bg-[#1A4231]/10 transition-colors">
                        <!-- Globe SVG -->
                        <svg class="w-7 h-7 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1A4231] mb-3 group-hover:text-[#387C5F] transition-colors">
                        {{ __('new_design.trial_boxes.feature1_title') }}
                    </h3>
                    <p class="text-[#6B7280] text-sm lg:text-base font-semibold leading-relaxed">
                        {{ __('new_design.trial_boxes.feature1_desc') }}
                    </p>
                </div>

                <!-- Card 2: Tasting Guide (دليل التذوق) -->
                <div class="bg-white rounded-[32px] p-8 lg:p-10 shadow-xl hover:scale-[1.03] hover:shadow-2xl transition-all duration-300 border border-gray-100/50 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-2xl bg-[#1A4231]/5 flex items-center justify-center mb-6 group-hover:bg-[#1A4231]/10 transition-colors">
                        <!-- Beaker Chemical SVG -->
                        <svg class="w-7 h-7 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1A4231] mb-3 group-hover:text-[#387C5F] transition-colors">
                        {{ __('new_design.trial_boxes.feature2_title') }}
                    </h3>
                    <p class="text-[#6B7280] text-sm lg:text-base font-semibold leading-relaxed">
                        {{ __('new_design.trial_boxes.feature2_desc') }}
                    </p>
                </div>

                <!-- Card 3: Self-Training (التدريب الذاتي) -->
                <div class="bg-white rounded-[32px] p-8 lg:p-10 shadow-xl hover:scale-[1.03] hover:shadow-2xl transition-all duration-300 border border-gray-100/50 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-2xl bg-[#1A4231]/5 flex items-center justify-center mb-6 group-hover:bg-[#1A4231]/10 transition-colors">
                        <!-- Graduation Cap SVG -->
                        <svg class="w-7 h-7 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1A4231] mb-3 group-hover:text-[#387C5F] transition-colors">
                        {{ __('new_design.trial_boxes.feature3_title') }}
                    </h3>
                    <p class="text-[#6B7280] text-sm lg:text-base font-semibold leading-relaxed">
                        {{ __('new_design.trial_boxes.feature3_desc') }}
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Call to Action Banner Section -->
    <section class="py-20 lg:py-24 relative bg-[#1A4231] overflow-hidden" 
             style="background-image: url('{{ asset('assets/elketar/Section - Why Partner With Us.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        
        <!-- Subtle dark green gradient overlay -->
        <div class="absolute inset-0 bg-[#1A4231]/75 z-0"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-white text-center flex flex-col items-center justify-center">
            
            <h2 class="text-3xl lg:text-5xl font-black mb-4 leading-tight max-w-3xl">
                {{ __('new_design.trial_boxes.cta_title') }}
            </h2>
            
            <p class="text-white/90 text-base lg:text-lg font-semibold max-w-2xl leading-relaxed mb-10">
                {{ __('new_design.trial_boxes.cta_subtitle') }}
            </p>

            <!-- Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('front.store') }}" class="bg-white text-[#1A4231] hover:bg-[#FBF0D8] active:scale-[0.98] px-9 py-4 rounded-full text-base font-bold transition-all shadow-lg flex items-center gap-2">
                    <!-- Shopping Bag Icon -->
                    <svg class="w-5 h-5 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>{{ __('new_design.trial_boxes.cta_btn_store') }}</span>
                </a>
                
                <a href="{{ route('experts') }}" class="border-2 border-white/80 hover:border-white hover:bg-white/10 active:scale-[0.98] px-9 py-3.5 rounded-full text-base font-bold transition-all flex items-center justify-center">
                    <span>{{ __('new_design.trial_boxes.cta_btn_expert') }}</span>
                </a>
            </div>

        </div>
    </section>

    <!-- White spacer before footer -->
    <div class="w-full h-16 lg:h-24 bg-white"></div>

</div>

@endsection
