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
            {{ $title ?? ($isRtl ? 'سياسة الاسترجاع' : 'Return Policy') }}
        </h1>
        <p class="text-white/90 text-base md:text-lg lg:text-xl font-semibold max-w-2xl mx-auto">
            {{ $isRtl ? 'تعرف على سياستنا لضمان أفضل تجربة لك' : 'Learn about our policy to ensure the best experience for you' }}
        </p>
    </div>
</section>

<!-- Content Section -->
<section class="py-16 lg:py-24 bg-white" dir="{{ $dir }}">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl text-start">
        <div class="prose prose-lg prose-slate max-w-none text-gray-700 font-semibold leading-loose">
            @php
                $contentRecord = CutomerServiceContent('return_policy');
                $locale = app()->getLocale();
                $descField = ($locale == 'fr' || $locale == 'ar') ? 'fr_description' : 'en_description';
            @endphp
            @if($contentRecord && $contentRecord->$descField)
                {!! $contentRecord->$descField !!}
            @else
                <div class="bg-[#FDF9F0] p-6 rounded-2xl border border-gray-100">
                    <h3 class="text-xl font-bold text-[#1A4231] mb-4">{{ $isRtl ? 'سياسة الاسترجاع الخاصة ببن القطار' : 'Al-Katar Return Policy' }}</h3>
                    <ul class="list-disc list-inside space-y-4 text-gray-600">
                        <li>{{ $isRtl ? 'يجب أن يكون المنتج بحالته الأصلية غير مفتوح وفي غلافه الأصلي' : 'The product must be in its original unopened condition and packaging' }}</li>
                        <li>{{ $isRtl ? 'يحق للعميل إرجاع المنتج خلال 7 أيام من تاريخ الاستلام' : 'The customer has the right to return the product within 7 days of receipt' }}</li>
                        <li>{{ $isRtl ? 'في حالة وجود عيب مصنعي يتم استبدال المنتج فوراً' : 'In case of a manufacturing defect, the product will be replaced immediately' }}</li>
                        <li>{{ $isRtl ? 'يتحمل العميل تكاليف الشحن في حالة الإرجاع بدون سبب جوهري' : 'The customer bears the shipping costs in case of return without a substantial reason' }}</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection
