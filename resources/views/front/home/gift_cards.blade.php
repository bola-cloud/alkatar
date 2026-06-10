@extends('front.layouts.new_design_layout')

@section('title', __('new_design.gift_cards.title'))

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<div class="gift-cards-page bg-white text-[#1A4231] overflow-hidden" dir="{{ $dir }}" style="font-family: 'Cairo', sans-serif;">

    <!-- Spacer Gap -->
    <div class="h-6 bg-white"></div>

    <div class="container mx-auto px-4 lg:px-8 pb-24 max-w-6xl flex flex-col gap-12">
        
        <!-- Premium Hero Banner -->
        <section class="bg-[#1A4231] rounded-3xl p-8 lg:p-12 text-white relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8"
                 style="background-image: url('{{ asset('assets/elketar/Background.png') }}'); background-size: cover; background-position: center; background-blend-mode: multiply; background-color: #1A4231;">
            <!-- Inner overlay for premium finish -->
            <div class="absolute inset-0 bg-[#1A4231]/80 z-0"></div>

            <!-- Content -->
            <div class="relative z-10 text-start flex flex-col gap-3 max-w-2xl">
                <h1 class="text-3xl lg:text-5xl font-black text-[#FDF9F0] leading-tight">
                    {{ __('new_design.gift_cards.hero_title') }}
                </h1>
                <p class="text-white/85 text-sm lg:text-base font-semibold leading-relaxed">
                    {{ __('new_design.gift_cards.hero_subtitle') }}
                </p>
            </div>

            <!-- Gift Outline Graphic Element -->
            <div class="relative z-10 hidden lg:block opacity-20 transform -rotate-12 select-none">
                <svg class="w-32 h-32 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </section>

        <!-- Interactive Gift Card Selection Section -->
        <section x-data="{ selectedPackage: 'gold', sendMethod: 'whatsapp' }" class="flex flex-col gap-8">
            
            <!-- Section Title & Info -->
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <h2 class="text-xl lg:text-2xl font-black text-[#1A4231]">
                    {{ __('new_design.gift_cards.section_title') }}
                </h2>
                <span class="text-xs lg:text-sm font-bold text-gray-400">
                    {{ __('new_design.gift_cards.options_count') }}
                </span>
            </div>

            <!-- 3 Packages Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Package 1: Gold (500 SAR) -->
                <div class="flex flex-col items-center gap-4">
                    <div x-on:click="selectedPackage = 'gold'" 
                         class="w-full rounded-2xl p-6 text-white cursor-pointer relative transition-all duration-300 shadow-md hover:shadow-lg min-h-[160px] flex flex-col justify-between"
                         style="background-color: #C29F38;"
                         :class="selectedPackage === 'gold' ? 'ring-4 ring-[#1A4231]/30 scale-[1.02]' : 'opacity-90 hover:opacity-100'">
                        
                        <!-- Top Row: Price & Icon -->
                        <div class="flex items-center justify-between w-full">
                            <span class="text-2xl font-black">
                                {{ __('new_design.gift_cards.gold_price') }}
                            </span>
                            <!-- Medal icon -->
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Bottom Row: Package Title & Desc -->
                        <div class="text-start mt-6">
                            <h3 class="text-sm font-bold text-white/80">
                                {{ __('new_design.gift_cards.gold_title') }}
                            </h3>
                            <p class="text-base font-black">
                                {{ __('new_design.gift_cards.gold_desc') }}
                            </p>
                        </div>
                    </div>
                    <!-- Custom Checkbox Selector -->
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-[#1A4231] select-none" x-on:click="selectedPackage = 'gold'">
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-all"
                             :class="selectedPackage === 'gold' ? 'border-[#1A4231] bg-[#1A4231]' : ''">
                            <div class="w-2 h-2 rounded-full bg-white" x-show="selectedPackage === 'gold'"></div>
                        </div>
                        <span>{{ __('new_design.gift_cards.select_package') }}</span>
                    </label>
                </div>

                <!-- Package 2: Silver (250 SAR) -->
                <div class="flex flex-col items-center gap-4">
                    <div x-on:click="selectedPackage = 'silver'" 
                         class="w-full rounded-2xl p-6 text-white cursor-pointer relative transition-all duration-300 shadow-md hover:shadow-lg min-h-[160px] flex flex-col justify-between"
                         style="background-color: #9CA9B8;"
                         :class="selectedPackage === 'silver' ? 'ring-4 ring-[#1A4231]/30 scale-[1.02]' : 'opacity-90 hover:opacity-100'">
                        
                        <!-- Top Row: Price & Icon -->
                        <div class="flex items-center justify-between w-full">
                            <span class="text-2xl font-black">
                                {{ __('new_design.gift_cards.silver_price') }}
                            </span>
                            <!-- Coffee cup icon -->
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Bottom Row: Package Title & Desc -->
                        <div class="text-start mt-6">
                            <h3 class="text-sm font-bold text-white/80">
                                {{ __('new_design.gift_cards.silver_title') }}
                            </h3>
                            <p class="text-base font-black">
                                {{ __('new_design.gift_cards.silver_desc') }}
                            </p>
                        </div>
                    </div>
                    <!-- Custom Checkbox Selector -->
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-[#1A4231] select-none" x-on:click="selectedPackage = 'silver'">
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-all"
                             :class="selectedPackage === 'silver' ? 'border-[#1A4231] bg-[#1A4231]' : ''">
                            <div class="w-2 h-2 rounded-full bg-white" x-show="selectedPackage === 'silver'"></div>
                        </div>
                        <span>{{ __('new_design.gift_cards.select_package') }}</span>
                    </label>
                </div>

                <!-- Package 3: Bronze (100 SAR) -->
                <div class="flex flex-col items-center gap-4">
                    <div x-on:click="selectedPackage = 'bronze'" 
                         class="w-full rounded-2xl p-6 text-white cursor-pointer relative transition-all duration-300 shadow-md hover:shadow-lg min-h-[160px] flex flex-col justify-between"
                         style="background-color: #A85A28;"
                         :class="selectedPackage === 'bronze' ? 'ring-4 ring-[#1A4231]/30 scale-[1.02]' : 'opacity-90 hover:opacity-100'">
                        
                        <!-- Top Row: Price & Icon -->
                        <div class="flex items-center justify-between w-full">
                            <span class="text-2xl font-black">
                                {{ __('new_design.gift_cards.bronze_price') }}
                            </span>
                            <!-- Gift box icon -->
                            <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Bottom Row: Package Title & Desc -->
                        <div class="text-start mt-6">
                            <h3 class="text-sm font-bold text-white/80">
                                {{ __('new_design.gift_cards.bronze_title') }}
                            </h3>
                            <p class="text-base font-black">
                                {{ __('new_design.gift_cards.bronze_desc') }}
                            </p>
                        </div>
                    </div>
                    <!-- Custom Checkbox Selector -->
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-sm text-[#1A4231] select-none" x-on:click="selectedPackage = 'bronze'">
                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-all"
                             :class="selectedPackage === 'bronze' ? 'border-[#1A4231] bg-[#1A4231]' : ''">
                            <div class="w-2 h-2 rounded-full bg-white" x-show="selectedPackage === 'bronze'"></div>
                        </div>
                        <span>{{ __('new_design.gift_cards.select_package') }}</span>
                    </label>
                </div>

            </div>

            <!-- Form Card Container -->
            <form action="#" method="POST" class="bg-white rounded-3xl border border-gray-200 p-6 lg:p-12 grid grid-cols-1 lg:grid-cols-2 gap-12 shadow-sm mt-6">
                @csrf
                <input type="hidden" name="package" :value="selectedPackage">
                <input type="hidden" name="method" :value="sendMethod">

                <!-- Right Column (Send Method & Recipient details) - first in RTL DOM order -->
                <div class="flex flex-col gap-6 order-1 lg:order-2">
                    
                    <!-- Title with icon -->
                    <div class="flex items-center gap-2 mb-2">
                        <!-- paper plane icon -->
                        <svg class="w-5 h-5 text-[#1A4231] transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <span class="text-[#1A4231] font-black text-lg">
                            {{ __('new_design.gift_cards.send_method') }}
                        </span>
                    </div>

                    <!-- WhatsApp & Email Toggle Buttons -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- WhatsApp button -->
                        <button type="button" x-on:click="sendMethod = 'whatsapp'"
                                class="flex items-center justify-center gap-2 py-4.5 px-6 rounded-xl font-bold text-sm transition-all duration-300"
                                :class="sendMethod === 'whatsapp' ? 'border-2 border-[#1A4231] bg-white text-[#1A4231]' : 'bg-gray-50 border border-gray-100 text-gray-400 hover:bg-gray-100'">
                            <!-- Whatsapp icon -->
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.968C16.588 1.97 14.118.946 11.998.945c-5.442 0-9.866 4.372-9.87 9.802 0 1.772.483 3.5 1.4 5.013L2.5 21.5l6.147-1.595z"/>
                            </svg>
                            <span>{{ __('new_design.gift_cards.method_whatsapp') }}</span>
                        </button>

                        <!-- Email button -->
                        <button type="button" x-on:click="sendMethod = 'email'"
                                class="flex items-center justify-center gap-2 py-4.5 px-6 rounded-xl font-bold text-sm transition-all duration-300"
                                :class="sendMethod === 'email' ? 'border-2 border-[#1A4231] bg-white text-[#1A4231]' : 'bg-gray-50 border border-gray-100 text-gray-400 hover:bg-gray-100'">
                            <!-- Email envelope icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ __('new_design.gift_cards.method_email') }}</span>
                        </button>
                    </div>

                    <!-- Input: Recipient Name -->
                    <div class="flex flex-col text-start mt-2">
                        <label class="block text-sm font-bold text-[#1A4231] mb-2">
                            {{ __('new_design.gift_cards.recipient_name') }}
                        </label>
                        <input type="text" name="recipient_name" required placeholder="{{ __('new_design.gift_cards.recipient_name_placeholder') }}" 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:border-[#1A4231] bg-[#F9FAFB] text-sm font-semibold">
                    </div>

                    <!-- Input: Phone Number (WhatsApp) -->
                    <div class="flex flex-col text-start" x-show="sendMethod === 'whatsapp'">
                        <label class="block text-sm font-bold text-[#1A4231] mb-2">
                            {{ __('new_design.gift_cards.phone_number') }}
                        </label>
                        <input type="text" name="phone" placeholder="{{ __('new_design.gift_cards.phone_number_placeholder') }}" 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:border-[#1A4231] bg-[#F9FAFB] text-sm font-semibold">
                    </div>

                    <!-- Input: Email Address -->
                    <div class="flex flex-col text-start" x-show="sendMethod === 'email'">
                        <label class="block text-sm font-bold text-[#1A4231] mb-2">
                            {{ __('new_design.gift_cards.email_address') }}
                        </label>
                        <input type="email" name="email" placeholder="{{ __('new_design.gift_cards.email_placeholder') }}" 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:border-[#1A4231] bg-[#F9FAFB] text-sm font-semibold">
                    </div>

                </div>

                <!-- Left Column (Add message & submit) - second in RTL DOM order -->
                <div class="flex flex-col justify-between order-2 lg:order-1">
                    
                    <div>
                        <!-- Title with icon -->
                        <div class="flex items-center gap-2 mb-4">
                            <!-- edit icon -->
                            <svg class="w-5 h-5 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="text-[#1A4231] font-black text-lg">
                                {{ __('new_design.gift_cards.add_message') }}
                            </span>
                        </div>

                        <!-- Special Message Textarea -->
                        <textarea name="message" placeholder="{{ __('new_design.gift_cards.message_placeholder') }}" rows="6" 
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:border-[#1A4231] bg-[#F9FAFB] text-sm font-semibold resize-none"></textarea>
                    </div>

                    <!-- Submit & Note Block -->
                    <div class="mt-6 flex flex-col gap-2">
                        <button type="submit" class="w-full bg-[#1A4231] hover:bg-[#235841] text-white font-extrabold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99] shadow-md">
                            <span>{{ __('new_design.gift_cards.confirm_btn') }}</span>
                            <!-- Heart icon -->
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <p class="text-xs text-gray-400 font-semibold text-center leading-relaxed">
                            {{ __('new_design.gift_cards.secure_pay_note') }}
                        </p>
                    </div>

                </div>

            </form>

        </section>

        <!-- Bottom Feature Highlights -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8 py-10 border-t border-gray-100">
            
            <!-- Feature 1: Validity -->
            <div class="flex flex-col items-center text-center p-4">
                <div class="w-12 h-12 rounded-full bg-[#F3F4F6] flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#1A4231] mb-2">
                    {{ __('new_design.gift_cards.feat_validity_title') }}
                </h3>
                <p class="text-xs text-gray-400 font-bold max-w-xs leading-relaxed">
                    {{ __('new_design.gift_cards.feat_validity_desc') }}
                </p>
            </div>

            <!-- Feature 2: Branches -->
            <div class="flex flex-col items-center text-center p-4">
                <div class="w-12 h-12 rounded-full bg-[#F3F4F6] flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#1A4231] mb-2">
                    {{ __('new_design.gift_cards.feat_branches_title') }}
                </h3>
                <p class="text-xs text-gray-400 font-bold max-w-xs leading-relaxed">
                    {{ __('new_design.gift_cards.feat_branches_desc') }}
                </p>
            </div>

            <!-- Feature 3: Delivery -->
            <div class="flex flex-col items-center text-center p-4">
                <div class="w-12 h-12 rounded-full bg-[#F3F4F6] flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-[#1A4231] mb-2">
                    {{ __('new_design.gift_cards.feat_delivery_title') }}
                </h3>
                <p class="text-xs text-gray-400 font-bold max-w-xs leading-relaxed">
                    {{ __('new_design.gift_cards.feat_delivery_desc') }}
                </p>
            </div>

        </section>

    </div>

</div>

@endsection
