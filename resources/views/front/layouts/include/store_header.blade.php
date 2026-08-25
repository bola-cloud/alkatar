@php
    $isRtl = app()->getLocale() != 'en';
    $dir = $isRtl ? 'rtl' : 'ltr';
    $searchText = $isRtl ? 'ابحث هنا' : 'Search here';
@endphp

<!-- Store Header -->
<header class="relative z-[1001] shadow-sm py-4 lg:py-6" dir="{{ $dir }}" style="background-image: url('{{ asset('assets/elketar/Section - Categories Showcase.png') }}'); background-size: cover; background-position: center;background-blend-mode: overlay;" x-data="{ mobileMenu: false }">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between gap-4">
            
            <!-- Right: Logo (in RTL) -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('front.store') }}" class="flex items-center">
                    <img src="{{ isset($allsettings['main_logo']) ? asset(IMG_LOGO_PATH . $allsettings['main_logo']) : asset('assets/elketar/logo.png') }}" alt="Logo" class="h-10 lg:h-14 object-contain">
                </a>
            </div>

            <!-- Center: Navigation Links (Hidden on Mobile) -->
            <nav class="hidden lg:flex items-center gap-8 text-[#1A4231] font-bold text-base" style="font-family: 'Cairo', sans-serif;">
                <a href="{{ route('front.store') }}" class="hover:opacity-85 transition-opacity">{{ $isRtl ? 'الرئيسية' : 'Home' }}</a>
                <a href="{{ route('coffee.crops') }}" class="hover:opacity-85 transition-opacity">{{ $isRtl ? 'المحاصيل' : 'Crops' }}</a>
                <a href="{{ route('technical.tools') }}" class="hover:opacity-85 transition-opacity">{{ $isRtl ? 'معدات التحضير' : 'Brewing Equipment' }}</a>
                <a href="{{ route('trial.boxes') }}" class="hover:opacity-85 transition-opacity">{{ $isRtl ? 'بوكسات التجربة' : 'Experience Boxes' }}</a>
                <a href="{{ route('custom.box') }}" class="hover:opacity-85 transition-opacity">{{ $isRtl ? 'البوكس المخصص' : 'Custom Box' }}</a>
            </nav>

            <!-- Left: Actions & Buttons (in RTL) -->
            <div class="flex items-center gap-3 lg:gap-5 shrink-0">
                
                <!-- Search Form -->
                <div x-data="searchSuggest()" class="relative hidden md:flex items-center">
                    <form action="{{ route('front.store.catalog') }}" method="GET" class="w-full" @click.away="isOpen = false">
                        <input type="text" name="search" placeholder="{{ $searchText }}" value="{{ request('search') }}" 
                               x-model="query" 
                               @input.debounce.300ms="fetchSuggestions" 
                               @focus="query.length > 1 ? isOpen = true : null"
                               class="w-32 lg:w-48 bg-white/50 border border-gray-200 rounded-full py-1.5 px-4 pr-10 text-sm focus:outline-none focus:ring-1 focus:ring-[#1A4231] text-[#1A4231] transition-all focus:bg-white focus:w-48 lg:focus:w-64">
                        <button type="submit" class="absolute {{ $isRtl ? 'left-1' : 'right-1' }} text-[#1A4231] hover:opacity-80 transition-opacity p-1.5 rounded-full hover:bg-white/10 bg-transparent border-none cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                        <!-- Suggestions Dropdown -->
                        <div x-show="isOpen" x-cloak x-transition class="absolute top-full {{ $isRtl ? 'left-0' : 'right-0' }} mt-2 w-64 lg:w-80 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden z-50">
                            <ul class="max-h-80 overflow-y-auto">
                                <li x-show="isLoading" class="p-4 text-center text-gray-400 text-sm">
                                    <svg class="animate-spin h-5 w-5 mx-auto text-[#1A4231]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </li>
                                <li x-show="!isLoading && suggestions.length === 0" class="p-4 text-center text-sm font-bold text-gray-500">
                                    {{ __('new_design.coffee_crops.no_products_found') ?? 'لا يوجد منتجات مطابقة للبحث' }}
                                </li>
                                <template x-for="item in suggestions" :key="item.id">
                                    <li>
                                        <a :href="`{{ route('single.product.new', '') }}/${item.en_Product_Slug}`" class="flex items-center gap-3 p-3 hover:bg-gray-50 transition-colors border-b border-gray-50">
                                            <img :src="item.Primary_Image" class="w-10 h-10 object-cover rounded-lg bg-gray-100 shrink-0">
                                            <div class="flex flex-col text-start">
                                                <span class="text-sm font-extrabold text-[#1A4231]" x-text="{{ $isRtl ? 'item.fr_Product_Name' : 'item.en_Product_Name' }}"></span>
                                            </div>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </form>
                </div>

                <!-- Profile Icon -->
                <a href="{{ auth()->check() ? route('user.profile') : route('login') }}" class="text-[#1A4231] hover:opacity-80 transition-opacity p-1.5 rounded-full hover:bg-white/10 hidden md:block">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>

                <!-- Cart Icon with Badge -->
                <a href="{{ route('front.cart') }}" class="relative text-[#1A4231] hover:opacity-80 transition-opacity p-1.5 rounded-full hover:bg-white/10 flex items-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <!-- Cart count badge -->
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-[#1A4231] text-white text-[9px] font-bold rounded-full flex items-center justify-center totalCountItem">
                        {{ Cart::count() }}
                    </span>
                </a>

                <!-- Login Button -->
                @auth
                    <a href="{{ route('user.profile') }}" class="hidden sm:inline-block bg-[#1A4231] text-white px-5 lg:px-7 py-2 lg:py-2.5 rounded-full text-sm font-extrabold hover:bg-[#235841] transition-all whitespace-nowrap" style="font-family: 'Cairo', sans-serif;">
                        {{ $isRtl ? 'الملف الشخصي' : 'Profile' }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block bg-[#1A4231] text-white px-5 lg:px-7 py-2 lg:py-2.5 rounded-full text-sm font-extrabold hover:bg-[#235841] transition-all whitespace-nowrap" style="font-family: 'Cairo', sans-serif;">
                        {{ $isRtl ? 'تسجيل الدخول' : 'Login' }}
                    </a>
                @endauth

                <!-- Qatar Community Pill Button -->
                <a href="{{ route('front') }}" class="hidden sm:inline-block bg-white text-[#1A4231] border border-[#1A4231] px-5 lg:px-7 py-2 lg:py-2.5 rounded-full text-sm font-extrabold hover:bg-gray-50 transition-all whitespace-nowrap" style="font-family: 'Cairo', sans-serif;">
                    {{ $isRtl ? 'مجتمع القطار' : 'Qatar Community' }}
                </a>

                <!-- Mobile Hamburger Button -->
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-1.5 text-[#1A4231] focus:outline-none">
                    <svg x-show="!mobileMenu" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                    <svg x-show="mobileMenu" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

            </div>

        </div>

        <!-- Mobile Menu Slider -->
        <div x-show="mobileMenu" x-transition class="lg:hidden mt-4 pb-4 border-t border-gray-200/50">
            <div class="mt-4 flex flex-col gap-4 text-start font-bold text-sm px-2">
                <a href="{{ route('front.store') }}" class="text-[#1A4231] py-3 border-b border-gray-200/30 flex items-center justify-between">
                    <span>{{ $isRtl ? 'الرئيسية' : 'Home' }}</span>
                </a>
                <a href="{{ route('coffee.crops') }}" class="text-[#1A4231] py-3 border-b border-gray-200/30 flex items-center justify-between">
                    <span>{{ $isRtl ? 'المحاصيل' : 'Crops' }}</span>
                </a>
                <a href="{{ route('technical.tools') }}" class="text-[#1A4231] py-3 border-b border-gray-200/30 flex items-center justify-between">
                    <span>{{ $isRtl ? 'معدات التحضير' : 'Brewing Equipment' }}</span>
                </a>
                <a href="{{ route('trial.boxes') }}" class="text-[#1A4231] py-3 border-b border-gray-200/30 flex items-center justify-between">
                    <span>{{ $isRtl ? 'بوكسات التجربة' : 'Experience Boxes' }}</span>
                </a>
                <a href="{{ route('custom.box') }}" class="text-[#1A4231] py-3 border-b border-gray-200/30 flex items-center justify-between">
                    <span>{{ $isRtl ? 'البوكس المخصص' : 'Custom Box' }}</span>
                </a>

                <!-- Mobile Actions -->
                <div class="flex flex-col gap-3 mt-4">
                    @auth
                        <a href="{{ route('user.profile') }}" class="w-full text-center bg-[#1A4231] text-white py-3 rounded-full font-bold">
                            {{ $isRtl ? 'الملف الشخصي' : 'Profile' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center bg-[#1A4231] text-white py-3 rounded-full font-bold">
                            {{ $isRtl ? 'تسجيل الدخول' : 'Login' }}
                        </a>
                    @endauth
                    <a href="{{ route('front') }}" class="w-full text-center bg-white text-[#1A4231] border border-[#1A4231] py-3 rounded-full font-bold">
                        {{ $isRtl ? 'مجتمع القطار' : 'Qatar Community' }}
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>

<!-- Prevent Alpine JS flicker -->
<style>
    [x-cloak] { display: none !important; }
</style>

<!-- Suggestion Data Logic -->
<script>
    document.addEventListener('alpine:init', () => {
        if (!window.searchSuggestDefined) {
            window.searchSuggestDefined = true;
            Alpine.data('searchSuggest', () => ({
                query: '{{ request('search') }}',
                suggestions: [],
                isOpen: false,
                isLoading: false,
                fetchSuggestions() {
                    if (this.query.length < 2) {
                        this.suggestions = [];
                        this.isOpen = false;
                        return;
                    }
                    this.isOpen = true;
                    this.isLoading = true;
                    
                    fetch(`{{ route('search.suggest') }}?query=${encodeURIComponent(this.query)}`)
                        .then(response => response.json())
                        .then(data => {
                            this.suggestions = data;
                            this.isLoading = false;
                        })
                        .catch(err => {
                            this.isLoading = false;
                            console.error('Search error:', err);
                        });
                }
            }));
        }
    });
</script>
