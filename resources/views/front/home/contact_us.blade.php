@extends('front.layouts.new_design_layout')

@section('title', __('new_design.contact.title'))

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<div class="contact-us-page bg-white text-[#1A4231] overflow-hidden" dir="{{ $dir }}" style="font-family: 'Cairo', sans-serif;">

    <!-- Spacer Gap -->
    <div class="h-6 bg-white"></div>

    <div class="container mx-auto px-4 lg:px-8 pb-24 max-w-6xl flex flex-col gap-12">
        
        <!-- Premium Hero Banner -->
        <section class="bg-[#1A4231] rounded-3xl p-8 lg:p-12 text-white relative overflow-hidden flex flex-col items-center justify-center text-center gap-4 min-h-[220px]"
                 style="background-image: url('{{ asset('assets/elketar/Background.png') }}'); background-size: cover; background-position: center; background-blend-mode: multiply; background-color: #1A4231;">
            <!-- Inner overlay for premium finish -->
            <div class="absolute inset-0 bg-[#1A4231]/85 z-0"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col gap-3 max-w-3xl">
                <h1 class="text-3xl lg:text-5xl font-black text-[#FDF9F0] leading-tight">
                    {{ __('new_design.contact.hero_title') }}
                </h1>
                <p class="text-white/85 text-xs lg:text-base font-semibold leading-relaxed">
                    {{ __('new_design.contact.hero_subtitle') }}
                </p>
            </div>
        </section>

        <!-- Two Column Layout: Form & Contact Info -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column (Contact Form) - Grid Span 7 -->
            <div class="lg:col-span-7 bg-white rounded-[32px] border border-gray-200 p-6 lg:p-10 shadow-sm order-2 lg:order-1">
                
                <form action="{{ route('contact.us.store') }}" method="POST" class="flex flex-col gap-6">
                    @csrf
                    
                    <!-- Row: Full Name & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="flex flex-col text-start">
                            <label class="block text-xs lg:text-sm font-bold text-gray-500 mb-2">
                                {{ __('new_design.contact.form_name') }}
                            </label>
                            <input type="text" name="name" required placeholder="{{ __('new_design.contact.form_name_placeholder') }}" 
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-[#1A4231] bg-[#F9FAFB] text-sm font-semibold">
                        </div>
                        
                        <!-- Email -->
                        <div class="flex flex-col text-start">
                            <label class="block text-xs lg:text-sm font-bold text-gray-500 mb-2">
                                {{ __('new_design.contact.form_email') }}
                            </label>
                            <input type="email" name="email" required placeholder="{{ __('new_design.contact.form_email_placeholder') }}" 
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-[#1A4231] bg-[#F9FAFB] text-sm font-semibold">
                        </div>
                    </div>

                    <!-- Subject -->
                    <div class="flex flex-col text-start">
                        <label class="block text-xs lg:text-sm font-bold text-gray-500 mb-2">
                            {{ __('new_design.contact.form_subject') }}
                        </label>
                        <input type="text" name="subject" required placeholder="{{ __('new_design.contact.form_subject_placeholder') }}" 
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-[#1A4231] bg-[#F9FAFB] text-sm font-semibold">
                    </div>

                    <!-- Message Message -->
                    <div class="flex flex-col text-start">
                        <label class="block text-xs lg:text-sm font-bold text-gray-500 mb-2">
                            {{ __('new_design.contact.form_message') }}
                        </label>
                        <textarea name="message" required placeholder="{{ __('new_design.contact.form_message_placeholder') }}" rows="6" 
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3.5 focus:outline-none focus:border-[#1A4231] bg-[#F9FAFB] text-sm font-semibold resize-none"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="bg-[#1A4231] hover:bg-[#235841] text-white font-extrabold py-4 px-8 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99] w-full md:w-fit shadow-md">
                        <span>{{ __('new_design.contact.form_submit') }}</span>
                        <!-- Paper plane send icon -->
                        <svg class="w-4 h-4 text-white transform rotate-180 rtl:rotate-180 ltr:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>

                </form>

            </div>

            <!-- Right Column (Contact Info Cards) - Grid Span 5 -->
            <div class="lg:col-span-5 flex flex-col gap-6 order-1 lg:order-2">
                
                <!-- Heading -->
                <h2 class="text-xl lg:text-2xl font-black text-[#1A4231] text-start mb-2">
                    {{ __('new_design.contact.info_title') }}
                </h2>

                <!-- 3 Contact Cards -->
                <div class="flex flex-col gap-4">
                    
                    <!-- Card 1: WhatsApp -->
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $allsettings['call_us'] ?? '') }}" target="_blank" class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 text-start shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="w-12 h-12 rounded-xl bg-[#F3F4F6] flex items-center justify-center shrink-0">
                            <!-- WhatsApp icon -->
                            <svg class="w-6 h-6 text-[#1A4231]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.968C16.588 1.97 14.118.946 11.998.945c-5.442 0-9.866 4.372-9.87 9.802 0 1.772.483 3.5 1.4 5.013L2.5 21.5l6.147-1.595z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-400">
                                {{ __('new_design.contact.whatsapp_lbl') }}
                            </span>
                            <span class="text-base font-black text-[#1A4231] mt-0.5" dir="ltr">
                                {{ $allsettings['call_us'] ?? __('new_design.contact.whatsapp_val') }}
                            </span>
                        </div>
                    </a>

                    <!-- Card 2: Email -->
                    <a href="mailto:{{ $allsettings['from_address'] ?? ($allsettings['email'] ?? __('new_design.contact.email_val')) }}" class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 text-start shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="w-12 h-12 rounded-xl bg-[#F3F4F6] flex items-center justify-center shrink-0">
                            <!-- Envelope icon -->
                            <svg class="w-6 h-6 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-400">
                                {{ __('new_design.contact.email_lbl') }}
                            </span>
                            <span class="text-base font-black text-[#1A4231] mt-0.5">
                                {{ $allsettings['from_address'] ?? ($allsettings['email'] ?? __('new_design.contact.email_val')) }}
                            </span>
                        </div>
                    </a>

                    <!-- Card 3: Work Hours -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 text-start shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="w-12 h-12 rounded-xl bg-[#F3F4F6] flex items-center justify-center shrink-0">
                            <!-- Clock icon -->
                            <svg class="w-6 h-6 text-[#1A4231]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-400">
                                {{ __('new_design.contact.hours_lbl') }}
                            </span>
                            <span class="text-base font-black text-[#1A4231] mt-0.5">
                                {{ __('new_design.contact.hours_val') }}
                            </span>
                        </div>
                    </div>

                </div>

                <!-- Follow Us Section -->
                @php
                    $socialLinks = getSocialLink();
                @endphp
                @if($socialLinks)
                    <div class="flex flex-col gap-3 mt-4 text-start">
                        <span class="text-xs font-bold text-gray-400">
                            {{ __('new_design.contact.follow_us') }}
                        </span>
                        <div class="flex items-center gap-3">
                            @if($socialLinks->Facebook)
                                <a href="{{ format_social_url($socialLinks->Facebook) }}" target="_blank" class="w-10 h-10 rounded-full border border-gray-200 hover:bg-gray-50 flex items-center justify-center text-[#1A4231] transition-all">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($socialLinks->Twitter)
                                <a href="{{ format_social_url($socialLinks->Twitter) }}" target="_blank" class="w-10 h-10 rounded-full border border-gray-200 hover:bg-gray-50 flex items-center justify-center text-[#1A4231] transition-all">
                                    <svg class="w-4.5 h-4.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($socialLinks->Instagram)
                                <a href="{{ format_social_url($socialLinks->Instagram) }}" target="_blank" class="w-10 h-10 rounded-full border border-gray-200 hover:bg-gray-50 flex items-center justify-center text-[#1A4231] transition-all">
                                    <svg class="w-4.5 h-4.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($socialLinks->Linkedin)
                                <a href="{{ format_social_url($socialLinks->Linkedin) }}" target="_blank" class="w-10 h-10 rounded-full border border-gray-200 hover:bg-gray-50 flex items-center justify-center text-[#1A4231] transition-all">
                                    <svg class="w-4.5 h-4.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

        </section>

        <!-- Google Map Iframe Container -->
        <section class="w-full">
            <div class="rounded-3xl overflow-hidden border border-gray-200 shadow-sm h-[320px]">
                <iframe src="{{ @$allsettings['contact_map_iframe'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115918.42398539268!2d46.72186835!3d24.81381395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f03890d489399%3A0xba974d1c98e91f55!2sRiyadh%20Saudi%20Arabia!5e0!3m2!1sen!2ssa!4v1680000000000!5m2!1sen!2ssa' }}" 
                        class="w-full h-full border-0" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </section>

    </div>

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
