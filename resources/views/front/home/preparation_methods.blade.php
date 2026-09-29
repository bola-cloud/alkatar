@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<!-- Hero Section -->
<section class="relative w-full min-h-[40vh] lg:min-h-[50vh] flex items-end overflow-hidden" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/gradient_image.png') }}'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-gradient-to-t from-[#1A4231]/95 via-[#1A4231]/30 to-transparent z-0"></div>

    <div class="container mx-auto px-4 lg:px-8 pb-16 relative z-10">
        <div class="max-w-4xl text-start">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                {{ $isRtl ? 'طرق التحضير' : 'Preparation Methods' }}
            </h1>
            <p class="text-white/95 text-base md:text-xl leading-relaxed font-semibold max-w-3xl">
                {{ $isRtl ? 'تعرف على أفضل طرق تحضير القهوة المختصة لتستمتع بكوب مثالي كل يوم.' : 'Discover the best preparation methods for specialty coffee to enjoy a perfect cup every day.' }}
            </p>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="py-16 lg:py-24 bg-white" dir="{{ $dir }}">
    <div class="container mx-auto px-4 lg:px-8 max-w-5xl text-start">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- V60 Method -->
            <div class="bg-[#FDF9F0] rounded-[32px] p-8 border border-gray-100 shadow-sm hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-[#1A4231] rounded-2xl flex items-center justify-center text-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-black text-[#1A4231]">{{ $isRtl ? 'الترشيح (V60)' : 'Pour Over (V60)' }}</h2>
                </div>
                <p class="text-gray-600 font-semibold leading-loose mb-6">
                    {{ $isRtl ? 'طريقة الترشيح V60 تعتبر من أفضل الطرق لإبراز النكهات والإيحاءات الفاكهية والزهرية في القهوة المختصة.' : 'The V60 pour-over method is one of the best ways to highlight the fruity and floral notes in specialty coffee.' }}
                </p>
                <ul class="space-y-3 text-sm text-gray-700 font-bold">
                    <li class="flex gap-2 items-center"><span class="w-2 h-2 bg-[#1A4231] rounded-full"></span> {{ $isRtl ? 'النسبة: 1 جرام قهوة لكل 15 مل ماء' : 'Ratio: 1g coffee to 15ml water' }}</li>
                    <li class="flex gap-2 items-center"><span class="w-2 h-2 bg-[#1A4231] rounded-full"></span> {{ $isRtl ? 'درجة الحرارة: 90 - 93 مئوية' : 'Temp: 90 - 93 °C' }}</li>
                    <li class="flex gap-2 items-center"><span class="w-2 h-2 bg-[#1A4231] rounded-full"></span> {{ $isRtl ? 'الطحنة: متوسطة الخشونة' : 'Grind: Medium-coarse' }}</li>
                </ul>
            </div>

            <!-- Espresso Method -->
            <div class="bg-[#FDF9F0] rounded-[32px] p-8 border border-gray-100 shadow-sm hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-[#1A4231] rounded-2xl flex items-center justify-center text-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-black text-[#1A4231]">{{ $isRtl ? 'الإسبريسو' : 'Espresso' }}</h2>
                </div>
                <p class="text-gray-600 font-semibold leading-loose mb-6">
                    {{ $isRtl ? 'كوب الإسبريسو يتميز بقوام غني ونكهة مركزة، وهو الأساس لمعظم مشروبات القهوة المحضرة بالحليب مثل اللاتيه والكابوتشينو.' : 'Espresso has a rich body and concentrated flavor, forming the base for most milk-based drinks like Latte and Cappuccino.' }}
                </p>
                <ul class="space-y-3 text-sm text-gray-700 font-bold">
                    <li class="flex gap-2 items-center"><span class="w-2 h-2 bg-[#1A4231] rounded-full"></span> {{ $isRtl ? 'الجرعة: 18 - 20 جرام' : 'Dose: 18 - 20 grams' }}</li>
                    <li class="flex gap-2 items-center"><span class="w-2 h-2 bg-[#1A4231] rounded-full"></span> {{ $isRtl ? 'الاستخلاص: 36 - 40 مل' : 'Yield: 36 - 40 ml' }}</li>
                    <li class="flex gap-2 items-center"><span class="w-2 h-2 bg-[#1A4231] rounded-full"></span> {{ $isRtl ? 'الوقت: 25 - 30 ثانية' : 'Time: 25 - 30 seconds' }}</li>
                </ul>
            </div>

        </div>
    </div>
</section>

@endsection
