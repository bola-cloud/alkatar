@props(['product'])

<div class="group relative bg-white rounded-3xl p-4 transition-all duration-500 hover:shadow-[0_20px_50px_rgba(30,57,50,0.1)] border border-transparent hover:border-katar-cream">
    <!-- Image -->
    <div class="relative h-64 mb-6 overflow-hidden rounded-2xl bg-katar-cream/30">
        @php 
            $prodImg = $product->Primary_Image;
            $imgSrc = (strpos($prodImg, 'http') === 0) ? $prodImg : asset(ProductImage().$prodImg);
        @endphp
        <img src="{{ $imgSrc }}" 
             alt="{{ $product->en_Product_Name }}" 
             class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-700">
        
        <!-- Hover Actions -->
        <div class="absolute inset-0 bg-katar-green/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
            <button onclick="addToCart({{ $product->id }}, {{ $product->Discount > 0 ? ($product->Price - ($product->Price * $product->Discount / 100)) : $product->Price }})" class="w-12 h-12 bg-white text-katar-green rounded-full flex items-center justify-center hover:bg-katar-gold hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </button>
            <button onclick="addToWishlist({{ $product->id }})" class="w-12 h-12 bg-white text-katar-green rounded-full flex items-center justify-center hover:bg-katar-gold hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </button>
        </div>

        @if($product->Discount > 0)
        <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
            -{{ $product->Discount }}%
        </span>
        @endif
    </div>

    <!-- Info -->
    <div class="text-center">
        <a href="{{ route('single.product', $product->en_Product_Slug) }}" class="block text-lg font-bold text-katar-green hover:text-katar-gold transition-colors mb-2">
            {{ langConverter($product->en_Product_Name, $product->fr_Product_Name) }}
        </a>
        <div class="flex items-center justify-center gap-2">
            <span class="text-xl font-black text-katar-gold">{{ currencyConverter($product->Price) }}</span>
            @if($product->Discount > 0)
            <span class="text-sm text-gray-400 line-through">{{ currencyConverter($product->Discount_Price) }}</span>
            @endif
        </div>
    </div>
</div>
