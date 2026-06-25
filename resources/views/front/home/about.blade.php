@extends('front.layouts.new_design_layout')

@section('content')

@php
    $isRtl = app()->getLocale() == 'fr' || app()->getLocale() == 'ar';
    $dir = $isRtl ? 'rtl' : 'ltr';
    $locale = app()->getLocale();

    $titleField = $locale == 'fr' || $locale == 'ar' ? 'fr_Title' : 'en_Title';
    $subtitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_Subtitle' : 'en_Subtitle';
    
    $visionLabelField = $locale == 'fr' || $locale == 'ar' ? 'fr_vision_label' : 'en_vision_label';
    $visionTitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_Title_One' : 'en_Title_One';
    $visionDescField = $locale == 'fr' || $locale == 'ar' ? 'fr_Description_One' : 'en_Description_One';
    
    $missionLabelField = $locale == 'fr' || $locale == 'ar' ? 'fr_mission_label' : 'en_mission_label';
    $missionTitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_Title_Two' : 'en_Title_Two';
    $missionDescField = $locale == 'fr' || $locale == 'ar' ? 'fr_Description_Two' : 'en_Description_Two';
    
    $experienceTextField = $locale == 'fr' || $locale == 'ar' ? 'fr_experience_text' : 'en_experience_text';
    
    $valuesTitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_values_title' : 'en_values_title';
    $valuesSubtitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_values_subtitle' : 'en_values_subtitle';
    
    $v1TitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_value_one_title' : 'en_value_one_title';
    $v1DescField = $locale == 'fr' || $locale == 'ar' ? 'fr_value_one_description' : 'en_value_one_description';
    
    $v2TitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_value_two_title' : 'en_value_two_title';
    $v2DescField = $locale == 'fr' || $locale == 'ar' ? 'fr_value_two_description' : 'en_value_two_description';
    
    $v3TitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_value_three_title' : 'en_value_three_title';
    $v3DescField = $locale == 'fr' || $locale == 'ar' ? 'fr_value_three_description' : 'en_value_three_description';
    
    $v4TitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_value_four_title' : 'en_value_four_title';
    $v4DescField = $locale == 'fr' || $locale == 'ar' ? 'fr_value_four_description' : 'en_value_four_description';
    
    $whyTitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_why_title' : 'en_why_title';
    $whySubtitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_why_subtitle' : 'en_why_subtitle';
    
    $whyItem1Field = $locale == 'fr' || $locale == 'ar' ? 'fr_why_item_one' : 'en_why_item_one';
    $whyItem2Field = $locale == 'fr' || $locale == 'ar' ? 'fr_why_item_two' : 'en_why_item_two';
    $whyItem3Field = $locale == 'fr' || $locale == 'ar' ? 'fr_why_item_three' : 'en_why_item_three';
    
    $ctaTitleField = $locale == 'fr' || $locale == 'ar' ? 'fr_cta_title' : 'en_cta_title';
    $ctaCropsField = $locale == 'fr' || $locale == 'ar' ? 'fr_cta_btn_crops' : 'en_cta_btn_crops';
    $ctaExpertField = $locale == 'fr' || $locale == 'ar' ? 'fr_cta_btn_expert' : 'en_cta_btn_expert';
@endphp

