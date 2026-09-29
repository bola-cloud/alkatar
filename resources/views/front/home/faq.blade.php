@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() == 'fr' || app()->getLocale() == 'ar';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<!-- Hero Section -->
<section class="relative w-full py-20 lg:py-32 flex items-end overflow-hidden" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/gradient_image.png') }}'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-gradient-to-t from-[#1A4231]/95 via-[#1A4231]/50 to-[#1A4231]/30 z-0"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 drop-shadow-lg">
            {{ $isRtl ? 'الأسئلة الشائعة' : 'Frequently Asked Questions' }}
        </h1>
        <p class="text-white/90 text-base md:text-lg lg:text-xl font-semibold max-w-2xl mx-auto">
            {{ $isRtl ? 'تجدون هنا إجابات لأكثر الأسئلة تكراراً' : 'Find answers to our most common questions here' }}
        </p>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 lg:py-24 bg-white" dir="{{ $dir }}">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl text-start">
        @if(isset($faqs) && $faqs->count() > 0)
            <div class="space-y-6">
                @foreach($faqs as $faq)
                    <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow">
                        <h3 class="text-xl font-bold text-[#1A4231] mb-3 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-[#1A4231]/10 flex items-center justify-center text-[#1A4231] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            {{ $faq->localized_title ?? $faq->title }}
                        </h3>
                        <div class="text-gray-600 font-semibold leading-relaxed pl-11 {{ $isRtl ? 'pr-11 pl-0' : '' }}">
                            {!! $faq->localized_description ?? $faq->description !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <h3 class="text-2xl font-bold text-gray-400">{{ $isRtl ? 'لا توجد أسئلة شائعة حالياً' : 'No FAQs available at the moment' }}</h3>
            </div>
        @endif
    </div>
</section>

@endsection
