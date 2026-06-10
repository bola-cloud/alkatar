@extends('front.layouts.new_design_layout')

@section('title', __('Order Confirmed'))

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<!-- Main Wrapper with White Background -->
<div class="thankyou-page bg-white text-[#1A4231] py-24" dir="{{ $dir }}" style="font-family: 'Cairo', sans-serif;">
    <div class="container mx-auto px-4 text-center max-w-xl flex flex-col items-center gap-6">
        
        <!-- Large animated checkmark -->
        <div class="w-20 h-20 rounded-full bg-green-50 border border-green-200 flex items-center justify-center text-green-500 text-4xl animate-bounce shadow-md">
            ✓
        </div>

        <!-- Heading -->
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-[#1A4231] tracking-wide mt-2">
            تم تأكيد طلبك بنجاح!
        </h1>

        <!-- Subheading -->
        <p class="text-xs sm:text-sm font-semibold text-gray-400 leading-relaxed max-w-md">
            شكراً لك لتسوقك من بن القطار. تم تسجيل طلبك بنجاح وجارٍ العمل على تجهيزه ليصلك في أسرع وقت. تم إرسال تفاصيل الطلب والتأكيد إلى بريدك الإلكتروني وهاتفك المحمول.
        </p>

        <!-- Order details card -->
        @if(Session::has('order_number'))
            <div class="w-full bg-[#FAF9F5] border border-gray-150 rounded-[20px] p-5 mt-4 text-start flex flex-col gap-3">
                <div class="flex justify-between items-center text-xs sm:text-sm border-b border-gray-100 pb-3">
                    <span class="font-bold text-gray-400">رقم الطلب:</span>
                    <span class="font-black text-[#1A4231]">{{ Session::get('order_number') }}</span>
                </div>
                <div class="flex justify-between items-center text-xs sm:text-sm">
                    <span class="font-bold text-gray-400">طريقة الدفع:</span>
                    <span class="font-black text-[#1A4231]">
                        @if(Session::get('payment_method_name') == 'COD')
                            الدفع عند الاستلام
                        @else
                            بطاقة ائتمانية / دفع إلكتروني
                        @endif
                    </span>
                </div>
            </div>
        @endif

        <!-- Action buttons -->
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-center w-full mt-6">
            <a href="{{ route('front') }}" 
               class="w-full sm:w-auto bg-[#1A4231] hover:bg-[#2C624A] text-white py-3.5 px-10 rounded-[16px] text-xs sm:text-sm font-black transition-all shadow-md text-center">
                العودة للرئيسية
            </a>
            
            <a href="{{ route('categories.show') }}" 
               class="w-full sm:w-auto border-2 border-[#1A4231] text-[#1A4231] hover:bg-[#FAF9F5] py-3.5 px-10 rounded-[16px] text-xs sm:text-sm font-black transition-all text-center">
                مواصلة التسوق
            </a>
        </div>

    </div>
</div>
@endsection
