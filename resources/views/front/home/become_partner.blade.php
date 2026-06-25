@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<style>
    /* Premium Cairo Styling */
    .partner-page {
        font-family: 'Cairo', sans-serif;
    }
    /* Responsive custom grids */
    .why-partner-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }
    @media (min-width: 640px) {
        .why-partner-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (min-width: 1024px) {
        .why-partner-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    /* Steps layout grid */
    .steps-partner-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 32px;
    }
    @media (min-width: 768px) {
        .steps-partner-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    /* Reusable Premium Section Spacing Utility */
    .premium-section-gap {
        margin-bottom: 4rem; /* 64px on mobile */
    }
    @media (min-width: 1024px) {
        .premium-section-gap {
            margin-bottom: 6rem; /* 96px on desktop */
        }
    }
</style>

<div class="partner-page bg-white text-[#1A4231] overflow-hidden" dir="{{ $dir }}">

    <!-- Hero Banner Section -->
    <section class="py-12 lg:py-16 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto">
            <!-- Hero Wavy Card -->
            <div class="relative overflow-hidden rounded-[40px] shadow-2xl min-h-[400px] lg:min-h-[500px] flex items-end p-8 lg:p-16 text-white" 
                 style="background-image: url('{{ asset('assets/elketar/Hero Section.png') }}'); background-size: cover; background-position: center;">
                
                <!-- Darkened gradient overlay for perfect readability -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#1A4231]/95 via-[#1A4231]/40 to-[#1A4231]/10 z-0"></div>

                <div class="relative z-10 max-w-4xl text-start">
                    <!-- B2B Badge -->
                    <span class="inline-block bg-[#FBF0D8] text-[#1A4231] font-bold text-xs lg:text-sm px-4 py-1.5 rounded-full mb-4 shadow-md uppercase tracking-wider">
                        {{ __('new_design.menu.become_partner') }}
                    </span>
                    
                    <!-- Heading -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#FBF0D8] leading-tight mb-4 drop-shadow-sm">
                        {{ __('new_design.become_partner.hero_title') }}
                    </h1>
                    <p class="text-white/90 text-sm lg:text-lg font-semibold max-w-2xl leading-relaxed">
                        {{ __('new_design.become_partner.hero_subtitle') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Join Us Section (لماذا تنضم إلينا؟) -->
    <section class="pt-20 pb-36 lg:pt-24 lg:pb-48 bg-white" style="background-image: url('{{ asset('assets/elketar/Impact Numbers Section.png') }}'); background-size: cover; background-position: center;">
        <div class="container mx-auto px-4 lg:px-8">
            
            <!-- Section Header -->
            <div class="max-w-3xl mx-auto text-center mb-16 text-white">
                <h2 class="text-3xl lg:text-5xl font-black text-[#FBF0D8] mb-4">
                    {{ __('new_design.become_partner.why_title') }}
                </h2>
                <div class="w-16 h-1 bg-[#FBF0D8] mx-auto mt-4 rounded-full"></div>
            </div>

            <!-- Features Grid -->
            <div class="why-partner-grid">
                
                <!-- Card 1: Quality -->
                <div class="bg-white/95 backdrop-blur-md rounded-[28px] p-8 shadow-xl hover:scale-[1.03] transition-all duration-300 border border-white/20 flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-full bg-[#1A4231]/10 flex items-center justify-center text-[#1A4231] mb-6">
                        <!-- Globe Icon -->
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1A4231] mb-4">{{ __('new_design.become_partner.card1_title') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed font-medium">{{ __('new_design.become_partner.card1_desc') }}</p>
                </div>

                <!-- Card 2: Brand -->
                <div class="bg-white/95 backdrop-blur-md rounded-[28px] p-8 shadow-xl hover:scale-[1.03] transition-all duration-300 border border-white/20 flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-full bg-[#1A4231]/10 flex items-center justify-center text-[#1A4231] mb-6">
                        <!-- Star / Shield / Award Icon -->
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1A4231] mb-4">{{ __('new_design.become_partner.card2_title') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed font-medium">{{ __('new_design.become_partner.card2_desc') }}</p>
                </div>

                <!-- Card 3: Support -->
                <div class="bg-white/95 backdrop-blur-md rounded-[28px] p-8 shadow-xl hover:scale-[1.03] transition-all duration-300 border border-white/20 flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-full bg-[#1A4231]/10 flex items-center justify-center text-[#1A4231] mb-6">
                        <!-- Support / Handshake Icon -->
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1A4231] mb-4">{{ __('new_design.become_partner.card3_title') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed font-medium">{{ __('new_design.become_partner.card3_desc') }}</p>
                </div>

            </div>
        </div>
    </section>

     <!-- Combined Steps & Form Section (Shares a single continuous wavy beige background) -->
    <section class="pt-20 pb-8 lg:pt-24 lg:pb-10 relative bg-[#EDEAE3]" 
             style="background-image: url('{{ asset('assets/elketar/Group 62.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        
        <!-- Subtle Overlay -->
        <div class="absolute inset-0 bg-[#EDEAE3]/20 z-0"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            
            <!-- 1. Steps Header -->
            <div class="max-w-3xl mx-auto text-center mb-16 text-[#1A4231]">
                <h2 class="text-3xl lg:text-5xl font-black mb-4">
                    {{ __('new_design.become_partner.steps_title') }}
                </h2>
                <div class="w-16 h-1 bg-[#1A4231] mx-auto mt-4 rounded-full"></div>
            </div>

            <!-- 2. Steps Grid (Elegant White Cards with Overlapping Circles) -->
            <div class="steps-partner-grid mb-24 pt-8">
                
                <!-- Step 1 -->
                <div class="relative bg-white rounded-[20px] shadow-lg p-8 pt-12 flex flex-col items-center text-center border border-gray-100/50 hover:scale-[1.03] transition-all duration-300">
                    <!-- Overlapping Number Circle -->
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 w-16 h-16 rounded-full bg-[#1A4231] text-[#FBF0D8] flex items-center justify-center font-black text-2xl border-4 border-[#EDEAE3] shadow-md">
                        1
                    </div>
                    <!-- Step Text -->
                    <h3 class="text-xl font-bold text-[#1A4231] mb-3">{{ __('new_design.become_partner.step1_title') }}</h3>
                    <p class="text-[#6B7280] text-sm lg:text-base font-medium leading-relaxed max-w-xs">{{ __('new_design.become_partner.step1_desc') }}</p>
                </div>

                <!-- Step 2 -->
                <div class="relative bg-white rounded-[20px] shadow-lg p-8 pt-12 flex flex-col items-center text-center border border-gray-100/50 hover:scale-[1.03] transition-all duration-300">
                    <!-- Overlapping Number Circle -->
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 w-16 h-16 rounded-full bg-[#1A4231] text-[#FBF0D8] flex items-center justify-center font-black text-2xl border-4 border-[#EDEAE3] shadow-md">
                        2
                    </div>
                    <!-- Step Text -->
                    <h3 class="text-xl font-bold text-[#1A4231] mb-3">{{ __('new_design.become_partner.step2_title') }}</h3>
                    <p class="text-[#6B7280] text-sm lg:text-base font-medium leading-relaxed max-w-xs">{{ __('new_design.become_partner.step2_desc') }}</p>
                </div>

                <!-- Step 3 -->
                <div class="relative bg-white rounded-[20px] shadow-lg p-8 pt-12 flex flex-col items-center text-center border border-gray-100/50 hover:scale-[1.03] transition-all duration-300">
                    <!-- Overlapping Number Circle -->
                    <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 w-16 h-16 rounded-full bg-[#1A4231] text-[#FBF0D8] flex items-center justify-center font-black text-2xl border-4 border-[#EDEAE3] shadow-md">
                        3
                    </div>
                    <!-- Step Text -->
                    <h3 class="text-xl font-bold text-[#1A4231] mb-3">{{ __('new_design.become_partner.step3_title') }}</h3>
                    <p class="text-[#6B7280] text-sm lg:text-base font-medium leading-relaxed max-w-xs">{{ __('new_design.become_partner.step3_desc') }}</p>
                </div>

            </div>

            <!-- 3. Partnership Request Form Container -->
            <div class="max-w-6xl mx-auto bg-white rounded-[40px] overflow-hidden shadow-2xl border border-gray-100">
                
                <!-- Inner Grid: Left/Right panels -->
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    
                    <!-- Side Info Badge Column (Green Background) -->
                    <div class="lg:col-span-4 bg-[#1A4231] text-white p-8 lg:p-12 flex flex-col justify-between relative overflow-hidden" 
                         style="background-image: url('{{ asset('assets/elketar/Impact Numbers Section.png') }}'); background-size: cover; background-position: center;">
                        
                        <div class="absolute inset-0 bg-[#1a4231]/30 z-0"></div>

                        <div class="relative z-10 flex flex-col gap-6">
                            
                            <!-- Small Brand Badge -->
                            <div class="w-14 h-14 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white mb-4">
                                <img src="{{ asset('assets/elketar/logo.png') }}" class="w-10 h-10 object-contain invert brightness-0" alt="Al-Katar Brand Logo">
                            </div>

                            <h3 class="text-2xl lg:text-3xl font-black text-[#FBF0D8] leading-tight">
                                {{ __('new_design.become_partner.form_badge_title') }}
                            </h3>
                            
                            <p class="text-white/85 text-sm lg:text-base font-semibold leading-relaxed">
                                {{ __('new_design.become_partner.form_badge_desc') }}
                            </p>

                            <!-- Bullet Benefits list -->
                            <ul class="space-y-4 mt-6 text-sm font-semibold text-white/95">
                                <li class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-[#FBF0D8] shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span>{{ __('new_design.become_partner.form_bullet1') }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-[#FBF0D8] shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span>{{ __('new_design.become_partner.form_bullet2') }}</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-[#FBF0D8] shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span>{{ __('new_design.become_partner.form_bullet3') }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Email Contact Box at Bottom -->
                        <div class="relative z-10 mt-12 pt-6 border-t border-white/10 flex items-center gap-4">
                            <span class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center text-white shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </span>
                            <div class="text-start">
                                <p class="text-xs text-white/60 font-semibold mb-0.5">{{ __('new_design.become_partner.email_label') }}</p>
                                <a href="mailto:b2b@al-qatar.com" class="text-sm font-black text-[#FBF0D8] hover:underline">b2b@al-qatar.com</a>
                            </div>
                        </div>

                    </div>

                    <!-- Main White Form Column -->
                    <form action="{{ route('become.partner.store') }}" method="POST" class="lg:col-span-8 p-8 lg:p-12 flex flex-col gap-6 text-start">
                        @csrf
                        <h3 class="text-2xl lg:text-3xl font-black text-[#1A4231] pb-2 border-b border-gray-100">
                            {{ __('new_design.become_partner.form_title') }}
                        </h3>

                        <!-- Grid Inputs -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            
                            <!-- input: Name -->
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-bold text-gray-700">{{ __('new_design.become_partner.input_name') }}</label>
                                <input type="text" name="name" required placeholder="{{ __('new_design.become_partner.input_name_placeholder') }}" 
                                       class="w-full bg-[#F9F8F6] border border-gray-200 rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#1A4231] focus:bg-white transition-all">
                            </div>

                            <!-- input: Company Name -->
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-bold text-gray-700">{{ __('new_design.become_partner.input_company') }}</label>
                                <input type="text" name="company" required placeholder="{{ __('new_design.become_partner.input_company_placeholder') }}" 
                                       class="w-full bg-[#F9F8F6] border border-gray-200 rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#1A4231] focus:bg-white transition-all">
                            </div>

                            <!-- input: Contact Phone -->
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-bold text-gray-700">{{ __('new_design.become_partner.input_phone') }}</label>
                                <input type="tel" name="phone" required placeholder="{{ __('new_design.become_partner.input_phone_placeholder') }}" 
                                       class="w-full bg-[#F9F8F6] border border-gray-200 rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#1A4231] focus:bg-white transition-all text-start" dir="ltr">
                            </div>

                            <!-- input: Email Address -->
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-bold text-gray-700">{{ __('new_design.become_partner.input_email') }}</label>
                                <input type="email" name="email" required placeholder="{{ __('new_design.become_partner.input_email_placeholder') }}" 
                                       class="w-full bg-[#F9F8F6] border border-gray-200 rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#1A4231] focus:bg-white transition-all text-start" dir="ltr">
                            </div>

                        </div>

                        <!-- Notes text area -->
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-bold text-gray-700">{{ __('new_design.become_partner.input_notes') }}</label>
                            <textarea name="message" rows="4" placeholder="{{ __('new_design.become_partner.input_notes_placeholder') }}" 
                                      class="w-full bg-[#F9F8F6] border border-gray-200 rounded-2xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#1A4231] focus:bg-white transition-all"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full sm:w-auto self-start mt-4 bg-[#1A4231] text-white hover:opacity-90 active:scale-[0.98] px-10 py-4 rounded-full text-base font-bold shadow-lg transition-all flex items-center justify-center gap-3">
                            <span>{{ __('new_design.become_partner.btn_submit') }}</span>
                            <svg class="w-5 h-5 transform {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </section>

    <!-- White spacer before footer -->
    <div class="w-full h-16 lg:h-24 bg-white"></div>

</div>

@if($errors->any())
    @push('scripts')
        <script>
            $(document).ready(function() {
                @foreach($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            });
        </script>
    @endpush
@endif

@endsection
