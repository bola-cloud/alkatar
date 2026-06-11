@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<style>
    .experts-page {
        font-family: 'Cairo', sans-serif;
    }
</style>

<div class="experts-page bg-[#EDEAE3] text-[#1A4231] overflow-hidden" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/Section - Categories Showcase.png') }}'); background-size: cover; background-position: center; background-repeat: repeat;">

    <div class="h-10 bg-white"></div>


    <!-- Content Sections -->
    <div class="container mx-auto px-4 lg:px-8 py-16 lg:py-24 flex flex-col gap-20">

        <!-- Why Consult an Expert Section -->
        <section class="text-center relative">
            <div class="flex items-center justify-center gap-3 mb-12">
                <!-- Chat / Avatar floating circular badge on the right side of the text in RTL -->
                <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform">
                    <!-- Headset/User Icon -->
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl lg:text-4xl font-black text-[#1A4231]">
                    {{ __('new_design.experts.section_title') }}
                </h2>
            </div>

            <!-- Steps Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12 max-w-6xl mx-auto pt-6">
                <!-- Step 1 -->
                <div class="flex flex-col items-center relative">
                    <div class="w-16 h-16 rounded-full bg-[#1A4231] text-white flex items-center justify-center text-xl font-bold shadow-lg z-10 -mb-8 border-4 border-white">
                        1
                    </div>
                    <div class="bg-white rounded-[24px] p-8 pt-12 shadow-md w-full border border-gray-100 hover:shadow-xl transition-all duration-300 min-h-[180px] flex flex-col justify-center">
                        <h3 class="text-xl font-bold text-[#1A4231] mb-2">
                            {{ __('new_design.experts.step1_title') }}
                        </h3>
                        <p class="text-gray-500 text-sm font-semibold leading-relaxed">
                            {{ __('new_design.experts.step1_desc') }}
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col items-center relative">
                    <div class="w-16 h-16 rounded-full bg-[#1A4231] text-white flex items-center justify-center text-xl font-bold shadow-lg z-10 -mb-8 border-4 border-white">
                        2
                    </div>
                    <div class="bg-white rounded-[24px] p-8 pt-12 shadow-md w-full border border-gray-100 hover:shadow-xl transition-all duration-300 min-h-[180px] flex flex-col justify-center">
                        <h3 class="text-xl font-bold text-[#1A4231] mb-2">
                            {{ __('new_design.experts.step2_title') }}
                        </h3>
                        <p class="text-gray-500 text-sm font-semibold leading-relaxed">
                            {{ __('new_design.experts.step2_desc') }}
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col items-center relative">
                    <div class="w-16 h-16 rounded-full bg-[#1A4231] text-white flex items-center justify-center text-xl font-bold shadow-lg z-10 -mb-8 border-4 border-white">
                        3
                    </div>
                    <div class="bg-white rounded-[24px] p-8 pt-12 shadow-md w-full border border-gray-100 hover:shadow-xl transition-all duration-300 min-h-[180px] flex flex-col justify-center">
                        <h3 class="text-xl font-bold text-[#1A4231] mb-2">
                            {{ __('new_design.experts.step3_title') }}
                        </h3>
                        <p class="text-gray-500 text-sm font-semibold leading-relaxed">
                            {{ __('new_design.experts.step3_desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Two-Column Contact Form Card -->
        <section class="max-w-5xl mx-auto w-full">
            <div class="bg-white rounded-[32px] shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12">
                
                <!-- Right Column (Dark Green Pattern Info) - rendered on the Right in RTL, Left in LTR -->
                <div class="lg:col-span-5 bg-[#1A4231] p-8 lg:p-12 text-white flex flex-col justify-between relative overflow-hidden" 
                     style="background-image: url('{{ asset('assets/elketar/Background.png') }}'); background-size: cover; background-position: center; background-blend-mode: multiply; background-color: #1A4231;">
                    <!-- Inner overlay for premium finish -->
                    <div class="absolute inset-0 bg-[#1A4231]/80 z-0"></div>

                    <div class="relative z-10 flex flex-col gap-6 text-start">
                        <h3 class="text-2xl lg:text-3xl font-black text-[#FDF9F0]">
                            {{ __('new_design.experts.sidebar_title') }}
                        </h3>
                        <p class="text-white/80 text-sm font-semibold leading-relaxed">
                            {{ __('new_design.experts.sidebar_desc') }}
                        </p>

                        <!-- Features bullet list -->
                        <ul class="flex flex-col gap-4 mt-4 font-semibold text-sm">
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-[#FDF9F0]/20 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-[#FDF9F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span>{{ __('new_design.experts.sidebar_feat1') }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-[#FDF9F0]/20 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-[#FDF9F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span>{{ __('new_design.experts.sidebar_feat2') }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full bg-[#FDF9F0]/20 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-[#FDF9F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span>{{ __('new_design.experts.sidebar_feat3') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Bottom Mail Box -->
                    <div class="relative z-10 pt-8 mt-12 border-t border-white/10 flex items-center gap-4 text-start">
                        <div class="w-10 h-10 rounded-full bg-[#FDF9F0]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#FDF9F0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs text-white/60 font-bold uppercase tracking-wider">
                                {{ __('new_design.experts.sidebar_email_label') }}
                            </span>
                            <a href="mailto:{{ @$allsettings['experts_email'] ?? 'b2b@al-qatar.com' }}" class="text-sm font-black hover:text-[#FDF9F0] transition-colors">
                                {{ @$allsettings['experts_email'] ?? 'b2b@al-qatar.com' }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Left Column (White Form) - rendered on the Left in RTL, Right in LTR -->
                <div class="lg:col-span-7 p-8 lg:p-12">
                    <h3 class="text-2xl font-bold text-[#1A4231] mb-8 text-center">
                        {{ __('new_design.experts.form_title') }}
                    </h3>

                    <form action="{{ route('experts.store') }}" method="POST" class="flex flex-col gap-6" style="display: flex; flex-direction: column; gap: 24px;">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="flex flex-col gap-2" style="display: flex; flex-direction: column; gap: 8px; text-align: start;">
                                <label class="text-sm font-bold text-gray-700" style="text-align: start;">
                                    {{ __('new_design.experts.form_name') }}
                                </label>
                                <input type="text" name="name" placeholder="{{ __('new_design.experts.form_name_placeholder') }}" 
                                       class="w-full bg-[#FDF9F0]/50 border border-gray-200 rounded-[14px] py-3 px-4 text-sm font-semibold focus:outline-none focus:ring-1 focus:ring-[#1A4231] focus:bg-white transition-all"
                                       style="width: 100%; text-align: start;">
                            </div>

                            <!-- Company Name -->
                            <div class="flex flex-col gap-2" style="display: flex; flex-direction: column; gap: 8px; text-align: start;">
                                <label class="text-sm font-bold text-gray-700" style="text-align: start;">
                                    {{ __('new_design.experts.form_company') }}
                                </label>
                                <input type="text" name="company" placeholder="{{ __('new_design.experts.form_company_placeholder') }}" 
                                       class="w-full bg-[#FDF9F0]/50 border border-gray-200 rounded-[14px] py-3 px-4 text-sm font-semibold focus:outline-none focus:ring-1 focus:ring-[#1A4231] focus:bg-white transition-all"
                                       style="width: 100%; text-align: start;">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Email Address -->
                            <div class="flex flex-col gap-2" style="display: flex; flex-direction: column; gap: 8px; text-align: start;">
                                <label class="text-sm font-bold text-gray-700" style="text-align: start;">
                                    {{ __('new_design.experts.form_email') }}
                                </label>
                                <input type="email" name="email" placeholder="{{ __('new_design.experts.form_email_placeholder') }}" 
                                       class="w-full bg-[#FDF9F0]/50 border border-gray-200 rounded-[14px] py-3 px-4 text-sm font-semibold focus:outline-none focus:ring-1 focus:ring-[#1A4231] focus:bg-white transition-all"
                                       style="width: 100%; text-align: start;">
                            </div>

                            <!-- Phone Number -->
                            <div class="flex flex-col gap-2" style="display: flex; flex-direction: column; gap: 8px; text-align: start;">
                                <label class="text-sm font-bold text-gray-700" style="text-align: start;">
                                    {{ __('new_design.experts.form_phone') }}
                                </label>
                                <input type="text" name="phone" placeholder="{{ __('new_design.experts.form_phone_placeholder') }}" 
                                       class="w-full bg-[#FDF9F0]/50 border border-gray-200 rounded-[14px] py-3 px-4 text-sm font-semibold focus:outline-none focus:ring-1 focus:ring-[#1A4231] focus:bg-white transition-all"
                                       style="width: 100%; text-align: start;">
                            </div>
                        </div>

                        <!-- Request Message -->
                        <div class="flex flex-col gap-2" style="display: flex; flex-direction: column; gap: 8px; text-align: start;">
                            <label class="text-sm font-bold text-gray-700" style="text-align: start;">
                                {{ __('new_design.experts.form_request') }}
                            </label>
                            <textarea name="message" rows="4" placeholder="{{ __('new_design.experts.form_request_placeholder') }}" 
                                      class="w-full bg-[#FDF9F0]/50 border border-gray-200 rounded-[14px] py-3 px-4 text-sm font-semibold focus:outline-none focus:ring-1 focus:ring-[#1A4231] focus:bg-white transition-all resize-none"
                                      style="width: 100%; text-align: start;"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-[#1A4231] hover:bg-[#235841] text-white font-bold py-4 rounded-[16px] transition-all duration-300 shadow-md hover:scale-[1.01] active:scale-[0.99] mt-2">
                            {{ __('new_design.experts.form_submit') }}
                        </button>
                    </form>
                </div>

            </div>
        </section>
    </div>
    <div class="h-20 bg-white"></div>

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
