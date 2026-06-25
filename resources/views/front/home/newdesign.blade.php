@extends('front.layouts.new_design_layout')

@section('content')

    @php
        $isRtl = app()->getLocale() != 'en';
        $dir = $isRtl ? 'rtl' : 'ltr';
    @endphp

    <!-- White Separator Above -->
    <div class="h-10 bg-white"></div>

    <!-- Hero Section (Exact Figma Match via Single Banner Image or Dynamic Content) -->
    <section class="w-full h-auto bg-[#EDEAE3] relative">
        @php
            $heroImg = asset('assets/elketar/ddd.png');
            if (!empty($heroSection) && !empty($heroSection->image)) {
                if (file_exists(public_path($heroSection->image))) {
                    $heroImg = asset($heroSection->image);
                } elseif (file_exists(public_path(PromotionImage() . $heroSection->image))) {
                    $heroImg = asset(PromotionImage() . $heroSection->image);
                } else {
                    $heroImg = asset(PromotionImage() . $heroSection->image);
                }
            }
        @endphp
        <img src="{{ $heroImg }}" alt="Hero Banner" class="w-full h-auto block">
    </section>

    <!-- White Separator Above -->
    <div class="h-10 bg-white"></div>

    <!-- Features Section (ما الذي يميز قطار القهوة؟) -->
    <section class="py-16 lg:py-24 relative overflow-hidden" dir="{{ $dir }}">

        <!-- Background Image: saaa.png -->
        <div class="absolute inset-0">
            <img src="{{ asset('assets/elketar/Intro Section (Notion Style).png') }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-[#1A4231]/60"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">

            <!-- 2-Column Layout on Desktop: Text & Cards (Right), Hand (Left) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                <!-- Right Column: Text & Cards (First in DOM -> Right side in RTL, Left in LTR) -->
                <div class="lg:col-span-8 flex flex-col gap-10 lg:gap-16 text-start">

                    <!-- Text Content -->
                    <div class="text-center lg:text-start">
                        @php
                            $whyTitle = $isRtl ? ($whyChoose->content_fr['title'] ?? '') : ($whyChoose->content_en['title'] ?? '');
                            $whyDesc = $isRtl ? ($whyChoose->content_fr['lead'] ?? '') : ($whyChoose->content_en['lead'] ?? '');
                            
                            $featuresContent = $isRtl ? ($features->content_fr ?? []) : ($features->content_en ?? []);
                            $featureItems = $featuresContent['items'] ?? [];
                        @endphp
                        <h2 class="text-3xl lg:text-5xl font-black text-white leading-tight mb-6">
                            {{ !empty($whyTitle) ? $whyTitle : __('new_design.home.why_title') }}
                        </h2>
                        <!-- Text and Vertical Line -->
                        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6">
                            <p class="text-white/90 text-base lg:text-lg leading-relaxed font-medium max-w-2xl text-center lg:text-start">
                                {{ !empty($whyDesc) ? $whyDesc : __('new_design.home.why_desc') }}
                            </p>
                            <!-- Line (Second in DOM -> Left of text in RTL) -->
                            <div class="w-1 h-24 bg-white/40 hidden lg:block rounded-full"></div>
                        </div>
                    </div>

                    <!-- 3 Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
                        @forelse($featureItems as $index => $it)
                            <!-- Box {{ $index + 1 }} -->
                            <div class="bg-[#FBF0D8] p-6 lg:p-8 rounded-2xl text-start shadow-xl hover:-translate-y-2 transition-transform duration-300 border-b-4 border-[#1A4231] flex flex-col items-start">
                                <div class="text-[#1A4231] mb-5">
                                    @if(!empty($it['icon']))
                                        {!! $it['icon'] !!}
                                    @else
                                        @if($index === 0)
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        @elseif($index === 1)
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        @else
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    @endif
                                </div>
                                <h3 class="text-lg lg:text-xl font-bold text-[#1A4231] mb-3">{{ $it['title'] ?? '' }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed font-semibold">
                                    {{ $it['desc'] ?? '' }}
                                </p>
                            </div>
                        @empty
                            <!-- Box 1 -->
                            <div class="bg-[#FBF0D8] p-6 lg:p-8 rounded-2xl text-start shadow-xl hover:-translate-y-2 transition-transform duration-300 border-b-4 border-[#1A4231] flex flex-col items-start">
                                <div class="text-[#1A4231] mb-5">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                </div>
                                <h3 class="text-lg lg:text-xl font-bold text-[#1A4231] mb-3">{{ __('new_design.home.feat_roast_title') }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed font-semibold">
                                    {{ __('new_design.home.feat_roast_desc') }}
                                </p>
                            </div>
                            <!-- Box 2 -->
                            <div class="bg-[#FBF0D8] p-6 lg:p-8 rounded-2xl text-start shadow-xl hover:-translate-y-2 transition-transform duration-300 border-b-4 border-[#1A4231] flex flex-col items-start">
                                <div class="text-[#1A4231] mb-5">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <h3 class="text-lg lg:text-xl font-bold text-[#1A4231] mb-3">{{ __('new_design.home.feat_trade_title') }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed font-semibold">
                                    {{ __('new_design.home.feat_trade_desc') }}
                                </p>
                            </div>
                            <!-- Box 3 -->
                            <div class="bg-[#FBF0D8] p-6 lg:p-8 rounded-2xl text-start shadow-xl hover:-translate-y-2 transition-transform duration-300 border-b-4 border-[#1A4231] flex flex-col items-start">
                                <div class="text-[#1A4231] mb-5">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="text-lg lg:text-xl font-bold text-[#1A4231] mb-3">{{ __('new_design.home.feat_quality_title') }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed font-semibold">
                                    {{ __('new_design.home.feat_quality_desc') }}
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Left Column: Hand Image (Second in DOM -> Left side in RTL, Right in LTR) -->
                <div class="lg:col-span-4 flex items-center justify-center lg:justify-end">
                    <div class="relative w-full max-w-sm">
                        @php
                            $whyChooseImg = asset('assets/elketar/65646 1.png');
                            if (!empty($whyChoose) && !empty($whyChoose->image)) {
                                if (file_exists(public_path($whyChoose->image))) {
                                    $whyChooseImg = asset($whyChoose->image);
                                } elseif (file_exists(public_path(PromotionImage() . $whyChoose->image))) {
                                    $whyChooseImg = asset(PromotionImage() . $whyChoose->image);
                                } else {
                                    $whyChooseImg = asset(PromotionImage() . $whyChoose->image);
                                }
                            }
                        @endphp
                        <img src="{{ $whyChooseImg }}" alt="Why Choose" class="w-full h-auto drop-shadow-2xl object-contain">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- White Separator Below -->
    <div class="h-10 bg-white"></div>

    <!-- Browse Our World (تصفح عالمنا) -->
    <section class="py-24 relative" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/Section - Categories Showcase.png') }}'); background-size: cover; background-position: center;">

        <div class="container mx-auto px-4 relative z-10">
            <!-- Section Title -->
            <div class="flex flex-col items-center mb-16 lg:mb-20">
                <h2 class="text-3xl lg:text-5xl font-black text-[#1A4231] text-center mb-4">{{ __('new_design.home.browse_title') }}</h2>
                <div class="w-24 h-1.5 bg-[#1A4231] rounded-full"></div>
            </div>

            <!-- 4 Ovals Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">

                <!-- Card 1: المحاصيل -->
                <div class="relative overflow-hidden rounded-[120px] lg:rounded-[200px] aspect-[2/3] shadow-xl group">
                    <img src="{{ asset('assets/elketar/محاصيل القهوة.png') }}" alt="المحاصيل" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 flex flex-col items-center text-center text-white">
                        <h3 class="text-2xl lg:text-3xl font-extrabold mb-3 drop-shadow-md">{{ __('new_design.home.cat_crops_title') }}</h3>
                        <p class="text-xs lg:text-sm font-medium mb-5 text-white/90 leading-relaxed">
                            {{ __('new_design.home.cat_crops_desc') }}
                        </p>
                        <a href="{{ route('coffee.crops') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-gray-200 transition-colors">
                            {{ __('new_design.home.learn_more') }}
                            <svg class="w-4 h-4 {{ $isRtl ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 2: الأدوات -->
                <div class="relative overflow-hidden rounded-[120px] lg:rounded-[200px] aspect-[2/3] shadow-xl group">
                    <img src="{{ asset('assets/elketar/أدوات تحضير القهوة.png') }}" alt="الأدوات" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 flex flex-col items-center text-center text-white">
                        <h3 class="text-2xl lg:text-3xl font-extrabold mb-3 drop-shadow-md">{{ __('new_design.home.cat_tools_title') }}</h3>
                        <p class="text-xs lg:text-sm font-medium mb-5 text-white/90 leading-relaxed">
                            {{ __('new_design.home.cat_tools_desc') }}
                        </p>
                        <a href="{{ route('technical.tools') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-gray-200 transition-colors">
                            {{ __('new_design.home.learn_more') }}
                            <svg class="w-4 h-4 {{ $isRtl ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 3: صناديق التجربة -->
                <div class="relative overflow-hidden rounded-[120px] lg:rounded-[200px] aspect-[2/3] shadow-xl group">
                    <img src="{{ asset('assets/elketar/صناديق التجارب.png') }}" alt="صناديق التجربة" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 flex flex-col items-center text-center text-white">
                        <h3 class="text-2xl lg:text-3xl font-extrabold mb-3 drop-shadow-md">{{ __('new_design.home.cat_boxes_title') }}</h3>
                        <p class="text-xs lg:text-sm font-medium mb-5 text-white/90 leading-relaxed">
                            {{ __('new_design.home.cat_boxes_desc') }}
                        </p>
                        <a href="{{ route('trial.boxes') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-gray-200 transition-colors">
                            {{ __('new_design.home.learn_more') }}
                            <svg class="w-4 h-4 {{ $isRtl ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 4: استشر خبيراً -->
                <div class="relative overflow-hidden rounded-[120px] lg:rounded-[200px] aspect-[2/3] shadow-xl group">
                    <img src="{{ asset('assets/elketar/استشر خبيراً.png') }}" alt="استشر خبيراً" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8 flex flex-col items-center text-center text-white">
                        <h3 class="text-2xl lg:text-3xl font-extrabold mb-3 drop-shadow-md">{{ __('new_design.home.cat_expert_title') }}</h3>
                        <p class="text-xs lg:text-sm font-medium mb-5 text-white/90 leading-relaxed">
                            {{ __('new_design.home.cat_expert_desc') }}
                        </p>
                        <a href="{{ route('experts') }}" class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-gray-200 transition-colors">
                            {{ __('new_design.home.book_appointment') }}
                            <svg class="w-4 h-4 {{ $isRtl ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- White Separator Above -->
    <div class="h-10 bg-white"></div>



    <!-- White Separator Above -->
    <div class="h-10 bg-white"></div>

    <!-- Stats Section (خطوات ملموسة) -->
    <section class="h-[220px] lg:h-[260px] w-full relative overflow-hidden bg-[#1A4231] flex flex-col justify-center" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/Impact Numbers Section.png') }}'); background-size: cover; background-position: center;">

        <!-- Floating Right Image (Loose Beans) -->
        <div class="absolute {{ $isRtl ? 'right-0' : 'left-0' }} bottom-0 w-44 md:w-60 lg:w-[340px] z-0 pointer-events-none opacity-40 lg:opacity-100 flex items-end justify-end">
            <img src="{{ asset('assets/elketar/istockphoto-1128469742-612x612 1.png') }}" class="w-full h-auto drop-shadow-2xl translate-y-8 {{ $isRtl ? 'translate-x-2 lg:translate-x-4' : '-translate-x-2 lg:-translate-x-4' }}" alt="Coffee Beans">
        </div>

        <!-- Floating Left Image (Hand with scoop) -->
        <div class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} bottom-0 w-44 md:w-60 lg:w-[340px] z-0 pointer-events-none opacity-40 lg:opacity-100 flex items-end justify-start">
            <img src="{{ asset('assets/elketar/65646 1.png') }}" class="w-full h-auto drop-shadow-2xl translate-y-6 lg:translate-y-10 {{ $isRtl ? '-translate-x-2 lg:-translate-x-4' : 'translate-x-2 lg:translate-x-4' }}" alt="Hand Scooping">
        </div>

        <div class="container mx-auto px-4 relative z-10 w-full">
            @php
                $statsTitle = $isRtl ? ($statsSection->content_fr['title'] ?? '') : ($statsSection->content_en['title'] ?? '');
                $statsLead = $isRtl ? ($statsSection->content_fr['lead'] ?? '') : ($statsSection->content_en['lead'] ?? '');
                
                $statsContent = $isRtl ? ($statsSection->content_fr ?? []) : ($statsSection->content_en ?? []);
                $statsItems = $statsContent['stats'] ?? [];
            @endphp

            <!-- Section Title -->
            <div class="flex flex-col items-center mb-4 lg:mb-6">
                <span class="text-[#FBF0D8] text-[10px] lg:text-xs font-bold mb-1 opacity-90">
                    {{ !empty($statsLead) ? $statsLead : __('new_design.home.impact_subtitle') }}
                </span>
                <h2 class="text-white text-center text-xl lg:text-2xl font-black drop-shadow-lg">
                    {{ !empty($statsTitle) ? $statsTitle : __('new_design.home.impact_title') }}
                </h2>
            </div>

            <!-- 4 Stats Grid -->
            <div class="grid grid-cols-4 gap-2 lg:gap-4 text-center text-white max-w-4xl mx-auto">
                @if(!empty($statsItems) && count($statsItems) >= 4)
                    @foreach($statsItems as $index => $item)
                        <div class="flex flex-col items-center justify-center">
                            <span class="text-2xl lg:text-4xl font-extrabold block mb-1 text-[#FBF0D8] drop-shadow-md">{{ $item['val'] ?? '' }}</span>
                            <span class="text-[9px] lg:text-xs font-bold text-white/90 leading-tight">{!! $item['lbl'] ?? '' !!}</span>
                        </div>
                    @endforeach
                @else
                    <!-- Stat 1 -->
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-2xl lg:text-4xl font-extrabold block mb-1 text-[#FBF0D8] drop-shadow-md">{{ __('new_design.home.stat_water_val') }}</span>
                        <span class="text-[9px] lg:text-xs font-bold text-white/90 leading-tight">{!! __('new_design.home.stat_water_lbl') !!}</span>
                    </div>

                    <!-- Stat 2 -->
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-2xl lg:text-4xl font-extrabold block mb-1 text-[#FBF0D8] drop-shadow-md">{{ __('new_design.home.stat_tools_val') }}</span>
                        <span class="text-[9px] lg:text-xs font-bold text-white/90 leading-tight">{!! __('new_design.home.stat_tools_lbl') !!}</span>
                    </div>

                    <!-- Stat 3 -->
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-2xl lg:text-4xl font-extrabold block mb-1 text-[#FBF0D8] drop-shadow-md">{{ __('new_design.home.stat_trees_val') }}</span>
                        <span class="text-[9px] lg:text-xs font-bold text-white/90 leading-tight">{!! __('new_design.home.stat_trees_lbl') !!}</span>
                    </div>

                    <!-- Stat 4 -->
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-2xl lg:text-4xl font-extrabold block mb-1 text-[#FBF0D8] drop-shadow-md">{{ __('new_design.home.stat_energy_val') }}</span>
                        <span class="text-[9px] lg:text-xs font-bold text-white/90 leading-tight">{!! __('new_design.home.stat_energy_lbl') !!}</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- White Separator Below -->
    <div class="h-10 bg-white"></div>

    <!-- Roasting Section (اكتشف عالم التحميص) -->
    <section class="py-24 relative overflow-hidden text-white" dir="{{ $dir }}">
        <!-- Background Image: saaa.png -->
        <div class="absolute inset-0">
            <img src="{{ asset('assets/elketar/saaa.png') }}" class="w-full h-full object-cover">
            <!-- Warm Sepia/Dark Gold Overlay -->
            <div class="absolute inset-0 backdrop-blur-[1px]"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <!-- Top Block: Title, Description, and Left Divider -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center mb-16">

                <!-- Text content -->
                <div class="lg:col-span-11 text-start">
                    <h2 class="text-3xl lg:text-5xl font-black mb-6 drop-shadow-md text-[#FBF0D8]">{{ __('new_design.home.roast_title') }}</h2>
                    <div class="max-w-4xl space-y-4">
                        <p class="text-base lg:text-lg font-medium leading-relaxed opacity-95">
                            {{ __('new_design.home.roast_desc1') }}
                        </p>
                        <p class="text-base lg:text-lg font-medium leading-relaxed opacity-95">
                            {{ __('new_design.home.roast_desc2') }}
                        </p>
                    </div>
                </div>

                <!-- Decorative vertical line -->
                <div class="hidden lg:flex lg:col-span-1 justify-end">
                    <div class="w-1.5 h-32 bg-white/80 rounded-full"></div>
                </div>

            </div>

            <!-- 3 Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 max-w-6xl mx-auto">

                <!-- Card 1 -->
                <div class="bg-[#FBF0D8] p-8 lg:p-10 rounded-[32px] text-center shadow-2xl transform hover:-translate-y-2 transition-all duration-300 flex flex-col items-center border border-[#1A4231]/10">
                    <div class="text-[#1A4231] mb-6 bg-[#1A4231]/10 p-4 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-black text-[#1A4231] mb-4">{{ __('new_design.home.feat_roast_title') }}</h3>
                    <p class="text-sm lg:text-base text-gray-700 leading-relaxed font-semibold">
                        {{ __('new_design.home.feat_roast_desc') }}
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#FBF0D8] p-8 lg:p-10 rounded-[32px] text-center shadow-2xl transform hover:-translate-y-2 transition-all duration-300 flex flex-col items-center border border-[#1A4231]/10">
                    <div class="text-[#1A4231] mb-6 bg-[#1A4231]/10 p-4 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-black text-[#1A4231] mb-4">{{ __('new_design.home.feat_trade_title') }}</h3>
                    <p class="text-sm lg:text-base text-gray-700 leading-relaxed font-semibold">
                        {{ __('new_design.home.feat_trade_desc') }}
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#FBF0D8] p-8 lg:p-10 rounded-[32px] text-center shadow-2xl transform hover:-translate-y-2 transition-all duration-300 flex flex-col items-center border border-[#1A4231]/10">
                    <div class="text-[#1A4231] mb-6 bg-[#1A4231]/10 p-4 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-black text-[#1A4231] mb-4">{{ __('new_design.home.feat_quality_title') }}</h3>
                    <p class="text-sm lg:text-base text-gray-700 leading-relaxed font-semibold">
                        {{ __('new_design.home.feat_quality_desc') }}
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- White Separator Below -->
    <div class="h-10 bg-white"></div>

    <!-- Newsletter Section (كن أول من يعرف عنا) -->
    <section class="py-16 relative overflow-hidden bg-cover bg-center" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/fff.png') }}');">
        <div class="container mx-auto px-4 relative z-10">

            <!-- Dark Green Inner Card -->
            <div class="relative max-w-5xl mx-auto rounded-[40px] overflow-hidden bg-[#1A4231] shadow-2xl py-12 lg:py-16 px-6 lg:px-12 text-center" style="background-image: url('{{ asset('assets/elketar/Section - Why Partner With Us.png') }}'); background-size: cover; background-position: center;">

                <!-- Inner soft overlay for text readability, keeping the pattern crisp -->
                <div class="absolute inset-0 bg-[#1A4231]/30 z-0"></div>            
                <div class="relative z-10 flex flex-col items-center">
                    <!-- Envelope Icon -->
                    <div class="text-[#FBF0D8] mb-4 bg-white/10 p-3.5 rounded-2xl backdrop-blur-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>

                    <!-- Title & Subtitle -->
                    <h2 class="text-white text-3xl lg:text-4xl font-black mb-3">{{ __('new_design.home.newsletter_title') }}</h2>
                    <p class="text-[#FBF0D8] text-sm lg:text-base font-semibold opacity-90 mb-8 max-w-md">
                        {{ __('new_design.home.newsletter_subtitle') }}
                    </p>

                    <!-- Form -->
                    <form id="newsletter-form" action="{{ route('subscribe') }}" method="POST" class="w-full max-w-xl flex flex-col sm:flex-row gap-4 items-center justify-center">
                        @csrf
                        <!-- Input Field -->
                        <div class="relative w-full sm:flex-1">
                            <input type="email" name="subscribe" required placeholder="{{ __('new_design.home.newsletter_placeholder') }}" class="w-full bg-white/10 border border-white/20 rounded-full px-6 py-4 text-white placeholder-white/60 text-start focus:outline-none focus:ring-2 focus:ring-[#FBF0D8] transition-all duration-300">
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="bg-white hover:bg-white/90 text-[#1A4231] font-black rounded-full px-10 py-4 transition-all duration-300 shadow-lg transform hover:scale-[1.02] active:scale-[0.98] w-full sm:w-auto shrink-0">
                            {{ __('new_design.home.newsletter_btn') }}
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </section>

    <!-- White Separator Below -->
    <div class="h-10 bg-white"></div>


@push('scripts')
<script>

$(document).ready(function() {
    $('#newsletter-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var emailInput = form.find('input[name="subscribe"]');
        var submitBtn = form.find('button[type="submit"]');
        var originalBtnText = submitBtn.text();
        
        submitBtn.prop('disabled', true).text("{{ __('Subscribing...') }}");
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                toastr.success(response.message || "{{ __('Subscription successful!') }}");
                emailInput.val('');
                submitBtn.prop('disabled', false).text(originalBtnText);
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).text(originalBtnText);
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    var firstError = Object.values(errors)[0][0];
                    toastr.error(firstError);
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else {
                    toastr.error("{{ __('Something went wrong. Please try again.') }}");
                }
            }
        });
    });
});
</script>
@endpush

@endsection
