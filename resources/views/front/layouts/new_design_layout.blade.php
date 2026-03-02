<!DOCTYPE html>
@php
  // Use `HTML_LANG` for the HTML lang attribute when present (ensures correct
  // `lang` and `dir` even if the application uses a legacy DB locale like 'fr')
  $htmlLocale = session('HTML_LANG', session('APP_LOCALE', app()->getLocale() ?? 'en'));
  // Compute direction from the HTML locale directly to avoid stale session values
  $dir = ($htmlLocale == 'ar') ? 'rtl' : 'ltr';
@endphp
<html lang="{{ $htmlLocale }}" dir="{{ $dir }}">

@include('front.layouts.include.newdesign_head')

<style>
  html,
  body {
    direction:
      {{ $dir == 'rtl' ? 'rtl !important' : 'ltr !important' }}
    ;
  }
</style>

<body>
  @include('front.layouts.include.newdesign_header')

  <main>
    @yield('content')
  </main>

  @include('front.layouts.include.newdesign_footer')

  {{-- Order success modal (shows after a successful checkout when session flag set) --}}
  @include('front.partials.order_success_modal')

  {{-- preserve frontend JS hooks and routes (required for add-to-cart / wishlist / compare) --}}
  <div id="AddToCompareItemUrl" data-url="{{ route('compare.add') }}"></div>
  <div id="AddToCartIntoSession" data-url="{{ route('add.to.cart') }}"></div>
  <div id="productWishlistUrl" data-url="{{ route('wishlist.add') }}"></div>
  <div id="currency-price-url" data-url="{{ route('currency_price') }}"></div>
  <div id="currency-symbol-url" data-url="{{ route('currency_symbol') }}"></div>
  <div id="productImgAsset" data-url="{{ asset(ProductImage()) }}"></div>

  <!-- Login Modal (same as master layout) -->
  <div class="modal fade common-modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="">{{ __('Login') }}</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('user.sign.modal') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">{{ __('Email') }}</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('Email') }}">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">{{ __('Password') }}</label>
              <input type="password" class="form-control" id="password" name="password"
                placeholder="{{ __('Password') }}" autocomplete="current-password">
            </div>

            <div class="modal-btn-wrap text-end">
              <button type="submit" class="primary-btn">{{ __('Submit') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Size Selection Modal (same as master layout) -->
  <div class="modal fade" id="sizeModal" tabindex="-1" aria-labelledby="sizeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header flex items-center justify-between">
          <h5 class="modal-title" id="sizeModalLabel">{{ __('Select Size and Additions') }}</h5>
          <div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        </div>
        <div class="modal-body">
          <!-- Unit Display & Amount Section -->
          <div class="mb-3" id="unitDisplaySection" style="display: none !important;">
          </div>

          <div class="mb-3" id="sizeOptionsSection">
            <h6>{{ __("Product Options") }}:</h6>
            <div id="sizeOptionsContainer" class="d-flex flex-wrap gap-2">
              <!-- Size options will be injected here -->
            </div>
          </div>

          <div class="mb-3" id="weightOptionsSection">
            <h6>{{ __("Weight Options") }}:</h6>
            <div id="weightOptionsContainer" class="d-flex flex-wrap gap-2">
              <!-- Weight options will be injected here -->
            </div>
          </div>

          <div class="mb-3" id="additionOptionsSection">
            <h6>{{ __("Addition Options") }}:</h6>
            <div id="additionOptionsContainer" class="d-flex flex-wrap gap-2">
              <!-- Addition options will be injected here -->
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="submitSelection">
            {{ __('Add To Cart') }}
          </button>
        </div>
      </div>
    </div>
  </div>

  <div id="DoNotSubscribe" data-url="{{ route('do.not.subscribe') }}"></div>
  <div id="SubscribeStore" data-url="{{ route('admin.subscribe.store') }}"></div>

  {{-- localized text used by frontend JS (matching master layout) --}}
  <script>
    var localizedText = {
      productAddedToCart: @json(__('Product Added to Cart Successfully')),
      selectSize: @json(__('Select Size for Product')),
      grams: @json(__('Grams')),
    };

    // locale used by frontend JS
    var locale = '{{ app()->getLocale() ?? config("app.locale") }}';

    // Hide the submit button initially using plain JS so this runs before jQuery is loaded
    document.addEventListener('DOMContentLoaded', function () {
      try {
        var el = document.getElementById('submitSelection');
        if (el) el.style.display = 'none';
      } catch (e) { }
    });
  </script>

  {{-- include the shared frontend scripts so old JS behaviors (addCart, MyWishList, CompareList, cart count updates)
  work --}}
  @include('front.layouts.include.script')
  <script>
    // jQuery-dependent helpers for the size modal. Placed after jQuery is loaded.
    (function ($) {
      $(document).on('click', '.size-option', function () {
        var weightCount = $('#weightOptionsContainer').children().length;
        if (weightCount === 0) {
          $('#submitSelection').show();
        }
      });

      $(document).on('keydown', '#sizeModal', function (e) {
        if (e.key === 'Enter') {
          $('#submitSelection').trigger('click');
        }
      });
    })(jQuery);
  </script>
  @stack('scripts')
</body>

</html>