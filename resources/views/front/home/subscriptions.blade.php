@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<style>
    /* Subscription pricing page premium styles */
    .subscriptions-page {
        font-family: 'Cairo', sans-serif;
    }
    .pricing-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 32px;
    }
    @media (min-width: 768px) {
        .pricing-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (min-width: 1024px) {
        .pricing-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>

<div class="subscriptions-page bg-white text-[#1A4231] overflow-hidden" dir="{{ $dir }}">

    <!-- Hero Header -->
    <section class="py-12 lg:py-16 px-4 sm:px-6 lg:px-8">
        <div class="container mx-auto">
            <div class="relative overflow-hidden rounded-[40px] shadow-2xl min-h-[300px] lg:min-h-[360px] flex items-center p-8 lg:p-16 text-white" 
                 style="background-image: url('{{ asset('assets/elketar/Hero Section.png') }}'); background-size: cover; background-position: center;">
                
                <div class="absolute inset-0 bg-gradient-to-t from-[#1A4231]/95 via-[#1A4231]/40 to-[#1A4231]/10 z-0"></div>

                <div class="relative z-10 max-w-4xl text-start">
                    <span class="inline-block bg-[#FBF0D8] text-[#1A4231] font-bold text-xs lg:text-sm px-4 py-1.5 rounded-full mb-4 shadow-md uppercase tracking-wider">
                        {{ __('Subscriptions') }}
                    </span>
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#FBF0D8] leading-tight mb-4">
                        {{ $isRtl ? 'باقات الاشتراكات الحصرية' : 'Exclusive Subscription Tiers' }}
                    </h1>
                    <p class="text-white/90 text-sm lg:text-lg font-semibold max-w-2xl leading-relaxed">
                        {{ $isRtl 
                            ? 'اشترك الآن في إحدى باقاتنا المتميزة واستمتع بخصومات حصرية، توصيل مجاني، والعديد من المزايا الرائعة التي تضمن لك الحصول على محاصيلك المفضلة باستمرار.' 
                            : 'Subscribe now to one of our premium tiers and enjoy exclusive discounts, free delivery, and great benefits that guarantee your coffee crop supply constantly.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
            <div class="bg-[#FAF9F5] border border-gray-150 rounded-[32px] p-8 lg:p-10 shadow-sm text-center">
                <h2 class="text-2xl lg:text-3xl font-black text-[#1A4231] mb-3">
                    {{ $isRtl ? 'كيف يعمل نظام اشتراكات بن القطار؟' : 'How Al-Katar\'s Subscriptions Work?' }}
                </h2>
                <p class="text-slate-500 text-sm font-semibold max-w-2xl mx-auto mb-12 leading-relaxed">
                    {{ $isRtl 
                        ? 'اشترك شهرياً لتفعيل حسابك المميز والاستفادة من خصومات حصرية على جميع المحاصيل والأدوات مع توصيل مجاني سريع لجميع طلباتك.' 
                        : 'Subscribe monthly to activate your premium membership and enjoy exclusive discounts on all crops and tools, along with fast free delivery on all your orders.' }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center gap-4 text-center p-4">
                        <div class="w-16 h-16 rounded-full bg-[#1A4231]/5 flex items-center justify-center text-[#1A4231] text-2xl font-black">
                            1
                        </div>
                        <h3 class="text-lg font-black text-[#1A4231]">
                            {{ $isRtl ? 'اختر باقتك المفضلة' : 'Choose Your Tier' }}
                        </h3>
                        <p class="text-xs lg:text-sm text-slate-500 font-semibold leading-relaxed">
                            {{ $isRtl 
                                ? 'حدد الباقة التي تناسب استهلاكك وميزانيتك الشهرية لبدء الاستفادة.' 
                                : 'Select the tier that best matches your consumption and monthly budget to get started.' }}
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center gap-4 text-center p-4 border-y md:border-y-0 md:border-x border-gray-200">
                        <div class="w-16 h-16 rounded-full bg-[#1A4231]/5 flex items-center justify-center text-[#1A4231] text-2xl font-black">
                            2
                        </div>
                        <h3 class="text-lg font-black text-[#1A4231] flex items-center gap-1.5 justify-center">
                            <span>{{ $isRtl ? 'تفعيل الأسعار الخاصة' : 'Activate Special Pricing' }}</span>
                            <span class="text-xs bg-[#387C5F] text-white px-2 py-0.5 rounded-full font-bold">✨</span>
                        </h3>
                        <p class="text-xs lg:text-sm text-slate-500 font-semibold leading-relaxed">
                            {{ $isRtl 
                                ? 'بعد الاشتراك، تتحول أسعار الموقع تلقائياً إلى أسعار الأعضاء المخفضة لتتسوق بأقل تكلفة.' 
                                : 'After subscribing, the store prices instantly change to discounted member prices, saving you more on every purchase.' }}
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center gap-4 text-center p-4">
                        <div class="w-16 h-16 rounded-full bg-[#1A4231]/5 flex items-center justify-center text-[#1A4231] text-2xl font-black">
                            3
                        </div>
                        <h3 class="text-lg font-black text-[#1A4231]">
                            {{ $isRtl ? 'استمتع بالخصومات والشحن' : 'Unlock Perks & Discounts' }}
                        </h3>
                        <p class="text-xs lg:text-sm text-slate-500 font-semibold leading-relaxed">
                            {{ $isRtl 
                                ? 'احصل على خصومات حصرية تلقائية على جميع المنتجات وشحن مجاني طوال فترة الاشتراك.' 
                                : 'Enjoy automatic exclusive discounts on all products and free shipping during your active tier.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Tiers Grid -->
    <section class="py-12 lg:py-20 bg-[#EDEAE3]/30" style="background-image: url('{{ asset('assets/elketar/Impact Numbers Section.png') }}'); background-size: cover; background-position: center; background-blend-mode: overlay;">
        <div class="container mx-auto px-4 lg:px-8">
            
            @if(session('success'))
                <div class="max-w-4xl mx-auto mb-8 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm text-start">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-green-500 font-bold text-lg">✓</div>
                        <div class="mx-3">
                            <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-4xl mx-auto mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm text-start">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-red-500 font-bold text-lg">⚠</div>
                        <div class="mx-3">
                            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="pricing-grid max-w-6xl mx-auto">
                @forelse($subscriptions as $sub)
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-2xl hover:scale-[1.03] transition-all duration-300 border border-gray-100 flex flex-col justify-between group">
                        
                        <!-- Header / Name -->
                        <div class="p-8 pb-4 text-center border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-2xl font-black text-[#1A4231] mb-2 group-hover:text-[#387C5F] transition-colors">{{ $sub->name }}</h3>
                             <div class="flex justify-center items-baseline my-4">
                                <span class="text-4xl font-extrabold text-[#1A4231]">{{ number_format($sub->price, 3) }}</span>
                                <img src="{{ asset('assets/elketar/light..png') }}" alt="ر.ع." class="currency-logo mx-1">
                                <span class="text-gray-400 font-semibold text-sm">/ {{ __($sub->period_type) }}</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-[#387C5F]/10 text-[#387C5F] mt-2 shadow-sm">
                                <span>✨</span>
                                <span>{{ $isRtl ? 'خصم حصري ' . $sub->discount_percent . '%' : 'Exclusive ' . $sub->discount_percent . '% Discount' }}</span>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="p-8 flex-grow">
                            <ul class="space-y-4 text-start font-semibold text-sm lg:text-base text-gray-700">
                                <li class="flex items-start gap-3 text-[#387C5F] font-bold">
                                    <span class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 mt-0.5">✓</span>
                                    <span>
                                        @if($isRtl)
                                            عضوية مميزة فعالة لمدة {{ $sub->period_value }} {{ $sub->period_type == 'month' ? 'شهر' : 'سنة' }}
                                        @else
                                            Premium membership active for {{ $sub->period_value }} {{ $sub->period_type }}
                                        @endif
                                    </span>
                                </li>
                                
                                @if($sub->discount_percent > 0)
                                    <li class="flex items-start gap-3">
                                        <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0 mt-0.5">✓</span>
                                        <span>
                                            {{ $isRtl ? 'خصم حصري ' . $sub->discount_percent . '% على جميع المحاصيل' : 'Exclusive ' . $sub->discount_percent . '% discount on all crops' }}
                                        </span>
                                    </li>
                                @endif

                                @if($sub->max_discount_amount > 0)
                                    <li class="flex items-start gap-3">
                                        <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0 mt-0.5">✓</span>
                                        <span>
                                            {{ $isRtl ? 'خصم أقصى يصل إلى ' . number_format($sub->max_discount_amount, 3) . ' ر.ع.' : 'Maximum discount up to ' . number_format($sub->max_discount_amount, 3) . ' OMR' }}
                                        </span>
                                    </li>
                                @endif

                                @if($sub->free_shipping)
                                    <li class="flex items-start gap-3">
                                        <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0 mt-0.5">✓</span>
                                        <span>
                                            {{ $isRtl ? 'شحن مجاني بالكامل لجميع المحافظات' : 'Fully free shipping to all governorates' }}
                                        </span>
                                    </li>
                                @endif

                                @if($sub->tax_exempt)
                                    <li class="flex items-start gap-3">
                                        <span class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0 mt-0.5">✓</span>
                                        <span>
                                            {{ $isRtl ? 'إعفاء ضريبي كامل على مشترياتك' : 'Full tax exemption on your purchases' }}
                                        </span>
                                    </li>
                                @endif

                                @if($sub->description)
                                    <li class="flex items-start gap-3 border-t border-gray-100 pt-4 mt-4">
                                        <span class="w-6 h-6 rounded-full bg-[#1A4231]/10 flex items-center justify-center text-[#1A4231] shrink-0 mt-0.5">★</span>
                                        <span class="text-xs text-gray-500 font-medium leading-relaxed">
                                            {{ $sub->description }}
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Action Button -->
                        <div class="p-8 pt-0 text-center">
                            @auth
                                <form action="{{ route('user.subscription.pay') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="subscription_id" value="{{ $sub->id }}">
                                    <button type="submit" class="w-full bg-[#1A4231] text-white hover:opacity-90 active:scale-[0.98] py-4 rounded-full font-bold shadow-lg transition-all">
                                        {{ $isRtl ? 'اشترك الآن' : 'Subscribe Now' }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login', ['redirect' => route('subscriptions')]) }}" class="block w-full bg-gray-100 text-[#1A4231] hover:bg-gray-200 active:scale-[0.98] py-4 rounded-full font-bold transition-all text-center">
                                    {{ $isRtl ? 'سجل دخول للاشتراك' : 'Login to Subscribe' }}
                                </a>
                            @endauth
                        </div>

                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 font-bold text-lg">
                            {{ $isRtl ? 'لا توجد باقات اشتراك نشطة حالياً.' : 'No active subscription packages found.' }}
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    <!-- White spacer before footer -->
    <div class="w-full h-16 lg:h-24 bg-white"></div>

</div>

@endsection
