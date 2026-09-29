@php
    $catSlug = $product->category ? $product->category->en_Category_Slug : '';
    $subcatId = $product->subcategory_id ?? 'all';
    $sizeIds = $product->sizes ? $product->sizes->pluck('id')->implode(',') : '';

    $hasOptions = ($product->sizes && $product->sizes->count() > 0) || ($product->weights && $product->weights->count() > 0);
    
    $productSizes = [];
    if($product->sizes) {
        foreach($product->sizes as $sz) {
            $productSizes[] = [
                'id' => $sz->id,
                'name' => $sz->Size,
                'name_ar' => $sz->Size_ar ?? $sz->Size,
                'price' => floatval($sz->pivot->price ?? $product->Price)
            ];
        }
    }
    
    $productWeights = [];
    if($product->weights) {
        foreach($product->weights as $wt) {
            $productWeights[] = [
                'id' => $wt->id,
                'name' => $wt->weight,
                'name_ar' => $wt->weight,
                'price' => floatval($wt->price ?? $product->Price)
            ];
        }
    }
@endphp
<!-- Product Card -->
<div class="product-card bg-white rounded-[32px] border border-[#1A4231] overflow-hidden flex flex-col justify-between hover:shadow-lg transition-all duration-300 h-full w-full max-w-[320px] mx-auto"
     data-category="{{ $catSlug }}"
     data-subcategory="{{ $subcatId }}"
     data-sizes="{{ $sizeIds }}"
     data-price="{{ $product->Price }}"
     data-created-at="{{ $product->created_at }}"
     data-search-text="{{ strtolower($product->en_Product_Name . ' ' . $product->fr_Product_Name . ' ' . $product->localized_name) }}">
    
    <a href="{{ route('single.product.new', $product->en_Product_Slug) }}" class="block hover:opacity-95 transition-opacity">
        <!-- Product Image Container -->
        <div class="relative w-full overflow-hidden" style="aspect-ratio: 2/3;">
            <!-- Tag -->
            @if($product->ItemTag)
            <span class="absolute top-4 {{ $isRtl ? 'right-4' : 'left-4' }} bg-white/95 text-[#1A4231] text-[11px] font-black px-4 py-1.5 rounded-full border border-[#1A4231]/10 shadow-sm backdrop-blur-md z-10">
                {{ $product->ItemTag == 'Beginner' ? __('new_design.store_page.tag_beginner') : __('new_design.store_page.tag_pro') }}
            </span>
            @endif
            <!-- Product image -->
            @php
                $imgSrc = resolve_product_image($product->Primary_Image);
            @endphp
            <img src="{{ $imgSrc }}" alt="{{ $product->localized_name }}" class="w-full h-full object-cover">
        </div>

        <!-- Product Info -->
        <div class="p-4 sm:p-6 flex flex-col text-start gap-2 sm:gap-4">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 sm:gap-4">
                <h3 class="text-sm sm:text-lg font-black text-[#1A4231] leading-tight line-clamp-2">
                    {{ $product->localized_name }}
                </h3>
                <span class="text-sm sm:text-base font-black text-[#1A4231] whitespace-nowrap">
                    {{ floatval($product->Price) }} {{ __('new_design.coffee_crops.currency') }}
                </span>
            </div>
            
            <p class="text-xs text-gray-500 font-semibold leading-relaxed line-clamp-2 min-h-[2rem]">
                {{ $product->localized_about }}
            </p>

        </div>
    </a>

    <!-- Add to Cart Button -->
    <div class="px-3 sm:px-6 pb-4 sm:pb-6 mt-auto">
        @if($product->Quantity <= 0)
            <button type="button" disabled class="w-full bg-gray-400 text-white py-2 sm:py-3.5 px-2 rounded-full text-[11px] sm:text-sm font-extrabold flex items-center justify-center gap-1.5 sm:gap-2 shadow-sm cursor-not-allowed opacity-80">
                <span class="whitespace-nowrap">{{ app()->getLocale() == 'fr' ? 'نفدت الكمية' : 'Out of Stock' }}</span>
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/>
                </svg>
            </button>
        @elseif($hasOptions)
            <button type="button" 
                onclick="openQuickViewModal({{ $product->id }}, '{{ addslashes(htmlspecialchars($product->localized_name, ENT_QUOTES)) }}', '{{ $imgSrc }}', {{ json_encode($productSizes) }}, {{ json_encode($productWeights) }}, {{ floatval($product->Price) }}, {{ floatval($product->Discount) }})" 
                class="w-full bg-[#1A4231] hover:bg-[#2C624A] text-white py-2 sm:py-3.5 px-2 rounded-full text-[11px] sm:text-sm font-extrabold flex items-center justify-center gap-1.5 sm:gap-2 hover:scale-[1.01] active:scale-[0.99] transition-all shadow-md">
                <span class="whitespace-nowrap">{{ __('new_design.store_page.add_to_cart') }}</span>
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </button>
        @else
            <button onclick="addToCart({{ $product->id }}, {{ $product->Discount > 0 ? ($product->Price - ($product->Price * $product->Discount / 100)) : $product->Price }})" class="w-full bg-[#1A4231] hover:bg-[#2C624A] text-white py-2 sm:py-3.5 px-2 rounded-full text-[11px] sm:text-sm font-extrabold flex items-center justify-center gap-1.5 sm:gap-2 hover:scale-[1.01] active:scale-[0.99] transition-all shadow-md">
                <span class="whitespace-nowrap">{{ __('new_design.store_page.add_to_cart') }}</span>
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </button>
        @endif
    </div>

</div>
