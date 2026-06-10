<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() != 'en' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($allsettings['title'] ?? 'بن القطار | Al-Katar'))</title>
    
    <!-- Google Fonts: Cairo for Arabic, Inter for English -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(app()->getLocale() != 'en')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <style>body { font-family: 'Cairo', sans-serif; }</style>
    @else
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <style>body { font-family: 'Inter', sans-serif; }</style>
    @endif
    <link rel="icon" href="{{ isset($allsettings['favicon']) ? asset(IMG_FAVICON_PATH . $allsettings['favicon']) : asset('assets/elketar/logo.png') }}">
    <!-- Tailwind CSS (Direct Asset) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Premium Toastr Styles Overrides (White Theme) -->
    <style>
        #toast-container {
            z-index: 999999 !important;
        }
        #toast-container > .toast {
            background-color: #FFFFFF !important;
            color: #1A4231 !important;
            border: 1px solid rgba(26, 66, 49, 0.08) !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.03) !important;
            font-family: 'Cairo', 'Inter', sans-serif !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            opacity: 1 !important;
            padding: 18px 24px 18px 56px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            letter-spacing: 0.01em;
            background-size: 24px !important;
        }
        #toast-container > .toast.rtl {
            padding: 18px 56px 18px 24px !important;
        }
        #toast-container > .toast-success {
            border-left: 6px solid #1A4231 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231A4231'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'/%3E%3C/svg%3E") !important;
        }
        #toast-container > .toast-success.rtl {
            border-left: none !important;
            border-right: 6px solid #1A4231 !important;
        }
        #toast-container > .toast-error {
            border-left: 6px solid #EF4444 !important;
            color: #EF4444 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23EF4444'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z'/%3E%3C/svg%3E") !important;
        }
        #toast-container > .toast-error.rtl {
            border-left: none !important;
            border-right: 6px solid #EF4444 !important;
        }
        #toast-container > .toast-info {
            border-left: 6px solid #3B82F6 !important;
            color: #3B82F6 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%233B82F6'%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z'/%3E%3C/svg%3E") !important;
        }
        #toast-container > .toast-info.rtl {
            border-left: none !important;
            border-right: 6px solid #3B82F6 !important;
        }
        .toast-progress {
            background-color: #1A4231 !important;
            opacity: 0.25 !important;
            height: 4px !important;
        }
        .toast-error .toast-progress {
            background-color: #EF4444 !important;
        }
        .toast-info .toast-progress {
            background-color: #3B82F6 !important;
        }
        .toast-close-button {
            color: #94A3B8 !important;
            opacity: 0.8 !important;
            text-shadow: none !important;
            transition: all 0.2s ease !important;
        }
        .toast-close-button:hover {
            color: #1A4231 !important;
            opacity: 1 !important;
        }
    </style>
</head>
<body class="bg-katar-cream text-katar-dark font-arabic overflow-x-hidden">
    
    @if(request()->routeIs('front.store') || request()->routeIs('front.cart') || request()->routeIs('checkout') || request()->routeIs('checkout.thankyou_page') || request()->routeIs('front.product_details') || request()->routeIs('single.product.new') || request()->routeIs('single.product') || request()->routeIs('user.profile') || (isset($isStorePage) && $isStorePage))
        @include('front.layouts.include.store_header')
    @else
        @include('front.layouts.include.newdesign_header')
    @endif

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('front.layouts.include.newdesign_footer')

    <!-- jQuery and Toastr (Static) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        // Configure premium toast settings
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "{{ app()->getLocale() != 'en' ? 'toast-top-left' : 'toast-top-right' }}",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showDuration": "200",
            "hideDuration": "600",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "rtl": {{ app()->getLocale() != 'en' ? 'true' : 'false' }}
        };

        // Display session flash messages
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        function addToCart(productId, price) {
            $.ajax({
                url: "{{ route('add.to.cart') }}",
                type: "POST",
                data: {
                    product_id: productId,
                    quantity: 1,
                    price: price,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    if (typeof window.showCartSuccess === 'function') {
                        window.showCartSuccess(data);
                    } else {
                        toastr.success("{{ __('Product Added to Cart Successfully') }}");
                        $(".totalCountItem").html(data[0]);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error);
                    } else {
                        toastr.error("{{ __('Failed to add product to cart') }}");
                    }
                }
            });
        }

        function addToWishlist(id) {
            toastr.info("{{ __('Product Added to Wishlist Successfully') }}");
        }

        function openRatingModal(id) {
            toastr.info("{{ __('Rating System Coming Soon') }}");
        }
    </script>

    @stack('scripts')
</body>
</html>
