@php
  // $products: collection of product models
  $carouselId = $carouselId ?? 'productCarousel_' .
    substr(md5(uniqid('', true)), 0, 6);
@endphp

<div class="product-carousel-section position-relative">
  <button class="carousel-arrow left-arrow btn btn-white shadow-sm position-absolute" aria-label="{{ __('Previous') }}"
    data-target="#{{ $carouselId }}">
    <i class="bi bi-chevron-left fs-4"></i>
  </button>

  <div id="{{ $carouselId }}" class="product-carousel-wrapper d-flex gap-2 overflow-auto pt-2 pb-3">
    @foreach($products as $product)
      @php
        // Use langConverter for consistent localization across the site
        $name = langConverter($product->en_Product_Name, $product->fr_Product_Name);

        // Prefer product-level Price when set (old design). Otherwise use weights/sizes.
        $basePrice = $product->Price ?? 0;
        if (empty($basePrice) || $basePrice == 0) {
          if ($product->weights && $product->weights->count()) {
            $basePrice = $product->weights->first()->price;
          } elseif ($product->sizes && $product->sizes->count()) {
            $firstSize = $product->sizes->first();
            $basePrice = $firstSize?->pivot->price ?? 0;
          }
        }

        // Displayed price: discounted price when discount applies, otherwise the base price
        $displayPrice = ($product->Discount > 0 && $product->Discount_Price) ? $product->Discount_Price : $basePrice;
        $img = $product->Primary_Image ?? null;
        $imgUrl = asset('new-design/images/special-offer.png');
        if ($img) {
          // Directly use the path without file_exists check to improve performance.
          // Using strict path concatenation based on standard folder structure.
          $imgUrl = asset(ProductImage() . $img);
        }
        $isWish = function_exists('isInWishlist') ? isInWishlist($product->id) : false;
      @endphp

      <div class="product-item card h-100 d-flex flex-column">
        <div class="position-relative">
          <a href="{{ route('single.product.new', $product->en_Product_Slug) }}">
            <img src="{{ $imgUrl }}" loading="lazy" class="card-img-top p-3" alt="{{ $name }}"
              style="height:160px; object-fit:contain;"
              onerror="this.onerror=null;this.src='{{ asset('new-design/images/special-offer.png') }}';">
          </a>

          <a href="javascript:void(0)"
            class="product-btn MyWishList btn btn-light btn-sm rounded-circle position-absolute top-0 end-0 m-3"
            data-id="{{ $product->id }}" title="{{ __('Add To Wishlist') }}">
            <i class="{{ $isWish ? 'bi bi-heart-fill text-danger' : 'bi bi-heart' }}"></i>
          </a>
        </div>

        <div class="card-body d-flex flex-column flex-grow-1 text-center">
          <h6 class="card-title mb-2">
            {{ \Illuminate\Support\Str::limit($name, 40) }}
          </h6>
          <div class="product-unit mb-2">
            @if($product->unit)
              <span class="badge bg-light text-dark border">
                <i class="bi bi-box-seam me-1"></i>{{ $product->unit }}
              </span>
            @endif
          </div>
          <div class="d-flex align-items-center justify-content-center mb-2">
            <span class="text-warning">★★★★★</span>
          </div>
          <div class="mt-auto">
            <div class="d-flex justify-content-center align-items-center mb-2">
              @if($product->Discount > 0)
                {{-- show discounted price first, then original struck-through to the right to match home --}}
                <span class="h6 mb-0" style="font-weight:600;">{{ currencyConverter($displayPrice) }}</span>
                <span class="h6 mb-0"
                  style="margin-left:8px; text-decoration-line: line-through; text-decoration-color: #b5c61a; text-decoration-thickness: 2px; color: #6b6b6b;">{{ currencyConverter($basePrice) }}</span>
              @else
                <span class="h6 mb-0">{{ currencyConverter($displayPrice) }}</span>
              @endif
            </div>
            <div class="w-100">
              <a href="javascript:void(0)" class="add-cart addCart btn btn-success w-100" data-id="{{ $product->id }}"
                data-product-id="{{ $product->id }}" data-name="{{ $name }}"
                data-sizes='@json($product->sizes()->withPivot("price")->get() ?? [])'
                data-weights='@json($product->weights ?? [])' data-additions='@json($product->additions ?? [])'
                data-price='{{ $displayPrice }}' data-base-price='{{ $basePrice }}'
                data-discount='{{ $product->Discount_Price ?? '' }}'
                data-percenteng='{{ number_format($product->Discount ?? 0, 0) }}' data-unit='{{ $product->unit ?? '' }}'
                title="{{ __('Add To Cart') }}">
                <i class="bi bi-bag me-1"></i> {{ __('Add To Cart') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <button class="carousel-arrow right-arrow btn btn-white shadow-sm position-absolute" aria-label="{{ __('Next') }}"
    data-target="#{{ $carouselId }}">
    <i class="bi bi-chevron-right fs-4"></i>
  </button>

  <style>
    /* Scoped styles for this carousel partial */
    #{{ $carouselId }} {
      scroll-behavior: smooth;
    }

    .product-carousel-section {
      padding: 10px 40px;
    }

    .product-carousel-wrapper::-webkit-scrollbar {
      display: none;
    }

    .product-carousel-wrapper {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }

    /* Ensure exactly 4 cards are visible (desktop) by making each card use 1/4 of the container width minus gaps */
    .product-carousel-wrapper {
      gap: 1rem;
    }

    .product-item {
      flex: 0 0 calc((100% - 3rem) / 4);
      max-width: calc((100% - 3rem) / 4);
    }

    .carousel-arrow {
      top: 260px;
      /* Aligned vertically with the unit (pc) badge area */
      z-index: 20;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #f8f9fa !important;
      color: #212529 !important;
      border: none !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 !important;
    }

    .carousel-arrow i {
      font-size: 1.2rem !important;
    }

    .carousel-arrow:active {
      transform: scale(0.95);
    }

    .left-arrow {
      left: 10px;
    }

    .right-arrow {
      right: 10px;
    }

    @media (max-width: 1199px) {

      /* on medium screens show 3 */
      .product-item {
        flex: 0 0 calc((100% - 2rem) / 3);
        max-width: calc((100% - 2rem) / 3);
      }
    }

    @media (max-width: 767px) {
      .product-carousel-section {
        padding: 0 !important;
      }

      .left-arrow {
        left: 0;
      }

      .right-arrow {
        right: 0;
      }

      /* on small screens show 1 */
      .product-item {
        flex: 0 0 calc(100% - 10px);
        max-width: calc(100% - 10px);
      }
    }

    @media (max-width: 479px) {

      /* on extra small screens show 1 */
      .product-item {
        flex: 0 0 100%;
        max-width: 100%;
      }
    }

    /* Ensure title and content don't grow cards unevenly */
    .product-item .card-title {
      min-height: 3.2rem;
      /* reserve space for up to ~2 lines */
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    .product-item .card-img-top {
      height: 160px;
      object-fit: contain;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const section = document.querySelector('#{{ $carouselId }}');
      if (!section) return;

      const leftBtn = section.parentElement.querySelector('.left-arrow');
      const rightBtn = section.parentElement.querySelector('.right-arrow');

      const scrollByAmount = () => {
        // scroll by one visible card width (including gap)
        const card = section.querySelector('.product-item');
        if (!card) return 260;
        const style = window.getComputedStyle(section);
        const gap = parseInt(style.gap || 16, 10) || 16;
        return card.offsetWidth + gap;
      };

      leftBtn && leftBtn.addEventListener('click', () => {
        section.scrollBy({ left: -scrollByAmount(), behavior: 'smooth' });
      });

      rightBtn && rightBtn.addEventListener('click', () => {
        section.scrollBy({ left: scrollByAmount(), behavior: 'smooth' });
      });
    });
  </script>
</div>