<style>
    .katar-premium-grid {
        display: flex;
        flex-direction: column;
        gap: 32px;
        align-items: center;
        width: 100%;
    }
    .katar-grid-left {
        width: 100%;
    }
    .katar-grid-right {
        width: 100%;
    }
    @media (min-width: 1024px) {
        .katar-premium-grid {
            flex-direction: {{ $isRtl ? 'row-reverse' : 'row' }};
            gap: 48px;
        }
        .katar-grid-left {
            width: 42%;
            flex-shrink: 0;
        }
        .katar-grid-right {
            width: 58%;
            flex-grow: 1;
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

<!-- Hero Section -->
<section class="relative w-full min-h-[calc(100vh-120px)] lg:min-h-[calc(100vh-140px)] flex items-end overflow-hidden" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/gradient_image.png') }}'); background-size: cover; background-position: center;">
    <!-- Dark/Green Gradient Overlay at the bottom -->
    <div class="absolute inset-0 bg-gradient-to-t from-[#1A4231]/95 via-[#1A4231]/30 to-transparent z-0"></div>

    <div class="container mx-auto px-4 lg:px-8 pb-16 lg:pb-24 relative z-10">
        <div class="max-w-4xl text-start">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-tight mb-6">
                {{ ($about && $about->$titleField) ? $about->$titleField : __('new_design.about.hero_title') }}
            </h1>
            <p class="text-white/95 text-base md:text-xl lg:text-2xl leading-relaxed font-semibold max-w-3xl">
                {{ ($about && $about->$subtitleField) ? $about->$subtitleField : __('new_design.about.hero_subtitle') }}
            </p>
        </div>
    </div>
</section>

<!-- Vision & Mission Section -->
<section class="py-16 lg:py-24 bg-white" dir="{{ $dir }}">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Right/Left Side: Vision and Mission Texts (First in DOM -> Renders Right in RTL, Left in LTR) -->
            <div class="lg:col-span-6 flex flex-col gap-10 lg:gap-12 text-start">
                
                <!-- Vision Item -->
                <div class="flex flex-col gap-3">
                    <span class="text-xs lg:text-sm font-black text-[#387C5F] uppercase tracking-wider">
                        {{ ($about && $about->$visionLabelField) ? $about->$visionLabelField : __('new_design.about.vision_label') }}
                    </span>
                    <h2 class="text-2xl lg:text-3xl font-extrabold text-[#1A4231] leading-tight">
                        {{ ($about && $about->$visionTitleField) ? $about->$visionTitleField : __('new_design.about.vision_title') }}
                    </h2>
                    <p class="text-gray-600 text-sm lg:text-base leading-relaxed font-semibold">
                        {{ ($about && $about->$visionDescField) ? $about->$visionDescField : __('new_design.about.vision_desc') }}
                    </p>
                </div>

                <!-- Subtle Separator -->
                <div class="w-20 h-[2px] bg-gray-100 rounded-full"></div>

                <!-- Mission Item -->
                <div class="flex flex-col gap-3">
                    <span class="text-xs lg:text-sm font-black text-[#387C5F] uppercase tracking-wider">
                        {{ ($about && $about->$missionLabelField) ? $about->$missionLabelField : __('new_design.about.mission_label') }}
                    </span>
                    <h2 class="text-2xl lg:text-3xl font-extrabold text-[#1A4231] leading-tight">
                        {{ ($about && $about->$missionTitleField) ? $about->$missionTitleField : __('new_design.about.mission_title') }}
                    </h2>
                    <p class="text-gray-600 text-sm lg:text-base leading-relaxed font-semibold">
                        {{ ($about && $about->$missionDescField) ? $about->$missionDescField : __('new_design.about.mission_desc') }}
                    </p>
                </div>

            </div>

            <!-- Left/Right Side: Image with Experience Badge (Second in DOM -> Renders Left in RTL, Right in LTR) -->
            <div class="lg:col-span-6 relative flex justify-center lg:justify-start">
                <div class="relative w-full max-w-lg">
                    <!-- Main Image (Roaster Machine) -->
                    <div class="rounded-[32px] overflow-hidden shadow-2xl">
                        <img src="{{ ($about && $about->Image) ? asset(aboutUsPage() . $about->Image) : asset('assets/elketar/about_roaster.png') }}" alt="Coffee Roaster" class="w-full h-auto block object-cover aspect-[4/5] max-h-[550px]">
                    </div>
                    
                    <!-- Experience Badge (overlaps bottom-left of the image) -->
                    <div class="absolute -bottom-6 {{ $isRtl ? '-left-4' : '-right-4' }} bg-[#1A4231] text-[#FBF0D8] p-6 rounded-[24px] shadow-2xl flex flex-col items-center justify-center min-w-[140px] border border-white/10">
                        <span class="text-3xl lg:text-4xl font-black tracking-tight text-white mb-1">
                            {{ ($about && $about->experience_years) ? $about->experience_years : '10+' }}
                        </span>
                        <span class="text-[12px] lg:text-xs font-bold text-white/90 whitespace-nowrap">
                            {{ ($about && $about->$experienceTextField) ? $about->$experienceTextField : __('new_design.about.experience_badge') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Core Values Section (قيمنا الجوهرية) -->
<section class="py-20 lg:py-24 relative overflow-hidden" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/Section - Categories Showcase.png') }}'); background-size: cover; background-position: center;">
    <!-- Green overlay for matching mockup tones -->
    <div class="absolute inset-0 bg-[#FBF0D8]/45 z-0"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-16 lg:mb-20">
            <h2 class="text-3xl lg:text-5xl font-black text-[#1A4231] mb-4">
                {{ ($about && $about->$valuesTitleField) ? $about->$valuesTitleField : __('new_design.about.values_title') }}
            </h2>
            <p class="text-gray-700 text-sm lg:text-base font-semibold">
                {{ ($about && $about->$valuesSubtitleField) ? $about->$valuesSubtitleField : __('new_design.about.values_subtitle') }}
            </p>
            <div class="w-16 h-1 bg-[#1A4231] mx-auto mt-6 rounded-full"></div>
        </div>

        <!-- 4 Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            
            <!-- Card 1: المجتمع -->
            <div class="bg-[#FDF9F0] p-8 rounded-[32px] text-start shadow-xl hover:-translate-y-2 transition-transform duration-300 flex flex-col items-start border border-gray-100/50">
                <div class="w-14 h-14 bg-[#1A4231] rounded-2xl flex items-center justify-center text-white mb-6">
                    <!-- Users Icon -->
                    <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#1A4231] mb-3">
                    {{ ($about && $about->$v1TitleField) ? $about->$v1TitleField : __('new_design.about.value_community_title') }}
                </h3>
                <p class="text-sm lg:text-base text-gray-600 leading-relaxed font-semibold">
                    {{ ($about && $about->$v1DescField) ? $about->$v1DescField : __('new_design.about.value_community_desc') }}
                </p>
            </div>

            <!-- Card 2: التعليم -->
            <div class="bg-[#FDF9F0] p-8 rounded-[32px] text-start shadow-xl hover:-translate-y-2 transition-transform duration-300 flex flex-col items-start border border-gray-100/50">
                <div class="w-14 h-14 bg-[#1A4231] rounded-2xl flex items-center justify-center text-white mb-6">
                    <!-- Graduation Cap Icon -->
                    <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                        <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 13c-2.76 0-5-2.24-5-5 0-.37.04-.74.12-1.1L12 12.5l4.88-2.6c.08.36.12.73.12 1.1 0 2.76-2.24 5-5 5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#1A4231] mb-3">
                    {{ ($about && $about->$v2TitleField) ? $about->$v2TitleField : __('new_design.about.value_education_title') }}
                </h3>
                <p class="text-sm lg:text-base text-gray-600 leading-relaxed font-semibold">
                    {{ ($about && $about->$v2DescField) ? $about->$v2DescField : __('new_design.about.value_education_desc') }}
                </p>
            </div>

            <!-- Card 3: الاستدامة -->
            <div class="bg-[#FDF9F0] p-8 rounded-[32px] text-start shadow-xl hover:-translate-y-2 transition-transform duration-300 flex flex-col items-start border border-gray-100/50">
                <div class="w-14 h-14 bg-[#1A4231] rounded-2xl flex items-center justify-center text-white mb-6">
                    <!-- Leaf Icon -->
                    <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                        <path d="M17 8C8 10 5.9 16.17 6.12 20c.07.26.28.36.42.36h1.23c5.38 0 9.83-3.18 10.9-8.4.12-.58.18-1.17.18-1.77C18.85 9.07 18.08 8.42 17 8zm-8 4c0-2.21-1.79-4-4-4s-4 1.79-4 4 1.79 4 4 4 4-1.79 4-4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#1A4231] mb-3">
                    {{ ($about && $about->$v3TitleField) ? $about->$v3TitleField : __('new_design.about.value_sustainability_title') }}
                </h3>
                <p class="text-sm lg:text-base text-gray-600 leading-relaxed font-semibold">
                    {{ ($about && $about->$v3DescField) ? $about->$v3DescField : __('new_design.about.value_sustainability_desc') }}
                </p>
            </div>

            <!-- Card 4: الجودة -->
            <div class="bg-[#FDF9F0] p-8 rounded-[32px] text-start shadow-xl hover:-translate-y-2 transition-transform duration-300 flex flex-col items-start border border-gray-100/50">
                <div class="w-14 h-14 bg-[#1A4231] rounded-2xl flex items-center justify-center text-white mb-6">
                    <!-- Quality Shield Icon -->
                    <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-[#1A4231] mb-3">
                    {{ ($about && $about->$v4TitleField) ? $about->$v4TitleField : __('new_design.about.value_quality_title') }}
                </h3>
                <p class="text-sm lg:text-base text-gray-600 leading-relaxed font-semibold">
                    {{ ($about && $about->$v4DescField) ? $about->$v4DescField : __('new_design.about.value_quality_desc') }}
                </p>
            </div>

        </div>

    </div>
</section>

<!-- Why Al-Katar Section (ما الذي يميز القطار؟) -->
<section class="py-20 lg:py-24 bg-white premium-section-gap" dir="{{ $dir }}">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Dark Green Wavy Card -->
        <div class="relative overflow-hidden rounded-[40px] shadow-2xl py-12 lg:py-16 px-8 lg:px-16 text-white bg-[#1A4231]" style="background-image: url('{{ asset('assets/elketar/Impact Numbers Section.png') }}'); background-size: cover; background-position: center;">
            
            <div class="absolute inset-0 bg-[#1A4231]/30 z-0"></div>

            <div class="katar-premium-grid relative z-10">
                
                <!-- Left/Right Side: Two Beautiful Side-by-Side Images -->
                <div class="katar-grid-left flex gap-4 lg:gap-6 justify-center items-center">
                    <!-- Image 1: Pouring Coffee Beans -->
                    <div class="w-1/2 aspect-square rounded-[24px] overflow-hidden shadow-2xl hover:scale-[1.03] transition-transform duration-500 border border-white/10">
                        <img src="{{ ($about && $about->why_image_one) ? asset(aboutUsPage() . $about->why_image_one) : asset('assets/elketar/coffee.png') }}" class="w-full h-full object-cover" alt="Pouring Coffee Beans">
                    </div>
                    <!-- Image 2: Coffee Latte Art -->
                    <div class="w-1/2 aspect-square rounded-[24px] overflow-hidden shadow-2xl hover:scale-[1.03] transition-transform duration-500 border border-white/10">
                        <img src="{{ ($about && $about->why_image_two) ? asset(aboutUsPage() . $about->why_image_two) : asset('assets/elketar/latee.png') }}" class="w-full h-full object-cover" alt="Specialty Coffee Latte Art">
                    </div>
                </div>

                <!-- Right/Left Side: Text and Bulleted Checklist -->
                <div class="katar-grid-right flex flex-col gap-6 text-start">
                    
                    <h2 class="text-3xl lg:text-5xl font-black text-[#FBF0D8] leading-tight">
                        {{ ($about && $about->$whyTitleField) ? $about->$whyTitleField : __('new_design.about.why_title') }}
                    </h2>
                    
                    <p class="text-white/90 text-base lg:text-lg leading-relaxed font-semibold">
                        {{ ($about && $about->$whySubtitleField) ? $about->$whySubtitleField : __('new_design.about.why_subtitle') }}
                    </p>

                    <!-- Checklist -->
                    <ul class="space-y-4 font-semibold text-white/95">
                        <li class="flex items-center gap-4 text-sm lg:text-base">
                            <span class="w-7 h-7 shrink-0 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-[#FBF0D8]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>
                                {{ ($about && $about->$whyItem1Field) ? $about->$whyItem1Field : __('new_design.about.why_item1') }}
                            </span>
                        </li>
                        <li class="flex items-center gap-4 text-sm lg:text-base">
                            <span class="w-7 h-7 shrink-0 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-[#FBF0D8]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>
                                {{ ($about && $about->$whyItem2Field) ? $about->$whyItem2Field : __('new_design.about.why_item2') }}
                            </span>
                        </li>
                        <li class="flex items-center gap-4 text-sm lg:text-base">
                            <span class="w-7 h-7 shrink-0 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-[#FBF0D8]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span>
                                {{ ($about && $about->$whyItem3Field) ? $about->$whyItem3Field : __('new_design.about.why_item3') }}
                            </span>
                        </li>
                    </ul>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- CTA Section (هل أنت مستعد لبدء رحلتك معنا؟) -->
<section class="py-24 relative overflow-hidden premium-section-gap" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/fff.png') }}'); background-size: cover; background-position: center;">
    
    <div class="container mx-auto px-4 relative z-10 text-center flex flex-col items-center">
        
        <h2 class="text-3xl lg:text-5xl font-black text-[#1A4231] mb-10 max-w-2xl leading-tight">
            {{ ($about && $about->$ctaTitleField) ? $about->$ctaTitleField : __('new_design.about.cta_title') }}
        </h2>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-center w-full max-w-lg">
            <a href="{{ route('coffee.crops') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-[#1A4231] hover:bg-[#133224] text-white font-black rounded-full px-10 py-4 transition-all duration-300 shadow-xl transform hover:scale-[1.02] active:scale-[0.98]">
                {{ ($about && $about->$ctaCropsField) ? $about->$ctaCropsField : __('new_design.about.cta_btn_crops') }}
            </a>
            <a href="{{ route('experts') }}" class="w-full sm:w-auto inline-flex items-center justify-center border-2 border-[#1A4231] text-[#1A4231] hover:bg-[#1A4231]/15 font-black rounded-full px-10 py-4 transition-all duration-300 shadow-lg transform hover:scale-[1.02] active:scale-[0.98]">
                {{ ($about && $about->$ctaExpertField) ? $about->$ctaExpertField : __('new_design.about.cta_btn_expert') }}
            </a>
        </div>

    </div>
</section>

@endsection
