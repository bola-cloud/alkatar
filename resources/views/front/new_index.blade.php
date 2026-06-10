<!-- Categories Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col items-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-katar-green text-center">
                @php $catSection = siteContentHomePage('categories'); @endphp
                {{ $catSection ? langConverter($catSection->en_Description_One, $catSection->fr_Description_One) : __('Browse Categories') }}
            </h2>
            <div class="w-24 h-1 bg-katar-gold mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($allCategories as $item)
                <a href="{{ route('category.product', $item->id) }}" class="group flex flex-col items-center p-6 rounded-2xl bg-katar-cream/50 hover:bg-white hover:shadow-xl transition-all duration-300">
                    <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                        @php 
                            $catIcon = $item->Category_Icon;
                            $iconSrc = (strpos($catIcon, 'http') === 0) ? $catIcon : asset(CategoryImage().$catIcon);
                        @endphp
                        <img src="{{ $iconSrc }}" alt="{{ $item->en_Category_Name }}" class="w-12 h-12 object-contain">
                    </div>
                    <span class="mt-4 font-bold text-katar-green group-hover:text-katar-gold transition-colors text-center">
                        {{ langConverter($item->en_Category_Name, $item->fr_Category_Name) }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Special Offers Section -->
<section class="py-20 bg-katar-cream/30">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-3xl font-bold text-katar-green">
                @php $saleSection = siteContentHomePage('on_sale'); @endphp
                {{ $saleSection ? langConverter($saleSection->en_Description_One, $saleSection->fr_Description_One) : __('Special Offers') }}
            </h2>
            <a href="{{ route('categories.show') }}" class="text-katar-gold font-bold flex items-center gap-2 hover:gap-3 transition-all">
                {{ __('See All') }} <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($products as $product)
                <x-frontend.katar-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- Best Sellers -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col items-center mb-12 text-center">
            <span class="text-katar-gold font-bold tracking-widest text-sm uppercase mb-2">{{ __('Premium Selection') }}</span>
            <h2 class="text-4xl font-extrabold text-katar-green">
                @php $bestSection = siteContentHomePage('best_selling'); @endphp
                {{ $bestSection ? langConverter($bestSection->en_Description_One, $bestSection->fr_Description_One) : __('Best Selling') }}
            </h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($best_selling as $product)
                <x-frontend.katar-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
