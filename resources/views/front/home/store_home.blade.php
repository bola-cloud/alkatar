@extends('front.layouts.new_design_layout')

@section('title', __('new_design.store_page.title'))

@section('content')

@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp

<!-- Main Wrapper with White Background -->
<div class="store-page bg-white text-[#1A4231] pb-24" dir="{{ $dir }}" style="font-family: 'Cairo', sans-serif;">

    <!-- Top Spacer -->
    <div class="h-6 bg-white"></div>

    <!-- Hero Banner Section (Full Width Swiper) -->
    <section class="w-full h-auto bg-[#EDEAE3] relative">
        <div class="swiper store-hero-swiper relative overflow-hidden w-full h-auto group">
            <div class="swiper-wrapper">
                @if(isset($sliders) && $sliders->count() > 0)
                    @foreach($sliders as $slider)
                        <div class="swiper-slide w-full h-auto">
                            @php
                                $imgName = !empty($slider->image) ? $slider->image : $slider->Image_One;
                                $imgUrl = file_exists(public_path($imgName)) ? asset($imgName) : asset(PromotionImage() . $imgName);
                            @endphp
                            <img src="{{ $imgUrl }}" class="w-full max-h-[calc(100vh-140px)] object-cover block" alt="{{ $slider->{app()->getLocale().'_title'} ?? 'Banner' }}">
                            
                            @if($slider->link)
                                <a href="{{ $slider->link }}" target="_blank" class="absolute inset-0 z-20"></a>
                            @endif
                            
                            @if($slider->{app()->getLocale().'_title'} || $slider->{app()->getLocale().'_subtitle'})
                            <div class="absolute inset-0 bg-black/20 flex flex-col justify-end p-8 lg:p-16 z-10">
                                <div class="max-w-4xl text-start">
                                    @if($slider->{app()->getLocale().'_subtitle'})
                                        <span class="inline-block bg-[#FBF0D8] text-[#1A4231] font-bold text-xs lg:text-sm px-4 py-1.5 rounded-full mb-4 shadow-md uppercase tracking-wider">
                                            {{ $slider->{app()->getLocale().'_subtitle'} }}
                                        </span>
                                    @endif
                                    @if($slider->{app()->getLocale().'_title'})
                                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-[#FBF0D8] leading-tight mb-4 drop-shadow-sm">
                                            {{ $slider->{app()->getLocale().'_title'} }}
                                        </h1>
                                    @endif
                                    @if($slider->{app()->getLocale().'_small_description'})
                                        <p class="text-white/90 text-sm lg:text-base font-medium mb-6 max-w-2xl leading-relaxed">
                                            {{ $slider->{app()->getLocale().'_small_description'} }}
                                        </p>
                                    @endif
                                    @if($slider->link)
                                        <a href="{{ $slider->link }}" class="inline-flex bg-[#1A4231] hover:bg-white text-white hover:text-[#1A4231] px-8 py-3 rounded-full font-bold transition-colors shadow-lg">
                                            {{ app()->getLocale() == 'en' ? 'Discover More' : 'اكتشف المزيد' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide w-full h-auto">
                        <img src="{{ asset('assets/elketar/ddd.png') }}" alt="Hero Banner" class="w-full max-h-[calc(100vh-140px)] object-cover block">
                    </div>
                @endif
            </div>
            <!-- Pagination & Nav -->
            <div class="swiper-pagination mb-2"></div>
            <div class="swiper-button-prev !text-white after:!text-xl hidden md:flex w-12 h-12 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100 {{ $isRtl ? '!right-6' : '!left-6' }}"></div>
            <div class="swiper-button-next !text-white after:!text-xl hidden md:flex w-12 h-12 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm transition-all opacity-0 group-hover:opacity-100 {{ $isRtl ? '!left-6' : '!right-6' }}"></div>
        </div>
    </section>

    <!-- White Separator Above -->
    <div class="h-16 bg-white"></div>

    <div class="container mx-auto px-4 lg:px-8 flex flex-col gap-16 max-w-[1400px]">

        <!-- Special Offers Section -->
        @if(isset($specialOffers) && $specialOffers->count() > 0)
        <section class="flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl lg:text-4xl font-black text-[#1A4231]">{{ $isRtl ? 'عروض خاصة' : 'Special Offers' }}</h2>
                <a href="{{ route('front.store.catalog') }}" class="text-[#1A4231] font-bold text-sm lg:text-base border-b-2 border-[#1A4231] hover:opacity-80 transition-opacity">{{ $isRtl ? 'عرض الكل' : 'View All' }}</a>
            </div>
            
            <div class="swiper products-swiper relative overflow-hidden w-full !pb-8 !px-2">
                <div class="swiper-wrapper">
                    @foreach($specialOffers as $product)
                        <div class="swiper-slide !w-auto">
                            @include('front.components.product_card', ['product' => $product, 'isRtl' => $isRtl])
                        </div>
                    @endforeach
                </div>
                <!-- Nav -->
                <div class="swiper-button-prev products-prev !text-[#1A4231] after:!text-xl w-10 h-10 rounded-full bg-white shadow-lg border border-gray-100 transition-all -left-2 top-1/2 -mt-5"></div>
                <div class="swiper-button-next products-next !text-[#1A4231] after:!text-xl w-10 h-10 rounded-full bg-white shadow-lg border border-gray-100 transition-all -right-2 top-1/2 -mt-5"></div>
            </div>
        </section>
        @endif

        <!-- Categories Section -->
        @if(isset($categories) && $categories->count() > 0)
        <section class="flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl lg:text-4xl font-black text-[#1A4231]">{{ $isRtl ? 'تصفح الفئات' : 'Browse Categories' }}</h2>
            </div>
            
            <div class="swiper categories-swiper relative overflow-hidden w-full !pb-8 !px-2">
                <div class="swiper-wrapper">
                    @foreach($categories as $cat)
                        <div class="swiper-slide !w-auto">
                            <a href="{{ route('front.store.catalog', ['category' => $cat->en_Category_Slug]) }}" class="group block w-40 sm:w-48 lg:w-56 aspect-square rounded-[32px] overflow-hidden relative shadow-md hover:shadow-xl transition-all duration-300">
                                @php
                                    $catImg = $cat->Category_Icon ? asset(CategoryImage().$cat->Category_Icon) : asset('assets/elketar/placeholder.png');
                                @endphp
                                <img src="{{ $catImg }}" alt="{{ $cat->localized_name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-4 left-0 right-0 px-4 text-center">
                                    <h3 class="text-white font-black text-sm sm:text-base lg:text-lg drop-shadow-md">{{ $cat->localized_name }}</h3>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <!-- Nav -->
                <div class="swiper-button-prev categories-prev !text-[#1A4231] after:!text-xl w-10 h-10 rounded-full bg-white shadow-lg border border-gray-100 transition-all -left-2 top-1/2 -mt-5"></div>
                <div class="swiper-button-next categories-next !text-[#1A4231] after:!text-xl w-10 h-10 rounded-full bg-white shadow-lg border border-gray-100 transition-all -right-2 top-1/2 -mt-5"></div>
            </div>
        </section>
        @endif

        <!-- Monthly Offers Section -->
        @if(isset($monthlyOffers) && $monthlyOffers->count() > 0)
        <section class="flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl lg:text-4xl font-black text-[#1A4231]">{{ $isRtl ? 'العروض الشهرية' : 'Monthly Offers' }}</h2>
                <a href="{{ route('monthly.offers') }}" class="text-[#1A4231] font-bold text-sm lg:text-base border-b-2 border-[#1A4231] hover:opacity-80 transition-opacity">{{ $isRtl ? 'عرض الكل' : 'View All' }}</a>
            </div>
            
            <div class="swiper monthly-swiper relative overflow-hidden w-full !pb-8 !px-2">
                <div class="swiper-wrapper">
                    @foreach($monthlyOffers as $product)
                        <div class="swiper-slide !w-auto">
                            @include('front.components.product_card', ['product' => $product, 'isRtl' => $isRtl])
                        </div>
                    @endforeach
                </div>
                <!-- Nav -->
                <div class="swiper-button-prev monthly-prev !text-[#1A4231] after:!text-xl w-10 h-10 rounded-full bg-white shadow-lg border border-gray-100 transition-all -left-2 top-1/2 -mt-5"></div>
                <div class="swiper-button-next monthly-next !text-[#1A4231] after:!text-xl w-10 h-10 rounded-full bg-white shadow-lg border border-gray-100 transition-all -right-2 top-1/2 -mt-5"></div>
            </div>
        </section>
        @endif

        <!-- Latest Products Section -->
        @if(isset($latestProducts) && $latestProducts->count() > 0)
        <section class="flex flex-col gap-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl lg:text-4xl font-black text-[#1A4231]">{{ $isRtl ? 'أحدث المنتجات' : 'Latest Products' }}</h2>
                <a href="{{ route('front.store.catalog') }}" class="text-[#1A4231] font-bold text-sm lg:text-base border-b-2 border-[#1A4231] hover:opacity-80 transition-opacity">{{ $isRtl ? 'تصفح كل المنتجات' : 'View All Products' }}</a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                @foreach($latestProducts as $product)
                    @include('front.components.product_card', ['product' => $product, 'isRtl' => $isRtl])
                @endforeach
            </div>
        </section>
        @endif

    </div>

</div>

<style>
    .store-page {
        font-family: 'Cairo', sans-serif;
    }
</style>

<!-- Swiper Initialization -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Swiper
    const storeHeroSwiper = new Swiper('.store-hero-swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        }
    });

    // Special Offers Swiper
    const productsSwiper = new Swiper('.products-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        navigation: {
            nextEl: '.products-next',
            prevEl: '.products-prev',
        },
        breakpoints: {
            640: {
                spaceBetween: 24,
            },
            1024: {
                spaceBetween: 32,
            }
        }
    });

    // Categories Swiper
    const categoriesSwiper = new Swiper('.categories-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        navigation: {
            nextEl: '.categories-next',
            prevEl: '.categories-prev',
        },
        breakpoints: {
            640: {
                spaceBetween: 24,
            },
            1024: {
                spaceBetween: 32,
            }
        }
    });

    // Monthly Offers Swiper
    const monthlySwiper = new Swiper('.monthly-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 16,
        navigation: {
            nextEl: '.monthly-next',
            prevEl: '.monthly-prev',
        },
        breakpoints: {
            640: {
                spaceBetween: 24,
            },
            1024: {
                spaceBetween: 32,
            }
        }
    });
});
</script>
@endpush

@endsection
